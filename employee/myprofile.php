<?php
session_start();
include_once('../db.php');

if (!isset($_SESSION['employee_id'])) {
  header("Location: login.php");
  exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch employee details based on your database structure
$stmt = $conn->prepare("SELECT id, employee_id, avatar, username, firstname, lastname, mobile_number, 
                        date_of_birth, marital_status, gender, nationality, address, city, state, zip_code,
                        designation, employee_type, salary_grade, department, cs_eligibility, working_days, 
                        joining_date, government_id, cv, service_record, appointment_paper, tor, cs_certificate, pds,
                        email, status
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
  <title>HR Employee Information System | My Profile</title>
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
      background-color: #f8fafc;
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
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-profile:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    /* Profile Container */
    .profile-container {
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow-light);
      overflow: hidden;
    }

    .profile-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 30px;
      text-align: center;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 5px solid white;
      margin: 0 auto 20px;
      box-shadow: var(--shadow);
      object-fit: cover;
    }

    .profile-name {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .profile-title {
      font-size: 16px;
      opacity: 0.9;
      margin-bottom: 5px;
    }

    .profile-department {
      font-size: 14px;
      opacity: 0.8;
    }

    /* Progress Steps */
    .progress-steps {
      display: flex;
      justify-content: center;
      padding: 25px 30px;
      background: white;
      border-bottom: 1px solid var(--border);
    }

    .step {
      display: flex;
      align-items: center;
      padding: 0 20px;
      position: relative;
    }

    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--light);
      color: var(--gray);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-right: 12px;
      border: 2px solid var(--border);
      transition: var(--transition);
    }

    .step.active .step-number {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .step.completed .step-number {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .step-text {
      font-weight: 600;
      color: var(--gray);
      transition: var(--transition);
    }

    .step.active .step-text {
      color: var(--primary);
    }

    .step.completed .step-text {
      color: var(--accent);
    }

    .step:not(:last-child):after {
      content: '';
      position: absolute;
      top: 20px;
      right: -10px;
      width: 40px;
      height: 2px;
      background: var(--border);
    }

    /* Form Sections */
    .form-section {
      display: none;
      padding: 40px;
    }

    .form-section.active {
      display: block;
      animation: fadeIn 0.5s ease;
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

    .section-title {
      font-size: 22px;
      font-weight: 600;
      color: var(--secondary);
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .section-title i {
      color: var(--primary);
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--secondary);
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 15px;
      transition: var(--transition);
      background: white;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(113, 82, 243, 0.1);
    }

    .form-control:read-only {
      background-color: #f8f9fa;
      color: var(--gray);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    /* Document Links */
    .document-links {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 10px;
    }

    .doc-link {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 15px;
      background: var(--light);
      border-radius: 6px;
      text-decoration: none;
      color: var(--dark);
      font-size: 14px;
      transition: var(--transition);
      border: 1px solid var(--border);
    }

    .doc-link:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
    }

    .doc-link i {
      font-size: 16px;
    }

    /* Navigation Buttons */
    .form-navigation {
      display: flex;
      justify-content: space-between;
      padding: 30px 40px;
      background: #f8f9fa;
      border-top: 1px solid var(--border);
    }

    .btn {
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-prev {
      background: white;
      color: var(--gray);
      border: 1px solid var(--border);
    }

    .btn-prev:hover {
      background: #f8f9fa;
      color: var(--dark);
    }

    .btn-next {
      background: var(--primary);
      color: white;
    }

    .btn-next:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    .btn-save {
      background: var(--accent);
      color: white;
    }

    .btn-save:hover {
      background: #00b894;
      transform: translateY(-2px);
    }

    /* Info Cards */
    .info-card {
      background: white;
      border-radius: var(--radius);
      padding: 25px;
      box-shadow: var(--shadow-light);
      margin-bottom: 25px;
      border-left: 4px solid var(--primary);
    }

    .info-card-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--secondary);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-card-title i {
      color: var(--primary);
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .info-item {
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
    }

    .info-item:last-child {
      border-bottom: none;
    }

    .info-label {
      font-weight: 600;
      color: var(--gray);
      font-size: 14px;
      margin-bottom: 5px;
    }

    .info-value {
      color: var(--dark);
      font-size: 16px;
      font-weight: 500;
    }

    /* Status Badge */
    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
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

      .profile-info {
        flex-direction: column;
        text-align: center;
      }

      .profile-info img {
        margin-right: 0;
        margin-bottom: 15px;
      }

      .progress-steps {
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
      }

      .step:not(:last-child):after {
        display: none;
      }

      .form-section {
        padding: 25px;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .form-navigation {
        flex-direction: column;
        gap: 15px;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .document-links {
        flex-direction: column;
      }
    }

    @media (max-width: 480px) {
      .main {
        padding: 70px 15px 15px;
      }

      .profile-header {
        padding: 20px;
      }

      .profile-avatar {
        width: 100px;
        height: 100px;
      }

      .profile-name {
        font-size: 24px;
      }

      .form-section {
        padding: 20px;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }

      .modal-content {
        padding: 25px 20px;
      }

      .modal-buttons {
        flex-direction: column;
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

      <li class="active">
        <a href="#">
          <i class="fas fa-user"></i> My Profile
        </a>
      </li>

      <li>
        <a href="leave_history.php">
          <i class="fas fa-calendar-alt"></i> Leave History
        </a>
      </li>

      <li>
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
          id="btn-profile" alt="Employee Avatar">
        <div class="profile-text">
          <h2><?php echo htmlspecialchars($employee['firstname'] . " " . $employee['lastname']); ?></h2>
          <p><?php echo htmlspecialchars($employee['designation']); ?></p>
        </div>
      </div>
      <button class="btn-profile">
        <i class="fas fa-user"></i> Profile
      </button>
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

    <!-- Profile Container -->
    <div class="profile-container">
      <!-- Profile Header -->
      <div class="profile-header">
        <img src="../admin/<?php echo !empty($employee['avatar']) ? $employee['avatar'] : '../assets/images/employee-default.jpg'; ?>"
          alt="Profile Picture" class="profile-avatar">
        <h1 class="profile-name"><?php echo htmlspecialchars($employee['firstname'] . " " . $employee['lastname']); ?></h1>
        <p class="profile-title"><?php echo htmlspecialchars($employee['designation']); ?></p>
        <p class="profile-department"><?php echo htmlspecialchars($employee['department']); ?> Department</p>
        <span class="status-badge status-<?php echo $employee['status']; ?>">
          <?php echo ucfirst($employee['status']); ?>
        </span>
      </div>

      <!-- Progress Steps -->
      <div class="progress-steps">
        <div class="step active" data-step="1">
          <div class="step-number">1</div>
          <div class="step-text">Personal Information</div>
        </div>
        <div class="step" data-step="2">
          <div class="step-number">2</div>
          <div class="step-text">Employment Details</div>
        </div>
        <div class="step" data-step="3">
          <div class="step-number">3</div>
          <div class="step-text">Documents</div>
        </div>
      </div>

      <!-- Personal Information Form -->
      <div class="form-section active" id="section-1">
        <div class="section-title">
          <i class="fas fa-user-circle"></i> Personal Information
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Employee ID</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['employee_id']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['username']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['firstname']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['lastname']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Mobile Number</label>
            <input type="tel" class="form-control" value="<?php echo htmlspecialchars($employee['mobile_number'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Date of Birth</label>
            <input type="text" class="form-control" value="<?php echo !empty($employee['date_of_birth']) ? date("F d, Y", strtotime($employee['date_of_birth'])) : 'N/A'; ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Gender</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['gender']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Marital Status</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Nationality</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['nationality'] ?? 'N/A'); ?>" readonly>
          </div>
        </div>

        <!-- Address Information -->
        <div class="info-card">
          <div class="info-card-title">
            <i class="fas fa-home"></i> Address Information
          </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['address'] ?? 'N/A'); ?>" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">City</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['city'] ?? 'N/A'); ?>" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">State/Province</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['state'] ?? 'N/A'); ?>" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">ZIP Code</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['zip_code'] ?? 'N/A'); ?>" readonly>
            </div>
          </div>
        </div>
      </div>

      <!-- Employment Details -->
      <div class="form-section" id="section-2">
        <div class="section-title">
          <i class="fas fa-briefcase"></i> Employment Details
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Designation</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['designation']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Department</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['department']); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Employee Type</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['employee_type'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Salary Grade</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['salary_grade'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Civil Service Eligibility</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['cs_eligibility'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Working Days</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['working_days'] ?? 'N/A'); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Joining Date</label>
            <input type="text" class="form-control" value="<?php echo date("F d, Y", strtotime($employee['joining_date'])); ?>" readonly>
          </div>

          <div class="form-group">
            <label class="form-label">Employment Status</label>
            <input type="text" class="form-control" value="<?php echo ucfirst($employee['status']); ?>" readonly>
          </div>
        </div>
      </div>

      <!-- Documents Section -->
      <div class="form-section" id="section-3">
        <div class="section-title">
          <i class="fas fa-folder-open"></i> Employee Documents
        </div>

        <div class="info-grid">
          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-id-card"></i> Government ID
            </div>
            <div class="info-item">
              <?php if (!empty($employee['government_id'])): ?>
                <a href="../admin/<?php echo $employee['government_id']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download Government ID
                </a>
              <?php else: ?>
                <p class="info-value">No government ID uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-file-alt"></i> Curriculum Vitae
            </div>
            <div class="info-item">
              <?php if (!empty($employee['cv'])): ?>
                <a href="../admin/<?php echo $employee['cv']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download CV
                </a>
              <?php else: ?>
                <p class="info-value">No CV uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-history"></i> Service Record
            </div>
            <div class="info-item">
              <?php if (!empty($employee['service_record'])): ?>
                <a href="../admin/<?php echo $employee['service_record']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download Service Record
                </a>
              <?php else: ?>
                <p class="info-value">No service record uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-file-contract"></i> Appointment Paper
            </div>
            <div class="info-item">
              <?php if (!empty($employee['appointment_paper'])): ?>
                <a href="../admin/<?php echo $employee['appointment_paper']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download Appointment Paper
                </a>
              <?php else: ?>
                <p class="info-value">No appointment paper uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-graduation-cap"></i> Transcript of Records
            </div>
            <div class="info-item">
              <?php if (!empty($employee['tor'])): ?>
                <a href="../admin/<?php echo $employee['tor']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download TOR
                </a>
              <?php else: ?>
                <p class="info-value">No TOR uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-certificate"></i> Civil Service Certificate
            </div>
            <div class="info-item">
              <?php if (!empty($employee['cs_certificate'])): ?>
                <a href="../admin/<?php echo $employee['cs_certificate']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download CS Certificate
                </a>
              <?php else: ?>
                <p class="info-value">No CS certificate uploaded</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-card">
            <div class="info-card-title">
              <i class="fas fa-file-pdf"></i> Personal Data Sheet
            </div>
            <div class="info-item">
              <?php if (!empty($employee['pds'])): ?>
                <a href="../admin/<?php echo $employee['pds']; ?>" class="doc-link" target="_blank">
                  <i class="fas fa-download"></i> Download PDS
                </a>
              <?php else: ?>
                <p class="info-value">No PDS uploaded</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="form-navigation">
        <button class="btn btn-prev" id="prevBtn" style="display: none;">
          <i class="fas fa-arrow-left"></i> Previous
        </button>
        <button class="btn btn-next" id="nextBtn">
          Next <i class="fas fa-arrow-right"></i>
        </button>
        <button class="btn btn-save" id="saveBtn" style="display: none;">
          <i class="fas fa-save"></i> Save Changes
        </button>
      </div>
    </div>
  </div>

  <script>
    // Navigation functionality
    let currentSection = 1;
    const totalSections = 3;

    function showSection(sectionNumber) {
      // Hide all sections
      document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
      });

      // Show current section
      document.getElementById(`section-${sectionNumber}`).classList.add('active');

      // Update progress steps
      document.querySelectorAll('.step').forEach(step => {
        const stepNumber = parseInt(step.dataset.step);
        step.classList.remove('active', 'completed');

        if (stepNumber === sectionNumber) {
          step.classList.add('active');
        } else if (stepNumber < sectionNumber) {
          step.classList.add('completed');
        }
      });

      // Update navigation buttons
      document.getElementById('prevBtn').style.display = sectionNumber === 1 ? 'none' : 'flex';
      document.getElementById('nextBtn').style.display = sectionNumber === totalSections ? 'none' : 'flex';
      document.getElementById('saveBtn').style.display = sectionNumber === totalSections ? 'flex' : 'none';
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
      if (currentSection < totalSections) {
        currentSection++;
        showSection(currentSection);
      }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
      if (currentSection > 1) {
        currentSection--;
        showSection(currentSection);
      }
    });

    // Initialize first section
    showSection(1);

    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
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

    // Logout functionality
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
  </script>
</body>

</html>