<?php
session_start();
require_once 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$reg_number = $_GET['reg'] ?? '';

// Fetch student and result details
$stmt = $pdo->prepare("
    SELECT s.fullname, s.reg_number, r.score, r.total_questions, r.exam_date 
    FROM students s 
    LEFT JOIN results r ON s.reg_number = r.reg_number 
    WHERE s.reg_number = ?
");
$stmt->execute([$reg_number]);
$record = $stmt->fetch();

if (!$record) {
    die("Candidate record not found.");
}

$percentage = ($record['total_questions'] > 0) ? ($record['score'] / $record['total_questions']) * 100 : 0;
$status = ($percentage >= 50) ? "PASSED" : "FAILED";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result Slip - <?= htmlspecialchars($record['fullname']); ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background: #fff; margin: 0; padding: 40px; display: flex; justify-content: center; }
        .slip-container { width: 550px; border: 3px double #003366; padding: 30px; border-radius: 8px; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #003366; padding-bottom: 15px; margin-bottom: 20px; }
        .school-logo { width: 75px; height: 75px; object-fit: contain; margin-bottom: 5px; }
        .header h2 { color: #003366; margin: 0; font-size: 22px; }
        .header h4 { color: #555; margin: 5px 0 0 0; font-size: 14px; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 8px; font-size: 15px; border-bottom: 1px solid #eee; }
        .info-table td:first-child { font-weight: bold; color: #333; width: 35%; }
        .score-box { background: #f8f9fa; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 6px; margin-bottom: 20px; }
        .score-box h3 { margin: 0; color: #003366; font-size: 20px; }
        .status { font-weight: bold; font-size: 16px; padding: 5px 15px; border-radius: 4px; display: inline-block; margin-top: 5px; }
        .passed { color: #2e7d32; background: #e8f5e9; }
        .failed { color: #c62828; background: #ffebee; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid #ccc; padding-top: 15px; }
        .print-btn-container { text-align: center; margin-top: 30px; }
        .print-btn { background: #003366; color: white; border: none; padding: 10px 25px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .print-btn:hover { background: #002244; }
        @media print {
            .print-btn-container { display: none; }
            body { padding: 0; }
            .slip-container { border: 2px solid #000; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>

<div class="slip-container">
    <div class="header">
        <img src="logo.png" alt="C.A.A.S.S Logo" class="school-logo">
        <h2>CHRIST ANGLICAN ACADEMY SECONDARY SCHOOL</h2>
        <h4>Bukuru, Plateau State - JSS1 Entrance Screening Result Slip</h4>
    </div>

    <table class="info-table">
        <tr>
            <td>Candidate Name:</td>
            <td><?= htmlspecialchars($record['fullname']); ?></td>
        </tr>
        <tr>
            <td>Registration Number:</td>
            <td><strong><?= htmlspecialchars($record['reg_number']); ?></strong></td>
        </tr>
        <tr>
            <td>Examination Subject:</td>
            <td>JSS1 Entrance Screening (Core Subjects)</td>
        </tr>
        <tr>
            <td>Date of Examination:</td>
            <td><?= htmlspecialchars($record['exam_date'] ?? 'N/A'); ?></td>
        </tr>
    </table>

    <div class="score-box">
        <p style="margin: 0 0 5px 0; color: #555; font-size: 14px;">Performance Score:</p>
        <h3><?= $record['score']; ?> / <?= $record['total_questions']; ?> (<?= round($percentage, 1); ?>%)</h3>
        <div class="status <?= ($status === 'PASSED') ? 'passed' : 'failed'; ?>">
            STATUS: <?= $status; ?>
        </div>
    </div>

    <div class="footer">
        <div><strong>Validated By:</strong> School Examination Officer</div>
        <div><strong>Stamp / Signature:</strong> ___________________</div>
    </div>

    <div class="print-btn-container">
        <button onclick="window.print();" class="print-btn">🖨️ Print Result Slip</button>
    </div>
</div>

</body>
</html>