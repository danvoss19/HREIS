<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Two-Factor Authentication</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #f4f4f4;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #b3d9f7;
      padding: 16px 24px;
      display: flex;
      align-items: center;
      gap: 15px;
      border-bottom: 2px solid #3172b7;
      flex-wrap: wrap;
    }

    header img {
      height: 55px;
    }

    header h1 {
      font-size: 18px;
      font-weight: 600;
      color: #003f5c;
      line-height: 1.5;
    }

    .auth-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex: 1;
      padding: 20px;
      animation: fadeIn 0.4s ease-in;
    }

    .auth-box {
      background-color: white;
      padding: 40px 35px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .auth-box h2 {
      margin-bottom: 15px;
      font-size: 24px;
      color: #003f5c;
    }

    .auth-box p {
      font-size: 15px;
      color: #444;
      margin-bottom: 25px;
    }

    .auth-box p a {
      color: #0073e6;
      text-decoration: none;
    }

    .auth-box p a:hover {
      text-decoration: underline;
    }

    .code-inputs {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin: 30px 0 20px;
    }

    .code-inputs input {
      width: 50px;
      height: 58px;
      font-size: 24px;
      text-align: center;
      border: 2px solid #ccc;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .code-inputs input:focus {
      border-color: #3172b7;
      box-shadow: 0 0 6px rgba(49, 114, 183, 0.3);
      outline: none;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      margin-top: 25px;
    }

    .buttons button {
      flex: 1;
      padding: 12px 20px;
      border: none;
      border-radius: 30px;
      font-size: 15px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: 600;
    }

    .cancel-btn {
      background-color: #ddd;
      color: #333;
    }

    .cancel-btn:hover {
      background-color: #bcbcbc;
    }

    .login-btn {
      background-color: #3172b7;
      color: white;
    }

    .login-btn:hover {
      background-color: #245c9c;
    }

    @media (max-width: 500px) {
      .auth-box {
        padding: 30px 20px;
      }

      .code-inputs {
        gap: 6px;
      }

      .code-inputs input {
        width: 42px;
        height: 50px;
        font-size: 20px;
      }

      header h1 {
        font-size: 16px;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

  <header>
    <img src="logo.png" alt="Seal of Polangui" />
    <h1>LGU Polangui Human Resource Employee Information System<br>(HREIS)</h1>
  </header>

  <div class="auth-container">
    <div class="auth-box">
      <h2><strong>Two-Factor Authentication</strong></h2>
      <p>
        Please enter the authentication code.<br>
        The code has been sent to: <a href="#">example@gmail.com</a>
      </p>
      <form>
        <div class="code-inputs">
          <input type="text" inputmode="numeric" maxlength="1" required autofocus>
          <input type="text" inputmode="numeric" maxlength="1" required>
          <input type="text" inputmode="numeric" maxlength="1" required>
          <input type="text" inputmode="numeric" maxlength="1" required>
          <input type="text" inputmode="numeric" maxlength="1" required>
          <input type="text" inputmode="numeric" maxlength="1" required>
        </div>
        <div class="buttons">
          <button type="button" class="cancel-btn">Cancel</button>
          <button type="submit" class="login-btn">Log In</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
