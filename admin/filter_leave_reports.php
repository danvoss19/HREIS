<?php
include '../db.php';

$sql = "SELECT l.*, e.firstname, e.lastname 
        FROM leaves l
        JOIN employees e ON l.leave_user_name = e.id 
        WHERE 1=1";

$params = [];
$types = "";

if (isset($_GET['employee_name']) && !empty($_GET['employee_name'])) {
    $sql .= " AND (e.firstname LIKE ? OR e.lastname LIKE ?)";
    $searchTerm = "%" . $_GET['employee_name'] . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if (isset($_GET['leave_type']) && !empty($_GET['leave_type'])) {
    $sql .= " AND l.leave_type = ?";
    $params[] = $_GET['leave_type'];
    $types .= "s";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $sql .= " AND l.leave_status = ?";
    $params[] = $_GET['status'];
    $types .= "s";
}

if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $sql .= " AND l.leave_start_date >= ?";
    $params[] = $_GET['start_date'];
    $types .= "s";
}

if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND l.leave_start_date <= ?";
    $params[] = $_GET['end_date'];
    $types .= "s";
}

if (isset($_GET['duration']) && !empty($_GET['duration'])) {
    switch ($_GET['duration']) {
        case '1':
            $sql .= " AND l.leave_duration = '1 Day'";
            break;
        case '2-5':
            $sql .= " AND (l.leave_duration LIKE '2 Day%' OR l.leave_duration LIKE '3 Day%' OR l.leave_duration LIKE '4 Day%' OR l.leave_duration LIKE '5 Day%')";
            break;
        case '6-10':
            $sql .= " AND (l.leave_duration LIKE '6 Day%' OR l.leave_duration LIKE '7 Day%' OR l.leave_duration LIKE '8 Day%' OR l.leave_duration LIKE '9 Day%' OR l.leave_duration LIKE '10 Day%')";
            break;
        case '10+':
            $sql .= " AND (l.leave_duration LIKE '11 Day%' OR l.leave_duration LIKE '12 Day%' OR l.leave_duration LIKE '13 Day%' OR l.leave_duration LIKE '14 Day%' OR l.leave_duration LIKE '15 Day%' OR l.leave_duration LIKE '%Week%' OR l.leave_duration LIKE '%Month%')";
            break;
    }
}

$sql .= " ORDER BY l.id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        $employeeName = htmlspecialchars($row['firstname'] . " " . $row['lastname']);
        echo "<td>" . $employeeName . "</td>";
        echo "<td>" . htmlspecialchars($row['leave_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['leave_duration']) . "</td>";
        echo "<td>" . date("F j, Y", strtotime($row['leave_start_date'])) . "</td>";
        echo "<td>" . date("F j, Y", strtotime($row['leave_end_date'])) . "</td>";

        $resumptionDate = $row['leave_resumption_date'] && $row['leave_resumption_date'] !== "0000-00-00"
            ? date("F j, Y", strtotime($row['leave_resumption_date']))
            : "";
        echo "<td>" . $resumptionDate . "</td>";

        echo "<td>" . htmlspecialchars($row['leave_reason']) . "</td>";

        $statusClass = '';
        if ($row['leave_status'] === 'Approved') {
            $statusClass = 'status-approved';
        } elseif ($row['leave_status'] === 'Pending') {
            $statusClass = 'status-pending';
        } elseif ($row['leave_status'] === 'Rejected') {
            $statusClass = 'status-rejected';
        }
        echo "<td class='{$statusClass}'>" . htmlspecialchars($row['leave_status']) . "</td>";
        echo "<td>";
        if ($row['leave_status'] === 'Pending') {
            echo "
                <div class='action-menu'>
                    <button class='btn-action toggle-action'>Action ▾</button>
                    <div class='action-options' style='display:none;'>
                        <button class='btn-action approveBtn' data-id='{$row['id']}'>Approve</button>
                        <button class='btn-action declineBtn' data-id='{$row['id']}'>Decline</button>
                    </div>
                </div>
            ";
        } else {
            echo "<button class='btn-action no-action' disabled>✔ No Action Needed</button>";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No leave requests found matching your filters</td></tr>";
}

$conn->close();
