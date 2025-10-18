<?php
session_start();
include_once('../db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $hashedPassword = md5($password);

  $stmt = $conn->prepare("SELECT id, email, password FROM employees WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_email, $db_password);
    $stmt->fetch();

    if ($hashedPassword === $db_password) {
      $otp = rand(100000, 999999);
      $_SESSION['employee_id'] = $id;
      $_SESSION['employee_email'] = $db_email;
      $_SESSION['otp'] = $otp;

      $mail = new PHPMailer(true);
      try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "dmmtv2k20@gmail.com";
        $mail->Password = "sjxxtdjhymchmdkl";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
        $mail->setFrom("dmmtv2k20@gmail.com", "LGU Polangui HREIS");
        $mail->addAddress($db_email);

        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code - LGU Polangui HREIS";
        $mail->Body    = "<h3>Your One-Time Password (OTP) is:</h3>
                          <h2 style='color:#3172b7;'>$otp</h2>
                          <p>This code will expire in 5 minutes.</p>";

        $mail->send();
        header("Location: verify-otp.php");
        exit();
      } catch (Exception $e) {
        echo "<script>alert('Could not send OTP. Please try again later.'); window.history.back();</script>";
      }
    } else {
      echo "<script>alert('Invalid password.'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('No admin account found with that email.'); window.history.back();</script>";
  }

  $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Login - LGU Polangui HREIS</title>
  <style>
    :root {
      --primary: #3172b7;
      --primary-dark: #245c9c;
      --secondary: #003f5c;
      --accent: #00C9A7;
      --light: #eaf6fc;
      --gray: #6C757D;
      --dark: #343A40;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--light) 0%, #bce3f2 100%);
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      padding: 25px 30px;
      display: flex;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      flex-wrap: wrap;
    }

    header img {
      height: 55px;
      margin-right: 20px;
      /*   filter: brightness(0) invert(1); */
    }

    header h1 {
      font-size: 22px;
      font-weight: 600;
      color: white;
      flex: 1;
      line-height: 1.4;
    }

    .back-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin: 30px auto 10px;
      padding: 12px 25px;
      background: white;
      border: 2px solid var(--primary);
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      border-radius: 30px;
      text-align: center;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(49, 114, 183, 0.2);
    }

    .back-button:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(49, 114, 183, 0.3);
    }

    .login-container {
      max-width: 450px;
      background: white;
      margin: 20px auto 60px;
      padding: 45px 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .login-icon {
      font-size: 48px;
      color: var(--primary);
      margin-bottom: 20px;
    }

    .login-container h2 {
      margin-bottom: 10px;
      color: var(--secondary);
      font-size: 28px;
      font-weight: 700;
    }

    .login-subtitle {
      color: var(--gray);
      margin-bottom: 30px;
      font-size: 15px;
    }

    .login-container input[type="email"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 16px 20px;
      margin-bottom: 20px;
      border: 2px solid #e9ecef;
      border-radius: 12px;
      font-size: 16px;
      background-color: #f8faff;
      transition: all 0.3s ease;
    }

    .login-container input:focus {
      outline: none;
      border-color: var(--primary);
      background-color: white;
      box-shadow: 0 0 0 3px rgba(49, 114, 183, 0.1);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      font-size: 14px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--gray);
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--primary);
    }

    .forgot-password {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forgot-password:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .login-container button {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .login-container button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(49, 114, 183, 0.4);
    }

    .security-notice {
      margin-top: 25px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 8px;
      border-left: 4px solid var(--accent);
      text-align: left;
    }

    .security-notice h4 {
      color: var(--secondary);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .security-notice p {
      color: var(--gray);
      font-size: 13px;
      line-height: 1.5;
    }

    @media (max-width: 500px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
      }

      header h1 {
        font-size: 18px;
      }

      .login-container {
        margin: 20px;
        padding: 35px 25px;
      }

      .form-options {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <header>
    <img src="../assets/images/logo.png" alt="LGU Polangui Logo">
    <h1>LGU Polangui Human Resource Employee Information System (HREIS)</h1>
  </header>

  <a href="../index.php" class="back-button">
    <i class="fas fa-arrow-left"></i> Back to Landing Page
  </a>

  <div class="login-container">
    <div class="login-icon">
      <i class="fas fa-user-shield"></i>
    </div>
    <h2>Employee Login</h2>
    <p class="login-subtitle">Secure access to your Employee portal</p>

    <form action="" method="POST">
      <input type="email" name="email" placeholder="Enter your email address" required>
      <input type="password" name="password" placeholder="Enter your password" required>


      <button type="submit">
        <i class="fas fa-sign-in-alt"></i>Login
      </button>
    </form>

    <div class="security-notice">
      <h4><i class="fas fa-shield-alt"></i> Enhanced Security</h4>
      <p>For your security, we'll send a verification code to your email after you enter your credentials.</p>
    </div>
  </div>

</body>

</html>