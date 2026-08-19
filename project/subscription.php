<?php
session_start();

// SSLCOMMERZ SANDBOX CREDENTIALS CONFIG
define("SSLCOMMERZ_STORE_ID", "inner6a79c5b174a8d");
define("SSLCOMMERZ_STORE_PASSWORD", "inner6a79c5b174a8d@ssl");
define("SSLCOMMERZ_IS_SANDBOX", true); 

$message = "";
$message_class = "";

// PAYMENT INITIATION HANDLER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plan_name = filter_input(INPUT_POST, 'plan_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $amount    = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

    // Exact Plan Name & Amount Mapping from UI
    $allowed_plans = [
        'Elite'          => 299,
        'Elite Infinity' => 399
    ];

    if (!array_key_exists($plan_name, $allowed_plans) || $amount !== (float)$allowed_plans[$plan_name]) {
        $message = "Invalid subscription plan selection.";
        $message_class = "alert-warning";
    } else {
        $tran_id = "TXN_" . uniqid();

        // Dynamically compute base URL
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $base_url = "{$protocol}://{$host}{$dir}/paymentslip.php";

        $post_data = array(
            'store_id'         => SSLCOMMERZ_STORE_ID,
            'store_passwd'     => SSLCOMMERZ_STORE_PASSWORD,
            'total_amount'     => $amount,
            'currency'         => "BDT",
            'tran_id'          => $tran_id,
            'value_a'          => $plan_name, // Store plan name in metadata
            
            'success_url'      => $base_url . "?tran_id=" . urlencode($tran_id) . "&status=success&plan=" . urlencode($plan_name) . "&amount=" . urlencode($amount),
            'fail_url'         => $base_url . "?tran_id=" . urlencode($tran_id) . "&status=fail&plan=" . urlencode($plan_name) . "&amount=" . urlencode($amount),
            'cancel_url'       => $base_url . "?tran_id=" . urlencode($tran_id) . "&status=cancel&plan=" . urlencode($plan_name) . "&amount=" . urlencode($amount),
            
            'cus_name'         => "Valued Subscriber",
            'cus_email'        => "subscriber@innerbloombd.com",
            'cus_phone'        => "01700000000",
            'cus_add1'         => "Dhaka",
            'cus_country'      => "Bangladesh",
            
            'ship_name'        => "Medicare Clinic",
            'product_name'     => $plan_name . " Subscription",
            'product_category' => "Healthcare",
            'product_profile'  => "service"
        );

        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt_array($handle, [
            CURLOPT_URL            => $direct_api_url,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => http_build_query($post_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $content = curl_exec($handle);
        $code    = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code === 200 && !curl_errno($handle)) {
            curl_close($handle);
            $sslcommerzResponse = json_decode($content, true);

            if (!empty($sslcommerzResponse['GatewayPageURL'])) {
                header("Location: " . $sslcommerzResponse['GatewayPageURL']);
                exit;
            } else {
                $reason = $sslcommerzResponse['failedreason'] ?? 'Gateway Mismatch.';
                $message = "Gateway Error: " . htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
                $message_class = "alert-danger";
            }
        } else {
            curl_close($handle);
            $message = "Connection error with SSLCommerz API Endpoint.";
            $message_class = "alert-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { min-height: 100vh; background: url('22.jpg') center/cover no-repeat; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .container { width: 100%; max-width: 1100px; }
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 10px; text-align: center; font-weight: 600; }
        .alert-danger { background-color: #f8d7da; color: #842029; }
        .alert-warning { background-color: #fff3cd; color: #664d03; }

        .top-section { background: linear-gradient(rgba(72, 168, 170, 0.85), rgba(110, 72, 170, 0.85)), url('9786.jpg'); background-size: cover; background-position: center; border-radius: 20px 20px 0 0; text-align: center; color: #fff; padding: 60px 20px 140px; }
        .top-section h1 { font-size: 42px; margin-bottom: 12px; }
        .top-section p { font-size: 18px; opacity: 0.95; }

        .cards { display: flex; justify-content: center; gap: 30px; margin-top: -90px; flex-wrap: wrap; }
        .card { background: #ffffff; width: 320px; border-radius: 20px; text-align: center; padding: 35px 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: transform 0.3s; }
        .card:hover { transform: translateY(-8px); }
        .plan { background: #4c4c9d; color: #ffffff; padding: 10px 25px; border-radius: 30px; display: inline-block; margin-bottom: 20px; font-weight: 700; }
        .price { font-size: 50px; color: #366B2B; font-weight: 800; }
        .month { color: #666; margin-bottom: 20px; font-size: 14px; }
        .features { list-style: none; text-align: left; margin: 20px 0 30px; }
        .features li { padding: 8px 0; color: #444; font-size: 15px; }

        .btn-pay { width: 100%; background: #00BCD4; color: #ffffff; padding: 14px; border-radius: 30px; font-weight: bold; font-size: 16px; border: none; cursor: pointer; transition: background 0.3s; }
        .btn-pay:hover { background: #c2185b; }

        .home-btn-wrapper { text-align: center; margin-top: 40px; }
        .btn-home { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; background: #ffffff; color: #333; padding: 12px 30px; border-radius: 30px; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $message_class; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="top-section">
        <h1>Your Plan, Your Choice</h1>
        <p>Choose the best mental health subscription plan that fits your needs.</p>
    </div>

    <div class="cards">
        <!-- ELITE PLAN (৳299) -->
        <div class="card">
            <div class="plan">Elite</div>
            <div class="price">৳299</div>
            <div class="month">প্রতি মাসে</div>
            <ul class="features">
                <li>✔ Wellness Consultation</li>
                <li>✔ Monthly Sessions</li>
                <li>✔ Community Support</li>
                <li>✔ Expert Guidance</li>
            </ul>
            <form action="" method="POST">
                <input type="hidden" name="plan_name" value="Elite">
                <input type="hidden" name="amount" value="299">
                <button type="submit" class="btn-pay"><i class="fa-solid fa-credit-card"></i> Pay ৳299</button>
            </form>
        </div>

        <!-- ELITE INFINITY PLAN (৳399) -->
        <div class="card">
            <div class="plan">Elite Infinity</div>
            <div class="price">৳399</div>
            <div class="month">প্রতি মাসে</div>
            <ul class="features">
                <li>✔ Personal Counseling</li>
                <li>✔ Weekly Sessions</li>
                <li>✔ Mental Health Support</li>
                <li>✔ Priority Appointment</li>
            </ul>
            <form action="" method="POST">
                <input type="hidden" name="plan_name" value="Elite Infinity">
                <input type="hidden" name="amount" value="399">
                <button type="submit" class="btn-pay"><i class="fa-solid fa-credit-card"></i> Pay ৳399</button>
            </form>
        </div>
    </div>

    <div class="home-btn-wrapper">
        <a href="dashboard.php" class="btn-home"><i class="fa-solid fa-house"></i> Back to Home</a>
    </div>
</div>

</body>
</html>