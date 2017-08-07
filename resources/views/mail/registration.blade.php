<h1>Hi there!</h1>
<p>
    You have a registration invite from {{ env('APP_NAME') }}.<br>
    To perform registration please follow this
    <a href="{{ route('users.register', ['token' => $token]) }}">link</a> or copy and paste it
    to you browser's address line<br>

    <a href="{{ route('users.register', ['token' => $token]) }}">{{ route('users.register', ['token' => $token]) }}</a>
</p>