<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | CheckIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            overflow-x: hidden;
            line-height: 1.6;
            color: #333; /* Default text color */
        }
        
        /* THEME COLOR VARIABLES */
        :root {
            --primary-red: #cc3657; /* The key red color used in your image */
            --button-red: #dc3545; /* Slightly brighter for the button */
            --dark-red: #b32030;
        }

        /* LOGIN SECTION */
        .login-wrapper {
            display: flex;
            flex-wrap: nowrap; /* Ensure no wrapping on desktop */
            width: 100%;
            min-height: 100vh;
        }

        /* LEFT SIDE (Image) */
        .login-left {
            flex: 1;
            min-width: 50%; /* Set to 50% for equal split as in your image */
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                        url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 80px;
        }

        .login-left h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.7;
            max-width: 400px;
            opacity: 0.9;
        }

        .social-icons { 
            margin-top: 25px; 
        }
        
        .social-icons i {
            font-size: 20px;
            margin-right: 15px;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .social-icons i:hover { 
            opacity: 0.8; 
        }

        /* RIGHT SIDE (Form) */
        .login-right {
            flex: 1;
            min-width: 50%; /* Set to 50% for equal split as in your image */
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Center form content vertically and horizontally */
            padding: 60px 80px;
        }

        .login-form-container {
            width: 100%;
            max-width: 420px; /* Constrain the form width */
        }

        .login-right h3 {
            font-weight: 600; /* Adjusted font weight to match image */
            font-size: 2.2rem;
            margin-bottom: 30px; /* Increased margin below title */
            color: var(--primary-red); /* Using the primary red */
        }

        .form-control {
            border-radius: 5px; /* Subtle radius */
            margin-bottom: 20px; /* Increased margin for inputs */
            height: 50px; /* Slightly taller inputs */
            font-size: 15px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            box-shadow: none !important; /* Remove Bootstrap focus glow */
        }

        /* LOGIN BUTTON STYLING */
        .btn-login {
            background: var(--primary-red);
            border: none;
            height: 50px;
            font-weight: 600; /* Bolder look for the button */
            color: white;
            transition: 0.3s;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px; /* Margin to separate from options */
        }

        .btn-login:hover { 
            background: var(--dark-red); 
        }

        /* REMEMBER & FORGOT PASSWORD */
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .remember-forgot a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 500; /* Matches the image */
        }

        .remember-forgot a:hover { 
            text-decoration: underline; 
        }

        /* REGISTER LINK */
        .register-text {
            text-align: center;
            margin-top: 20px;
            font-size: 15px;
        }
        
        .register-text a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600; /* Highlighted link */
        }
        
        .register-text a:hover { 
            text-decoration: underline; 
        }

        /* PASSWORD FIELD TOGGLE */
        .password-input-group {
            position: relative;
            margin-bottom: 20px; /* Match standard input margin */
        }

        .password-input-group .form-control {
            margin-bottom: 0;
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
            z-index: 10;
            outline: none;
        }

        .password-toggle:hover {
            color: var(--primary-red);
        }

        /* ERROR/SUCCESS MESSAGES - IMPROVED STYLING */
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

        /* REMOVE UNUSED STYLES / RESPONSIVE SECTION REMAINS THE SAME */
        /* ... (Keep your responsive styles from the original file here) ... */

        /* RESPONSIVE STYLES ADJUSTED */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
            }
            .login-left {
                min-height: 35vh;
                min-width: 100%;
            }
            .login-right {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-left">
            <h1>CheckIn</h1>
            <p>Welcome to CheckIn — your modern hotel booking platform designed for comfort, style, and simplicity. Wherever you go, we make every stay feel like home.</p>
            <div class="social-icons">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-linkedin-in"></i>
            </div>
        </div>

        <div class="login-right">
            <div class="login-form-container">
                <h3>Sign In</h3>

                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('status') || session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') ?? session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <input name="email" id="emailInput" value="{{ old('email') }}" type="email" 
                        class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autofocus>
                    
                    <div class="password-input-group">
                        <input name="password" id="passwordInput" type="password" 
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('passwordInput')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="remember-forgot">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} class="me-2">
                            <label for="remember" class="mb-0">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-login">Login</button>
                    
                    <p class="register-text">No account? <a href="{{ route('register') }}">Register here</a></p>
                </form>
            </div>
        </div>
    </div>

    <div class="intro-section">
        </div>

    <footer>
        &copy; 2025 CheckIn. All Rights Reserved.
    </footer>

    <script>
        // ... (Your JavaScript functions here) ...
    </script>

</body>
</html>