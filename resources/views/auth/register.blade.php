@extends('layouts.auth')

@section('content')
    <div class="card border border-light-subtle rounded-4">
        <div class="card-header bg-secondary">
            <h5 class="text-center text-light">REGISTER</h5>
        </div>
        <div class="card-body p-3 p-md-4 ">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="row gy-3 overflow-hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" required
                                    class="form-control @error('fname')
                                is-invalid
                            @enderror"
                                    name="fname" id="fname" placeholder="First Name" value={{ old('fname') }}>
                                @error('fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="fname" class="form-label">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" required
                                    class="form-control @error('mname')
                                is-invalid
                            @enderror"
                                    name="mname" id="mname" placeholder="Middle Name" value={{ old('mname') }}>
                                @error('mname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="mname" class="form-label">Middle Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" required
                                    class="form-control @error('lname')
                                is-invalid
                            @enderror"
                                    name="lname" id="lname" placeholder="Last Name" value={{ old('lname') }}>
                                @error('lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="lname" class="form-label">Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input required type="text"
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
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="email" required
                                class="form-control @error('email')
                            is-invalid
                        @enderror"
                                name="email" id="email" placeholder="Email" value={{ old('email') }}>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <label for="email" class="form-label">Email</label>
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
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" placeholder="Confirm Password" autocomplete="new-password">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Register</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="d-flex gap-2 gap-md-4 mt-2 flex-md-row justify-content-md-center">
                <a href="{{ route('login') }}">Login</a>
                <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
            </div>
        </div>
    </div>
@endsection
