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
  <title>Employee Data - HR System</title>
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

    .avatar {
      width: 40px;
      border-radius: 50%;
    }

    /* Custom Table Styling */
    .employee-data {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      padding: 1.5rem;
      margin-top: 1.5rem;
      overflow: hidden;
    }

    .employee-data h3 {
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 1.5rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #eaeef2;
      text-align: center;
      font-size: 1.5rem;
    }

    /* Table Top Controls */
    .table-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    #searchInput {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #dee2e6;
      width: 300px;
      max-width: 100%;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
    }

    #searchInput:focus {
      border-color: #4a6ee0;
      box-shadow: 0 0 0 0.2rem rgba(74, 110, 224, 0.15);
      background-color: white;
    }

    .table-top>div {
      display: flex;
      gap: 0.75rem;
    }

    /* Button Styling */
    .add-btn,
    .filter-btn {
      border-radius: 8px;
      padding: 0.75rem 1.25rem;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      border: none;
      cursor: pointer;
    }

    .add-btn {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
      color: white;
    }

    .add-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(74, 110, 224, 0.3);
    }

    .filter-btn {
      background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
      color: white;
    }

    .filter-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }

    /* Table Styling */
    #employeeTable {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
    }

    #employeeTable thead {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
    }

    #employeeTable thead th {
      color: white;
      font-weight: 600;
      padding: 1rem;
      border: none;
      text-align: left;
    }

    #employeeTable tbody tr {
      transition: all 0.2s ease;
    }

    #employeeTable tbody tr:hover {
      background-color: #f8f9ff;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    #employeeTable tbody td {
      padding: 1rem;
      border-bottom: 1px solid #f1f3f4;
      vertical-align: middle;
    }

    /* Status Badges */
    .status-badge {
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-weight: 500;
      font-size: 0.85rem;
    }

    .status-active {
      background-color: #e7f7ef;
      color: #28a745;
    }

    .status-inactive {
      background-color: #fde8e8;
      color: #dc3545;
    }

    /* Action Buttons */
    .btn-edit,
    .btn-view,
    .btn-delete {
      border-radius: 6px;
      padding: 0.5rem 0.75rem;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-right: 0.5rem;
    }

    .btn-edit {
      background-color: #ffc107;
      color: white;
    }

    .btn-edit:hover {
      background-color: #e0a800;
      transform: scale(1.05);
    }



    .btn-delete {
      background-color: #dc3545;
      color: white;
    }

    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }

    /* Pagination */
    .pagination-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid #eaeef2;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .pagination-info {
      color: #6c757d;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .pagination-controls {
      display: flex;
      gap: 0.5rem;
    }

    .pagination-link {
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      text-decoration: none;
      color: #4a6ee0;
      font-weight: 500;
      border: 1px solid #dee2e6;
      transition: all 0.2s ease;
      min-width: 40px;
      text-align: center;
    }

    .pagination-link:hover {
      background-color: #f8f9ff;
      border-color: #4a6ee0;
    }

    .pagination-link.active {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
      color: white;
      border-color: #4a6ee0;
    }

    .pagination-link.disabled {
      color: #adb5bd;
      border-color: #dee2e6;
      cursor: not-allowed;
      background-color: #f8f9fa;
    }

    .pagination-link.disabled:hover {
      background-color: #f8f9fa;
      border-color: #dee2e6;
    }

    /* Filter Popup */
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1050;
    }

    .popup-box {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      width: 90%;
      max-width: 500px;
      max-height: 80vh;
      overflow-y: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .popup-box h3 {
      margin-top: 0;
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 1.5rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #eaeef2;
    }

    .popup-section {
      margin-bottom: 1.5rem;
    }

    .popup-section h4 {
      font-size: 1rem;
      color: #495057;
      margin-bottom: 0.75rem;
      font-weight: 600;
    }

    .popup-section label {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 6px;
      transition: background-color 0.2s ease;
    }

    .popup-section label:hover {
      background-color: #f8f9fa;
    }

    .popup-section input[type="checkbox"],
    .popup-section input[type="radio"] {
      margin-right: 0.75rem;
      accent-color: #4a6ee0;
      width: 18px;
      height: 18px;
    }

    filterPopup .popup-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }

    filterPopup .btn-cancel,
    .btn-apply {
      padding: 0.6rem 1.25rem;
      border-radius: 6px;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    /* Scoped Filter Popup Buttons */
    #filterPopup .btn-cancel {
      background-color: #dc3545;
      /* a unique red for this cancel */
      color: white;
    }

    #filterPopup .btn-cancel:hover {
      background-color: #c82333;
    }

    #filterPopup .btn-apply {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
      color: white;
    }

    #filterPopup .btn-apply:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(74, 110, 224, 0.2);
    }


    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .table-top {
        flex-direction: column;
        align-items: stretch;
      }

      #searchInput {
        width: 100%;
      }

      .table-top>div {
        justify-content: center;
      }

      .pagination-container {
        flex-direction: column;
        text-align: center;
      }

      .pagination-controls {
        flex-wrap: wrap;
        justify-content: center;
      }

      #employeeTable {
        font-size: 0.9rem;
      }

      #employeeTable thead th,
      #employeeTable tbody td {
        padding: 0.75rem 0.5rem;
      }
    }

    @media (max-width: 576px) {
      .employee-data {
        padding: 1rem;
      }

      .add-btn,
      .filter-btn {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
      }

      .btn-edit,
      .btn-view,
      .btn-delete {
        padding: 0.4rem 0.6rem;
        margin-right: 0.25rem;
      }
    }

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

    #logoutModal .modal-content {
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
        gap: 15px;
        text-align: center;
      }

      .table-top {
        flex-direction: column;
        align-items: stretch;
      }

      .table-top input {
        width: 100%;
        margin-bottom: 10px;
      }

      .table-top>div {
        display: flex;
        gap: 10px;
        justify-content: space-between;
      }

      .table-top button {
        flex: 1;
      }
    }

    @media (max-width: 480px) {
      .employee-data {
        padding: 15px;
      }

      .popup-box {
        padding: 15px;
      }

      .popup-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
      }

      .popup-section h4 {
        grid-column: 1 / -1;
      }

      .btn-edit,
      .btn-view {
        padding: 4px 8px;
        font-size: 12px;
      }
    }

    .employee-data {
      margin-top: 20px;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-top {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      flex-wrap: wrap;
      gap: 10px;
    }

    .add-btn,
    .filter-btn {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .add-btn {
      background-color: #007bff;
      color: white;
    }

    .filter-btn {
      background-color: #7b57ff;
      color: white;
    }

    .step-indicators {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .step {
      text-align: center;
      flex: 1;
      position: relative;
    }

    .step-number {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
      font-weight: bold;
    }

    .step.active .step-number {
      background-color: #007bff;
      color: white;
    }

    .step.completed .step-number {
      background-color: #28a745;
      color: white;
    }

    .step-line {
      position: absolute;
      top: 15px;
      left: -50%;
      width: 100%;
      height: 2px;
      background-color: #e9ecef;
      z-index: -1;
    }

    .modal-step {
      display: none;
    }

    .modal-step.active {
      display: block;
    }

    .file-upload-container {
      border: 2px dashed #dee2e6;
      border-radius: 5px;
      padding: 20px;
      text-align: center;
      margin-bottom: 15px;
      cursor: pointer;
    }

    .file-upload-container:hover {
      border-color: #007bff;
    }

    .file-list {
      margin-top: 10px;
    }

    .file-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px;
      border-bottom: 1px solid #eee;
    }

    .pagination-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      padding: 15px 0;
      border-top: 1px solid #ddd;
    }

    .pagination-info {
      color: #666;
      font-size: 14px;
    }

    .pagination-controls {
      display: flex;
      gap: 5px;
    }

    .pagination-link {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-decoration: none;
      color: #007acc;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .pagination-link:hover {
      background-color: #f5f5f5;
      border-color: #007acc;
    }

    .pagination-link.active {
      background-color: #007acc;
      color: white;
      border-color: #007acc;
    }

    .pagination-link.disabled {
      color: #999;
      border-color: #ddd;
      cursor: not-allowed;
      background-color: #f9f9f9;
    }

    .pagination-link.disabled:hover {
      background-color: #f9f9f9;
      border-color: #ddd;
    }

    @media (max-width: 768px) {
      .pagination-container {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .pagination-controls {
        flex-wrap: wrap;
        justify-content: center;
      }

      .pagination-link {
        padding: 6px 10px;
        font-size: 12px;
      }
    }

    /* Custom Modal Styling */
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .modal-header {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
      color: white;
      padding: 1.2rem 1.5rem;
      border-bottom: none;
    }

    .modal-header .btn-close {
      filter: invert(1);
      opacity: 0.8;
    }

    .modal-header .btn-close:hover {
      opacity: 1;
    }

    .modal-title {
      font-weight: 600;
      font-size: 1.4rem;
    }

    /* Enhanced Tabs */
    .nav-tabs {
      border-bottom: 1px solid #e9ecef;
      padding: 0 1.5rem;
      margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
      border: none;
      color: #6c757d;
      font-weight: 500;
      padding: 0.8rem 1.2rem;
      border-radius: 8px 8px 0 0;
      transition: all 0.3s ease;
      position: relative;
      margin-bottom: -1px;
    }

    .nav-tabs .nav-link:hover {
      color: #4a6ee0;
      background-color: rgba(74, 110, 224, 0.05);
    }

    .nav-tabs .nav-link.active {
      color: #4a6ee0;
      background-color: white;
      border-bottom: 3px solid #4a6ee0;
      font-weight: 600;
    }

    .nav-tabs .nav-link i {
      margin-right: 8px;
      font-size: 1.1rem;
    }

    /* Profile Header */
    #profileHeader {
      background: linear-gradient(to right, #f8f9ff, #f0f4ff);
      border-radius: 10px;
      padding: 1.5rem;
      margin: 0 1.5rem 1.5rem;
      border-left: 4px solid #4a6ee0;
    }

    #profileHeader img {
      border: 3px solid white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Form Styling */
    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #dee2e6;
      transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #4a6ee0;
      box-shadow: 0 0 0 0.2rem rgba(74, 110, 224, 0.25);
    }

    /* File Upload Enhancement */
    .file-upload-container {
      border: 2px dashed #cbd3e3;
      border-radius: 10px;
      padding: 2rem;
      text-align: center;
      margin-bottom: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #f8f9ff;
    }

    .file-upload-container:hover {
      border-color: #4a6ee0;
      background-color: #f0f4ff;
    }

    .file-upload-container i {
      color: #4a6ee0;
      margin-bottom: 0.5rem;
    }

    .file-upload-container p {
      margin-bottom: 0.3rem;
    }

    .file-list {
      margin-top: 10px;
    }

    .file-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 12px;
      background-color: #f8f9fa;
      border-radius: 6px;
      margin-bottom: 5px;
    }

    /* Avatar Upload */
    #avatarLabel {
      border-radius: 12px;
      border: 2px dashed #cbd3e3;
      transition: all 0.3s ease;
      background-color: #f8f9ff;
    }

    #avatarLabel:hover {
      border-color: #4a6ee0;
      background-color: #f0f4ff;
    }

    /* Tab Content */
    .tab-content {
      padding: 0 1.5rem;
    }

    .tab-pane {
      padding-bottom: 1rem;
    }

    /* Modal Footer */
    .modal-footer {
      border-top: 1px solid #e9ecef;
      padding: 1.2rem 1.5rem;
      background-color: #f8f9fa;
    }

    .btn {
      border-radius: 8px;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4a6ee0 0%, #6a11cb 100%);
      border: none;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(74, 110, 224, 0.3);
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary {
      background-color: #6c757d;
      border: none;
    }

    /* Leave Table */
    .table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
      background-color: #4a6ee0;
      color: white;
      border: none;
      padding: 1rem;
    }

    .table tbody td {
      padding: 0.8rem 1rem;
      vertical-align: middle;
    }

    /* Status Badges */
    .badge {
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .modal-dialog {
        margin: 1rem;
      }

      .nav-tabs .nav-link {
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
      }

      #profileHeader {
        flex-direction: column;
        text-align: center;
      }

      #profileHeader>div:first-child {
        margin-bottom: 1rem;
      }
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
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
      <li style="margin-bottom: 10px;">
        <a href="dashboard.php"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; 
                border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; 
                transition: background 0.3s;">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
      </li>
      <li class="active"
        style="background: #5b3fd6; border-radius: 8px; margin-bottom: 10px; padding: 12px 15px; 
               display: flex; align-items: center; gap: 10px; font-size: 14px;">
        <i class="fas fa-users"></i> Employee
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
          <i class="fas fa-users"></i> Employee
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
      #changePasswordModal .modal-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--border);
      }

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

      #profileModal .modal-header {
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

      .swal2-container {
        z-index: 99999 !important;
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
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max($page, 1);
    $offset = ($page - 1) * $limit;

    $count_sql = "SELECT COUNT(*) as total FROM employees";
    $count_result = $conn->query($count_sql);
    $total_rows = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);
    $sql = "SELECT id, employee_id, firstname, lastname, designation, department, status 
        FROM employees 
        ORDER BY employee_id ASC
        LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    ?>
    <div class="employee-data">
      <h3>EMPLOYEE DATA</h3>
      <div class="table-top">
        <input type="text" id="searchInput" placeholder="Search" onkeyup="filterTable()">
        <div>
          <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-user-plus"></i> Add Employee
          </button>
          <button class="filter-btn" id="openFilter"> <i class="fas fa-filter"></i> Filter</button>
          <div class="popup-overlay" id="filterPopup">
            <div class="popup-box">
              <h3>Filter</h3>
              <div class="popup-section">
                <h4>Department</h4>
                <label><input type="checkbox" class="dept-filter" value="HR Department">Human Resource</label>
                <label><input type="checkbox" class="dept-filter" value="Accounting Office">Accounting Office</label>
                <label><input type="checkbox" class="dept-filter" value="Planning and Development Office">Planning and Development Office</label>
                <label><input type="checkbox" class="dept-filter" value="Assessor's Office">Assessor's Office</label>
                <label><input type="checkbox" class="dept-filter" value="Agriculture Office" checked>Agriculture Office</label>
                <label><input type="checkbox" class="dept-filter" value="Engineering Office">Engineering Office</label>
              </div>
              <div class="popup-section">
                <h4>Status</h4>
                <label><input type="radio" name="status" class="status-filter" value="Active"> Active</label>
                <label><input type="radio" name="status" class="status-filter" value="Inactive"> Inactive</label>
              </div>
              <div class="popup-actions">
                <button class="btn-cancel" id="cancelFilter">Cancel</button>
                <button class="btn-apply">Apply</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <table id="employeeTable">
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['employee_id'] ?></td>
                <td><?= htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></td>
                <td><?= htmlspecialchars($row['designation']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td>
                  <span class="status-badge status-active"><?= ucfirst($row['status']) ?></span>
                </td>

                <td>
                  <button class="btn-edit" data-id="<?php echo $row['id']; ?>">
                    <i class="fas fa-pencil-alt" style="color: gray;"></i>
                  </button>
                  <button class="btn-view btn btn-info"
                    style="background-color: #7152F3; border: none;"
                    data-id="<?php echo $row['id']; ?>"
                    onmouseover="this.style.backgroundColor='#5b3fd6'"
                    onmouseout="this.style.backgroundColor='#7152F3'">
                    <i class="fas fa-eye" style="color: white;"></i>
                  </button>

                  <button class="btn-delete btn btn-danger" data-id="<?php echo $row['id']; ?>">
                    <i class="fas fa-trash-alt" style="color: white;"></i>
                  </button>
                </td>

              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6">No employees found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination-container">
        <div class="pagination-info">
          Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $total_rows) ?> of <?= $total_rows ?> entries
        </div>
        <div class="pagination-controls">
          <?php if ($page > 1): ?>
            <a href="?page=1" class="pagination-link">First</a>
            <a href="?page=<?= $page - 1 ?>" class="pagination-link">Previous</a>
          <?php else: ?>
            <span class="pagination-link disabled">First</span>
            <span class="pagination-link disabled">Previous</span>
          <?php endif; ?>

          <?php
          $start_page = max(1, $page - 2);
          $end_page = min($total_pages, $page + 2);

          for ($i = $start_page; $i <= $end_page; $i++):
          ?>
            <a href="?page=<?= $i ?>" class="pagination-link <?= $i == $page ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="pagination-link">Next</a>
            <a href="?page=<?= $total_pages ?>" class="pagination-link">Last</a>
          <?php else: ?>
            <span class="pagination-link disabled">Next</span>
            <span class="pagination-link disabled">Last</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php $conn->close(); ?>
  </div>
  <?php if (isset($_GET['msg'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      <?php if ($_GET['msg'] === 'added'): ?>
        Swal.fire({
          icon: 'success',
          title: 'Employee Added',
          text: 'The new employee has been successfully added!',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          window.history.replaceState({}, document.title, window.location.pathname);
        });
      <?php elseif ($_GET['msg'] === 'duplicate'): ?>
        Swal.fire({
          icon: 'warning',
          title: 'Duplicate Email',
          text: 'The email address you entered is already in use. Please use another one.',
          confirmButtonColor: '#d33'
        }).then(() => {
          window.history.replaceState({}, document.title, window.location.pathname);
        });
      <?php elseif ($_GET['msg'] === 'error'): ?>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '<?php echo isset($_GET["details"]) ? urldecode($_GET["details"]) : "Something went wrong while processing your request."; ?>',
          confirmButtonColor: '#d33'
        }).then(() => {
          window.history.replaceState({}, document.title, window.location.pathname);
        });
      <?php endif; ?>
    </script>
  <?php endif; ?>
  <div id="biodataPrint" style="display:none; padding: 30px; font-family: Arial, sans-serif; color: #333;">
    <div style="text-align: center; margin-bottom: 20px;">
      <img id="printAvatar" src="../assets/images/employee-default.jpg"
        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #7152F3;">
      <h2 id="printName" style="margin: 15px 0 5px; font-weight: bold;"></h2>
      <p id="printDesignation" style="margin: 0; font-size: 14px; color: #666;"></p>
      <p id="printEmail" style="margin: 0; font-size: 14px; color: #666;"></p>
    </div>

    <h4 style="border-bottom: 2px solid #7152F3; padding-bottom: 5px;">Personal Information</h4>
    <table style="width:100%; border-collapse: collapse; margin-bottom: 20px;">
      <tr>
        <td><strong>First Name:</strong></td>
        <td id="printFirstname"></td>
      </tr>
      <tr>
        <td><strong>Last Name:</strong></td>
        <td id="printLastname"></td>
      </tr>
      <tr>
        <td><strong>Mobile:</strong></td>
        <td id="printMobile"></td>
      </tr>
      <tr>
        <td><strong>Date of Birth:</strong></td>
        <td id="printDob"></td>
      </tr>
      <tr>
        <td><strong>Marital Status:</strong></td>
        <td id="printMarital"></td>
      </tr>
      <tr>
        <td><strong>Gender:</strong></td>
        <td id="printGender"></td>
      </tr>
      <tr>
        <td><strong>Nationality:</strong></td>
        <td id="printNationality"></td>
      </tr>
      <tr>
        <td><strong>Address:</strong></td>
        <td id="printAddress"></td>
      </tr>
    </table>

    <h4 style="border-bottom: 2px solid #7152F3; padding-bottom: 5px;">Professional Information</h4>
    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td><strong>Employee ID:</strong></td>
        <td id="printEmpId"></td>
      </tr>
      <tr>
        <td><strong>Designation:</strong></td>
        <td id="printEmpDesignation"></td>
      </tr>
      <tr>
        <td><strong>Employee Type:</strong></td>
        <td id="printEmpType"></td>
      </tr>
      <tr>
        <td><strong>Salary Grade:</strong></td>
        <td id="printSalary"></td>
      </tr>
      <tr>
        <td><strong>Department:</strong></td>
        <td id="printDepartment"></td>
      </tr>
      <tr>
        <td><strong>Civil Service Eligibility:</strong></td>
        <td id="printCs"></td>
      </tr>
      <tr>
        <td><strong>Working Days:</strong></td>
        <td id="printWorkingDays"></td>
      </tr>
      <tr>
        <td><strong>Joining Date:</strong></td>
        <td id="printJoining"></td>
      </tr>
    </table>
  </div>

  <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addEmployeeModalLabel"><i class="fas fa-user-plus me-2"></i>Add New Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="employeeForm" method="POST" enctype="multipart/form-data" action="add_employee.php" novalidate>
          <div class="modal-body p-0">
            <!-- Enhanced Tabs -->
            <ul class="nav nav-tabs" id="employeeTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                  <i class="fas fa-user"></i> Personal Information
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button" role="tab">
                  <i class="fas fa-briefcase"></i> Professional Information
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                  <i class="fas fa-file-alt"></i> Documents
                </button>
              </li>
              <li class="nav-item" role="presentation" id="leave-tab-container" style="display: none;">
                <button class="nav-link" id="leave-tab" data-bs-toggle="tab" data-bs-target="#leave" type="button" role="tab">
                  <i class="fas fa-calendar-alt"></i> Leave
                </button>
              </li>
            </ul>

            <!-- Profile Header -->
            <div id="profileHeader" class="d-flex align-items-center">
              <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; flex-shrink: 0;">
                <img id="profileAvatar" src="../assets/images/employee-default.jpg" alt="Profile Avatar" class="w-100 h-100" style="object-fit: cover;">
              </div>
              <div class="ms-3">
                <h5 class="mb-1 fw-bold" id="profileName">Employee Name</h5>
                <p class="mb-0 text-muted" id="profileDesignation"><i class="fas fa-briefcase me-2"></i>Designation</p>
                <p class="mb-0 text-muted" id="profileEmail"><i class="fas fa-envelope me-2"></i>Email</p>
              </div>
            </div>

            <div class="tab-content mt-3">
              <!-- Personal Info -->
              <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <div class="row mb-3">
                  <div class="col-md-12 text-center">
                    <label for="avatar" class="form-label d-block fw-semibold">Profile Image</label>
                    <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                    <label for="avatar" id="avatarLabel" class="d-inline-flex align-items-center justify-content-center" style="cursor: pointer; width: 120px; height: 120px; overflow: hidden;">
                      <i id="cameraIcon" class="fas fa-camera fa-2x text-secondary"></i>
                      <img id="preview" src="" alt="Preview" class="d-none w-100 h-100" style="object-fit: cover;">
                    </label>
                  </div>
                </div>

                <!-- Hidden fields for old files -->
                <input type="hidden" name="old_avatar" id="old_avatar">
                <input type="hidden" name="old_gov_id" id="old_gov_id">
                <input type="hidden" name="old_cv" id="old_cv">
                <input type="hidden" name="old_service_record" id="old_service_record">
                <input type="hidden" name="old_appointment_paper" id="old_appointment_paper">
                <input type="hidden" name="old_tor" id="old_tor">
                <input type="hidden" name="old_cs_certificate" id="old_cs_certificate">
                <input type="hidden" name="old_pds" id="old_pds">

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" placeholder="First Name" name="firstname">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastname">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" placeholder="Mobile Number" name="mobile_number">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Email Address" name="email">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="date_of_birth">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Marital Status</label>
                    <select class="form-select" name="marital_status">
                      <option value="">Select Marital Status</option>
                      <option>Single</option>
                      <option>Married</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                      <option value="">Select Gender</option>
                      <option>Male</option>
                      <option>Female</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nationality</label>
                    <input type="text" class="form-control" placeholder="Nationality" name="nationality">
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" placeholder="Address" name="address">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" placeholder="City" name="city">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" placeholder="State" name="state">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">ZIP Code</label>
                    <input type="text" class="form-control" placeholder="ZIP Code" name="zip_code">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="********" id="password" name="password">
                  </div>
                </div>
              </div>

              <!-- Professional Info -->
              <div class="tab-pane fade" id="professional" role="tabpanel">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Employee ID</label>
                    <input type="text" class="form-control" placeholder="Employee ID" name="employeeID">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Designation</label>
                    <input type="text" class="form-control" placeholder="Designation" name="designation">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="employeeType" class="form-label">Employee Type</label>
                    <select class="form-select" id="employeeType" name="employee_type">
                      <option value="">Select Employee Type</option>
                      <option value="Permanent">Permanent</option>
                      <option value="Contractual">Contractual</option>
                      <option value="Part-time">Part-time</option>
                      <option value="Job Order">Job Order</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="salaryGrade" class="form-label">Salary Grade</label>
                    <input type="text" class="form-control" id="salaryGrade" name="salary_grade" placeholder="e.g., SG-12">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-select" id="department" name="department">
                      <option value="">Select Department</option>
                      <option value="HR Department">HR Department</option>
                      <option value="Accounting Office">Accounting Office</option>
                      <option value="Planning and Development Office">Planning and Development Office</option>
                      <option value="Assessor's Office">Assessor's Office</option>
                      <option value="Agriculture Office">Agriculture Office</option>
                      <option value="Engineering Office">Engineering Office</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="csEligibility" class="form-label">Civil Service Eligibility</label>
                    <input type="text" class="form-control" id="csEligibility" name="cs_eligibility" placeholder="e.g., Civil Service Eligible">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="workingDays" class="form-label">Working Days</label>
                    <input type="text" class="form-control" id="workingDays" name="working_days" placeholder="e.g., Mon-Fri">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="joiningDate" class="form-label">Joining Date</label>
                    <input type="datetime-local" class="form-control" id="joiningDate" name="joining_date">
                  </div>
                </div>
              </div>

              <!-- Documents -->
              <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Government Issued ID & Clearances</label>
                    <div class="file-upload-container" onclick="document.getElementById('govIdFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="govIdFile" name="gov_id" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="govIdFileList"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Resume / CV</label>
                    <div class="file-upload-container" onclick="document.getElementById('cvFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="cvFile" name="cv" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="cvFileList"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Service Record</label>
                    <div class="file-upload-container" onclick="document.getElementById('serviceFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="serviceFile" name="service_record" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="serviceFileList"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Appointment Paper</label>
                    <div class="file-upload-container" onclick="document.getElementById('appointmentFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="appointmentFile" name="appointment_paper" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="appointmentFileList"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Transcript of Records & Diploma</label>
                    <div class="file-upload-container" onclick="document.getElementById('torFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="torFile" name="tor" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="torFileList"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Civil Service Eligibility Certificate</label>
                    <div class="file-upload-container" onclick="document.getElementById('csFile').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                      <p>Drag & Drop or <span class="text-primary">choose file</span> to upload</p>
                      <p class="text-muted">Supported formats: Jpeg, pdf</p>
                      <input type="file" id="csFile" name="cs_certificate" class="d-none" accept=".jpg,.jpeg,.pdf">
                    </div>
                    <div class="file-list" id="csFileList"></div>
                  </div>
                </div>
              </div>

              <!-- Leave Tab -->
              <div class="tab-pane fade" id="leave" role="tabpanel">
                <div class="leave-container">
                  <h5 class="mb-4">Leave History</h5>
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Leave Type</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Duration</th>
                          <th>Status</th>
                          <th>Reason</th>
                        </tr>
                      </thead>
                      <tbody id="leaveHistoryTable">
                        <!-- Leave data will be populated here -->
                      </tbody>
                    </table>
                  </div>
                  <div id="noLeaveData" class="text-center py-4" style="display: none;">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No leave records found for this employee.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between align-items-center">
            <div></div>
            <div class="text-center flex-grow-1" style="margin-left: 120px;">
              <button type="button" class="btn btn-primary" id="printBioBtn">
                <i class="fas fa-print"></i> Print BioData
              </button>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
              <button type="submit" class="btn btn-success d-none" id="submitBtn">Submit</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Employee Updated',
        text: 'The employee record has been successfully updated!',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
      }).then(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    </script>
  <?php endif; ?>
  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <script>
      Swal.fire({
        icon: "success",
        title: "Deleted!",
        text: "Employee has been deleted successfully.",
        confirmButtonColor: "#3085d6"
      }).then(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    </script>
  <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'delete_error'): ?>
    <script>
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Failed to delete employee.",
        confirmButtonColor: "#d33"
      });
    </script>
  <?php endif; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- for clearing modal -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('avatar').addEventListener('change', function(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('preview');
      const cameraIcon = document.getElementById('cameraIcon');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('d-none');
          cameraIcon.classList.add('d-none');
        }
        reader.readAsDataURL(file);
      }
    });
    /* file documents */
    function handleFileUpload(inputId, listId) {
      const input = document.getElementById(inputId);
      const list = document.getElementById(listId);

      input.addEventListener('change', function() {
        list.innerHTML = "";
        const file = this.files[0];
        if (!file) return;

        const fileType = file.type;
        const fileName = file.name;
        if (fileType.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function(e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            img.classList.add("me-2", "mb-2", "border", "rounded");
            list.appendChild(img);
          };
          reader.readAsDataURL(file);
        } else {
          const fileDiv = document.createElement("div");
          fileDiv.innerHTML = `<i class="fas fa-file-pdf text-danger"></i> ${fileName}`;
          list.appendChild(fileDiv);
        }
      });
    }

    handleFileUpload("govIdFile", "govIdFileList");
    handleFileUpload("cvFile", "cvFileList");
    handleFileUpload("serviceFile", "serviceFileList");
    handleFileUpload("appointmentFile", "appointmentFileList");
    handleFileUpload("torFile", "torFileList");
    handleFileUpload("csFile", "csFileList");
  </script>
  <script>
    document.getElementById("addEmployeeModal").addEventListener("hidden.bs.modal", function() {
      document.querySelector("#employeeForm").reset();
      document.querySelectorAll("#employeeForm input[type='hidden']").forEach(el => el.value = "");
      document.getElementById("profileHeader").style.display = "none";
      document.getElementById("govIdFile").style.display = "none";


      let preview = document.getElementById("preview");
      let cameraIcon = document.getElementById("cameraIcon");
      preview.classList.add("d-none");
      cameraIcon.classList.remove("d-none");
      preview.src = "";

      document.querySelector("#employeeForm").action = "add_employee.php";
      document.querySelector("#submitBtn").textContent = "Add";
    });

    document.getElementById("employeeForm").addEventListener("submit", function(e) {
      let form = this;

      if (form.action.includes("add_employee.php")) {
        e.preventDefault();

        let requiredFields = form.querySelectorAll("input, select, textarea");
        let emptyFields = [];

        requiredFields.forEach(field => {
          if (field.type === "hidden") return;
          if (field.value.trim() === "") {
            emptyFields.push(field.name || field.placeholder || field.id);
          }
        });

        if (emptyFields.length > 0) {
          Swal.fire({
            icon: "error",
            title: "Missing Fields",
            text: "Please complete all required fields before submitting.",
            confirmButtonColor: "#d33"
          });
        } else {
          form.submit();
        }
      }
    });
    document.querySelectorAll(".btn-delete").forEach(button => {
      button.addEventListener("click", function() {
        let employeeId = this.getAttribute("data-id");

        Swal.fire({
          title: "Are you sure?",
          text: "This will permanently delete the employee record.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "delete_employee.php?id=" + employeeId;
          }
        });
      });
    });
  </script>

  <script>
    /* For tab employee */
    document.addEventListener("DOMContentLoaded", function() {
      const editButtons = document.querySelectorAll(".btn-edit");
      const nextBtn = document.getElementById("nextBtn");
      const submitBtn = document.getElementById("submitBtn");
      const cancelBtn = document.querySelector("button[data-bs-dismiss='modal']");
      const tabEl = document.querySelectorAll('button[data-bs-toggle="tab"]');
      const leaveTabContainer = document.getElementById('leave-tab-container');
      const profileHeaderContainer = document.getElementById('profileHeader');

      function isViewMode() {
        return document.querySelector("#addEmployeeModalLabel").textContent === "View Employee";
      }

      function updateButtons(activeTab) {
        if (isViewMode()) {
          nextBtn.classList.remove("d-none");
          submitBtn.classList.add("d-none");
          if (cancelBtn) cancelBtn.classList.remove("d-none");
          return;
        }
        if (activeTab === "personal" || activeTab === "professional") {
          nextBtn.classList.remove("d-none");
          submitBtn.classList.add("d-none");
        } else if (activeTab === "documents") {
          nextBtn.classList.add("d-none");
          submitBtn.classList.remove("d-none");
        } else if (activeTab === "leave") {
          nextBtn.classList.add("d-none");
          submitBtn.classList.add("d-none");
        }
      }

      // Function to load leave data for an employee
      function loadLeaveData(employeeId) {
        if (!employeeId) return;

        fetch(`get_employee_leaves.php?employee_id=${employeeId}`)
          .then(response => response.json())
          .then(data => {
            const leaveTable = document.getElementById('leaveHistoryTable');
            const noLeaveData = document.getElementById('noLeaveData');

            leaveTable.innerHTML = '';

            if (data.length > 0) {
              noLeaveData.style.display = 'none';
              data.forEach(leave => {
                const row = document.createElement('tr');
                row.innerHTML = `
              <td>${leave.leave_type}</td>
              <td>${formatDate(leave.leave_start_date)}</td>
              <td>${formatDate(leave.leave_end_date)}</td>
              <td>${leave.leave_duration} day(s)</td>
              <td><span class="badge ${getStatusClass(leave.leave_status)}">${leave.leave_status}</span></td>
              <td>${leave.leave_reason}</td>
            `;
                leaveTable.appendChild(row);
              });
            } else {
              noLeaveData.style.display = 'block';
            }
          })
          .catch(error => {
            console.error('Error loading leave data:', error);
            document.getElementById('noLeaveData').style.display = 'block';
          });
      }

      function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      }

      function getStatusClass(status) {
        switch (status) {
          case 'Approved':
            return 'bg-success';
          case 'Pending':
            return 'bg-warning';
          case 'Rejected':
            return 'bg-danger';
          default:
            return 'bg-secondary';
        }
      }

      tabEl.forEach(btn => {
        btn.addEventListener("shown.bs.tab", function(event) {
          const targetId = event.target.getAttribute("data-bs-target").substring(1);
          updateButtons(targetId);

          // If leave tab is activated and we're in view mode, load leave data
          if (targetId === 'leave' && isViewMode()) {
            const employeeId = document.querySelector('input[name="employeeID"]')?.value;
            if (employeeId) {
              loadLeaveData(employeeId);
            }
          }
        });
      });

      nextBtn.addEventListener("click", function() {
        const currentTab = document.querySelector("#employeeTab .nav-link.active");
        const nextTab = currentTab.parentElement.nextElementSibling?.querySelector(".nav-link");
        if (nextTab) {
          new bootstrap.Tab(nextTab).show();
        }
      });

      // Show/hide leave tab based on mode
      function toggleLeaveTab() {
        if (isViewMode()) {
          leaveTabContainer.style.display = 'block';
        } else {
          leaveTabContainer.style.display = 'none';
        }
      }

      function toggleProfileHeader() {
        if (isViewMode()) {
          profileHeaderContainer.classList.remove('d-none');
        } else {
          profileHeaderContainer.classList.add('d-none');
        }
      }

      updateButtons("personal");
      toggleLeaveTab();
      toggleProfileHeader();

      const modal = document.getElementById('addEmployeeModal');
      if (modal) {
        modal.addEventListener('show.bs.modal', function() {
          toggleLeaveTab();
          toggleProfileHeader();
        });
      }
    });

    /* for edit employee */
    document.querySelectorAll(".btn-edit").forEach(button => {
      button.addEventListener("click", function() {
        let employeeId = this.getAttribute("data-id");

        fetch("update_employee.php?id=" + employeeId)
          .then(res => res.json())
          .then(data => {
            document.querySelector("#addEmployeeModalLabel").textContent = "Edit Employee";
            document.querySelector("input[name='firstname']").value = data.firstname;
            document.querySelector("input[name='lastname']").value = data.lastname;
            document.querySelector("input[name='mobile_number']").value = data.mobile_number;
            document.querySelector("input[name='email']").value = data.email;
            document.querySelector("input[name='date_of_birth']").value = data.date_of_birth;
            document.querySelector("select[name='gender']").value = data.gender;
            document.querySelector("input[name='nationality']").value = data.nationality;
            document.querySelector("input[name='address']").value = data.address;
            document.querySelector("input[name='city']").value = data.city;
            document.querySelector("input[name='state']").value = data.state;
            document.querySelector("input[name='zip_code']").value = data.zip_code;
            document.querySelector("select[name='marital_status']").value = data.marital_status;
            document.querySelector("input[name='employeeID']").value = data.employee_id;
            document.querySelector("input[name='designation']").value = data.designation;
            document.querySelector("select[name='employee_type']").value = data.employee_type;
            document.querySelector("input[name='salary_grade']").value = data.salary_grade;
            document.querySelector("select[name='department']").value = data.department;
            document.querySelector("input[name='cs_eligibility']").value = data.cs_eligibility;
            document.querySelector("input[name='working_days']").value = data.working_days;
            document.querySelector("input[name='joining_date']").value = data.joining_date;
            document.querySelector("input[name='password']").value = "";
            document.querySelector("input[name='old_avatar']").value = data.avatar || "";
            document.querySelector("input[name='old_gov_id']").value = data.government_id || "";
            document.querySelector("input[name='old_cv']").value = data.cv || "";
            document.querySelector("input[name='old_service_record']").value = data.service_record || "";
            document.querySelector("input[name='old_appointment_paper']").value = data.appointment_paper || "";
            document.querySelector("input[name='old_tor']").value = data.tor || "";
            document.querySelector("input[name='old_cs_certificate']").value = data.cs_certificate || "";
            document.querySelector("input[name='old_pds']").value = data.pds || "";

            document.getElementById("profileHeader").style.display = "flex";
            document.getElementById("profileName").textContent = data.firstname + " " + data.lastname;
            document.getElementById("profileDesignation").innerHTML = `<i class="fas fa-briefcase me-2"></i>${data.designation || "N/A"}`;
            document.getElementById("profileEmail").innerHTML = `<i class="fas fa-envelope me-2"></i>${data.email || "N/A"}`;

            // Avatar for header
            let profileAvatar = document.getElementById("profileAvatar");
            if (data.avatar && data.avatar.trim() !== "") {
              profileAvatar.src = data.avatar;
            } else {
              profileAvatar.src = "../assets/images/employee-default.jpg";
            }

            let preview = document.getElementById("preview");
            let cameraIcon = document.getElementById("cameraIcon");

            if (data.avatar && data.avatar.trim() !== "") {
              preview.src = data.avatar;
              preview.classList.remove("d-none");
              cameraIcon.classList.add("d-none");
            } else {
              preview.src = "../assets/images/employee-default.jpg";
              preview.classList.remove("d-none");
              cameraIcon.classList.add("d-none");
            }
            const files = {
              government_id: "govIdFileList",
              cv: "cvFileList",
              service_record: "serviceFileList",
              appointment_paper: "appointmentFileList",
              tor: "torFileList",
              cs_certificate: "csFileList"
            };

            Object.keys(files).forEach(key => {
              let container = document.getElementById(files[key]);
              container.innerHTML = "";
              if (data[key] && data[key].trim() !== "") {
                let fileUrl = data[key];
                let fileName = fileUrl.split("/").pop();
                container.innerHTML = `<a href="${fileUrl}" target="_blank">${fileName}</a>`;
              } else {
                container.innerHTML = `<span class="text-muted">No file uploaded</span>`;
              }
            });

            document.querySelector("#employeeForm").action = "update_employee.php";
            let hiddenId = document.querySelector("#employeeForm input[name='id']");
            if (!hiddenId) {
              hiddenId = document.createElement("input");
              hiddenId.type = "hidden";
              hiddenId.name = "id";
              document.querySelector("#employeeForm").appendChild(hiddenId);
            }
            hiddenId.value = employeeId;

            document.querySelectorAll("#employeeForm input, #employeeForm select").forEach(el => {
              el.removeAttribute("disabled");
            });

            document.querySelector("#submitBtn").classList.remove("d-none");
            document.querySelector("#submitBtn").textContent = "Update";
            document.querySelector("#nextBtn").classList.add("d-none");

            let modal = new bootstrap.Modal(document.getElementById("addEmployeeModal"));
            modal.show();
          });
      });
    });


    /* for view employee */
    document.querySelectorAll(".btn-view").forEach(button => {
      button.addEventListener("click", function() {
        let employeeId = this.getAttribute("data-id");

        fetch("get_employee.php?id=" + employeeId)
          .then(res => res.json())
          .then(data => {
            document.querySelector("#addEmployeeModalLabel").textContent = "View Employee";
            document.querySelector("input[name='firstname']").value = data.firstname;
            document.querySelector("input[name='lastname']").value = data.lastname;
            document.querySelector("input[name='mobile_number']").value = data.mobile_number;
            document.querySelector("input[name='email']").value = data.email;
            document.querySelector("input[name='date_of_birth']").value = data.date_of_birth;
            document.querySelector("select[name='gender']").value = data.gender;
            document.querySelector("input[name='nationality']").value = data.nationality;
            document.querySelector("input[name='address']").value = data.address;
            document.querySelector("input[name='city']").value = data.city;
            document.querySelector("input[name='state']").value = data.state;
            document.querySelector("input[name='zip_code']").value = data.zip_code;
            document.querySelector("input[name='password']").value = data.password;
            document.querySelector("select[name='marital_status']").value = data.marital_status;
            document.querySelector("input[name='employeeID']").value = data.employee_id;
            document.querySelector("input[name='designation']").value = data.designation;
            document.querySelector("select[name='employee_type']").value = data.employee_type;
            document.querySelector("input[name='salary_grade']").value = data.salary_grade;
            document.querySelector("select[name='department']").value = data.department;
            document.querySelector("input[name='cs_eligibility']").value = data.cs_eligibility;
            document.querySelector("input[name='working_days']").value = data.working_days;
            document.querySelector("input[name='joining_date']").value = data.joining_date;
            // Show profile header
            document.getElementById("profileHeader").style.display = "flex";
            // Inside your fetch then(data => { ... })
            document.getElementById("printAvatar").src = data.avatar && data.avatar.trim() !== "" ?
              data.avatar :
              "../assets/images/employee-default.jpg";

            document.getElementById("printName").textContent = data.firstname + " " + data.lastname;
            document.getElementById("printDesignation").textContent = data.designation || "N/A";
            document.getElementById("printEmail").textContent = data.email || "N/A";

            document.getElementById("printFirstname").textContent = data.firstname;
            document.getElementById("printLastname").textContent = data.lastname;
            document.getElementById("printMobile").textContent = data.mobile_number;
            document.getElementById("printDob").textContent = data.date_of_birth;
            document.getElementById("printMarital").textContent = data.marital_status;
            document.getElementById("printGender").textContent = data.gender;
            document.getElementById("printNationality").textContent = data.nationality;
            document.getElementById("printAddress").textContent = data.address + ", " + data.city + ", " + data.state + " " + data.zip_code;

            document.getElementById("printEmpId").textContent = data.employee_id;
            document.getElementById("printEmpDesignation").textContent = data.designation;
            document.getElementById("printEmpType").textContent = data.employee_type;
            document.getElementById("printSalary").textContent = data.salary_grade;
            document.getElementById("printDepartment").textContent = data.department;
            document.getElementById("printCs").textContent = data.cs_eligibility;
            document.getElementById("printWorkingDays").textContent = data.working_days;
            document.getElementById("printJoining").textContent = data.joining_date;

            // Set profile data
            document.getElementById("profileName").textContent = data.firstname + " " + data.lastname;
            document.getElementById("profileDesignation").innerHTML = `<i class="fas fa-briefcase me-2"></i>${data.designation || "N/A"}`;
            document.getElementById("profileEmail").innerHTML = `<i class="fas fa-envelope me-2"></i>${data.email || "N/A"}`;

            // Avatar for header
            let profileAvatar = document.getElementById("profileAvatar");
            if (data.avatar && data.avatar.trim() !== "") {
              profileAvatar.src = data.avatar;
            } else {
              profileAvatar.src = "../assets/images/employee-default.jpg"; // fallback image
            }

            let preview = document.getElementById("preview");
            let cameraIcon = document.getElementById("cameraIcon");

            if (data.avatar && data.avatar.trim() !== "") {
              // Employee has an uploaded avatar
              preview.src = data.avatar;
              preview.classList.remove("d-none");
              cameraIcon.classList.add("d-none");
            } else {
              // No avatar → show default static avatar
              preview.src = "../assets/images/employee-default.jpg"; // ← your placeholder image
              preview.classList.remove("d-none");
              cameraIcon.classList.add("d-none");
            }

            const files = {
              government_id: "govIdFileList",
              cv: "cvFileList",
              service_record: "serviceFileList",
              appointment_paper: "appointmentFileList",
              tor: "torFileList",
              cs_certificate: "csFileList"
            };

            Object.keys(files).forEach(key => {
              let container = document.getElementById(files[key]);
              container.innerHTML = "";
              if (data[key] && data[key].trim() !== "") {
                let fileUrl = data[key];
                let fileName = fileUrl.split("/").pop();
                container.innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <span>${fileName}</span>
        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary" title="View">
          <i class="fas fa-eye"></i>
        </a>
        <a href="${fileUrl}" download class="btn btn-sm btn-outline-success" title="Download">
          <i class="fas fa-download"></i>
        </a>
      </div>
    `;
              } else {
                container.innerHTML = `<span class="text-muted">No file uploaded</span>`;
              }
            });

            let modal = new bootstrap.Modal(document.getElementById("addEmployeeModal"));
            modal.show();
            document.querySelectorAll("#employeeForm input, #employeeForm select").forEach(el => {
              el.setAttribute("disabled", true);
            });

            document.querySelector("#nextBtn").classList.remove("d-none");
            document.querySelector("#submitBtn").classList.add("d-none");

            const activeTab = document.querySelector("#employeeTab .nav-link.active");
            const targetId = activeTab.getAttribute("data-bs-target").substring(1);
            updateButtons(targetId);


          });
      });
    });
    /* Print bio data */
    document.getElementById("printBioBtn").addEventListener("click", function() {
      let biodataContent = document.getElementById("biodataPrint").innerHTML;
      let printWindow = window.open("", "", "width=900,height=700");
      printWindow.document.write(`
    <html>
      <head>
        <title>Employee Bio Data</title>
        <style>
          body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
          table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          td { padding: 6px 10px; vertical-align: top; }
          tr:nth-child(even) { background: #f9f9f9; }
          h2, h4 { color: #7152F3; }
        </style>
      </head>
      <body>
        ${biodataContent}
      </body>
    </html>
  `);
      printWindow.document.close();
      printWindow.print();
    });



    /* adding employee */
    document.getElementById('addEmployeeModal').addEventListener('hidden.bs.modal', function() {

      document.querySelectorAll("#employeeForm input, #employeeForm select").forEach(el => {
        el.removeAttribute("disabled");
      });
      document.querySelector("#addEmployeeModalLabel").textContent = "Add New Employee";
      document.getElementById("employeeForm").reset();
      const firstTab = document.querySelector("#employeeTab .nav-link");
      new bootstrap.Tab(firstTab).show();
    });

    /* sidebar */
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



    /* Search */
    function filterTable() {
      const searchValue = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll("#employeeTable tbody tr");

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? "" : "none";
      });
    }
    window.filterTable = filterTable;

    const filterBtn = document.getElementById("openFilter");
    const filterPopup = document.getElementById("filterPopup");
    const cancelFilter = document.getElementById("cancelFilter");
    const applyFilter = document.querySelector(".btn-apply");

    filterBtn.addEventListener("click", () => filterPopup.style.display = "flex");
    cancelFilter.addEventListener("click", () => filterPopup.style.display = "none");

    applyFilter.addEventListener("click", () => {
      const selectedDepts = Array.from(document.querySelectorAll(".dept-filter:checked"))
        .map(cb => cb.value.toLowerCase());
      const selectedStatus = document.querySelector(".status-filter:checked")?.value.toLowerCase() || "";

      const rows = document.querySelectorAll("#employeeTable tbody tr");
      rows.forEach(row => {
        const dept = row.cells[3].textContent.toLowerCase();
        const status = row.cells[4].textContent.toLowerCase();

        const deptMatch = selectedDepts.length === 0 || selectedDepts.includes(dept);
        const statusMatch = !selectedStatus || status.includes(selectedStatus);

        row.style.display = deptMatch && statusMatch ? "" : "none";
      });

      filterPopup.style.display = "none";
    });
    /* Avatar */
  </script>
</body>

</html>