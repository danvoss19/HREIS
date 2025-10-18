<?php
session_start();
include_once('../db.php');

if (!isset($_SESSION['employee_id'])) {
  header("Location: login.php");
  exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee details
$stmt = $conn->prepare("SELECT firstname, lastname, email, designation, department, salary_grade, joining_date, avatar 
                        FROM employees 
                        WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HR Employee Information System | Personal Data Sheet</title>
  <style>
    :root {
      --primary: #7152F3;
      --primary-dark: #5b3fd6;
      --secondary: #2A3042;
      --accent: #00C9A7;
      --light: #F8F9FA;
      --gray: #6C757D;
      --dark: #343A40;
      --border: #E9ECEF;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --shadow-light: 0 2px 6px rgba(0, 0, 0, 0.05);
      --radius: 10px;
      --transition: all 0.3s ease;
    }

    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      min-height: 100vh;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      color: var(--dark);
      line-height: 1.6;
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      background: var(--primary);
      padding: 25px 20px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      position: fixed;
      height: 100vh;
      z-index: 100;
      transition: transform 0.3s ease;
      box-shadow: var(--shadow);
    }

    .sidebar-toggle {
      display: none;
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 200;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 14px;
      cursor: pointer;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .sidebar-toggle:hover {
      background: var(--primary-dark);
    }

    .logo-section {
      text-align: center;
      margin-bottom: 35px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-section img {
      width: 70px;
      height: 70px;
      margin-bottom: 12px;
      border-radius: 50%;
      background: white;
      padding: 8px;
      box-shadow: var(--shadow);
    }

    .logo-section h4 {
      font-size: 14px;
      font-weight: 600;
      line-height: 1.4;
      text-transform: uppercase;
      color: white;
      letter-spacing: 0.5px;
    }

    .menu {
      list-style: none;
      width: 100%;
      padding: 0;
      margin: 0;
    }

    .menu li {
      margin-bottom: 8px;
      border-radius: 8px;
      transition: var(--transition);
    }

    .menu li.active {
      background: rgba(255, 255, 255, 0.15);
      font-weight: 600;
    }

    .menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      border-radius: 8px;
      text-decoration: none;
      color: rgba(255, 255, 255, 0.9);
      font-size: 15px;
      transition: var(--transition);
    }

    .menu a:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
    }

    .menu li.active a {
      color: white;
    }

    .menu i {
      width: 20px;
      text-align: center;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 30px 40px;
      margin-left: 280px;
      transition: margin-left 0.3s ease;
    }

    .header {
      background: white;
      padding: 25px 30px;
      border-radius: var(--radius);
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: var(--shadow-light);
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 20px;
    }

    .profile-info {
      display: flex;
      align-items: center;
    }

    .profile-info img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin-right: 18px;
      border: 3px solid white;
      box-shadow: var(--shadow);
      cursor: pointer;
      transition: var(--transition);
      object-fit: cover;
    }

    .profile-info img:hover {
      transform: scale(1.05);
    }

    .profile-text h2 {
      margin: 0 0 5px 0;
      color: var(--secondary);
      font-size: 24px;
      font-weight: 600;
    }

    .profile-text p {
      margin: 0;
      font-size: 15px;
      color: var(--gray);
      font-weight: 500;
    }

    .btn-profile {
      background-color: var(--primary);
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: var(--transition);
      font-weight: 600;
      box-shadow: var(--shadow-light);
    }

    .btn-profile:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .header-content h1 {
      color: var(--secondary);
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .header-content p {
      color: var(--gray);
      font-size: 15px;
      font-weight: 500;
    }

    .header img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      border: 3px solid white;
      box-shadow: var(--shadow);
      cursor: pointer;
      transition: var(--transition);
      object-fit: cover;
    }

    .header img:hover {
      transform: scale(1.05);
    }

    /* PDS Container */
    .pds-container {
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow-light);
      overflow: hidden;
      margin-bottom: 30px;
    }

    .pds-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 30px;
      text-align: center;
      position: relative;
    }

    .pds-header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 20px;
      height: 20px;
      background: var(--primary);
      transform: rotate(45deg);
    }

    .pds-icon {
      font-size: 48px;
      margin-bottom: 15px;
      display: block;
    }

    .pds-title {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .pds-subtitle {
      font-size: 16px;
      opacity: 0.9;
      font-weight: 500;
    }

    /* Preview Section */
    .preview-section {
      padding: 40px;
      text-align: center;
    }

    .preview-container {
      background: var(--light);
      border-radius: var(--radius);
      padding: 30px;
      margin: 30px 0;
      border: 2px dashed var(--border);
      transition: var(--transition);
    }

    .preview-container:hover {
      border-color: var(--primary);
      background: rgba(113, 82, 243, 0.02);
    }

    .pdf-preview {
      position: relative;
      display: inline-block;
      margin: 20px 0;
    }

    .pdf-preview img {
      width: 100%;
      max-width: 600px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      transition: var(--transition);
      border: 1px solid var(--border);
    }

    .pdf-preview img:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }

    .preview-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(113, 82, 243, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      opacity: 0;
      transition: var(--transition);
    }

    .pdf-preview:hover .preview-overlay {
      opacity: 1;
    }

    .preview-text {
      color: white;
      font-size: 18px;
      font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .action-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 15px 30px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      min-width: 180px;
      justify-content: center;
    }

    .btn-download {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      box-shadow: 0 4px 15px rgba(113, 82, 243, 0.3);
    }

    .btn-download:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(113, 82, 243, 0.4);
    }

    .btn-print {
      background: linear-gradient(135deg, var(--accent), #00b894);
      color: white;
      box-shadow: 0 4px 15px rgba(0, 201, 167, 0.3);
    }

    .btn-print:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 201, 167, 0.4);
    }

    /* Info Cards */
    .info-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      margin-top: 40px;
    }

    .info-card {
      background: white;
      border-radius: var(--radius);
      padding: 25px;
      box-shadow: var(--shadow-light);
      border-left: 4px solid var(--primary);
      transition: var(--transition);
    }

    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .info-card-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--secondary);
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-card-title i {
      color: var(--primary);
    }

    .info-card-content {
      color: var(--gray);
      line-height: 1.6;
    }

    /* Logout Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 500;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(3px);
    }

    .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: var(--radius);
      text-align: center;
      max-width: 400px;
      width: 90%;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-content h3 {
      margin-bottom: 20px;
      color: var(--secondary);
      font-size: 20px;
    }

    .modal-content p {
      color: var(--gray);
      margin-bottom: 25px;
    }

    .modal-buttons {
      display: flex;
      gap: 12px;
      justify-content: center;
    }

    .modal-content button {
      padding: 12px 25px;
      border: none;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 15px;
      font-weight: 600;
      transition: var(--transition);
      flex: 1;
    }

    .btn-cancel {
      background: #f1f3f5;
      color: var(--gray);
    }

    .btn-cancel:hover {
      background: #e9ecef;
    }

    .btn-logout {
      background: #e74c3c;
      color: white;
    }

    .btn-logout:hover {
      background: #c0392b;
    }


    /* Responsive Styles */
    @media (max-width: 1024px) {
      .sidebar {
        width: 240px;
      }

      .main {
        margin-left: 240px;
        padding: 25px;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .sidebar-toggle {
        display: block;
      }

      .main {
        margin-left: 0;
        padding: 70px 20px 20px;
      }

      .header {
        flex-direction: column;
        text-align: center;
        padding: 20px;
        gap: 15px;
      }

      .header-content {
        text-align: center;
      }

      .preview-section {
        padding: 25px;
      }

      .action-buttons {
        flex-direction: column;
        align-items: center;
      }

      .action-btn {
        width: 100%;
        max-width: 300px;
      }

      .info-cards {
        grid-template-columns: 1fr;
      }

      .pdf-preview img {
        max-width: 100%;
      }
    }

    @media (max-width: 480px) {
      .main {
        padding: 70px 15px 15px;
      }

      .pds-header {
        padding: 20px;
      }

      .pds-title {
        font-size: 24px;
      }

      .preview-section {
        padding: 20px;
      }

      .preview-container {
        padding: 20px;
      }

      .modal-content {
        padding: 25px 20px;
      }

      .modal-buttons {
        flex-direction: column;
      }
    }

    @media print {

      .sidebar,
      .header,
      .action-buttons,
      .info-cards {
        display: none !important;
      }

      .main {
        margin: 0;
        padding: 0;
      }

      .pds-container {
        box-shadow: none;
        border-radius: 0;
      }

      .pdf-preview img {
        box-shadow: none;
        max-width: 100%;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="logo-section">
      <img src="../assets/images/logo.png" alt="Company Logo" />
      <h4>HUMAN RESOURCE<br>EMPLOYEE INFORMATION SYSTEM</h4>
    </div>

    <!-- Menu -->
    <ul class="menu">
      <li>
        <a href="dashboard.php">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
      </li>

      <li>
        <a href="myprofile.php">
          <i class="fas fa-user"></i> My Profile
        </a>
      </li>

      <li>
        <a href="leave_history.php">
          <i class="fas fa-calendar-alt"></i> Leave History
        </a>
      </li>

      <li class="active">
        <a href="pds.php">
          <i class="fas fa-id-card"></i> Personal Data Sheet
        </a>
      </li>
    </ul>
  </div>

  <div class="modal" id="logoutModal">
    <div class="modal-content">
      <h3>Are you sure you want to logout?</h3>
      <form action="logout.php" method="POST" style="margin-top:10px;">
        <button type="submit" class="btn-logout">Yes, Logout</button>
        <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      </form>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="header">
      <div class="profile-info">
        <img src="../admin/<?php echo !empty($employee['avatar']) ? $employee['avatar'] : '../assets/images/employee-default.jpg'; ?>"
          id="btn-profile" alt="Employee Avatar" style="width:60px; height:60px; border-radius:50%; object-fit:cover;">
        <div class="profile-text">
          <h2><?php echo htmlspecialchars($employee['firstname'] . " " . $employee['lastname']); ?></h2>
          <p><?php echo htmlspecialchars($employee['designation']); ?></p>
        </div>
      </div>

      <div class="header-actions">
        <button class="btn-profile">
          <i class="fas fa-user"></i> Profile
        </button>
        <button class="btn-profile" style="margin-left:10px; background:#5b3fd6; color:#fff;">
          <i class="fas fa-id-card"></i> Personal Data Sheet
        </button>
      </div>
    </div>
    <div class="modal" id="profileModal">
      <div class="modal-content profile-modal">
        <div class="modal-header">
          <h3>Account Menu</h3>
          <button class="close-modal" onclick="closeProfileModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Profile Info Centered -->
        <div class="profile-info-centered">
          <img src="../admin/<?php echo !empty($employee['avatar']) ? $employee['avatar'] : '../assets/images/employee-default.jpg'; ?>" alt="Admin Avatar" />
          <h4><?php echo htmlspecialchars($employee['firstname'] . " " . $employee['lastname']); ?></h4>
          <p class="profile-email"><?php echo htmlspecialchars($employee['email']); ?></p>
        </div>

        <!-- Account Info Box -->
        <div class="account-info-box">
          <h5><i class="fas fa-id-card"></i> Account Information</h5>

          <div class="info-row">
            <i class="fas fa-user-tag"></i>
            <span>First Name: <?php echo htmlspecialchars($employee['firstname']); ?></span>
          </div>

          <div class="info-row">
            <i class="fas fa-user-friends"></i>
            <span>Last Name: <?php echo htmlspecialchars($employee['lastname']); ?></span>
          </div>

          <div class="info-row">
            <i class="fas fa-briefcase"></i>
            <span>Designation: <?php echo htmlspecialchars($employee['designation']); ?></span>
          </div>

          <div class="info-row">
            <i class="fas fa-envelope"></i>
            <span>Email: <?php echo htmlspecialchars($employee['email']); ?></span>
          </div>


        </div>
        <div class="modal-menu"> <!-- Change Password -->
          <div class="menu-item" onclick="openSettings()">
            <div class="menu-icon"> <i class="fas fa-key"></i> </div>
            <div class="menu-content"> <span class="menu-title">Change password</span> <span class="menu-desc">Change password settings</span> </div> <i class="fas fa-chevron-right menu-arrow"></i>
          </div> <!-- Activity Log -->
          <div class="menu-item" onclick="openActivityLog()">
            <div class="menu-icon"> <i class="fas fa-history"></i> </div>
            <div class="menu-content"> <span class="menu-title">Activity Log</span> <span class="menu-desc">Manage your activity</span> </div> <i class="fas fa-chevron-right menu-arrow"></i>
          </div>
          <div class="menu-divider"></div> <!-- Logout -->
          <div class="menu-item logout-item" onclick="confirmLogout()">
            <div class="menu-icon"> <i class="fas fa-door-open"></i> </div>
            <div class="menu-content"> <span class="menu-title">Logout</span> <span class="menu-desc">Sign out from your account</span> </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <p>HR Employee Information System v1.0</p>
        </div>
      </div>
    </div>


    <!-- Logout Confirmation Modal -->
    <div class="modal" id="logoutConfirmModal">
      <div class="modal-content confirm-modal">
        <div class="confirm-icon">
          <i class="fas fa-sign-out-alt"></i>
        </div>
        <h3>Confirm Logout</h3>
        <p>Are you sure you want to logout from your account?</p>
        <div class="confirm-buttons">
          <button class="btn-cancel" onclick="closeLogoutConfirm()">Cancel</button>
          <button class="btn-confirm" onclick="performLogout()">Yes, Logout</button>
        </div>
      </div>
    </div>
    <!-- Change Password Modal -->
    <div class="modal" id="changePasswordModal">
      <div class="modal-content password-modal">
        <div class="modal-header">
          <h3>Change Password</h3>
          <button class="close-modal" onclick="closeChangePasswordModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <form id="changePasswordForm">
            <div class="form-group">
              <label for="currentPassword">
                <i class="fas fa-lock"></i> Current Password
              </label>
              <div class="password-input-container">
                <input type="password" id="currentPassword" name="currentPassword" required>
                <button type="button" class="toggle-password" onclick="togglePassword('currentPassword')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label for="newPassword">
                <i class="fas fa-key"></i> New Password
              </label>
              <div class="password-input-container">
                <input type="password" id="newPassword" name="newPassword" required minlength="6">
                <button type="button" class="toggle-password" onclick="togglePassword('newPassword')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <div class="password-strength">
                <div class="strength-bar">
                  <div class="strength-fill" id="strengthFill"></div>
                </div>
                <span class="strength-text" id="strengthText">Password strength</span>
              </div>
            </div>

            <div class="form-group">
              <label for="confirmPassword">
                <i class="fas fa-check-double"></i> Confirm New Password
              </label>
              <div class="password-input-container">
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <div class="password-match" id="passwordMatch">
                <i class="fas fa-info-circle"></i>
                <span>Passwords must match</span>
              </div>
            </div>

            <div class="password-requirements">
              <h4>Password Requirements:</h4>
              <ul>
                <li id="reqLength"><i class="fas fa-check"></i> At least 6 characters</li>
                <li id="reqUppercase"><i class="fas fa-check"></i> One uppercase letter</li>
                <li id="reqLowercase"><i class="fas fa-check"></i> One lowercase letter</li>
                <li id="reqNumber"><i class="fas fa-check"></i> One number</li>
              </ul>
            </div>

            <div class="form-actions">
              <button type="button" class="btn-cancel" onclick="closeChangePasswordModal()">
                Cancel
              </button>
              <button type="submit" class="btn-save" id="savePasswordBtn">
                <i class="fas fa-save"></i> Update Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <style>
      /* Change Password Modal Styles */
      .password-modal {
        max-width: 450px;
        width: 90%;
        max-height: 90vh;
        /* don’t exceed viewport height */
        overflow-y: auto;
        /* make modal content scrollable */
        border-radius: 16px;
        border: 1px solid var(--border);
        background: white;
        display: flex;
        flex-direction: column;
        padding: 0;
      }

      .password-modal {
        scrollbar-width: thin;
        scrollbar-color: var(--primary) var(--light);
      }

      .password-modal::-webkit-scrollbar {
        width: 6px;
      }

      .password-modal::-webkit-scrollbar-thumb {
        background-color: var(--primary);
        border-radius: 6px;
      }

      .password-modal::-webkit-scrollbar-track {
        background: var(--light);
      }

      .modal-body {
        padding: 25px;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
      }

      .form-group label i {
        color: var(--primary);
        font-size: 14px;
        width: 16px;
      }

      .password-input-container {
        position: relative;
        display: flex;
        align-items: center;
      }

      .password-input-container input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        transition: var(--transition);
        background: white;
      }

      .password-input-container input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(113, 82, 243, 0.1);
      }

      .toggle-password {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: var(--transition);
      }

      .toggle-password:hover {
        color: var(--primary);
        background: var(--light);
      }

      .password-strength {
        margin-top: 8px;
      }

      .strength-bar {
        width: 100%;
        height: 4px;
        background: var(--border);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 4px;
      }

      .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 2px;
        transition: all 0.3s ease;
      }

      .strength-text {
        font-size: 11px;
        color: var(--gray);
        font-weight: 500;
      }

      .password-match {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 11px;
        color: var(--gray);
        opacity: 0;
        transition: var(--transition);
      }

      .password-match.show {
        opacity: 1;
      }

      .password-match i {
        font-size: 12px;
      }

      .password-match.matching {
        color: var(--accent);
      }

      .password-match.not-matching {
        color: #e74c3c;
      }

      .password-requirements {
        background: var(--light);
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
      }

      .password-requirements h4 {
        margin: 0 0 10px 0;
        font-size: 13px;
        color: var(--secondary);
        font-weight: 600;
      }

      .password-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .password-requirements li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--gray);
        margin-bottom: 6px;
      }

      .password-requirements li i {
        font-size: 10px;
        width: 12px;
      }

      .password-requirements li.valid {
        color: var(--accent);
      }

      .password-requirements li.invalid {
        color: #e74c3c;
      }

      .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 25px;
      }

      .btn-cancel {
        padding: 12px 24px;
        background: var(--light);
        color: var(--gray);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
      }

      .btn-cancel:hover {
        background: #e9ecef;
        color: var(--dark);
      }

      .btn-save {
        padding: 12px 24px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .btn-save:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-1px);
      }

      .btn-save:disabled {
        background: var(--gray-light);
        color: var(--gray);
        cursor: not-allowed;
        transform: none;
      }

      /* Password strength colors */
      .strength-weak {
        background: #e74c3c;
        width: 33%;
      }

      .strength-medium {
        background: #f39c12;
        width: 66%;
      }

      .strength-strong {
        background: var(--accent);
        width: 100%;
      }

      /* Success/Error Messages */
      .message {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        display: none;
      }

      .message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        display: block;
      }

      .message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        display: block;
      }

      /* Loading state */
      .loading {
        opacity: 0.7;
        pointer-events: none;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }

      .fa-spinner {
        animation: spin 1s linear infinite;
      }

      /* Profile centered section */
      .profile-info-centered {
        text-align: center;
        padding: 25px 20px;
        background: white;
        border-bottom: 1px solid var(--border);
      }

      .profile-info-centered img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px solid var(--primary);
        object-fit: cover;
        margin-bottom: 10px;
      }

      .profile-info-centered h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--secondary);
      }

      .profile-info-centered .profile-email {
        margin: 5px 0 0 0;
        font-size: 14px;
        color: var(--gray);
      }

      /* Account Information Box */
      .account-info-box {
        margin: 15px 20px;
        padding: 15px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--light);
      }

      .account-info-box h5 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 8px;
      }

      .info-row i {
        font-size: 14px;
        color: var(--primary);
      }


      .profile-modal {
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        /* keep it inside screen */
        overflow-y: auto;
        /* inner scroll */
        padding: 0;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: white;
      }


      .profile-modal {
        scrollbar-width: thin;
        scrollbar-color: var(--primary) var(--light);
      }

      .profile-modal::-webkit-scrollbar {
        width: 6px;
      }

      .profile-modal::-webkit-scrollbar-thumb {
        background-color: var(--primary);
        border-radius: 6px;
      }

      .profile-modal::-webkit-scrollbar-track {
        background: var(--light);
      }


      .modal-header {
        position: relative;
        /* allows absolute positioning inside */
        padding: 20px 25px;
        border-bottom: 1px solid var(--border);
        background: white;
        text-align: center;
        /* keeps title centered */
      }

      .close-modal {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #333;
      }

      .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--secondary);
      }

      .close-modal:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
      }

      .profile-info-section {
        padding: 25px;
        background: white;
        border-bottom: 1px solid var(--border);
      }

      .profile-avatar {
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .profile-avatar img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid var(--primary);
        object-fit: cover;
      }

      .profile-details h4 {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--secondary);
      }

      .profile-details p {
        margin: 0 0 8px 0;
        font-size: 14px;
        color: var(--gray);
      }

      .profile-status {
        display: inline-block;
        padding: 4px 8px;
        background: var(--accent);
        color: white;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
      }

      .modal-menu {
        padding: 10px 0;
      }

      .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        background: none;
        width: 100%;
        text-align: left;
      }

      .menu-item:hover {
        background: var(--light);
      }

      .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        transition: var(--transition);
      }

      .menu-item:hover .menu-icon {
        background: var(--primary);
      }

      .menu-icon i {
        color: var(--primary);
        font-size: 16px;
        transition: var(--transition);
      }

      .menu-item:hover .menu-icon i {
        color: white;
      }

      .menu-content {
        flex: 1;
      }

      .menu-title {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 3px;
      }

      .menu-desc {
        display: block;
        font-size: 12px;
        color: var(--gray);
      }

      .menu-arrow {
        color: var(--gray);
        font-size: 12px;
        transition: var(--transition);
      }

      .menu-item:hover .menu-arrow {
        color: var(--primary);
        transform: translateX(3px);
      }

      .menu-divider {
        height: 1px;
        background: var(--border);
        margin: 10px 25px;
      }

      .logout-item .menu-icon {
        background: #fee;
      }

      .logout-item .menu-icon i {
        color: #e74c3c;
      }

      .logout-item:hover .menu-icon {
        background: #e74c3c;
      }

      .logout-item:hover .menu-title {
        color: #e74c3c;
      }

      .modal-footer {
        padding: 15px 25px;
        text-align: center;
        border-top: 1px solid var(--border);
        background: var(--light);
      }

      .modal-footer p {
        margin: 0;
        font-size: 11px;
        color: var(--gray);
      }

      /* Logout Confirmation Modal */
      .confirm-modal {
        max-width: 350px;
        width: 90%;
        text-align: center;
        padding: 30px;
        border: 1px solid var(--border);
      }

      .confirm-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fee;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
      }

      .confirm-icon i {
        font-size: 24px;
        color: #e74c3c;
      }

      .confirm-modal h3 {
        margin: 0 0 10px 0;
        color: var(--secondary);
        font-size: 20px;
      }

      .confirm-modal p {
        color: var(--gray);
        margin-bottom: 25px;
        line-height: 1.5;
      }

      .confirm-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
      }

      .btn-cancel {
        flex: 1;
        padding: 12px 20px;
        background: var(--light);
        color: var(--gray);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
      }

      .btn-cancel:hover {
        background: #e9ecef;
        color: var(--dark);
      }

      .btn-confirm {
        flex: 1;
        padding: 12px 20px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
      }

      .btn-confirm:hover {
        background: #c0392b;
      }

      /* Modal Backdrop */
      .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
      }

      .modal-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }

        to {
          opacity: 1;
        }
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px) scale(0.95);
        }

        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }

      /* Responsive Design */
      @media (max-width: 480px) {
        .profile-modal {
          width: 95%;
          margin: 20px;
        }

        .modal-header {
          padding: 15px 20px;
        }

        .profile-info-section {
          padding: 20px;
        }

        .menu-item {
          padding: 12px 20px;
        }

        .confirm-modal {
          padding: 25px 20px;
        }

        .confirm-buttons {
          flex-direction: column;
        }
      }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      function openProfileModal() {
        document.getElementById('profileModal').style.display = 'flex';
      }

      function closeProfileModal() {
        document.getElementById('profileModal').style.display = 'none';
      }

      function openProfileSettings() {
        closeProfileModal();
        alert('Opening Change Password Settings...');
      }

      function openChangePasswordModal() {
        document.getElementById('changePasswordModal').style.display = 'flex';
        resetPasswordForm();
      }

      function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').style.display = 'none';
      }

      function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = passwordField.nextElementSibling.querySelector('i');

        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          toggleIcon.className = 'fas fa-eye-slash';
        } else {
          passwordField.type = 'password';
          toggleIcon.className = 'fas fa-eye';
        }
      }

      function checkPasswordStrength(password) {
        let strength = 0;
        const requirements = {
          length: password.length >= 6,
          uppercase: /[A-Z]/.test(password),
          lowercase: /[a-z]/.test(password),
          number: /[0-9]/.test(password)
        };

        // Update requirement indicators
        Object.keys(requirements).forEach(req => {
          const element = document.getElementById(`req${req.charAt(0).toUpperCase() + req.slice(1)}`);
          if (requirements[req]) {
            element.classList.add('valid');
            element.classList.remove('invalid');
            element.innerHTML = '<i class="fas fa-check"></i> ' + element.textContent.replace(/^.*? - /, '');
            strength++;
          } else {
            element.classList.add('invalid');
            element.classList.remove('valid');
            element.innerHTML = '<i class="fas fa-times"></i> ' + element.textContent.replace(/^.*? - /, '');
          }
        });

        // Update strength bar and text
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        strengthFill.className = 'strength-fill';

        if (password.length === 0) {
          strengthFill.style.width = '0%';
          strengthText.textContent = 'Password strength';
          strengthText.style.color = 'var(--gray)';
        } else if (strength <= 1) {
          strengthFill.classList.add('strength-weak');
          strengthText.textContent = 'Weak password';
          strengthText.style.color = '#e74c3c';
        } else if (strength <= 3) {
          strengthFill.classList.add('strength-medium');
          strengthText.textContent = 'Medium strength';
          strengthText.style.color = '#f39c12';
        } else {
          strengthFill.classList.add('strength-strong');
          strengthText.textContent = 'Strong password';
          strengthText.style.color = 'var(--accent)';
        }
      }

      function checkPasswordMatch() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const matchElement = document.getElementById('passwordMatch');

        if (confirmPassword.length === 0) {
          matchElement.classList.remove('show', 'matching', 'not-matching');
          return false;
        }

        matchElement.classList.add('show');

        if (newPassword === confirmPassword) {
          matchElement.classList.add('matching');
          matchElement.classList.remove('not-matching');
          matchElement.innerHTML = '<i class="fas fa-check-circle"></i><span>Passwords match</span>';
          return true;
        } else {
          matchElement.classList.add('not-matching');
          matchElement.classList.remove('matching');
          matchElement.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Passwords do not match</span>';
          return false;
        }
      }

      function resetPasswordForm() {
        document.getElementById('changePasswordForm').reset();
        document.getElementById('strengthFill').style.width = '0%';
        document.getElementById('strengthText').textContent = 'Password strength';
        document.getElementById('strengthText').style.color = 'var(--gray)';
        document.getElementById('passwordMatch').classList.remove('show', 'matching', 'not-matching');

        // Reset requirement indicators
        const requirements = ['Length', 'Uppercase', 'Lowercase', 'Number'];
        requirements.forEach(req => {
          const element = document.getElementById(`req${req}`);
          element.classList.remove('valid', 'invalid');
          element.innerHTML = `<i class="fas fa-check"></i> ${element.textContent.replace(/^.*? - /, '')}`;
        });
      }

      // Update your openSettings function
      function openSettings() {
        closeProfileModal();
        openChangePasswordModal();
      }

      // Form submission
      document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updatePassword();
      });

      document.getElementById('newPassword').addEventListener('input', function() {
        checkPasswordStrength(this.value);
      });

      document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);

      function updatePassword() {
        const formData = new FormData(document.getElementById('changePasswordForm'));
        const currentPassword = formData.get('currentPassword');
        const newPassword = formData.get('newPassword');
        const confirmPassword = formData.get('confirmPassword');

        const saveBtn = document.getElementById('savePasswordBtn');
        const originalText = saveBtn.innerHTML;

        // Validation
        if (newPassword !== confirmPassword) {
          showMessage('Passwords do not match!', 'error');
          return;
        }

        if (newPassword.length < 6) {
          showMessage('New password must be at least 6 characters long!', 'error');
          return;
        }

        // Show loading state
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        saveBtn.disabled = true;
        document.getElementById('changePasswordForm').classList.add('loading');

        // Send AJAX request
        fetch('update_password.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              currentPassword: currentPassword,
              newPassword: newPassword,
              confirmPassword: confirmPassword
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showMessage(data.message, 'success');
              setTimeout(() => {
                closeChangePasswordModal();
                // Optional: Redirect or show success message
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Password updated successfully!',
                  confirmButtonText: 'OK',
                  timer: 2000,
                  timerProgressBar: true,
                  didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = 9999;
                  }
                });


              }, 1500);
            } else {
              showMessage(data.message, 'error');
            }
          })
          .catch(error => {
            showMessage('An error occurred while updating password.', 'error');
            console.error('Error:', error);
          })
          .finally(() => {
            // Reset button state
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            document.getElementById('changePasswordForm').classList.remove('loading');
          });
      }

      function showMessage(message, type) {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => msg.remove());

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;

        const modalBody = document.querySelector('.modal-body');
        modalBody.insertBefore(messageDiv, modalBody.firstChild);

        // Auto-remove success messages after 5 seconds
        if (type === 'success') {
          setTimeout(() => {
            messageDiv.remove();
          }, 5000);
        }
      }

      // Close modal when clicking outside
      document.addEventListener('click', function(event) {
        const changePasswordModal = document.getElementById('changePasswordModal');
        if (event.target === changePasswordModal) {
          closeChangePasswordModal();
        }
      });

      // Close modal with Escape key
      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
          closeChangePasswordModal();
        }
      });

      function openActivityLog() {
        closeProfileModal();
        alert('Opening Acitivity Log...');
      }

      function confirmLogout() {
        closeProfileModal();
        document.getElementById('logoutConfirmModal').style.display = 'flex';
      }

      function closeLogoutConfirm() {
        document.getElementById('logoutConfirmModal').style.display = 'none';
      }

      function performLogout() {
        window.location.href = 'logout.php';
      }
      document.addEventListener('click', function(event) {
        const profileModal = document.getElementById('profileModal');
        const logoutModal = document.getElementById('logoutConfirmModal');

        if (event.target === profileModal) {
          closeProfileModal();
        }

        if (event.target === logoutModal) {
          closeLogoutConfirm();
        }
      });

      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
          closeProfileModal();
          closeLogoutConfirm();
        }
      });
      document.addEventListener('DOMContentLoaded', function() {
        /* const profileBtn = document.querySelector('.btn-profile'); */
        const profileBtn = document.getElementById('btn-profile');

        /*   if (profileBtn) {
            profileBtn.addEventListener('click', openProfileModal);
          } */

        if (profileBtn) {
          profileBtn.addEventListener('click', openProfileModal);
        }
      });
    </script>

    <!-- PDS Container -->
    <div class="pds-container">
      <div class="pds-header">
        <i class="fas fa-file-contract pds-icon"></i>
        <h1 class="pds-title">Personal Data Sheet</h1>
        <p class="pds-subtitle">Official Government Document</p>
      </div>

      <div class="preview-section">
        <h2 style="color: var(--secondary); margin-bottom: 20px; font-size: 24px;">
          <i class="fas fa-eye" style="color: var(--primary);"></i> Document Preview
        </h2>
        <p style="color: var(--gray); margin-bottom: 30px; font-size: 16px;">
          Preview your Personal Data Sheet before downloading or printing
        </p>

        <div class="preview-container">
          <div class="pdf-preview">
            <img src="pds-preview1.jpg" alt="Personal Data Sheet Preview">
            <!--  <div class="preview-overlay">
              <span class="preview-text">Click to View Full Size</span>
            </div> -->
          </div>
        </div>

        <div class="action-buttons">
          <a href="pds-form.pdf" download class="action-btn btn-download">
            <i class="fas fa-download"></i> Download PDS
          </a>
          <a href="pds-form.pdf" target="_blank" class="action-btn btn-print">
            <i class="fas fa-print"></i> Open to Print
          </a>
        </div>
      </div>
    </div>

    <!-- Information Cards -->
    <div class="info-cards">
      <div class="info-card">
        <div class="info-card-title">
          <i class="fas fa-info-circle"></i> About PDS
        </div>
        <div class="info-card-content">
          The Personal Data Sheet (PDS) is an official document that contains comprehensive information about government employees. It serves as the primary basis for personnel actions and records.
        </div>
      </div>

      <div class="info-card">
        <div class="info-card-title">
          <i class="fas fa-check-circle"></i> Requirements
        </div>
        <div class="info-card-content">
          Ensure all information is accurate and up-to-date. The PDS must be signed and notarized for official submission. Keep a copy for your personal records.
        </div>
      </div>

      <div class="info-card">
        <div class="info-card-title">
          <i class="fas fa-clock"></i> Last Updated
        </div>
        <div class="info-card-content">
          It is recommended to update your PDS annually or whenever there are significant changes in your personal or employment information.
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar toggle
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('sidebarToggle');

      if (window.innerWidth <= 768 &&
        !sidebar.contains(event.target) &&
        !toggleBtn.contains(event.target) &&
        sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
      }
    });

    // Logout modal
    const logoutAvatar = document.getElementById('logoutAvatar');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');

    logoutAvatar.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    cancelLogout.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });

    // Add click effect to action buttons
    document.querySelectorAll('.action-btn').forEach(button => {
      button.addEventListener('click', function(e) {
        // Add ripple effect
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
          position: absolute;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.6);
          transform: scale(0);
          animation: ripple 0.6s linear;
          width: ${size}px;
          height: ${size}px;
          left: ${x}px;
          top: ${y}px;
        `;

        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);

        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });

    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>

</html>