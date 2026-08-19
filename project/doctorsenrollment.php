<?php
declare(strict_types=1);



error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require_once __DIR__ . '/db_connect.php';

/* 
    API HELPER FUNCTION DEFINITION
*/
function jsonResponse(string $status, string $message, array $extra = []): void {
    header("Content-Type: application/json; charset=UTF-8");
    //jeson encoding
    echo json_encode(array_merge([
        "status"  => $status,
        "message" => $message
    ], $extra), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/* 
   Request Content-Type Detection
*/
$is_api = (isset($_SERVER["CONTENT_TYPE"]) && stripos(trim($_SERVER["CONTENT_TYPE"]), 'application/json') !== false);
$message = '';
$success = false;

/* 
   FORM SUBMISSION & AUTHENTICATION CONTROLLER
   HTTP Method Verification
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $is_api 
        ? (json_decode(file_get_contents('php://input'), true) ?? [])
        : $_POST;
//Data Sanitization & Type Casting
    $email = trim((string)($input['email'] ?? ''));
    $password = trim((string)($input['password'] ?? ''));
//Format Validation
    if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $message = "Valid email and password are required.";
        if ($is_api) { jsonResponse("error", $message); }
    } else {
        /* 
          MULTI-TABLE DATABASE QUERY & CREDENTIAL VERIFICATION
          
        */
        try {
            $user_found = false;
            $user_data = null;
            $target_redirect = 'dashboard.php';

            // Query sequence across database tables
            $tables = ['doctors', 'login', 'registration'];

            foreach ($tables as $table) {
                //SQL Injection Prevention
                $stmt = $conn->prepare("SELECT id, email, password FROM `{$table}` WHERE LOWER(email) = LOWER(?) LIMIT 1");
                if ($stmt) {
                    //Parameter Binding
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows === 1) {
                        //Associative Array Fetching
                        $user_data = $result->fetch_assoc();
                        $user_found = true;
                        
                        // dynamic redirection based on table 
                        if ($table === 'doctors') {
                            $target_redirect = 'doctordashboard.php';
                        }
                        $stmt->close();
                        break;
                    }
                    $stmt->close();
                }
            }

            if ($user_found && $user_data) {
                $db_password = $user_data['password'];

         ////Password Hashing Verification
                if ($password === $db_password || password_verify($password, $db_password)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id']       = $user_data['id'];
                    $_SESSION['email']         = $user_data['email'];
                    $_SESSION['authenticated'] = true;
                    $success = true;

                    http_response_code(200);
                    if ($is_api) {
                        jsonResponse("success", "Login successful!", [
                            "redirect" => $target_redirect
                        ]);
                    } else {
                        header("Location: " . $target_redirect);
                        exit;
                    }
                } else {
                    http_response_code(401);
                    $message = "Invalid email or password.";
                    if ($is_api) { jsonResponse("error", $message); }
                }
            } else {
                http_response_code(401);
                $message = "Invalid email or password.";
                if ($is_api) { jsonResponse("error", $message); }
            }
        } catch (Exception $e) {
            error_log("Login Error: " . $e->getMessage());
            http_response_code(500);
            $message = "Internal Server Error: " . $e->getMessage();
            if ($is_api) { jsonResponse("error", $message); }
        }
    }
}


