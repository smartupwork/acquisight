@extends('auth.layout')
@section('seller-register-content')
    {{-- seller registeration blade --}}
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="{{ url('assets/images/admin-logo.jpg') }}" height="50"
                                                alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started as Seller</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <form class="my-4" method="POST" action="{{ route('buyer.register.submit') }}">
                                        @csrf
                                        <input type="hidden" name="roles_id" value="4" />
                                        <input type="hidden" name="deal_id" value="{{ $deal_id }}" />
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Name">
                                        </div>
                                        @error('name')
                                            <span>{{ $message }}</span>
                                        @enderror
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="Enter email">
                                        </div>
                                        @error('email')
                                            <span>{{ $message }}</span>
                                        @enderror

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control" name="password" id="userpassword"
                                                placeholder="Enter password">
                                        </div>
                                        @error('password')
                                            <span>{{ $message }}</span>
                                        @enderror
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userpassword">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="userpassword" placeholder="Confirm password">
                                        </div>


                                        <div class="form-group row mt-3">
                                            <div class="col-12">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox" id="termsCheckbox"
                                                        disabled>
                                                    <label class="form-check-label" for="termsCheckbox">
                                                        By registering, you agree to the
                                                        <a href="#" class="text-primary" data-bs-toggle="modal"
                                                            data-bs-target="#termsModal">Terms of Use</a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit" id="registerBtn"
                                                        disabled>Register <i class="fas fa-sign-in-alt ms-1"></i>
                                                    </button>
                                                </div>
                                            </div><!--end col-->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <h5>David Moore & Partners Buyer Non-Disclosure Agreement</h5>
                    <p>I/we understand and agree:</p>
                    <ol>
                        <li><strong>No Disclosure.</strong> The Recipient (Buyer) will hold the Confidential information in
                            confidence for a
                            period of two (2) years from the date I/we sign this Non-Disclosure Agreement and will not
                            disclose the Confidential information to any person or entity without the prior written consent
                            of David Moore & Partners.</li>
                        <li><strong>No Copying/Modifying.</strong> The Recipient will not copy or modify any Confidential
                            Information without
                            the prior written consent of the Seller.
                        </li>
                        <li><strong>Unauthorized Use.</strong> The Recipient shall promptly advise the Seller if the
                            Recipient becomes aware
                            of any possible unauthorized disclosure or use of the Confidential Information.</li>
                        <li>The Recipient will not to contact the landlord, employees, suppliers, franchisor or customers of
                            the Business for Sale without the express written consent of David Moore & Partners.
                        </li>
                        <li><strong>Non Circumvention.</strong> For a period of two (2) years after the date this agreement
                            was signed, the
                            Recipient will not attempt to do business with, or otherwise solicit any business contacts found
                            or otherwise referred by Seller to Recipient for the purpose of circumventing, the result of
                            which shall be to prevent the Seller from realizing or recognizing a profit, fees, or otherwise,
                            without the specific written approval of the Seller. If such circumvention shall occur the
                            Seller shall be entitled to any commissions due pursuant to this Agreement or relating to such
                            transaction.
                        </li>
                        <li><strong>Relationship of Parties.</strong> Neither party has an obligation under this Agreement
                            to purchase any
                            service or item from the other party, or commercially offer any products using or incorporating
                            the Confidential information. This Agreement does not create any agency, partnership or joint
                            venture. David Moore & Partners is compensated from any transaction that results between the
                            buyer and the seller.</li>
                        <li><strong>No Warranty and Due Diligence.</strong> The Recipient acknowledges and agrees that the
                            Confidential
                            Information is provided on an “AS IS” basis. David Moore & Partners makes no warranties, express
                            or implied, with respect to the confidential information and hereby expressly disclaims any and
                            all implied warranties of merchantability and fitness for a particular purpose. In no event
                            shall David Moore & Partners be liable for any direct, indirect, special or consequential damage
                            in connection with or arising out of the performance, use , or inaccuracies of any portion of
                            the Confidential Information. The Recipient acknowledges the responsibility to perform a due
                            diligence review at it’s own cost and expense prior to any acquisition.</li>
                        <li>Due to the sensitive nature of the financial records and the potential effects violations of
                            this non-disclosure agreement (“Agreement”) could have on an ongoing basis, the Buyer agrees
                            that each violation of this Agreement shall have liquidated damages of Twenty Five Thousand
                            Dollars ($25,000.00) per violation. Violation of this Agreement will cause the Seller to incur
                            substantial economic damages and losses of types and in amounts which are impossible to compute
                            and ascertain with certainty as a basis for recovery by the Seller of actual damages. The above
                            liquidated damages represent a fair, reasonable, and appropriate estimate of the damage likely
                            to occur for violation of this Agreement. Such liquidated damages are intended to represent
                            estimated actual damages as the exact amount of damages is indeterminable, the liquidated
                            damages are not intended as a penalty, and Buyer shall pay them to Seller without limiting David
                            Moore & Partners’ or the Seller’s right to terminate this Agreement as provided elsewhere.
                            Examples of a breach of this Agreement include but are not limited to: Disclosing the company
                            name to anyone that has not signed this NDA. Forwarding emails and financial information to
                            third parties. Contacting customers, employees, landlords and franchisors.
                        </li>
                        <li>The respective obligations of the parties under this Agreement shall survive for a period of two
                            (2) years following the date hereof.
                        </li>
                    </ol>
                    <p>Smaller transactions do not always have a full set of financials. Small Companies do not have CFO’s
                        and controllers. Most of these companies operate on a tax accounting basis. We provide Generally
                        Accepted Recast Financials that are created from Quickbooks, tax returns, and bank statements.
                        Recast Income Statements are based on assumptions and are not actual financials.
                    </p>
                    <p><strong> We handle transactions differently than some brokers. We do not present buyers to sellers
                            that have not proven they have the ability to pay for, or get financing for business
                            acquisition. We do not release material that would be considered Due Diligence material (such as
                            tax returns, customer lists, etc) without a letter of intent. We require in person site visits
                            prior to accepting any letters of intent. While we understand this may prevent some buyers from
                            moving forward, our intent is to protect our client’s sensitive information and prevent people
                            that do not have the ability to “close” from gathering information.
                        </strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="checkbox" id="agreeTerms">
                    <label for="agreeTerms">I agree to the Terms & Conditions</label>
                    <button type="button" class="btn btn-primary" id="acceptTermsBtn" disabled
                        data-bs-dismiss="modal">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable "Accept" button only when the user checks the "I agree" checkbox in the modal
        document.getElementById("agreeTerms").addEventListener("change", function() {
            document.getElementById("acceptTermsBtn").disabled = !this.checked;
        });

        // When "Accept" is clicked in the modal, check the main terms checkbox and enable the register button
        document.getElementById("acceptTermsBtn").addEventListener("click", function() {
            document.getElementById("termsCheckbox").checked = true;
            document.getElementById("registerBtn").disabled = false;
        });

        // Prevent form submission if the terms checkbox is not checked
        document.querySelector("form").addEventListener("submit", function(event) {
            if (!document.getElementById("termsCheckbox").checked) {
                event.preventDefault();
                alert("You must agree to the Terms of Use before proceeding.");
            }
        });
    </script>
@endsection
