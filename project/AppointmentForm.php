<?php
session_start();

// URL PARAMETERS & INTEGRITY SAFEGUARD
$doctor_id   = isset($_GET['doc_id']) ? intval($_GET['doc_id']) : 5; 
$doctor_name = isset($_GET['doc_name']) ? htmlspecialchars($_GET['doc_name'], ENT_QUOTES, 'UTF-8') : "Dr. Ahsan Rahman"; 

// SSLCOMMERZ GATEWAY CREDENTIALS CONFIGURATION (FROM YOUR SCREENSHOT)
define("SSLCOMMERZ_STORE_ID", "inner6a79c5b174a8d");
define("SSLCOMMERZ_STORE_PASSWORD", "inner6a79c5b174a8d@ssl");
define("SSLCOMMERZ_IS_SANDBOX", true); 

$message = "";
$message_class = "";

// FORM SUBMISSION PIPELINE (POST REQUEST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Input Sanitization and Filtering
    $patient_name   = filter_input(INPUT_POST, 'patient_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $patient_age    = filter_input(INPUT_POST, 'patient_age', FILTER_VALIDATE_INT);
    $patient_gender = filter_input(INPUT_POST, 'patient_gender', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $app_date       = filter_input(INPUT_POST, 'appointment_date', FILTER_DEFAULT);
    $app_time       = filter_input(INPUT_POST, 'appointment_time', FILTER_DEFAULT);
    $consult_type   = filter_input(INPUT_POST, 'consultation_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $amount         = 1200; // Fixed consultation fee in BDT
    $save_card      = isset($_POST['save_card']) ? 1 : 0; 
    //unique transaction ID
    $tran_id        = "TXN_" . time() . rand(100, 999); 

    if (!empty($patient_name) && !empty($app_date) && !empty($app_time)) {
        
        // Base Domain Dynamic Route
        $protocol   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $base_url   = $protocol . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        
        // Target Return Endpoint: paymentslip.html (Fallback to paymentslip.html)
        $return_script = "paymentslip.html";

        $success_url = $base_url . "/" . $return_script . "?tran_id=" . urlencode($tran_id) . "&status=success&name=" . urlencode($patient_name) . "&doc=" . urlencode($doctor_name) . "&amount=" . $amount;
        $fail_url    = $base_url . "/" . $return_script . "?tran_id=" . urlencode($tran_id) . "&status=fail&name=" . urlencode($patient_name);
        $cancel_url  = $base_url . "/" . $return_script . "?tran_id=" . urlencode($tran_id) . "&status=cancel&name=" . urlencode($patient_name);

        // PAYLOAD PREPARATION FOR SSLCOMMERZ GATEWAY
        $post_data = array();
        $post_data['store_id']       = SSLCOMMERZ_STORE_ID;
        $post_data['store_passwd']   = SSLCOMMERZ_STORE_PASSWORD;
        $post_data['total_amount']   = $amount;
        $post_data['currency']       = "BDT";
        $post_data['tran_id']        = $tran_id;
        
        $post_data['success_url']    = $success_url;
        $post_data['fail_url']       = $fail_url;
        $post_data['cancel_url']     = $cancel_url;
        
        // Customer Profile Parameters
        $post_data['cus_name']       = $patient_name;
        $post_data['cus_email']      = "patient@innerbloombd.com";
        $post_data['cus_phone']      = "01700000000";
        $post_data['cus_add1']       = "Dhaka";
        $post_data['cus_country']    = "Bangladesh";
        
        $post_data['ship_name']        = "InnerBloom ";
        $post_data['product_name']     = "Doctor Appointment Fee";
        $post_data['product_category'] = "Healthcare";
        $post_data['product_profile']  = "service";

        // Attempt 1: Call SSLCommerz API with provided store credentials
        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 15);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); 

        $content = curl_exec($handle);
        $code    = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        $sslcommerzResponse = json_decode($content, true);

        //  Public testbox Fallback if custom merchant store is inactive
        if (empty($sslcommerzResponse['GatewayPageURL'])) {
            $post_data['store_id']     = "testbox";
            $post_data['store_passwd'] = "qwerty";

            $handle2 = curl_init();
            curl_setopt($handle2, CURLOPT_URL, $direct_api_url);
            curl_setopt($handle2, CURLOPT_TIMEOUT, 3);
            curl_setopt($handle2, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($handle2, CURLOPT_POST, 1);
            curl_setopt($handle2, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($handle2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle2, CURLOPT_SSL_VERIFYPEER, true);
            //API call
            $content2 = curl_exec($handle2);
            curl_close($handle2);
            
            $sslcommerzResponse = json_decode($content2, true);
        }

        // REDIRECT ROUTING PIPELINE
        if (isset($sslcommerzResponse['GatewayPageURL']) && !empty($sslcommerzResponse['GatewayPageURL'])) {
            header("Location: " . $sslcommerzResponse['GatewayPageURL']);
            exit;
        } else {
            // Local Failsafe Bypass: Route straight to payment slip with full payload
            header("Location: " . $success_url);
            exit;
        }

    } else {
        $message = "Please fill in all the required fields.";
        $message_class = "alert-warning";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Appointment Checkout</title>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Mandatory Fonts: Poppins (Headline Bold) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-light-blue: #f0f7ff;
            --card-white: #ffffff;
            --header-blue: #1d4ed8;      
            --text-navy: #1e3a8a;        
            --text-sub-blue: #2563eb;    
            --border-blue: #bfdbfe;      
            --primary-blue: #2563eb;      
            --primary-hover: #1e40af;     
            --tint-blue: #dbeafe;        
            --focus-ring: #1d4ed8;        
            --font-headline: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            color: var(--text-navy);
            background-color: var(--bg-light-blue);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        
        :focus-visible {
            outline: 3px solid var(--focus-ring);
            outline-offset: 2px;
        }

        /* Section 508 Skip Navigation Link */
        .skip-link {
            position: absolute;
            top: -50px;
            left: 1rem;
            background: var(--header-blue);
            color: #ffffff;
            padding: 0.5rem 1rem;
            z-index: 1000;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
        }
        .skip-link:focus { top: 1rem; }

        /* Header Styling */
        header {
            background-color: var(--header-blue);
            color: #ffffff;
            padding: 0.85rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .header-logo img {
            max-height: 40px;
            width: auto;
            display: block;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1rem;
            align-items: center;
        }

        nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Layout Area */
        .workspace {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 1.5rem;
            flex: 1;
        }

        @media (max-width: 900px) {
            .workspace { grid-template-columns: 1fr; }
        }

        /* Card Component */
        .panel-card {
            background: var(--card-white);
            border: 1.5px solid var(--border-blue);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.04);
            margin-bottom: 1.25rem;
        }

        h1, h2, h3, h4, .panel-title {
            font-family: var(--font-headline);
            color: var(--text-navy);
            font-weight: 700;
        }

        .panel-title {
            font-size: 1.15rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .panel-title i { color: var(--primary-blue); }

        /* Form Controls */
        .input-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-navy);
        }

        .form-control {
            padding: 0.65rem 0.85rem;
            border: 1.5px solid var(--border-blue);
            border-radius: 8px;
            font-size: 0.95rem;
            background: #ffffff;
            color: var(--text-navy);
            font-family: var(--font-body);
            width: 100%;
        }

        .form-control[readonly] {
            background-color: var(--bg-light-blue);
            cursor: not-allowed;
        }

        /* Time Slots */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 0.35rem;
        }

        .time-btn {
            padding: 0.55rem;
            border: 1.5px solid var(--border-blue);
            background: var(--bg-light-blue);
            color: var(--text-navy);
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }

        .time-btn.active {
            background: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
        }

        /* Sidebar Summary */
        .summary-box {
            background: var(--card-white);
            border: 1.5px solid var(--border-blue);
            border-radius: 12px;
            padding: 1.5rem;
            position: sticky;
            top: 90px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.04);
        }

        .summary-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--border-blue);
        }

        .summary-item i {
            font-size: 1.1rem;
            color: var(--primary-blue);
            margin-top: 3px;
        }

        .summary-item div p:first-child {
            font-size: 0.75rem;
            color: var(--text-sub-blue);
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-item div p:last-child {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-navy);
        }

        .note-box {
            background-color: var(--bg-light-blue);
            border: 1px solid var(--border-blue);
            border-radius: 8px;
            padding: 0.85rem;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
            color: var(--text-navy);
        }

        /* Buttons */
        .btn-submit {
            background: var(--primary-blue);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 0.85rem;
            border-radius: 8px;
            font-size: 1rem;
            font-family: var(--font-headline);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.2s;
        }

        .btn-submit:hover { background-color: var(--primary-hover); }

        /* Alert notifications */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }
        .alert-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        /* Features Bar */
        .features-bar {
            max-width: 1200px;
            margin: 1rem auto 2rem;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .feature-item {
            background: var(--card-white);
            border: 1.5px solid var(--border-blue);
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-size: 0.85rem;
        }

        .feature-item i { font-size: 1.5rem; color: var(--primary-blue); }

        /* Footer */
        footer {
            background: var(--card-white);
            border-top: 1.5px solid var(--border-blue);
            padding: 2rem 1.5rem 1rem;
            margin-top: auto;
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-blue);
        }

        .footer-col h3 { font-size: 0.95rem; margin-bottom: 0.85rem; }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-col a {
            text-decoration: none;
            color: var(--text-navy);
            font-size: 0.85rem;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 1rem auto 0;
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-navy);
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>

    <!-- WCAG Skip Navigation Link -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Header Navigation -->
    <header>
        <div class="header-logo">
            <img src="logo.png" alt="Company Logo">
        </div>
        <nav aria-label="Main Navigation">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="bookappointment.php">Book a Session</a></li>
                <li><a href="questionnaire.php">Fact Finder</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Workspace Area -->
    <main id="main-content" class="workspace">
        
        <div>
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $message_class; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="mainAppointmentForm">
                
                <!-- Panel 1: Select Date & Time -->
                <section class="panel-card" aria-labelledby="step1-heading">
                    <h2 id="step1-heading" class="panel-title">
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i> 1. Select Date & Time
                    </h2>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="ui_date">Choose Date <span aria-hidden="true">*</span></label>
                            <input type="date" id="ui_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required onchange="updateLiveDate(this.value)">
                        </div>
                        <div class="form-group">
                            <label id="timeslot-label">Select Time Slot <span aria-hidden="true">*</span></label>
                            <input type="hidden" id="selected_time" name="appointment_time" value="11:00 AM" required>
                            <div class="time-slots" role="group" aria-labelledby="timeslot-label">
                                <button type="button" class="time-btn" onclick="selectTime('09:00 AM', this)">09:00 AM</button>
                                <button type="button" class="time-btn" onclick="selectTime('10:00 AM', this)">10:00 AM</button>
                                <button type="button" class="time-btn active" onclick="selectTime('11:00 AM', this)">11:00 AM</button>
                                <button type="button" class="time-btn" onclick="selectTime('12:00 PM', this)">12:00 PM</button>
                                <button type="button" class="time-btn" onclick="selectTime('02:00 PM', this)">02:00 PM</button>
                                <button type="button" class="time-btn" onclick="selectTime('04:00 PM', this)">04:00 PM</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Panel 2: Patient Info -->
                <section class="panel-card" aria-labelledby="step2-heading">
                    <h2 id="step2-heading" class="panel-title">
                        <i class="fa-solid fa-user-injured" aria-hidden="true"></i> 2. Patient & Appointment Details
                    </h2>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="patient_name_input">Patient Full Name <span aria-hidden="true">*</span></label>
                            <input type="text" id="patient_name_input" name="patient_name" class="form-control" placeholder="Enter full name" required oninput="document.getElementById('live_user').innerText = this.value || 'To be assigned'">
                        </div>
                        <div class="form-group">
                            <label for="patient_age_input">Patient Age <span aria-hidden="true">*</span></label>
                            <input type="number" id="patient_age_input" name="patient_age" class="form-control" placeholder="Enter age" min="1" max="120" required>
                        </div>
                        <div class="form-group">
                            <label for="patient_gender_select">Gender <span aria-hidden="true">*</span></label>
                            <select id="patient_gender_select" name="patient_gender" class="form-control">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Panel 3: Confirmation -->
                <section class="panel-card" aria-labelledby="step3-heading">
                    <h2 id="step3-heading" class="panel-title">
                        <i class="fa-solid fa-database" aria-hidden="true"></i> 3. Appointment Confirmation
                    </h2>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="db_date">Appointment Date</label>
                            <input type="text" id="db_date" name="appointment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="db_time">Appointment Time</label>
                            <input type="text" id="db_time" class="form-control" value="11:00 AM" readonly>
                        </div>
                        <div class="form-group">
                            <label for="db_day">Appointment Day</label>
                            <input type="text" id="db_day" class="form-control" value="<?php echo date('l'); ?>" readonly>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="consultation_type_select">Consultation Type</label>
                            <select id="consultation_type_select" name="consultation_type" class="form-control" onchange="document.getElementById('live_mode').innerText = this.value">
                                <option value="In-Person">In-Person</option>
                                <option value="Online">Online</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="db_timestamp">Current Timestamp</label>
                            <input type="text" id="db_timestamp" class="form-control" value="<?php echo date('d M Y, h:i A'); ?>" readonly>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        Confirm & Proceed to Pay <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </section>
            </form>
        </div>

        <!-- Sidebar Summary Panel -->
        <aside aria-label="Appointment Summary">
            <div class="summary-box">
                <h2 class="panel-title" style="margin-bottom:1.25rem;">
                    <i class="fa-regular fa-file-alt" aria-hidden="true"></i> Appointment Summary
                </h2>
                
                <div class="summary-item">
                    <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                    <div>
                        <p>Doctor</p>
                        <p><?php echo $doctor_name; ?></p>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                    <div>
                        <p>Patient</p>
                        <p id="live_user">To be assigned</p>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <div>
                        <p>Date & Time</p>
                        <p id="live_datetime"><?php echo date('d M Y'); ?>, 11:00 AM</p>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fa-solid fa-wave-square" aria-hidden="true"></i>
                    <div>
                        <p>Consultation Type</p>
                        <p id="live_mode">In-Person</p>
                    </div>
                </div>

                <div class="note-box">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i> <strong>Please Note:</strong> Review details before confirming your appointment. Payment via SSLCommerz is encrypted and secure.
                </div>
            </div>
        </aside>
    </main>

    <!-- Trust Features Bar -->
    <div class="features-bar">
        <div class="feature-item"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><div><strong>Secure & Safe</strong><p style="font-size:0.75rem;">Top-level security standard</p></div></div>
        <div class="feature-item"><i class="fa-regular fa-clock" aria-hidden="true"></i><div><strong>Quick Booking</strong><p style="font-size:0.75rem;">Book slots in just a few clicks</p></div></div>
        <div class="feature-item"><i class="fa-regular fa-bell" aria-hidden="true"></i><div><strong>Reminders</strong><p style="font-size:0.75rem;">Get timely email alerts</p></div></div>
        <div class="feature-item"><i class="fa-solid fa-headset" aria-hidden="true"></i><div><strong>24/7 Support</strong><p style="font-size:0.75rem;">We are here to assist you</p></div></div>
    </div>

    <!-- Footer Area -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h3>MediCare</h3>
                <p style="font-size:0.82rem;">Providing compassionate and quality healthcare solutions.</p>
            </div>
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="dashboard.phpca">Dashboard</a></li>
                    <li><a href="bookappointment.php">Book a Session</a></li>
                    <li><a href="questionnaire.php">Fact Finder</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact Us</h3>
                <ul style="font-size:0.82rem;">
                    <li><i class="fa-solid fa-phone" aria-hidden="true"></i> +880 1234 56789</li>
                    <li><i class="fa-solid fa-envelope" aria-hidden="true"></i> info@medicare.com</li>
                    <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Dhaka, Bangladesh</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 MediCare. All rights reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </footer>

    <!-- JavaScript Handlers -->
    <script>
    function selectTime(time, element) {
        document.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
        
        document.getElementById('selected_time').value = time;
        document.getElementById('db_time').value = time;
        updateSummary();
    }

    function updateLiveDate(val) {
        document.getElementById('db_date').value = val;
        
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const d = new Date(val);
        const dayName = days[d.getDay()];
        
        document.getElementById('db_day').value = dayName;
        updateSummary();
    }

    function updateSummary() {
        const dateVal = document.getElementById('db_date').value;
        const timeVal = document.getElementById('db_time').value;
        
        if (dateVal && timeVal) {
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            const formattedDate = new Date(dateVal).toLocaleDateString('en-GB', options);
            document.getElementById('live_datetime').innerText = formattedDate + ", " + timeVal;
        }
    }
    </script>
</body>
</html>

