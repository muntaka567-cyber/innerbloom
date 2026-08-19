<?php
session_start();

// Disable Undefined Variable Warnings and Safely Extract GET/POST
$tran_id = $_GET['tran_id'] ?? $_POST['tran_id'] ?? 'N/A';
$status  = $_GET['status'] ?? $_POST['status'] ?? 'unknown';
$plan    = $_GET['plan'] ?? $_POST['value_a'] ?? $_POST['plan_name'] ?? 'Subscription Plan';
$amount  = $_GET['amount'] ?? $_POST['amount'] ?? $_POST['total_amount'] ?? 0;

// Sanitize Output Data
$tran_id = htmlspecialchars((string)$tran_id, ENT_QUOTES, 'UTF-8');
$status  = htmlspecialchars((string)$status, ENT_QUOTES, 'UTF-8');
$plan    = htmlspecialchars((string)$plan, ENT_QUOTES, 'UTF-8');
$amount  = floatval($amount);

$is_success = (strtolower($status) === 'success' || strtolower($_POST['status'] ?? '') === 'valid');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Receipt - InnerBloom BD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .slip-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .slip-header {
            background: <?php echo $is_success ? '#2e7d32' : '#c62828'; ?>;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }

        .slip-header i {
            font-size: 55px;
            margin-bottom: 12px;
        }

        .slip-header h2 {
            font-size: 24px;
        }

        .slip-body {
            padding: 30px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            font-size: 15px;
            color: #444;
        }

        .row .label {
            font-weight: 600;
            color: #777;
        }

        .divider {
            border-top: 1px dashed #e0e0e0;
            margin: 20px 0;
        }

        .actions {
            display: flex;
            gap: 12px;
            padding: 0 30px 30px;
        }

        .btn-action {
            flex: 1;
            text-align: center;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-action:hover { opacity: 0.9; }
        .btn-home { background: #4c4c9d; color: #ffffff; }
        .btn-print { background: #e0e0e0; color: #333333; }

        @media print {
            .actions { display: none; }
            body { background: #ffffff; }
            .slip-card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

<div class="slip-card">
    <div class="slip-header">
        <?php if ($is_success): ?>
            <i class="fa-solid fa-circle-check"></i>
            <h2>Payment Successful</h2>
        <?php else: ?>
            <i class="fa-solid fa-circle-xmark"></i>
            <h2>Payment <?php echo ucfirst($status); ?></h2>
        <?php endif; ?>
    </div>

    <div class="slip-body">
        <div class="row">
            <span class="label">Plan Name:</span>
            <span><strong style="color: #4c4c9d;"><?php echo $plan; ?></strong></span>
        </div>
        <div class="row">
            <span class="label">Transaction ID:</span>
            <span><?php echo $tran_id; ?></span>
        </div>
        <div class="row">
            <span class="label">Date:</span>
            <span><?php echo date("Y-m-d H:i"); ?></span>
        </div>

        <div class="divider"></div>

        <div class="row" style="font-size: 18px;">
            <span class="label">Total Paid:</span>
            <span style="color: <?php echo $is_success ? '#2e7d32' : '#c62828'; ?>;">
                <strong>৳<?php echo number_format($amount, 2); ?></strong>
            </span>
        </div>
    </div>

    <div class="actions">
        <a href="dashboard.php" class="btn-action btn-home">
            <i class="fa-solid fa-house"></i> Home
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
</div>

</body>
</html>
