<?php 
// Section 1: Database Setup — Establishes persistent MySQL connection ($conn) for session & profile operations.
include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - InnerBloom</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-main: #f5f7fb;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-main);
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-container {
            background-color: var(--card-bg);
            width: 100%;
            max-width: 440px;
            padding: 40px 32px;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid var(--border-color);
        }

        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .logo-icon {
            font-size: 32px;
            background: linear-gradient(135deg, #a78bfa, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: #4338ca;
        }

        .header-text {
            text-align: center;
            margin-bottom: 32px;
        }

        .header-text h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-text p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 16px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(90deg, #7c3aed, #6366f1);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-submit:hover {
            opacity: 0.95;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .back-to-login {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
        }

        .back-to-login a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Logo -->
        <div class="logo-area">
            <i class="fa-solid fa-seedling logo-icon"></i>
            <span class="logo-text">InnerBloom</span>
        </div>

        <!-- Header -->
        <div class="header-text">
            <h2>Reset Your Password</h2>
            <p>Enter your Email ID and create a new password to recover access to your account.</p>
        </div>

        <!-- Form directing to dashboard.php -->
        <form action="dashboard.php" method="POST" onsubmit="return validatePasswords()">

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email ID</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="example@domain.com" required>
                </div>
            </div>

            <!-- New Password Field -->
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-shield-halved"></i>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password" required>
                </div>
            </div>

            <!-- Submit Button (Directs to dashboard.php) -->
            <button type="submit" class="btn-submit">
                Reset & Continue <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i>
            </button>
        </form>

        <!-- Back Link -->
        <div class="back-to-login">
            Remembered your password? <a href="login.php">Log In</a>
        </div>
    </div>

    <!-- Frontend Password Match Validation Script -->
    <script>
        function validatePasswords() {
            const pwd = document.getElementById('password').value;
            const confirmPwd = document.getElementById('confirm-password').value;
            
            if (pwd !== confirmPwd) {
                alert("Passwords do not match. Please check and try again.");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>