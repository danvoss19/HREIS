<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../db.php';

$department = isset($_GET['department']) ? $_GET['department'] : '';

$sql = "SELECT id, firstname, lastname, avatar, designation, email 
        FROM employees 
        WHERE department = ? 
        ORDER BY lastname, firstname";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();
$members = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php

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
    <title><?= htmlspecialchars($department) ?> Department</title>
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

        .sidebar h4 {
            font-size: 13px;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar img {
            width: 50px;
            display: block;
            margin: 0 auto 10px;
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
            padding: 30px;
            margin-left: 240px;
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

        .header h2 {
            margin: 0;
        }

        .back-button {
            background: #7152F3;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
        }

        .back-button:hover {
            background: #007acc;
        }

        /* Members List */
        .members-list {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .member {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .member:last-child {
            border-bottom: none;
        }

        .member img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .member-position {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }

        .member-email {
            color: #007acc;
            font-size: 14px;
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

            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header h2 {
                text-align: center;
            }

            .back-button {
                align-self: center;
            }
        }

        @media (max-width: 480px) {
            .member {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .member-info {
                text-align: center;
            }
        }

        :root {
            --primary: #7152F3;
            --primary-dark: #5b3ed1;

        }

        .btn-profile.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(113, 82, 243, 0.5);
            border: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <button class="sidebar-toggle" id="sidebarToggle">☰</button>
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
            <li style="margin-bottom: 10px;">
                <a href="dashboard.php"
                    style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="employee.php"
                    style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
                    <i class="fas fa-users"></i> Employee
                </a>
            </li>
            <li class="active"
                style="background: #5b3fd6; border-radius: 8px; margin-bottom: 10px; padding: 12px 15px; 
               display: flex; align-items: center; gap: 10px; font-size: 14px;">
                <i class="fas fa-building"></i> All Departments
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

    <div class="main">
        <div class="header">
            <div class="profile-info">

                <img src="../assets/images/admin.jpg" class="avatar" id="logoutAvatar" alt="Admin Avatar" />
                <div class="profile-text">
                    <h2><?php echo htmlspecialchars($admin['name']); ?></h2>
                    <h2><?= htmlspecialchars($department) ?> Department - <?= count($members) ?> Members</h2>

                </div>
            </div>

            <div class="header-actions">
                <button class="btn-profile">
                    <i class="fas fa-user"></i> Profile
                </button>
                <button class="btn-profile active" style="margin-left:10px; background:#5b3fd6; color:#fff;" onclick="window.location.href='department.php'">
                    <i class="fas fa-building"></i> All Departments
                </button>
            </div>
        </div>


        <div class="members-list">
            <?php if (count($members) > 0): ?>
                <?php foreach ($members as $m): ?>
                    <div class="member">
                        <?php
                        $avatar = trim($m['avatar']);
                        if ($avatar === '' || $avatar === null || $avatar === 'uploads/avatars/default.jpg') {
                            $avatar = '../assets/images/employee-default.jpg';
                        }
                        ?>
                        <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($m['firstname'] . ' ' . $m['lastname']) ?>">
                        <div class="member-info">
                            <h3 class="member-name"><?= htmlspecialchars($m['firstname'] . ' ' . $m['lastname']) ?></h3>
                            <p class="member-position"><?= htmlspecialchars($m['designation'] ?? 'No position specified') ?></p>
                        </div>
                        <div class="member-email"><?= htmlspecialchars($m['email'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No members found in this department.</p>
            <?php endif; ?>
        </div>
    </div>

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
    </script>
</body>

</html>