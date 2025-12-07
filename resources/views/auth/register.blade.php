<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | CheckIn</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* BASE & RESET */
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
        
        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: #fff;
            overflow-x: hidden;
            line-height: 1.6;
            color: #333;
        }
        
        /* THEME COLOR VARIABLES */
        :root {
            --primary-red: #cc3657; /* Key red color */
            --dark-red: #b32030;
        }

        /* --- GENERAL LAYOUT (Split Screen) --- */
        .registration-wrapper {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            min-height: 100vh;
        }

        /* --- LEFT SIDE (Image & Text) --- */
        .register-left {
            flex: 1;
            min-width: 50%; 
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('https://images.unsplash.com/photo-1571003463690-3490710777e3?auto=format&fit=crop&q=80&w=1974') center/cover no-repeat; /* Placeholder Image URL */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 80px;
        }

        .register-left h1 {
            font-weight: 700;
            font-size: 3em;
            margin-bottom: 15px;
        }

        .register-left p {
            font-size: 1.1em;
            line-height: 1.6;
            max-width: 450px;
            opacity: 0.95;
        }

        /* --- RIGHT SIDE (Form) --- */
        .register-right {
            flex: 1;
            min-width: 50%;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
        }
        
        .register-form-container {
            width: 100%;
            max-width: 480px; 
        }

        .form-title {
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: var(--primary-red);
        }

        /* Input Group for Three Name Fields */
        .name-input-group {
            display: flex;
            gap: 15px; 
            margin-bottom: 15px;
        }

        .name-input-group .form-control {
            flex: 1; 
            margin-bottom: 0;
            height: 50px;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        /* General form control styling for the other fields */
        .form-control {
            height: 50px;
            margin-bottom: 15px; 
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%; 
            font-size: 15px;
            box-shadow: none !important;
        }
        
        /* Password toggle styling */
        .password-input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .password-input-group .form-control {
             margin-bottom: 0;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 10;
            background: none;
            border: none;
        }
        .password-toggle:hover {
            color: var(--primary-red);
        }
        
        /* Button styling */
        .btn-register {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .btn-register:hover {
            background-color: var(--dark-red);
        }

        /* Login Link */
        .login-link-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95em;
        }
        
        .login-link-text a {
            color: var(--primary-red);
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link-text a:hover {
            text-decoration: underline;
        }
        
        /* ERROR/SUCCESS MESSAGES */
        .alert {
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* --- RESPONSIVE STYLES --- */
        @media (max-width: 992px) {
            .registration-wrapper {
                flex-direction: column;
            }
            .register-left {
                min-height: 40vh;
                min-width: 100%;
                padding: 40px;
                text-align: center;
                align-items: center;
            }
            .register-right {
                min-width: 100%;
                padding: 40px 30px;
            }
        }
        @media (max-width: 768px) {
            .name-input-group {
                flex-direction: column; /* Stack name fields on small screens */
                gap: 0;
            }
            .name-input-group .form-control {
                margin-bottom: 15px;
            }
            .register-left {
                padding: 30px;
            }
            .register-left h1 {
                font-size: 2.2em;
            }
            .register-left p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

    <div class="registration-wrapper">
        <div class="register-left"> 
            <h1 class="app-title">Join CheckIn</h1>
            <p class="app-description">
                Discover your next stay with ease. Create your account and experience hassle-free booking designed for modern travelers.
            </p>
        </div>

        <div class="register-right">
            <div class="register-form-container">
                <h2 class="form-title">Create Your Account</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="name-input-group">
                        <input type="text" name="first_name" placeholder="First name" 
                               class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name') }}" required autocomplete="given-name">
                        <input type="text" name="middle_name" placeholder="Middle name" 
                               class="form-control @error('middle_name') is-invalid @enderror" 
                               value="{{ old('middle_name') }}" autocomplete="additional-name">
                        <input type="text" name="last_name" placeholder="Last name" 
                               class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name') }}" required autocomplete="family-name">
                    </div>

                    <input type="email" name="email" placeholder="Email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required autocomplete="email">

                    <input type="text" name="phone_number" placeholder="Phone number" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           value="{{ old('phone_number') }}" autocomplete="tel">
                           
                    <div class="password-input-group">
                        <input name="password" id="regPassword" type="password" placeholder="Password" 
                               class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('regPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="password-input-group">
                        <input name="password_confirmation" id="confirmPassword" type="password" placeholder="Confirm password" 
                               class="form-control" required autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <button type="submit" class="btn btn-register">Register</button>
                    
                    <p class="login-link-text">
                        Already have an account? <a href="{{ route('login') }}">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.password-toggle i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>