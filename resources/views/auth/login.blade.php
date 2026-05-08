@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <img src="{{ asset('images/RMSLogo.png') }}" alt="Reseva logo" style="width: 100px; height: 100px; object-fit: contain;" />
    </div>
    <h1 class="auth-title">Welcome to Reseva</h1>
    <p class="auth-subtitle">Sign in to continue</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Z" fill="#64748b"/>
                        <path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4v1H4v-1Z" fill="#64748b"/>
                    </svg>
                </span>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10V8a6 6 0 1 1 12 0v2h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V11a1 1 0 0 1 1-1h1Zm2 0h8V8a4 4 0 0 0-8 0v2Z" fill="#64748b"/>
                    </svg>
                </span>
                <input id="password" type="password" name="password" class="form-control" placeholder="************" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign In</button>
    </form>
</div>
@endsection
