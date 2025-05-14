<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DealRequest;
use App\Models\DealInvitation;
use App\Models\User;
use App\Mail\DealRequestMail;
use App\Mail\InformBuyerMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Deal;
use Illuminate\Support\Facades\Log;

class DealRequestController extends Controller
{
    public function store($deal_id)
    {
        $user_id = auth()->id();

        $existingRequest = DealRequest::where('deal_id', $deal_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You have already requested access to this deal.');
        }


        $brokerEmail = DealInvitation::where('deal_id', $deal_id)
            ->where('user_type', 2)
            ->value('email');

        if (!$brokerEmail) {

            $admin = User::where('roles_id', 1)->first();
            $admin_email = $admin->email;

            $dealRequest = DealRequest::create([
                'deal_id' => $deal_id,
                'user_id' => $user_id,
                'broker_id' => $admin->id,
                'status' => 'pending',
            ]);

            $deal = Deal::find($deal_id);

            Mail::to($admin_email)->send(new DealRequestMail($deal, auth()->user(), $admin));

            return redirect()->back()->with('success', 'Your request has been sent to the admin.');
        }


        $broker = User::where('email', $brokerEmail)->first();

        if (!$broker) {
            return redirect()->back()->with('error', 'Broker not found.');
        }

        $dealRequest = DealRequest::create([
            'deal_id' => $deal_id,
            'user_id' => $user_id,
            'broker_id' => $broker->id,
            'status' => 'pending',
        ]);

        // Fetch the deal
        $deal = Deal::find($deal_id);

        Mail::to($brokerEmail)->send(new DealRequestMail($deal, auth()->user(), $broker));

        return redirect()->back()->with('success', 'Your request has been sent to the broker.');
    }

    public function getBrokerDealRequests()
    {
        $broker_id = auth()->id();
        $dealRequests = DealRequest::where('broker_id', $broker_id)
            ->with(['user:id,name,email', 'deal:id,gcs_deal_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.broker.request', ['dealRequests' => $dealRequests]);
    }

    public function getAdminDealRequests()
    {

        $dealRequests = DealRequest::with(['user:id,name,email', 'deal:id,gcs_deal_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.admin.request.index', ['dealRequests' => $dealRequests]);
    }

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $dealRequest = DealRequest::find($id);

        if (!$dealRequest) {
            return response()->json(['message' => 'Deal request not found.'], 404);
        }

        $dealRequest->status = $request->status;
        $dealRequest->save();

        if ($request->status === 'approved') {

            try {
                Mail::to($dealRequest->user->email)->send(new InformBuyerMail($dealRequest));
                Log::info('Email sent successfully to buyer.', [
                    'user_email' => $dealRequest->user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send email to buyer.', [
                    'error' => $e->getMessage(),
                    'user_email' => $dealRequest->user->email
                ]);
            }
        }

        return response()->json(['message' => 'Deal request updated successfully.', 'status' => $dealRequest->status]);
    }
}
