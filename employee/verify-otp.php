<?php
session_start();
if (!isset($_SESSION['employee_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enteredOtp = implode('', $_POST['otp']); // join all 6 boxes
    if ($enteredOtp == $_SESSION['otp']) {
        unset($_SESSION['otp']);
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Two-factor Authentication</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .otp-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 420px;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 22px;
            font-weight: bold;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-bottom: 25px;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .otp-inputs input {
            width: 48px;
            height: 55px;
            font-size: 22px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .otp-inputs input:focus {
            outline: none;
            border-color: #3172b7;
            box-shadow: 0 0 4px rgba(49, 114, 183, 0.4);
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }

        .btn-cancel:hover {
            background: #ccc;
        }

        .btn-submit {
            background: #3172b7;
            color: #fff;
        }

        .btn-submit:hover {
            background: #245c9c;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="otp-container">
        <h2>Two-factor Authentication</h2>
        <p>Please enter the authentication code.<br>
            The authentication code has been sent to your email:
            <strong><?php echo htmlspecialchars($_SESSION['employee_email']); ?></strong>
        </p>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="otp-inputs">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" required>
                <?php endfor; ?>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-cancel" onclick="window.location='login.php'">Cancel</button>
                <button type="submit" class="btn btn-submit">Log In</button>
            </div>
        </form>
    </div>

    <script>
        // Auto focus next input
        const inputs = document.querySelectorAll(".otp-inputs input");
        inputs.forEach((input, index) => {
            input.addEventListener("input", () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>

</html>