if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login - Inner Bloom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #16a34a;
            --error-color: #dc2626;
            --transition-speed: 0.5s;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; 
        }

        body { 
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.85)), 
                        url('765.jpg') center center / cover no-repeat fixed;
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-card { 
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            width: 100%; 
            max-width: 440px; 
            padding: 45px 40px; 
            border-radius: 16px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            transform: translateY(30px);
            opacity: 0;
            animation: cardEntrance var(--transition-speed) cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        @keyframes cardEntrance {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .logo-section { 
            text-align: center; 
            margin-bottom: 35px; 
        }

        .logo-section i.fa-stethoscope {
            font-size: 45px;
            color: var(--primary-color);
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover .logo-section i.fa-stethoscope {
            transform: scale(1.1) rotate(-10deg);
        }

        .logo-section h1 { 
            color: #1e293b; 
            font-size: 30px; 
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .logo-section p { 
            color: #64748b; 
            font-size: 14px; 
            margin-top: 5px;
        }

        .form-group { 
            display: flex; 
            flex-direction: column; 
            margin-bottom: 22px; 
        }

        label { 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #334155; 
            font-size: 14px; 
        }

        input { 
            padding: 14px; 
            border: 1.5px solid #cbd5e1; 
            border-radius: 8px; 
            width: 100%; 
            outline: none; 
            font-size: 14px; 
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        input:focus { 
            border-color: var(--primary-color); 
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 211, 0.18);
        }

        .btn-box { 
            display: flex; 
            gap: 15px; 
            margin-top: 30px; 
        }

        .submit-btn { 
            padding: 14px; 
            width: 100%; 
            border: none; 
            border-radius: 8px; 
            background: var(--primary-color); 
            color: white; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
            text-align: center; 
            text-decoration: none; 
            display: inline-block;
        }

        .submit-btn:hover { 
            background: #1d4ed8; 
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(37, 99, 211, 0.25);
        }

        .btn-home { 
            background: #64748b; 
        }

        .btn-home:hover { 
            background: #475569; 
            box-shadow: 0 8px 16px rgba(100, 116, 139, 0.25);
        }

        .btn-dashboard { 
            background: var(--success-color); 
            margin-top: 12px; 
        }

        .btn-dashboard:hover { 
            background: #15803d; 
            box-shadow: 0 8px 16px rgba(22, 163, 74, 0.25);
        }

        .message-box { 
            padding: 16px; 
            border-radius: 8px; 
            text-align: center; 
            font-weight: 500; 
            font-size: 14px; 
            margin-bottom: 25px; 
            width: 100%; 
            max-width: 440px;
            animation: messageEntrance 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes messageEntrance {
            from { opacity: 0; transform: translateY(-10px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .error-msg { 
            background: #fef2f2; 
            color: var(--error-color); 
            border: 1px solid #fee2e2; 
        }

        .success-msg { 
            background: #f0fdf4; 
            color: #15803d; 
            border: 1px solid #dcfce7; 
        }

        .seed-msg { 
            background: #ecfdf5; 
            color: #065f46; 
            border: 1px solid #d1fae5; 
        }

        .forgot-link {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 440px;
        }

        .forgot-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .forgot-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php if (!empty($seed_message)) { ?>
    <div class="message-box seed-msg">
        <!--Attack Prevention , Output Encoding(htmlspecialchars())-->
        <p><i class="fa-solid fa-square-check"></i> <?php echo htmlspecialchars($seed_message); ?></p>
    </div>
<?php } ?>

<div class="login-card">
    <div class="logo-section">
        <i class="fa-solid fa-stethoscope"></i>
        <h1>Secure Login</h1>
        <p>Access your provider medical dashboard</p>
    </div>

    <?php if (!empty($message)) { ?>
        <div class="message-box <?php echo $success ? 'success-msg' : 'error-msg'; ?>">
            <p><i class="fa-solid <?php echo $success ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i> <?php echo htmlspecialchars($message); ?></p>
            <?php if ($success) { ?>
                <a href="dashboard.php" class="submit-btn btn-dashboard">
                    Go to Dashboard <i class="fa-solid fa-arrow-right"></i>
                </a>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (!$success) { ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="ayesha.rahman@innerbloom.com" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="forgot-link">
            <a href="forgot_password1.php"><i class="fa-solid fa-key"></i> Forgot Password?</a>
        </div>

        <div class="btn-box">
            <button type="submit" class="submit-btn"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
            <a href="index.php" class="submit-btn btn-home"><i class="fa-solid fa-house"></i> Home</a>
        </div>
    </form>
    <?php } ?>
</div>

</body>
</html>