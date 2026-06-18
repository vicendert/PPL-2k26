@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Login</h1>
    <p class="auth-subtitle">Masuk ke dashboard sesuai role Anda.</p>

    @include('partials.flash')

    <form method="post" action="{{ route('login.submit') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username" value="{{ old('username') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <input class="form-control" type="password" id="password" name="password" required data-password>
                <button class="toggle-password" type="button" data-toggle-password aria-label="Tampilkan password">
                    Lihat
                </button>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Login</button>
        </div>
    </form>

    <p class="helper-text">Belum punya akun? <a href="{{ route('register') }}">Register sekarang</a></p>
@endsection
