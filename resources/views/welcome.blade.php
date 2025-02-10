<form method="post" action="{{ route('submit-file') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" />
    <button type="submit"></button>
</form>
