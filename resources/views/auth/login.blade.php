@extends('layouts.auth')

@section('content')
    <div class="card border border-light-subtle rounded-4">
        <div class="card-header bg-secondary">
            <h5 class="text-center text-light">LOGIN</h5>
        </div>
        <div class="card-body p-3 p-md-4 ">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="row gy-3 overflow-hidden">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control @error('username')
                            is-invalid
                        @enderror"
                                name="username" id="username" placeholder="Username" value={{ old('username') }}>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <label for="username" class="form-label">Username</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="password"
                                class="form-control @error('password')
                            is-invalid
                            @enderror"
                                name="password" id="password" placeholder="Password" autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <label for="password" class="form-label">Password</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" name="remember_me"
                                id="remember_me">
                            <label class="form-check-label text-secondary" for="remember_me">
                                Keep me logged in
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Log in</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="d-flex gap-2 gap-md-4 mt-2 flex-md-row justify-content-md-center">
                <a href="{{ route('register') }}">Create new account</a>
                <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
            </div>
        </div>
    </div>
@endsection
