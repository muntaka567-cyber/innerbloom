<?php
declare(strict_types=1);

/* 
    SCRIPT SETUP & ERROR LOGGING CONFIGURATION
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require_once __DIR__ . '/db_connect.php';

/* 
    API HELPER FUNCTION DEFINITION
*/
function jsonResponse(string $status, string $message, array $extra = []): void {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(array_merge([
        "status"  => $status,
        "message" => $message
    ], $extra), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/* 
   INCOMING REQUEST DETECTOR & STATE INITIALIZATION
*/
$is_api = (isset($_SERVER["CONTENT_TYPE"]) && stripos(trim($_SERVER["CONTENT_TYPE"]), 'application/json') !== false);
$message = '';
$success = false;

/* 
   FORM SUBMISSION & AUTHENTICATION CONTROLLER
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $is_api 
        ? (json_decode(file_get_contents('php://input'), true) ?? [])
        : $_POST;

    $email = trim((string)($input['email'] ?? ''));
    $password = trim((string)($input['password'] ?? ''));

    if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $message = "Valid email and password are required.";
        if ($is_api) { jsonResponse("error", $message); }
    } else {
        try {
            $user_found = false;
            $user_data = null;
            $target_redirect = 'dashboard.php';

            $tables = ['doctors', 'login', 'registration'];

            foreach ($tables as $table) {
                $stmt = $conn->prepare("SELECT id, email, password FROM `{$table}` WHERE LOWER(email) = LOWER(?) LIMIT 1");
                if ($stmt) {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows === 1) {
                        $user_data = $result->fetch_assoc();
                        $user_found = true;
                        
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

                if ($password === $db_password || password_verify($password, $db_password)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id']       = $user_data['id'];
                    $_SESSION['email']         = $user_data['email'];
                    $_SESSION['user_name']     = 'Dr. Muntaka Mayesha';
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

// Session display defaults
$logged_user = $_SESSION['user_name'] ?? 'Dr. Muntaka Mayesha';

/* 
   5. CLEANUP & RESOURCE DEALLOCATION
*/
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inner Bloom - Provider Medical Dashboard</title>
    
    <!-- Google Fonts: Poppins for Headings, Inter for Body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            
            --primary-blue: #1d4ed8;       
            --dark-blue: #1e3a8a;          
            --light-blue: #eff6ff;         
            --border-color: #cbd5e1;       
            --text-dark: #0f172a;          
            --text-muted: #475569;         
            --white: #ffffff;
            --success-green: #15803d;
            --error-red: #b91c1c;
            --focus-ring: #2563eb;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body { 
            font-family: 'Inter', sans-serif;
            background-color: var(--light-blue);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        a:focus-visible, button:focus-visible, input:focus-visible {
            outline: 3px solid var(--focus-ring);
            outline-offset: 2px;
        }

        /* HEADER SECTION */
        .header {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 14px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .header-title {
            font-size: 22px;
            color: var(--white);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-badge {
            background-color: rgba(255, 255, 255, 0.18);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .btn-appointments {
            background-color: var(--white);
            color: var(--primary-blue);
        }

        .btn-appointments:hover {
            background-color: #f1f5f9;
        }

        .badge-count {
            background-color: var(--error-red);
            color: var(--white);
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
            margin-left: 4px;
        }

        .btn-logout {
            background-color: transparent;
            color: var(--white);
            border: 1.5px solid var(--white);
        }

        .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* MAIN CONTAINER */
        .main-container {
            max-width: 1280px;
            margin: 30px auto;
            padding: 0 20px;
            width: 100%;
            flex-grow: 1;
        }

        /* LOGIN CONTAINER */
        .login-card { 
            background: var(--white);
            width: 100%; 
            max-width: 440px; 
            margin: 40px auto;
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .form-group { 
            display: flex; 
            flex-direction: column; 
            margin-bottom: 20px; 
        }

        label { 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: var(--text-dark); 
            font-size: 14px; 
        }

        input { 
            padding: 12px; 
            border: 1.5px solid var(--border-color); 
            border-radius: 6px; 
            font-size: 14px; 
            color: var(--text-dark);
            background: var(--white);
        }

        .submit-btn { 
            padding: 12px; 
            width: 100%; 
            border: none; 
            border-radius: 6px; 
            background: var(--primary-blue); 
            color: var(--white); 
            font-size: 15px; 
            font-weight: 600; 
            cursor: pointer; 
            text-align: center; 
            text-decoration: none; 
            display: inline-block;
        }

        /* DASHBOARD GRID SYSTEM */
        .dashboard-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .today-card {
            border: 2px solid var(--primary-blue);
            background: #f8fafc;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--light-blue);
        }

        .card-header h2 {
            font-size: 18px;
            color: var(--dark-blue);
        }

        /* MONTH SELECTOR FOR CALENDAR */
        .month-selector {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .month-tab {
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: var(--white);
            color: var(--text-muted);
            cursor: pointer;
            white-space: nowrap;
        }

        .month-tab.active {
            background-color: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
        }

        /* CALENDAR SECTION */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            text-align: center;
        }

        .cal-day-name {
            font-weight: 700;
            font-size: 12px;
            color: var(--text-muted);
            padding: 6px 0;
            text-transform: uppercase;
        }

        .cal-date {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 8px 4px;
            min-height: 52px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white);
            font-size: 13px;
        }

        .cal-date.active-today {
            background-color: #dbeafe;
            border: 2px solid var(--primary-blue);
            font-weight: 700;
        }

        .cal-date .badge {
            background-color: var(--primary-blue);
            color: var(--white);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 10px;
            margin-top: 2px;
        }

        /* APPOINTMENT LISTS */
        .list-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 480px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--primary-blue);
            border-radius: 6px;
            background-color: var(--white);
            transition: all 0.3s ease;
        }

        .today-item {
            border-left: 4px solid var(--success-green);
            background-color: #f0fdf4;
        }

        .patient-info h3 {
            font-size: 15px;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .patient-info p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        .btn-accept { background-color: var(--success-green); color: var(--white); }
        .btn-decline { background-color: var(--error-red); color: var(--white); }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .message-box { 
            padding: 14px; 
            border-radius: 6px; 
            text-align: center; 
            font-weight: 500; 
            font-size: 14px; 
            margin-bottom: 20px; 
        }

        .error-msg { background: #fef2f2; color: var(--error-red); border: 1px solid #fecaca; }
        .success-msg { background: #f0fdf4; color: var(--success-green); border: 1px solid #bbf7d0; }
    </style>
</head>
<body>

    <!-- HEADER SECTION -->
    <header class="header" role="banner">
        <div class="header-brand">
           <img src="logo.png" alt="Inner Bloom Logo" class="header-logo" width="200" height="200">

            <a href="dashboard.php" class="header-title"><strong>InnerBloom</strong></a>
        </div>
        <nav class="header-actions" aria-label="Main Navigation">
            <div class="user-badge">
                <i class="fa-solid fa-user-doctor"></i>
                <span><?php echo htmlspecialchars($logged_user); ?></span>
            </div>
            
            <!-- (a) Direct Link to appoinment.html without counter linkage -->
            <a href="appoinment.html" class="nav-btn btn-appointments" aria-label="Go to Appointments">
                <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Appointments
            </a>
            <a href="index.html" class="nav-btn btn-logout" aria-label="Log out of system">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-container" id="main-content">
        
        <?php if (!empty($message)) { ?>
            <div class="message-box <?php echo $success ? 'success-msg' : 'error-msg'; ?>" role="alert">
                <p><i class="fa-solid <?php echo $success ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i> <?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php } ?>

        <?php if (!$success && $_SERVER['REQUEST_METHOD'] === 'POST') { ?>
            <div class="login-card">
                <h2>Login</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        <?php } else { ?>

            <!-- DASHBOARD TOP HEADER & SCHEDULING ACTION -->
            <div class="dashboard-top">
                <div>
                    <!-- Personalized Welcome Message -->
                    <h1>Welcome, <?php echo htmlspecialchars($logged_user); ?></h1>
                    <p style="color: var(--text-muted); font-size:14px;">Manage medical requests and schedule for 2026</p>
                </div>
                <a href="appoinment.html" class="nav-btn btn-appointments" style="background-color: var(--primary-blue); color: var(--white);">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>  Schedule
                </a>
            </div>

            <div class="dashboard-grid">
                
                <!-- CALENDAR CARD -->
                <section class="card" aria-labelledby="cal-heading">
                    <div class="card-header">
                        <h2 id="cal-heading"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> Schedule Calendar</h2>
                        <a href="appoinment.html" class="nav-btn btn-appointments" style="font-size:12px; padding: 4px 8px;">Schedule</a>
                    </div>
                    
                    <div class="month-selector">
                        <button class="month-tab active" onclick="switchMonth('aug', this)">August 2026</button>
                        <button class="month-tab" onclick="switchMonth('sep', this)">September 2026</button>
                        <button class="month-tab" onclick="switchMonth('oct', this)">October 2026</button>
                        <button class="month-tab" onclick="switchMonth('nov', this)">November 2026</button>
                        <button class="month-tab" onclick="switchMonth('dec', this)">December 2026</button>
                    </div>

                    <!-- AUGUST 2026 GRID -->
                    <div class="calendar-grid cal-month-grid" id="cal-aug">
                        <div class="cal-day-name">Sat</div><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div><div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div>
                        <div class="cal-date"><span>1</span><span class="badge">3 Appts</span></div><div class="cal-date"><span>2</span></div><div class="cal-date"><span>3</span><span class="badge">5 Appts</span></div><div class="cal-date"><span>4</span></div>
                        <div class="cal-date active-today"><span>5 Today</span><span class="badge">4 Appts</span></div>
                        <div class="cal-date"><span>6</span></div><div class="cal-date"><span>7</span></div><div class="cal-date"><span>8</span><span class="badge">4 Appts</span></div><div class="cal-date"><span>9</span></div><div class="cal-date"><span>10</span><span class="badge">6 Appts</span></div><div class="cal-date"><span>11</span></div><div class="cal-date"><span>12</span><span class="badge">1 Appt</span></div><div class="cal-date"><span>13</span></div><div class="cal-date"><span>14</span></div><div class="cal-date"><span>15</span><span class="badge">3 Appts</span></div><div class="cal-date"><span>16</span></div><div class="cal-date"><span>17</span><span class="badge">7 Appts</span></div><div class="cal-date"><span>18</span></div><div class="cal-date"><span>19</span></div><div class="cal-date"><span>20</span><span class="badge">2 Appts</span></div><div class="cal-date"><span>21</span></div><div class="cal-date"><span>22</span></div><div class="cal-date"><span>23</span><span class="badge">4 Appts</span></div><div class="cal-date"><span>24</span></div><div class="cal-date"><span>25</span></div><div class="cal-date"><span>26</span><span class="badge">3 Appts</span></div><div class="cal-date"><span>27</span></div><div class="cal-date"><span>28</span></div><div class="cal-date"><span>29</span></div><div class="cal-date"><span>30</span><span class="badge">2 Appts</span></div><div class="cal-date"><span>31</span></div>
                    </div>

                    <!-- SEPTEMBER 2026 GRID -->
                    <div class="calendar-grid cal-month-grid" id="cal-sep" style="display: none;">
                        <div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div>
                        <div class="cal-date"><span>1</span></div><div class="cal-date"><span>2</span></div><div class="cal-date"><span>3</span></div><div class="cal-date"><span>4</span></div><div class="cal-date"><span>5</span></div><div class="cal-date"><span>6</span></div><div class="cal-date"><span>7</span></div><div class="cal-date"><span>8</span></div><div class="cal-date"><span>9</span></div><div class="cal-date"><span>10</span></div><div class="cal-date"><span>11</span></div><div class="cal-date"><span>12</span></div><div class="cal-date"><span>13</span></div><div class="cal-date"><span>14</span></div><div class="cal-date"><span>15</span></div><div class="cal-date"><span>16</span></div><div class="cal-date"><span>17</span></div><div class="cal-date"><span>18</span></div><div class="cal-date"><span>19</span></div><div class="cal-date"><span>20</span></div><div class="cal-date"><span>21</span></div><div class="cal-date"><span>22</span></div><div class="cal-date"><span>23</span></div><div class="cal-date"><span>24</span></div><div class="cal-date"><span>25</span></div><div class="cal-date"><span>26</span></div><div class="cal-date"><span>27</span></div><div class="cal-date"><span>28</span></div><div class="cal-date"><span>29</span></div><div class="cal-date"><span>30</span></div>
                    </div>

                    <!-- OCTOBER 2026 GRID -->
                    <div class="calendar-grid cal-month-grid" id="cal-oct" style="display: none;">
                        <div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div><div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div>
                        <div class="cal-date"><span>1</span></div><div class="cal-date"><span>2</span></div><div class="cal-date"><span>3</span></div><div class="cal-date"><span>4</span></div><div class="cal-date"><span>5</span></div><div class="cal-date"><span>6</span></div><div class="cal-date"><span>7</span></div><div class="cal-date"><span>8</span></div><div class="cal-date"><span>9</span></div><div class="cal-date"><span>10</span></div><div class="cal-date"><span>11</span></div><div class="cal-date"><span>12</span></div><div class="cal-date"><span>13</span></div><div class="cal-date"><span>14</span></div><div class="cal-date"><span>15</span></div><div class="cal-date"><span>16</span></div><div class="cal-date"><span>17</span></div><div class="cal-date"><span>18</span></div><div class="cal-date"><span>19</span></div><div class="cal-date"><span>20</span></div><div class="cal-date"><span>21</span></div><div class="cal-date"><span>22</span></div><div class="cal-date"><span>23</span></div><div class="cal-date"><span>24</span></div><div class="cal-date"><span>25</span></div><div class="cal-date"><span>26</span></div><div class="cal-date"><span>27</span></div><div class="cal-date"><span>28</span></div><div class="cal-date"><span>29</span></div><div class="cal-date"><span>30</span></div><div class="cal-date"><span>31</span></div>
                    </div>

                    <!-- NOVEMBER 2026 GRID -->
                    <div class="calendar-grid cal-month-grid" id="cal-nov" style="display: none;">
                        <div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div><div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div>
                        <div class="cal-date"><span>1</span></div><div class="cal-date"><span>2</span></div><div class="cal-date"><span>3</span></div><div class="cal-date"><span>4</span></div><div class="cal-date"><span>5</span></div><div class="cal-date"><span>6</span></div><div class="cal-date"><span>7</span></div><div class="cal-date"><span>8</span></div><div class="cal-date"><span>9</span></div><div class="cal-date"><span>10</span></div><div class="cal-date"><span>11</span></div><div class="cal-date"><span>12</span></div><div class="cal-date"><span>13</span></div><div class="cal-date"><span>14</span></div><div class="cal-date"><span>15</span></div><div class="cal-date"><span>16</span></div><div class="cal-date"><span>17</span></div><div class="cal-date"><span>18</span></div><div class="cal-date"><span>19</span></div><div class="cal-date"><span>20</span></div><div class="cal-date"><span>21</span></div><div class="cal-date"><span>22</span></div><div class="cal-date"><span>23</span></div><div class="cal-date"><span>24</span></div><div class="cal-date"><span>25</span></div><div class="cal-date"><span>26</span></div><div class="cal-date"><span>27</span></div><div class="cal-date"><span>28</span></div><div class="cal-date"><span>29</span></div><div class="cal-date"><span>30</span></div>
                    </div>

                    <!-- DECEMBER 2026 GRID -->
                    <div class="calendar-grid cal-month-grid" id="cal-dec" style="display: none;">
                        <div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div>
                        <div class="cal-date"><span>1</span></div><div class="cal-date"><span>2</span></div><div class="cal-date"><span>3</span></div><div class="cal-date"><span>4</span></div><div class="cal-date"><span>5</span></div><div class="cal-date"><span>6</span></div><div class="cal-date"><span>7</span></div><div class="cal-date"><span>8</span></div><div class="cal-date"><span>9</span></div><div class="cal-date"><span>10</span></div><div class="cal-date"><span>11</span></div><div class="cal-date"><span>12</span></div><div class="cal-date"><span>13</span></div><div class="cal-date"><span>14</span></div><div class="cal-date"><span>15</span></div><div class="cal-date"><span>16</span></div><div class="cal-date"><span>17</span></div><div class="cal-date"><span>18</span></div><div class="cal-date"><span>19</span></div><div class="cal-date"><span>20</span></div><div class="cal-date"><span>21</span></div><div class="cal-date"><span>22</span></div><div class="cal-date"><span>23</span></div><div class="cal-date"><span>24</span></div><div class="cal-date"><span>25</span></div><div class="cal-date"><span>26</span></div><div class="cal-date"><span>27</span></div><div class="cal-date"><span>28</span></div><div class="cal-date"><span>29</span></div><div class="cal-date"><span>30</span></div><div class="cal-date"><span>31</span></div>
                    </div>
                </section>

                <!-- (2) APPOINTMENT REQUESTS SECTION -->
                <section class="card" aria-labelledby="requests-heading">
                    <div class="card-header">
                        <h2 id="requests-heading">
                            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i> 
                            Appointment Requests (<span id="section-request-count">20</span>)
                        </h2>
                    </div>
                    <div class="list-container" id="requests-list">
                        <!-- Dynamic JavaScript insertion for request items -->
                    </div>
                </section>

            </div>

            <!-- TODAY'S APPOINTMENTS SECTION -->
            <section class="card today-card" aria-labelledby="today-heading">
                <div class="card-header">
                    <h2 id="today-heading" style="color: var(--primary-blue);">
                        <i class="fa-solid fa-calendar-day" aria-hidden="true"></i> 
                        Today's Appointments - August 05, 2026 
                    </h2>
                    <span class="badge-count" style="background-color: var(--success-green); padding: 4px 10px; font-size:12px;">Active Today</span>
                </div>
                <div class="list-container">
                    <div class="list-item today-item">
                        <div class="patient-info">
                            <h3>John Doe</h3>
                            <p><strong>Today - 09:00 AM</strong> | Consultation</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Start Session</a>
                    </div>
                    <div class="list-item today-item">
                        <div class="patient-info">
                            <h3>Jane Smith</h3>
                            <p><strong>Today - 10:30 AM</strong> | Routine Checkup</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Start Session</a>
                    </div>
                    <div class="list-item today-item">
                        <div class="patient-info">
                            <h3>Carlos Martinez</h3>
                            <p><strong>Today - 01:15 PM</strong> | Follow-up</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Start Session</a>
                    </div>
                    <div class="list-item today-item">
                        <div class="patient-info">
                            <h3>Sophia Patel</h3>
                            <p><strong>Today - 03:00 PM</strong> | Dermatology Assessment</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Start Session</a>
                    </div>
                    <div class="list-item today-item">
    <div class="patient-info">
        <h3>Michael Johnson</h3>
        <p><strong>Today - 09:00 AM</strong> | General Consultation</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Emily Davis</h3>
        <p><strong>Today - 11:15 AM</strong> | Therapy Session</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Daniel Wilson</h3>
        <p><strong>Today - 12:30 PM</strong> | Routine Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Sophia Martinez</h3>
        <p><strong>Today - 01:45 PM</strong> | Pediatric Consultation</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>James Anderson</h3>
        <p><strong>Today - 02:30 PM</strong> | Dental Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Olivia Taylor</h3>
        <p><strong>Today - 03:15 PM</strong> | Eye Examination</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>William Thomas</h3>
        <p><strong>Today - 04:00 PM</strong> | Follow-up Visit</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Ava Hernandez</h3>
        <p><strong>Today - 04:45 PM</strong> | Therapy Session</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Benjamin Moore</h3>
        <p><strong>Today - 05:30 PM</strong> | General Examination</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

<div class="list-item today-item">
    <div class="patient-info">
        <h3>Isabella Clark</h3>
        <p><strong>Today - 06:15 PM</strong> | Routine Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Start Session</a>
</div>

                </div>
            </section>

            <!-- UPCOMING APPOINTMENTS SECTION -->
            <section class="card" aria-labelledby="upcoming-heading">
                <div class="card-header">
                    <h2 id="upcoming-heading">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> 
                        Upcoming August Appointments <span id="upcoming-count"></span>
                    </h2>
                    <a href="appoinment.html" class="nav-btn btn-appointments" style="font-size:12px; padding: 4px 8px;">View All</a>
                </div>
                <div class="list-container" id="upcoming-list">
                    <div class="list-item">
                        <div class="patient-info">
                            <h3>Liam Johnson</h3>
                            <p>August 06, 2026 - 09:30 AM | Therapy Session</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Details</a>
                    </div>
                    <div class="list-item">
                        <div class="patient-info">
                            <h3>Olivia Brown</h3>
                            <p>August 06, 2026 - 11:00 AM | Cardiology Consultation</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Details</a>
                    </div>
                    <div class="list-item">
                        <div class="patient-info">
                            <h3>Ethan Davis</h3>
                            <p>August 06, 2026 - 02:30 PM | General Examination</p>
                        </div>
                        <a href="#" class="btn-action btn-accept">Details</a>
                    </div>
                    <div class="list-item">
    <div class="patient-info">
        <h3>Olivia Johnson</h3>
        <p>August 07, 2026 - 11:00 AM | Dental Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Liam Brown</h3>
        <p>August 08, 2026 - 03:15 PM | Eye Examination</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Ava Wilson</h3>
        <p>August 09, 2026 - 09:30 AM | Therapy Session</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Noah Thompson</h3>
        <p>August 10, 2026 - 01:00 PM | General Consultation</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Emma White</h3>
        <p>August 11, 2026 - 04:45 PM | Follow-up Visit</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>James Harris</h3>
        <p>August 12, 2026 - 10:15 AM | Routine Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Isabella Clark</h3>
        <p>August 13, 2026 - 02:00 PM | Pediatric Consultation</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>William Lewis</h3>
        <p>August 14, 2026 - 11:45 AM | Orthopedic Checkup</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Sophia Walker</h3>
        <p>August 15, 2026 - 03:30 PM | Therapy Session</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

<div class="list-item">
    <div class="patient-info">
        <h3>Benjamin Hall</h3>
        <p>August 16, 2026 - 09:00 AM | General Examination</p>
    </div>
    <a href="#" class="btn-action btn-accept">Details</a>
</div>

                </div>
            </section>

        <?php } ?>

    </main>

    <!-- DYNAMIC REQUEST & CALENDAR INTERACTION JAVASCRIPT -->
    <script>
        // Initial Appointment Requests Array (Section 2 Only)
        let requestsData = [
            { id: 1, name: "Sarah Jenkins", date: "Aug 06, 2026 - 10:00 AM", type: "Consultation" },
            { id: 2, name: "Michael Chang", date: "Aug 06, 2026 - 11:30 AM", type: "Follow-up" },
            { id: 3, name: "Emily Watson", date: "Aug 07, 2026 - 02:00 PM", type: "Checkup" },
            { id: 4, name: "David Miller", date: "Aug 07, 2026 - 03:30 PM", type: "Therapy" },
            { id: 5, name: "Jessica Taylor", date: "Aug 08, 2026 - 09:15 AM", type: "Consultation" },
            { id: 6, name: "Robert Wilson", date: "Aug 08, 2026 - 01:00 PM", type: "Follow-up" },
            { id: 7, name: "Amanda White", date: "Aug 09, 2026 - 11:00 AM", type: "Routine Checkup" },
            { id: 8, name: "James Anderson", date: "Aug 10, 2026 - 09:00 AM", type: "Pediatrics" },
            { id: 9, name: "Patricia Thomas", date: "Aug 10, 2026 - 10:45 AM", type: "Consultation" },
            { id: 10, name: "Christopher Jackson", date: "Aug 11, 2026 - 02:15 PM", type: "Checkup" },
            { id: 11, name: "Barbara Harris", date: "Aug 12, 2026 - 11:30 AM", type: "Follow-up" },
            { id: 12, name: "Daniel Martin", date: "Aug 13, 2026 - 03:00 PM", type: "Checkup" },
            { id: 13, name: "Nancy Thompson", date: "Aug 14, 2026 - 10:00 AM", type: "Consultation" },
            { id: 14, name: "Matthew Garcia", date: "Aug 15, 2026 - 01:30 PM", type: "Checkup" },
            { id: 15, name: "Laura Martinez", date: "Aug 17, 2026 - 09:00 AM", type: "Checkup" },
            { id: 16, name: "Kevin Robinson", date: "Aug 17, 2026 - 11:15 AM", type: "Routine Checkup" },
            { id: 17, name: "Karen Clark", date: "Aug 18, 2026 - 02:00 PM", type: "Follow-up" },
            { id: 18, name: "Brian Rodriguez", date: "Aug 20, 2026 - 10:30 AM", type: "Consultation" },
            { id: 19, name: "Lisa Lewis", date: "Aug 23, 2026 - 04:00 PM", type: "Therapy" },
            { id: 20, name: "Steven Lee", date: "Aug 26, 2026 - 01:00 PM", type: "Follow-up" },
            { id: 21, name: "Angela Walker", date: "Aug 27, 2026 - 09:45 AM", type: "Checkup" },
            { id: 22, name: "Paul Young", date: "Aug 28, 2026 - 02:30 PM", type: "Consultation" },
            { id: 23, name: "Emily King", date: "Aug 29, 2026 - 11:00 AM", type: "Therapy" },
            { id: 24, name: "Joshua Scott", date: "Aug 30, 2026 - 03:15 PM", type: "Follow-up" },
            { id: 25, name: "Sophia Green", date: "Sep 01, 2026 - 10:00 AM", type: "Routine Checkup" },
            { id: 26, name: "Andrew Adams", date: "Sep 02, 2026 - 01:00 PM", type: "Consultation" },
            { id: 27, name: "Olivia Baker", date: "Sep 03, 2026 - 04:30 PM", type: "Therapy" },
            { id: 28, name: "David Gonzalez", date: "Sep 04, 2026 - 09:00 AM", type: "Checkup" },
            { id: 29, name: "Megan Perez", date: "Sep 05, 2026 - 12:15 PM", type: "Follow-up" },
            { id: 30, name: "Christopher Hall", date: "Sep 06, 2026 - 02:45 PM", type: "Consultation" }

        ];

        let confirmedCount = 3;

        function renderRequests() {
            //chatch the container for requests
            const container = document.getElementById('requests-list');
            // Catch the tag that shows the number of requests
            const sectionCount = document.getElementById('section-request-count');

            // Updates ONLY the request count inside section 2
            if (sectionCount) {
                sectionCount.textContent = requestsData.length;
            }
            //Remove the specific request from the array.
            if (requestsData.length === 0) {
                container.innerHTML = '<div class="empty-state"><i class="fa-solid fa-circle-check" style="font-size:32px; color: var(--success-green); margin-bottom:10px;"></i><p>All appointment requests have been addressed!</p></div>';
                return;
            }
//Create an HTML box for a request.
            container.innerHTML = requestsData.map(req => `
                 <div class="list-item" id="request-item-${req.id}">
                        <h3>${req.name}</h3>
                        <p>Requested: ${req.date} | ${req.type}</p>
                    </div>
                    <div class="action-btns">
                        <button class="btn-action btn-accept" onclick="acceptRequest(${req.id})">Accept</button>
                        <button class="btn-action btn-decline" onclick="deleteRequest(${req.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Action: Delete/Decline Request (Updates Section 2 only)
        function deleteRequest(id) {
            requestsData = requestsData.filter(item => item.id !== id);
            renderRequests();
        }

        // Action: Accept Request
        function acceptRequest(id) {
            const reqToAccept = requestsData.find(item => item.id === id);
            if (reqToAccept) {
                deleteRequest(id);

                confirmedCount++;
                //To show the updated confirmed count on the screen.
                document.getElementById('upcoming-count').textContent = confirmedCount;

                const upcomingList = document.getElementById('upcoming-list');
                const newItem = document.createElement('div');
                newItem.className = 'list-item';
                newItem.innerHTML = `
                    <div class="patient-info">
                        <h3>${reqToAccept.name}</h3>
                        <p>${reqToAccept.date} | ${reqToAccept.type}</p>
                    </div>
                    <a href="appoinment.html" class="btn-action btn-accept">Details</a>
                `;
                upcomingList.prepend(newItem);
            }
        }

        // Calendar Month Switcher
        function switchMonth(monthKey, btnElement) {
            document.querySelectorAll('.month-tab').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            document.querySelectorAll('.cal-month-grid').forEach(grid => grid.style.display = 'none');
            
            const targetGrid = document.getElementById(`cal-${monthKey}`);
            if (targetGrid) {
                targetGrid.style.display = 'grid';
            }
        }

        // Initial Render
        document.addEventListener('DOMContentLoaded', () => {
            renderRequests();
        });
    </script>
</body>
</html>