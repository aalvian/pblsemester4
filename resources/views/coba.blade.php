<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300&display=swap"
        rel="stylesheet" />
    <link rel="icon" href="{{ asset('image/icon.png') }}" class="spinner-border">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <section class="container-fluid">
        <div class="form-box">
            <div class="form-box1">
                <div class="login">{{ __('Password ') }}</div>
                <form class="form1" method="post" action="{{ route('verifikasi', ['token' => $token]) }}" id="login-form">
                    @csrf
                    <div class="mb-3" >
                        <p>
                            perbarui passwordmu!
                        </p>
                    </div>

                    {{-- Password --}}
                    <div class="inputbox">
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password"
                            required autocomplete="password" autofocus>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="">{{ __('Password') }}
                            <span class="small text-danger">*</span>
                        </label>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="inputbox">
                        <input id="confirm-password" type="password" class="@error('confirm password') is-invalid @enderror"
                            name="confirm-password" >
                        @error('confirm password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="confirm password">{{ __('confirm password') }}
                            <span class="small text-danger">*</span>
                        </label>
                        <i id="eyeIcon" class="fa fa-eye-slash"></i>
                    </div>

                    

                    <button type="submit">
                        {{ __('Login') }}
                    </button>
                </form>

                <div class="social-container mt-3"></div>
            </div>

            <!-- <div class="form-box2">
                <img class="image2" src="{{ asset('image/Group 148.png') }}" />
            </div> -->
        </div>
    </section>

    @include('partials.script')

    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ $message }}",
            });
        </script>
    @endif

    @if ($message = Session::get('captcha_failed'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ $message }}",
            });
        </script>
    @endif

    
</body>

</html>