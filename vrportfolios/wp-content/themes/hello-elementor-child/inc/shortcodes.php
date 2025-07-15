<?php

// Check for loggedd in user
add_action('template_redirect', function () {
    if (is_user_logged_in() && is_front_page()) {
        if (is_page('login') || is_page('register')) {
            $current_user = wp_get_current_user();
            wp_redirect(home_url('/' . $current_user->user_nicename));
            exit;
        }
    }
});

add_shortcode('custom_login_register_form', 'shortcode_custom_login_register_form');
function shortcode_custom_login_register_form()
{
    ob_start();
?>
    <style>
        * {
            box-sizing: border-box;
        }

        .auth-container {
            max-width: 420px;
            margin: 40px auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .auth-inner {
            background: white;
            border-radius: 18px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-inner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            animation: gradient-shift 3s ease-in-out infinite;
        }

        @keyframes gradient-shift {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .auth-toggle-buttons {
            display: flex;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 50px;
            padding: 4px;
            position: relative;
        }

        .auth-toggle-buttons button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            color: #64748b;
        }

        .auth-toggle-buttons button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .auth-form {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            position: absolute;
            width: calc(100% - 80px);
        }

        .auth-form.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
            position: relative;
            width: 100%;
        }

        .auth-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-group input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .auth-message {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            display: none;
        }

        .auth-message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            display: block;
        }

        .auth-message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            display: block;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 25px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #64748b;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .auth-container {
                margin: 20px;
                max-width: none;
            }

            .auth-inner {
                padding: 30px 25px;
            }

            .auth-form h2 {
                font-size: 24px;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading .submit-btn {
            background: #94a3b8;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
    </style>

    <div class="auth-container">
        <div class="auth-inner">
            <div class="auth-toggle-buttons">
                <button type="button" id="show-login" class="active">Sign In</button>
                <button type="button" id="show-register">Sign Up</button>
            </div>

            <div id="login-form" class="auth-form active">
                <h2>Welcome Back</h2>
                <div id="login-message" class="auth-message"></div>
                <form id="user-login-form">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="submit-btn">Sign In</button>
                </form>
                <div class="forgot-password">
                    <a href="#" id="forgot-password-link">Forgot your password?</a>
                </div>
            </div>

            <div id="register-form" class="auth-form">
                <h2>Create Account</h2>
                <div id="register-message" class="auth-message"></div>
                <form id="user-register-form">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Create Password" required>
                    </div>
                    <button type="submit" class="submit-btn">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showLogin = document.getElementById('show-login');
            const showRegister = document.getElementById('show-register');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            function switchToLogin() {
                showLogin.classList.add('active');
                showRegister.classList.remove('active');
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
            }

            function switchToRegister() {
                showRegister.classList.add('active');
                showLogin.classList.remove('active');
                registerForm.classList.add('active');
                loginForm.classList.remove('active');
            }

            showLogin.addEventListener('click', switchToLogin);
            showRegister.addEventListener('click', switchToRegister);

            // Form submission handlers with loading states
            document.getElementById('user-login-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<span class="spinner"></span>Signing In...';
                submitBtn.disabled = true;

                // Your login logic here
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            });

            document.getElementById('user-register-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<span class="spinner"></span>Creating Account...';
                submitBtn.disabled = true;

                // Your registration logic here
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            });

            // Message display function
            window.showAuthMessage = function(type, message, formType) {
                const messageEl = document.getElementById(formType + '-message');
                messageEl.className = 'auth-message ' + type;
                messageEl.textContent = message;
                messageEl.style.display = 'block';

                setTimeout(() => {
                    messageEl.style.display = 'none';
                }, 5000);
            };
        });
    </script>
<?php
    return ob_get_clean();
}

/* add_shortcode('custom_register_form', 'shortcode_custom_register_form');
function shortcode_custom_register_form()
{
    ob_start();
?>
    <div>
        <div id="register-message"></div>
        <form id='user-register-form'>
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="submit" value="Register">
        </form>
    </div>
<?php return ob_get_clean();
} */

/* add_shortcode('custom_login_form', 'shortcode_custom_login_form');
function shortcode_custom_login_form()
{
    ob_start();
?>
    <div>
        <div id="login-message"></div>
        <form id='user-login-form'>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
<?php return ob_get_clean();
} */