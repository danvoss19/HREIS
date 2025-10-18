<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave Form - HREIS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      margin: 0;
      display: flex;
      background-color: #f5f6fa;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #fff;
      padding: 20px;
      border-right: 1px solid #ddd;
      height: 100vh;
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
      padding: 0;
    }

    .menu li {
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      cursor: pointer;
    }

    .menu li:hover,
    .menu li.active {
      background-color: #e3f2fd;
      color: #007acc;
      font-weight: bold;
    }

    .menu li i {
      margin-right: 10px;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 30px;
    }

    .header {
      background: #dceeff;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h2 {
      margin: 0;
    }

    .avatar {
      width: 40px;
      border-radius: 50%;
    }

    /* Leave Form */
    .form-container {
      margin-top: 30px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      max-width: 600px;
    }

    .form-container h3 {
      margin-bottom: 20px;
      color: #007acc;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }

    .form-group textarea {
      resize: vertical;
    }

    .btn-submit {
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    .btn-submit:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="logo.png" />
    <h4>HUMAN RESOURCE EMPLOYEE<br>INFORMATION SYSTEM</h4>
    <ul class="menu">
      <li><a href="dashboard.php" style="text-decoration:none; color:inherit;">📊 Dashboard</a></li>
      <li><a href="employee.php" style="text-decoration:none; color:inherit;">👥 Employee</a></li>
      <li><a href="department.php" style="text-decoration:none; color:inherit;">🏢 All Departments</a></li>
      <li class="active">📝 Leave Form</li>
      <li><a href="leavereports.php" style="text-decoration:none; color:inherit;">📂 Leave Reports</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="header">
      <h2>Leave Form 📝</h2>
      <img src="admin.jpg" class="avatar" />
    </div>

    <div class="form-container">
      <h3>Request Leave</h3>
      <form>
        <div class="form-group">
          <label for="employeeName">Employee Name</label>
          <input type="text" id="employeeName" name="employeeName" placeholder="Enter your name" required>
        </div>

        <div class="form-group">
          <label for="department">Department</label>
          <select id="department" name="department" required>
            <option value="">Select department</option>
            <option value="HR">Human Resources</option>
            <option value="IT">Information Technology</option>
            <option value="Finance">Finance</option>
            <option value="Marketing">Marketing</option>
          </select>
        </div>

        <div class="form-group">
          <label for="leaveType">Leave Type</label>
          <select id="leaveType" name="leaveType" required>
            <option value="">Select leave type</option>
            <option value="Vacation">Vacation Leave</option>
            <option value="Sick">Sick Leave</option>
            <option value="Emergency">Emergency Leave</option>
          </select>
        </div>

        <div class="form-group">
          <label for="startDate">Start Date</label>
          <input type="date" id="startDate" name="startDate" required>
        </div>

        <div class="form-group">
          <label for="endDate">End Date</label>
          <input type="date" id="endDate" name="endDate" required>
        </div>

        <div class="form-group">
          <label for="reason">Reason</label>
          <textarea id="reason" name="reason" rows="4" placeholder="Enter reason for leave" required></textarea>
        </div>

        <button type="submit" class="btn-submit">Submit Leave Request</button>
      </form>
    </div>
  </div>

</body>
</html>
