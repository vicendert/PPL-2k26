@extends('layouts.auth')

@section('content')
    <h1 class="auth-title">Register</h1>
    <p class="auth-subtitle">Daftar sebagai User untuk melakukan booking lapangan.</p>

    @include('partials.flash')

    <form method="post" action="{{ route('register.submit') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="inline-grid">
            <div class="form-group">
                <label class="form-label" for="phone">Nomor HP</label>
                <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="gender">Jenis Kelamin</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Pilih</option>
                    <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="address">Alamat</label>
            <input class="form-control" type="text" id="address" name="address" value="{{ old('address') }}">
        </div>
        <div class="inline-grid">
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
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Daftar</button>
        </div>
    </form>

    <p class="helper-text">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
@endsection
