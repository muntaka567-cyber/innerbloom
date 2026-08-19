     <?php 
// Section 1: Session & Database Init — Starts PHP session & connects to database.
session_start();
include 'db_connect.php'; 

// Section 2: Response State Variables — Initializes feedback state flags.
$message = "";
$success = false;

// Section 3: POST Handler & Input Sanitization — Processes submitted payload.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name     = trim($_POST["name"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $phone    = trim($_POST["phone"] ?? '');
    $age      = trim($_POST["age"] ?? '');
    $gender   = $_POST["gender"] ?? '';

    // Section 4: Server-Side Validation — Ensures input integrity before querying DB.
    if (empty($name)) {
        $message = "Name is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid Email Address.";
    } elseif (strlen($password) < 5) {
        $message = "Password must contain at least 5 characters.";
    } elseif (strlen($phone) < 11) {
        $message = "Phone must contain at least 11 characters.";
    } elseif (!is_numeric($age) || (int)$age <= 0) {
        $message = "Please enter a valid age.";
    } elseif (empty($gender)) {
        $message = "Gender is required.";
    } else {
        // Section 5: Duplicate Check — Prepared statement checking for registered emails.
        $check = $conn->prepare("SELECT email FROM registration WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists. Please login.";
        } else {
            // Section 6: Secure Password Hashing — Hashes string with standard bcrypt.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Section 7: User Insertion — Registers new account record safely (NID omitted).
            $stmt = $conn->prepare("INSERT INTO registration (name, email, password, phone, age, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $name, $email, $hashedPassword, $phone, $age, $gender);

            if ($stmt->execute()) {
                $_SESSION['name']  = $name;
                $_SESSION['email'] = $email;
                $message = "Registration Completed Successfully!";
                $success = true;
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}

// Section 8: Database Connection Cleanup — Releases active DB resource handles.
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Section 9: Document Metadata — Encoding & mobile scaling properties. -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Form - InnerBloom</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <!-- Section 10: Accessible CSS Rules — Contrast enforcement & focus indicators. -->
  <style>
    :root {
      --primary-color: #005a5a; /* Darkened teal for WCAG 4.5:1 text contrast */
      --primary-hover: #004040;
      --text-main: #093f5e;
      --error-red: #b91c1c;
      --focus-ring: #2563eb;
    }

    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: url('87.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    /* Focus outline for keyboard navigation compliance (WCAG SC 2.4.7) */
    a:focus-visible, 
    button:focus-visible, 
    input:focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 2px;
    }

    main.register_box {
      width: 100%;
      max-width: 400px;
      background: #ffffff; 
      border-radius: 15px;
      padding: 2.5em 2em;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.4);
    }

    .register_header { 
      text-align: center; 
      margin-bottom: 24px; 
    }

    .register_header h1 { 
      font-size: 28px; 
      font-weight: bold; 
      color: var(--text-main); 
      margin: 0;
    }

    .input_box { 
      margin-bottom: 18px; 
    }

    .input_box label { 
      display: block; 
      margin-bottom: 6px; 
      font-size: 14px; 
      font-weight: 600; 
      color: var(--text-main); 
    }

    .input_box input[type="text"],
    .input_box input[type="email"],
    .input_box input[type="password"],
    .input_box input[type="number"] { 
      width: 100%; 
      padding: 12px; 
      border-radius: 8px; 
      border: 1.5px solid #64748b; 
      box-sizing: border-box;
      font-size: 14px;
    }

    .radio_group {
      display: flex;
      gap: 15px;
      align-items: center;
      margin-top: 6px;
    }

    .radio_group label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-weight: normal;
      cursor: pointer;
    }

    .btn_box { 
      text-align: center; 
      margin-top: 24px;
    }

    .btn-main {
      background: var(--primary-color); 
      color: #ffffff; 
      border: none; 
      border-radius: 8px;
      padding: 12px 24px; 
      font-weight: 600; 
      font-size: 16px;
      cursor: pointer; 
      transition: background 0.3s ease;
      width: 100%;
    }

    .btn-main:hover { 
      background: var(--primary-hover); 
    }

    .message-box { 
      text-align: center; 
      margin-top: 20px; 
      font-size: 15px; 
      color: var(--text-main); 
      font-weight: bold; 
      padding: 12px;
      border-radius: 8px;
    }

    .message-box.error {
      background-color: #fef2f2;
      color: var(--error-red);
      border: 1px solid #fca5a5;
    }

    .message-box.success-alert {
      background-color: #f0fdf4;
      color: var(--primary-color);
      border: 1px solid #86efac;
    }

    .message-box a { 
      color: var(--primary-color); 
      text-decoration: underline; 
      font-weight: bold; 
    }
  </style>
</head>
<body>

  <!-- Section 11: Main Container — ARIA landmark grouping content for assistive tools. -->
  <main class="register_box" role="main">
    
    <!-- Section 12: Header Branding — Form title heading. -->
    <div class="register_header">
      <h1>Sign Up</h1>
    </div>

    <!-- Section 13: Accessible Form Inputs — Explicit label/input pairs with autocomplete tokens. -->
    <form method="POST" action="" novalidate>
      
      <div class="input_box">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required autocomplete="name" aria-required="true">
      </div>

      <div class="input_box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email" aria-required="true">
      </div>

      <div class="input_box">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="new-password" aria-required="true">
      </div>

      <div class="input_box">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required autocomplete="tel" aria-required="true">
      </div>

      <div class="input_box">
        <label for="age">Age</label>
        <input type="number" id="age" name="age" required min="1" aria-required="true">
      </div>

      <!-- Section 14: Accessible Radio Fieldset — Groups gender options with standard legend. -->
      <fieldset class="input_box" style="border: none; padding: 0; margin: 0 0 18px 0;">
        <legend style="font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Gender</legend>
        <div class="radio_group">
          <label for="gender_male">
            <input type="radio" id="gender_male" name="gender" value="Male" required> Male
          </label>
          <label for="gender_female">
            <input type="radio" id="gender_female" name="gender" value="Female"> Female
          </label>
        </div>
      </fieldset>

      <!-- Section 15: Submission Trigger — Single action submit button. -->
      <div class="btn_box">
        <button type="submit" class="btn-main">Sign Up</button>
      </div>
    </form>

    <!-- Section 16: Dynamic Accessibility Alert Box — Broadcasts success or error messages to screen readers. -->
    <?php if (!empty($message)) { ?>
      <div 
        class="message-box <?php echo $success ? 'success-alert' : 'error'; ?>" 
        role="alert" 
        aria-live="assertive"
      >
        <p><?php echo htmlspecialchars($message); ?></p>
        <?php if ($success) { ?>
          <p><a href="dashboard.php"><i class="fa-solid fa-gauge" aria-hidden="true"></i> Go to Dashboard</a></p>
        <?php } else { ?>
          <p><a href="INDEX.PHP">Back to Home</a></p>
        <?php } ?>
      </div>
    <?php } ?>

  </main>

</body>
</html>
