<?php
session_start();
include_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT name FROM admin 
                        WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$stmt->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>HR Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

    .menu {
      list-style: none;
      width: 100%;
      padding: 0;
      margin: 0;
    }

    .menu li {
      margin-bottom: 8px;
      border-radius: var(--radius-sm);
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
      border-radius: var(--radius-sm);
      text-decoration: none;
      color: rgba(255, 255, 255, 0.9);
      font-size: 15px;
      transition: var(--transition);
    }

    .menu a:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(5px);
    }

    .menu li.active a {
      color: white;
    }

    .menu i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 30px;
      margin-left: 240px;
      transition: margin-left 0.3s ease;
    }

    .cards {
      display: flex;
      gap: 20px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .card {
      flex: 1;
      min-width: 220px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .card h4 {
      margin: 0;
      font-size: 14px;
      color: #666;
    }

    .card p {
      font-size: 28px;
      font-weight: bold;
      margin: 10px 0 0;
    }

    .graph-section {
      margin-top: 30px;
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .graph-box {
      flex: 2;
      background: white;
      padding: 20px;
      border-radius: 12px;
      min-width: 300px;
    }

    .employee-list {
      flex: 1;
      background: white;
      padding: 20px;
      border-radius: 12px;
      min-width: 200px;
    }

    .employee-list h4 {
      margin-bottom: 10px;
    }

    .employee-list ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .employee-list li {
      padding: 6px 0;
      border-bottom: 1px solid #eee;
    }

    .btn-add {
      margin-top: 30px;
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    .btn-add:hover {
      background-color: #0056b3;
    }

    canvas {
      width: 100% !important;
      max-height: 250px !important;
    }

    .avatar {
      width: 40px;
      border-radius: 50%;
      cursor: pointer;
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
        width: 200px;
      }

      .main {
        margin-left: 200px;
        padding: 20px;
      }

      .card {
        min-width: calc(50% - 20px);
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
        padding: 60px 15px 15px;
      }

      .card {
        min-width: 100%;
      }

      .graph-box,
      .employee-list {
        min-width: 100%;
      }

      .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle">☰</button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar"
    style="width: 260px; height: 100vh; background: #7152F3; color: #fff; 
            display: flex; flex-direction: column; align-items: center; 
            padding: 20px 15px; position: fixed; left: 0; top: 0;">

    <!-- Logo -->
    <img src="../assets/images/logo.png" alt="Logo"
      style="width: 80px; margin-bottom: 15px; border-radius: 50%; background:#fff; padding:8px;" />

    <!-- Title -->
    <h4 style="text-align: center; font-size: 14px; font-weight: 600; line-height: 20px; margin-bottom: 25px;">
      HUMAN RESOURCE <br> EMPLOYEE INFORMATION SYSTEM
    </h4>

    <!-- Menu -->
    <ul class="menu" style="list-style: none; width: 100%; padding: 0; margin: 0;">

      <li class="active"
        style="background: #5b3fd6; border-radius: 8px; margin-bottom: 10px; padding: 12px 15px; 
               display: flex; align-items: center; gap: 10px; font-size: 14px;">
        <i class="fas fa-chart-line"></i> Dashboard
      </li>

      <li style="margin-bottom: 10px;">
        <a href="employee.php"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
          <i class="fas fa-users"></i> Employee
        </a>
      </li>

      <li style="margin-bottom: 10px;">
        <a href="department.php"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
          <i class="fas fa-building"></i> All Departments
        </a>
      </li>
      <li style="margin-bottom: 10px;">
        <a href="leaveform.php"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
          <i class="fas fa-file-signature"></i> Leave Form
        </a>
      </li>
      <li>
        <a href="leavereports.php"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
          <i class="fas fa-folder-open"></i> Leave Reports
        </a>
      </li>
    </ul>
  </div>

  <!-- Hover Effect -->
  <style>
    .sidebar .menu a:hover {
      background: #5b3fd6;
    }
  </style>
  <div class="modal" id="logoutModal">
    <div class="modal-content">
      <h3>Are you sure you want to logout?</h3>
      <form action="logout.php" method="POST" style="margin-top:10px;">
        <button type="submit" class="btn-logout">Yes, Logout</button>
        <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      </form>
    </div>
  </div>
  <?php
  include_once '../db.php';

  $totalEmployees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM employees"))['count'];

  $newHires = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) as count FROM employees WHERE MONTH(joining_date) = MONTH(CURDATE()) AND YEAR(joining_date) = YEAR(CURDATE())"
  ))['count'];

  $onLeave = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) as count FROM leaves WHERE CURDATE() BETWEEN leave_start_date AND leave_end_date AND leave_status='Approved'"
  ))['count'];

  $recentEmployees = mysqli_query($conn, "SELECT firstname, lastname, avatar FROM employees ORDER BY id DESC LIMIT 5");
  ?>

  <?php
  include_once '../db.php';

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Employee Growth: count new hires per month
  $growthData = [];
  for ($m = 1; $m <= 12; $m++) {
    $sql = "SELECT COUNT(*) as total FROM employees WHERE MONTH(joining_date) = $m AND YEAR(joining_date) = YEAR(CURDATE())";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $growthData[] = (int)$row['total'];
  }

  // Gender Ratio
  $monthQuery = "SELECT MONTHNAME(leave_start_date) as month, COUNT(*) as total 
               FROM leaves 
               GROUP BY MONTH(leave_start_date)";
  $monthResult = $conn->query($monthQuery);
  $leaveMonths = [];
  $leaveMonthCounts = [];
  while ($row = $monthResult->fetch_assoc()) {
    $leaveMonths[] = $row['month'];
    $leaveMonthCounts[] = (int)$row['total'];
  }
  // Department Distribution
  $deptSql = "SELECT department, COUNT(*) as total FROM employees GROUP BY department";
  $deptRes = $conn->query($deptSql);
  $deptLabels = [];
  $deptData = [];
  while ($row = $deptRes->fetch_assoc()) {
    $deptLabels[] = $row['department'];
    $deptData[] = (int)$row['total'];
  }
  ?>


  <!-- Main Content -->
  <div class="main">
    <div class="header">
      <div class="profile-info">

        <img src="../assets/images/admin.jpg" class="avatar" id="btn-profile" alt="Admin Avatar" />
        <div class="profile-text">
          <h2><?php echo htmlspecialchars($admin['name']); ?></h2>
          <p>Administrator</p>
        </div>
      </div>
      <style>
        .btn-profile.active {
          background: #542eedff;
        }
      </style>

      <div class="header-actions">
        <button class="btn-profile">
          <i class="fas fa-user"></i> Profile
        </button>
        <button class="btn-profile active">
          <i class="fas fa-chart-line"></i> dashboard
        </button>
      </div>
    </div>
    <!-- Profile Modal -->
    <div class="modal" id="profileModal">
      <div class="modal-content profile-modal">
        <div class="modal-header">
          <h3>Account Menu</h3>
          <button class="close-modal" onclick="closeProfileModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="profile-info-section">
          <div class="profile-avatar">
            <img src="../assets/images/admin.jpg" alt="Admin Avatar" />
            <div class="profile-details">
              <h4>System Admin</h4>
              <p>Administrator</p>
              <span class="profile-status">Online</span>
            </div>
          </div>
        </div>

        <div class="modal-menu">
          <!-- Change Password -->
          <div class="menu-item" onclick="openSettings()">
            <div class="menu-icon">
              <i class="fas fa-key"></i>
            </div>
            <div class="menu-content">
              <span class="menu-title">Change password</span>
              <span class="menu-desc">Change password settings</span>
            </div>
            <i class="fas fa-chevron-right menu-arrow"></i>
          </div>

          <!-- Activity Log -->
          <div class="menu-item" onclick="openActivityLog()">
            <div class="menu-icon">
              <i class="fas fa-history"></i>
            </div>
            <div class="menu-content">
              <span class="menu-title">Activity Log</span>
              <span class="menu-desc">Manage your activity</span>
            </div>
            <i class="fas fa-chevron-right menu-arrow"></i>
          </div>

          <div class="menu-divider"></div>

          <!-- Logout -->
          <div class="menu-item logout-item" onclick="confirmLogout()">
            <div class="menu-icon">
              <i class="fas fa-door-open"></i>
            </div>
            <div class="menu-content">
              <span class="menu-title">Logout</span>
              <span class="menu-desc">Sign out from your account</span>
            </div>
          </div>
        </div>


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
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border);
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

      /* Profile Modal Styles */
      .profile-modal {
        max-width: 400px;
        width: 90%;
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border);
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
    <div class="cards">
      <div class="card">
        <h4>Total Employees</h4>
        <p><?php echo $totalEmployees; ?></p>
      </div>
      <div class="card">
        <h4>New Hires This Month</h4>
        <p><?php echo $newHires; ?></p>
      </div>
      <div class="card">
        <h4>On Leave Today</h4>
        <p><?php echo $onLeave; ?></p>
      </div>
    </div>

    <div class="graph-section">
      <div class="graph-box" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        <h4><i class="fas fa-chart-line"></i> Employee Growth Over Time</h4>
        <canvas id="employeeChart"></canvas>
      </div>
      <div class="graph-box" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        <h4><i class="fas fa-building"></i> Employees per department</h4>
        <canvas id="departmentChart"></canvas>
      </div>
      <div class="graph-box" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        <h4><i class="fas fa-calendar-alt"></i> Leave Months Reports</h4>
        <canvas id="leaveMonthChart"></canvas>
      </div>
      <div class="employee-list">
        <h4>Recently Added Employees</h4>
        <ul>
          <?php while ($emp = mysqli_fetch_assoc($recentEmployees)): ?>
            <li style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
              <img src="<?php echo $emp['avatar'] ? $emp['avatar'] : '../assets/images/employee-default.jpg'; ?>"
                alt="Avatar"
                style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
              <?php echo htmlspecialchars($emp['firstname'] . " " . $emp['lastname']); ?>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>



  <script>
    // Logout Modal Logic
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

  <script>
    const growthData = <?php echo json_encode($growthData); ?>;
    const leaveMonths = <?php echo json_encode($leaveMonths); ?>;
    const leaveMonthCounts = <?php echo json_encode($leaveMonthCounts); ?>;
    const deptLabels = <?php echo json_encode($deptLabels); ?>;
    const deptData = <?php echo json_encode($deptData); ?>;
  </script>

  <script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
    });

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


    new Chart(document.getElementById('employeeChart'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'New Hires',
          data: growthData,
          borderColor: '#3498db',
          tension: 0.4
        }]
      }
    });

    // Department Distribution
    new Chart(document.getElementById('departmentChart'), {
      type: 'pie',
      data: {
        labels: deptLabels,
        datasets: [{
          data: deptData,
          backgroundColor: ['#7152F3', '#2ecc71', '#f39c12', '#e74c3c', '#1abc9c']
        }]
      }
    });
    // Leave Ratio
    new Chart(document.getElementById('leaveMonthChart'), {
      type: 'doughnut',
      data: {
        labels: leaveMonths,
        datasets: [{
          data: leaveMonthCounts,
          backgroundColor: ['#3498db', '#e91e63']
        }]
      },
      options: {
        plugins: {
          datalabels: {
            color: '#fff', // text color
            font: {
              weight: 'bold',
              size: 14
            },
            formatter: (value, context) => {
              let dataArr = context.chart.data.datasets[0].data;
              let total = dataArr.reduce((acc, val) => acc + val, 0);
              let percentage = ((value / total) * 100).toFixed(1) + "%";
              return percentage;
            }
          },
          legend: {
            position: 'bottom'
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>

</body>

</html>