<?php
session_start();
include_once('../db.php');

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

$stmt = $conn->prepare("SELECT e.firstname, e.lastname, e.designation, e.department, 
                        e.employee_id, e.mobile_number, e.email,
                        l.leave_duration, l.leave_start_date, l.leave_end_date, 
                        l.leave_resumption_date, l.leave_type, l.leave_reason
                        FROM employees e
                        LEFT JOIN leaves l 
                        ON l.id = (
                            SELECT id FROM leaves 
                            WHERE leave_user_name = e.id AND leave_status = 'Approved' 
                            ORDER BY id DESC LIMIT 1
                        )
                        WHERE e.id = ?");

$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    $stmt = $conn->prepare("SELECT firstname, lastname, designation, department, 
                           employee_id, mobile_number, email
                           FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
require_once __DIR__ . '/../vendor/autoload.php';

if (ob_get_length()) {
    ob_end_clean();
}

class MYPDF extends TCPDF
{
    // Page header
    public function Header()
    {
        $image_file = '../assets/images/logo.png';
        $this->Image($image_file, 15, 10, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(12);
        $this->Cell(0, 0, 'REPUBLIC OF THE PHILIPPINES', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 8, 'MUNICIPALITY OF POLANGUI', 0, 1, 'C');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 0, 'PROVINCE OF ALBAY', 0, 1, 'C');

        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'LEAVE APPLICATION FORM', 0, 1, 'C');

        $this->Line(15, 45, 195, 45);
        $this->Ln(10);
    }

    // Page footer
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Create PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('LGU Polangui HREIS');
$pdf->SetAuthor('HR Department');
$pdf->SetTitle('Leave Application Form');
$pdf->SetSubject('Employee Leave Application');
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

// ================= EMPLOYEE INFORMATION =================
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'EMPLOYEE INFORMATION', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(5);

$employee_info = array(
    array('Full Name:', $data['firstname'] . ' ' . $data['lastname']),
    array('Employee ID:', $data['employee_id']),
    array('Position:', $data['designation']),
    array('Department:', $data['department']),
    array('Contact Number:', $data['mobile_number'] ?? 'N/A'),
    array('Email:', $data['email'])
);

foreach ($employee_info as $row) {
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(40, 6, $row[0], 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, $row[1], 0, 1, 'L');
}

$pdf->Ln(8);

// ================= LEAVE DETAILS =================
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'LEAVE DETAILS', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(5);

if (isset($data['leave_type'])) {
    $leave_info = array(
        array('Type of Leave:', $data['leave_type']),
        array('Duration:', $data['leave_duration'] . ' day(s)'),
        array('Start Date:', date('F d, Y', strtotime($data['leave_start_date']))),
        array('End Date:', date('F d, Y', strtotime($data['leave_end_date']))),
        array('Resumption Date:', date('F d, Y', strtotime($data['leave_resumption_date']))),
        array('Reason:', $data['leave_reason'])
    );
} else {
    $leave_info = array(
        array('Type of Leave:', '_________________________'),
        array('Duration:', '_________________________'),
        array('Start Date:', '_________________________'),
        array('End Date:', '_________________________'),
        array('Resumption Date:', '_________________________'),
        array('Reason:', '________________________________________________________________')
    );
}

foreach ($leave_info as $row) {
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(45, 6, $row[0], 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, $row[1], 0, 1, 'L');
}

$pdf->Ln(15);

// ================= NOTES =================
$pdf->SetFont('helvetica', 'I', 9);
$pdf->MultiCell(0, 4, 'Note: Please fill out all required fields. For sick leave exceeding 3 days, a medical certificate must be attached. Vacation leaves must be filed at least 3 working days in advance.', 0, 'L');
$pdf->Ln(15);

// ================= SIGNATURES =================
$signature_table = '
<table border="0" cellpadding="5">
<tr>
<td width="50%" align="center">
<br><br><br>
_________________________<br>
<strong>Employee\'s Signature</strong><br>
Date: _________________
</td>
<td width="50%" align="center">
<br><br><br>
_________________________<br>
<strong>Immediate Supervisor</strong><br>
Date: _________________
</td>
</tr>
<tr>
<td width="50%" align="center">
<br><br><br>
_________________________<br>
<strong>Department Head</strong><br>
Date: _________________
</td>
<td width="50%" align="center">
<br><br><br>
_________________________<br>
<strong>HR Officer</strong><br>
Date: _________________
</td>
</tr>
</table>';

$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($signature_table, true, false, false, false, '');

$filename = 'Leave_Form_' . $data['firstname'] . '_' . $data['lastname'] . '_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'D');
