<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CWC - Service</title>
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
</head>

<body>
    <div class="container p-4">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <div class="mb-5">
                    <div class="text-center mb-4">
                        <a href="#!">
                            <img src="{{ asset('public/images/login_logo.png') }}"
                                alt="Council for the Welfare of Children" height="140">
                        </a>
                    </div>
                    <h4 class="text-center mb-4">CWC - Service v1.0.0</h4>
                </div>
                
                    @yield('content')
                
            </div>
        </div>
    </div>
    </div>
</body>

</html>
