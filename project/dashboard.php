<?php
session_start();
require_once 'db_connect.php';

$user_email = $_SESSION['email'] ?? 'muntaka@example.com';
$user_name = $_SESSION['name'] ?? 'Muntaka Mayesha';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - INNERBLOOM</title>
    
    <!-- Mandatory Fonts: Poppins (Headlines) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-main: #f1f5f9;
            --sidebar-width: 260px;
            --text-dark: #0f172a;
            --text-muted: #475569;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: #cbd5e1;
            --border-radius: 16px;
            --font-headline: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
            --focus-ring: 3px solid #60a5fa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #e2e8f0 0%, #f8fafc 100%);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ACCESSIBILITY FOCUS STYLES (Section 508 / WCAG AA) */
        a:focus-visible, button:focus-visible, input:focus-visible, [tabindex="0"]:focus-visible {
            outline: var(--focus-ring);
            outline-offset: 2px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-headline);
            color: var(--text-dark);
        }

        /* 4. SIDEBAR MUST BE BLUE */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            border-right: 1px solid #1d4ed8;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            color: #ffffff;
        }

        /* 3. MAKE LOGO MORE ZOOM AND BIGGER */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 30px;
            padding-left: 2px;
        }

        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            transform: scale(1.15);
        }

        .logo-text h2 {
            font-size: 15px;
            font-weight: 500;
            color: #f0f4f5;
            letter-spacing: 0.8px;
            line-height: 1.1;
        }

        .logo-text p {
            font-size: 10px;
            color: #93c5fd;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            color: #121b39;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-item a:hover, .nav-item.active a {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item a i {
            width: 20px;
            font-size: 16px;
            color: #93c5fd;
        }

        .nav-item.active a i {
            color: #ffffff;
        }

        .badge {
            background-color: #ef4444;
            color: #aedef3;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        /* 5. ADD A SUBSCRIPTION BUTTON IN SIDEBAR */
        .subscription-box {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin-top: 20px;
        }

        .subscription-box h4 {
            color: #ffffff;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .subscription-box p {
            color: #bfdbfe;
            font-size: 11px;
            margin-bottom: 12px;
        }

        .btn-subscribe {
            width: 100%;
            background: linear-gradient(90deg, #f59e0b, #d97706);
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: transform 0.2s, background 0.2s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .btn-subscribe:hover {
            transform: translateY(-1px);
            background: linear-gradient(90deg, #d97706, #b45309);
        }

        /* MAIN VIEWPORT */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 24px 36px;
            max-width: calc(100vw - var(--sidebar-width));
        }

        /* TOP HEADER BLUE BAR */
        .top-header {
            background: linear-gradient(90deg, #1e40af 0%, #2563eb 100%);
            border-radius: var(--border-radius);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
        }

        .header-left-actions {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-logo img {
            width: 100px;
            height: 100px;
            background: #142937;
          border-radius: 60%;
            padding: 5px;
        }
         
        .header-logo span {
            color: #ffffff;
            font-family: var(--font-headline);
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .search-container {
            position: relative;
            width: 280px;
        }

        .search-container input {
            width: 100%;
            padding: 8px 16px 8px 36px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            outline: none;
            font-size: 13px;
        }

        .search-container input::placeholder {
            color: #cbd5e1;
        }

        .search-container i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: 13px;
        }

        .header-right-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile-top {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
        }

        .user-info-text h4 {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }

        .user-info-text p {
            font-size: 11px;
            color: #93c5fd;
        }

        /* LOGOUT BUTTON IN HEADER */
        .logout-header-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            color: #ffffff;
            background-color: rgba(239, 68, 68, 0.9);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .logout-header-btn:hover {
            background-color: #dc2626;
        }

        /* WELCOME BANNER */
        .welcome-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .welcome-row .avatar-large {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
        }

        .welcome-text h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .welcome-text p {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* MAIN TWO-COLUMN DASHBOARD GRID */
        .dashboard-main-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr;
            gap: 24px;
            align-items: start;
        }

        .col-stack {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* METRICS CARDS */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .metric-card {
            padding: 16px 12px;
            border-radius: 12px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .card-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .card-teal { background: linear-gradient(135deg, #0d9488, #0f766e); }
        .card-blue { background: linear-gradient(135deg, #2563eb, #1e40af); }

        .metric-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .metric-info h2 {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.1;
        }

        .metric-info p {
            font-size: 10px;
            opacity: 0.95;
        }

        /* GLASSMORPHISM CARDS */
        .content-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: var(--border-radius);
            padding: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
        }

        .card-title i {
            color: var(--primary);
        }

        .btn-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        /*  NO PENDING APPOINTMENT TAB OR LIST ITEM */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 6px;
        }

        .tab-btn.active {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .appointment-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .appointment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: rgba(255,255,255,0.7);
            border: 1px solid var(--border-color);
            border-radius: 10px;
        }

        .doctor-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doc-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .doc-name {
            font-size: 13px;
            font-weight: 600;
        }

        .doc-specialty {
            font-size: 11px;
            color: var(--text-muted);
        }

        .app-timing {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 11px;
            color: var(--text-muted);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-badge.confirmed { background: #dcfce7; color: #15803d; }
        .status-badge.completed { background: #e0e7ff; color: #3730a3; }

        /*  CALENDAR WITH MONTH NAVIGATION CONTROLS */
        .mood-selector {
            display: flex;
            justify-content: space-between;
            gap: 6px;
            background: var(--primary-light);
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .mood-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 4px;
            border: 2px solid transparent;
            background: #ffffff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .mood-btn i {
            font-size: 18px;
        }

        .mood-btn.selected {
            border-color: var(--primary);
            background: #eff6ff;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .cal-nav-btn {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-dark);
            font-size: 12px;
            transition: background-color 0.2s;
        }

        .cal-nav-btn:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            text-align: center;
        }

        .day-name {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            padding-bottom: 6px;
        }

        .cal-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: border-color 0.2s;
        }

        .cal-day:hover {
            border-color: var(--primary);
        }

        .cal-day .mood-icon {
            font-size: 10px;
            margin-top: 2px;
        }

        /* USER INFORMATION SECTION */
        .info-layout-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 16px;
            margin-top: 8px;
        }

        .profile-side-card {
            text-align: center;
            border-right: 1px solid var(--border-color);
            padding-right: 12px;
        }

        .avatar-huge {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 8px;
        }

        .data-column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .data-cell {
            display: flex;
            flex-direction: column;
        }

        .cell-label {
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .cell-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* FOOTER BANNER */
        .footer-banner {
            margin-top: 24px;
            background: linear-gradient(90deg, #dbeafe 0%, #e0e7ff 100%);
            border-radius: var(--border-radius);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>

    <!--  SIDEBAR NAVIGATION -->
    <aside class="sidebar" aria-label="Main Navigation">
        
        <div class="logo-area">
            <img src="logo.png" alt="INNERBLOOM Logo" class="logo-img" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3062/3062634.png'">
            <div class="logo-text">
                <h2>INNERBLOOM</h2>
                <p>Mental Wellness Partner</p>
            </div>
        </div>

        <ul class="nav-links">
           
            <li class="nav-item">
                <a href="myappointments.php">
                    <div class="nav-left"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i><span>Appointments</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="bookappointment.php">
                    <div class="nav-left"><i class="fa-solid fa-user-doctor" aria-hidden="true"></i><span>Doctor List</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="myrecords.php">
                    <div class="nav-left"><i class="fa-regular fa-folder-open" aria-hidden="true"></i><span>My Records</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="journal.php">
                    <div class="nav-left"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i><span>Reflect & Record</span></div> 
                </a>
            </li>
             
            <li class="nav-item">
                <a href="medicationreminder.php">
                    <div class="nav-left"><i class="fa-solid fa-capsules" aria-hidden="true"></i><span>Medications</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="questionnaire.php">
                    <div class="nav-left"><i class="fa-regular fa-face-smile" aria-hidden="true"></i><span>Mood Tracker</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <div class="nav-left"><i class="fa-solid fa-sliders" aria-hidden="true"></i><span>Settings</span></div>
                </a>
            </li>
             
            <li class="nav-item">
                <a href="breathing.php">
                    <div class="nav-left"><i class="fa-solid fa-wind" aria-hidden="true"></i><span>Breathing Exercises</span></div>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <div class="nav-left"><i class="fa-regular fa-circle-question" aria-hidden="true"></i><span>Help & Support</span></div>
                </a>
            </li>
        </ul>

        <!-- 5. SUBSCRIPTION BUTTON IN SIDEBAR -->
        <div class="subscription-box">
            <h4>Premium Access</h4>
            <p>Get unlimited therapy sessions and insights</p>
            <button class="btn-subscribe" onclick="window.location.href='subscription.php'">
                <i class="fa-solid fa-crown" aria-hidden="true"></i> Subscribe Now
            </button>
        </div>
    </aside>

    <!-- MAIN VIEWPORT -->
    <main class="main-content" id="main-content">
        
        <!-- HEADER WITH BLUE NAVIGATION BAR -->
        <header class="top-header">
            <div class="header-left-actions">
                <div class="header-logo">
                    <img src="logo.png" alt="" aria-hidden="true" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3062/3062634.png'">
                    <span>INNERBLOOM</span>
                </div>
            </div>
            
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="text" placeholder="Search anything..." aria-label="Search dashboard">
            </div>

            <div class="header-right-actions">
                <div class="user-profile-top" tabindex="0" role="region" aria-label="User Profile Details">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="avatar" alt="Muntaka Mayesha Avatar">
                    <div class="user-info-text">
                        <!-- 6. SANITIZE USER NAME TO PREVENT XSS -->
                        <h4><?php echo htmlspecialchars($user_name); ?></h4>
                        <p>User</p>
                    </div>
                </div>

                <a href="index.html" class="logout-header-btn" aria-label="Logout of session">
                    <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                    <span>Logout</span>
                </a>
            </div>
        </header>

        <!-- WELCOME HEADER -->
        <section class="welcome-row">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="avatar-large" alt="">
            <div class="welcome-text">
                <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                <p>Take care of your mind. You matter every single day.</p>
            </div>
        </section>

        <!-- DASHBOARD MAIN 2-COLUMN GRID -->
        <div class="dashboard-main-grid">
            
            <!-- LEFT COLUMN: METRICS, APPOINTMENTS & USER INFO -->
            <div class="col-stack">
                
                <!-- METRICS GRID -->
                <section class="metrics-grid" aria-label="Summary Statistics">
                    <div class="metric-card card-purple">
                        <div class="metric-icon-box"><i class="fa-regular fa-calendar" aria-hidden="true"></i></div>
                        <div class="metric-info">
                            <h2>02</h2>
                            <p>Upcoming</p>
                        </div>
                    </div>
                    <div class="metric-card card-teal">
                        <div class="metric-icon-box"><i class="fa-regular fa-circle-check" aria-hidden="true"></i></div>
                        <div class="metric-info">
                            <h2>05</h2>
                            <p>Completed</p>
                        </div>
                    </div>
                    <div class="metric-card card-blue">
                        <div class="metric-icon-box"><i class="fa-solid fa-heart" aria-hidden="true"></i></div>
                        <div class="metric-info">
                            <h2>72%</h2>
                            <p>Wellness Score</p>
                        </div>
                    </div>
                </section>

                <!-- 2. APPOINTMENTS MANAGEMENT CARD (NO PENDING OPTION) -->
                <section class="content-card" aria-labelledby="app-heading">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                            <h3 id="app-heading">Appointments Overview</h3>
                        </div>
                        <button class="btn-link" onclick="window.location.href='myappointments.php'">See More <i class="fa-solid fa-angle-right" aria-hidden="true"></i></button>
                    </div>

                    <div class="filter-tabs" role="tablist" aria-label="Appointment Categories">
                        <button class="tab-btn active" role="tab" aria-selected="true" onclick="filterAppointments('all', this)">All</button>
                        <button class="tab-btn" role="tab" aria-selected="false" onclick="filterAppointments('upcoming', this)">Upcoming (2)</button>
                        <button class="tab-btn" role="tab" aria-selected="false" onclick="filterAppointments('completed', this)">Completed (5)</button>
                    </div>

                    <div class="appointment-list" id="appointment-container">
                        <div class="appointment-item" data-category="upcoming">
                            <div class="doctor-profile">
                                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=100&auto=format&fit=crop&q=80" class="doc-avatar" alt="Dr. Sarah Johnson">
                                <div>
                                    <h4 class="doc-name">Dr. Sarah Johnson</h4>
                                    <p class="doc-specialty">Psychologist</p>
                                </div>
                            </div>
                            <div class="app-timing">
                                <div><i class="fa-regular fa-calendar" aria-hidden="true"></i> Aug 12, 2026</div>
                                <div><i class="fa-regular fa-clock" aria-hidden="true"></i> 10:00 AM</div>
                            </div>
                            <span class="status-badge confirmed">Confirmed</span>
                        </div>

                        <div class="appointment-item" data-category="upcoming">
                            <div class="doctor-profile">
                                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=100&auto=format&fit=crop&q=80" class="doc-avatar" alt="Dr. Michael Brown">
                                <div>
                                    <h4 class="doc-name">Dr. Michael Brown</h4>
                                    <p class="doc-specialty">Therapist</p>
                                </div>
                            </div>
                            <div class="app-timing">
                                <div><i class="fa-regular fa-calendar" aria-hidden="true"></i> Aug 14, 2026</div>
                                <div><i class="fa-regular fa-clock" aria-hidden="true"></i> 02:30 PM</div>
                            </div>
                            <span class="status-badge confirmed">Confirmed</span>
                        </div>

                        <div class="appointment-item" data-category="completed" style="display:none;">
                            <div class="doctor-profile">
                                <img src="https://images.unsplash.com/photo-1594824813566-88855ce7890b?w=100&auto=format&fit=crop&q=80" class="doc-avatar" alt="Dr. Emily Davis">
                                <div>
                                    <h4 class="doc-name">Dr. Emily Davis</h4>
                                    <p class="doc-specialty">Counselor</p>
                                </div>
                            </div>
                            <div class="app-timing">
                                <div><i class="fa-regular fa-calendar" aria-hidden="true"></i> Jul 28, 2026</div>
                                <div><i class="fa-regular fa-clock" aria-hidden="true"></i> 11:00 AM</div>
                            </div>
                            <span class="status-badge completed">Completed</span>
                        </div>
                    </div>
                </section>

                <!-- YOUR INFORMATION -->
                <section class="content-card" aria-labelledby="user-info-heading">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <h3 id="user-info-heading">Your Information</h3>
                        </div>
                        <button class="btn-link" onclick="window.location.href='settings.php'"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit Profile</button>
                    </div>

                    <div class="info-layout-grid">
                        <div class="profile-side-card">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" class="avatar-huge" alt="">
                            <h3 style="font-size: 14px;"><?php echo htmlspecialchars($user_name); ?></h3>
                            <p style="font-size: 11px; color: var(--text-muted);">CSE Student</p>
                        </div>

                        <div class="data-column">
                            <div class="data-cell">
                                <span class="cell-label">Email</span>
                                <!-- 6. SANITIZE USER EMAIL TO PREVENT XSS -->
                                <span class="cell-value"><?php echo htmlspecialchars($user_email); ?></span>
                            </div>
                            <div class="data-cell">
                                <span class="cell-label">Location</span>
                                <span class="cell-value">Dhaka, Bangladesh</span>
                            </div>
                            <div class="data-cell">
                                <span class="cell-label">Emergency Contact</span>
                                <span class="cell-value">+880 1700-000000</span>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            <!-- RIGHT COLUMN: CALENDAR MOOD TRACKER -->
            <div class="col-stack">
                
                <section class="content-card" aria-labelledby="mood-heading">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-regular fa-face-smile" aria-hidden="true"></i>
                            <h3 id="mood-heading">Interactive Mood Calendar</h3>
                        </div>
                    </div>

                    <p style="font-size: 11px; color: var(--text-muted); margin-bottom: 12px;">Select a mood below, then click any date to log or update it.</p>

                    <!-- MOOD SELECTION PALETTE -->
                    <div class="mood-selector" role="radiogroup" aria-label="Select Mood">
                        <button class="mood-btn selected" data-mood="happy" data-icon="fa-face-smile" onclick="selectMood(this)">
                            <i class="fa-regular fa-face-smile" style="color: #16a34a;" aria-hidden="true"></i>
                            <span>Happy</span>
                        </button>
                        <button class="mood-btn" data-mood="neutral" data-icon="fa-face-meh" onclick="selectMood(this)">
                            <i class="fa-regular fa-face-meh" style="color: #ca8a04;" aria-hidden="true"></i>
                            <span>Neutral</span>
                        </button>
                        <button class="mood-btn" data-mood="sad" data-icon="fa-face-frown" onclick="selectMood(this)">
                            <i class="fa-regular fa-face-frown" style="color: #dc2626;" aria-hidden="true"></i>
                            <span>Sad</span>
                        </button>
                        <button class="mood-btn" data-mood="excited" data-icon="fa-face-laugh-beam" onclick="selectMood(this)">
                            <i class="fa-regular fa-face-laugh-beam" style="color: #2563eb;" aria-hidden="true"></i>
                            <span>Excited</span>
                        </button>
                    </div>

                    <!-- 1. CALENDAR HEADER WITH MONTH CHANGE CONTROLS (< >) -->
                    <div class="calendar-header">
                        <button class="cal-nav-btn" onclick="changeMonth(-1)" aria-label="Previous Month">
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <h4 style="font-size: 14px; font-weight: 700;" id="current-month-year">August 2026</h4>
                        <button class="cal-nav-btn" onclick="changeMonth(1)" aria-label="Next Month">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="calendar-grid" id="calendar-days-grid" role="grid" aria-labelledby="current-month-year">
                        <!-- Dynamic Calendar Rendered Via JS -->
                    </div>
                </section>

                <!-- DAILY WELLNESS TIP CARD -->
                <section class="content-card" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff;">
                    <div class="card-title" style="color: #ffffff; margin-bottom: 8px;">
                        <i class="fa-solid fa-seedling" style="color: #7dd3fc;" aria-hidden="true"></i>
                        <h3 style="color: #ffffff;">Daily Wellness Tip</h3>
                    </div>
                    <p style="font-size: 12px; line-height: 1.5; opacity: 0.95;">Take a deep breath and give yourself credit for how far you have come. Progression happens step by step.</p>
                </section>

            </div>

        </div>

        <!-- FOOTER BANNER -->
        <footer class="footer-banner">
            <div>
                <h3 style="font-size: 13px; color: var(--primary);">"Small daily actions lead to long-term transformation."</h3>
                <p style="font-size: 11px; color: var(--text-muted);">Keep prioritizing your mental wellbeing with INNERBLOOM. 💜</p>
            </div>
            <i class="fa-solid fa-leaf" style="font-size: 24px; color: var(--primary);" aria-hidden="true"></i>
        </footer>

    </main>

    <!-- INTERACTIVE SCRIPTS -->
    <script>
        let selectedMoodData = {
            mood: 'happy',
            icon: 'fa-face-smile',
            color: '#16a34a'
        };

        let currentDate = new Date(2026, 7, 1); // August 2026
        const trackedMoods = {}; // Stores recorded moods per date key (e.g. "2026-7-15")

        function selectMood(button) {
            document.querySelectorAll('.mood-btn').forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');

            const iconElement = button.querySelector('i');
            selectedMoodData = {
                mood: button.getAttribute('data-mood'),
                icon: button.getAttribute('data-icon'),
                color: iconElement.style.color
            };
        }

        /* 1. DYNAMIC CALENDAR RENDER & MONTH NAVIGATION */
        function renderCalendar() {
            const monthYearLabel = document.getElementById('current-month-year');
            const calendarGrid = document.getElementById('calendar-days-grid');
            
            const monthNames = ["January", "February", "March", "April", "May", "June", 
                                "July", "August", "September", "October", "November", "December"];
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            monthYearLabel.textContent = `${monthNames[month]} ${year}`;

            const firstDayIndex = new Date(year, month, 1).getDay();
            const lastDay = new Date(year, month + 1, 0).getDate();

            let gridHTML = `
                <div class="day-name">Su</div>
                <div class="day-name">Mo</div>
                <div class="day-name">Tu</div>
                <div class="day-name">We</div>
                <div class="day-name">Th</div>
                <div class="day-name">Fr</div>
                <div class="day-name">Sa</div>
            `;
//GAP-->1ST DAY OF MONTH  
            for (let i = 0; i < firstDayIndex; i++) {
                gridHTML += `<div class="cal-day empty" style="visibility:hidden;"></div>`;
            }
//Full-Month Calendar Tracker with Saved Mood Icons

            for (let day = 1; day <= lastDay; day++) {
                const dateKey = `${year}-${month}-${day}`;
                const savedMood = trackedMoods[dateKey];

                let moodHTML = '';
                if (savedMood) {
                    moodHTML = `<i class="fa-regular ${savedMood.icon}" style="color: ${savedMood.color}"></i>`;
                }

                gridHTML += `
                    <div class="cal-day" tabindex="0" role="gridcell" aria-label="${monthNames[month]} ${day}, ${year}" onclick="trackMoodOnDate(this, ${year}, ${month}, ${day})">
                        <span>${day < 10 ? '0' + day : day}</span>
                        <div class="mood-icon">${moodHTML}</div>
                    </div>
                `;
            }

            calendarGrid.innerHTML = gridHTML;
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            renderCalendar();
        }
//Track Mood On Date
        function trackMoodOnDate(dayElement, year, month, day) {
            const dateKey = `${year}-${month}-${day}`;
            trackedMoods[dateKey] = { ...selectedMoodData };

            const moodContainer = dayElement.querySelector('.mood-icon');
            moodContainer.innerHTML = `<i class="fa-regular ${selectedMoodData.icon}" style="color: ${selectedMoodData.color}"></i>`;
        }

        /* 2. FILTER APPOINTMENTS */
        function filterAppointments(category, tabBtn) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            tabBtn.classList.add('active');
            tabBtn.setAttribute('aria-selected', 'true');

            const items = document.querySelectorAll('.appointment-item');
            items.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Initial Calendar Load
        renderCalendar();
    </script>
</body>
</html>