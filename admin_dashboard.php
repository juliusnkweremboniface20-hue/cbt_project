<?php
session_start();
require_once 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all students and their results
$stmt = $pdo->query("
    SELECT s.reg_number, s.fullname, s.status, r.score, r.total_questions, r.exam_date 
    FROM students s 
    LEFT JOIN results r ON s.reg_number = r.reg_number
    ORDER BY s.id ASC
");
$candidates = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - C.A.A.S.S Bukuru</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background: #f2f4f8; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #003366; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #003366; margin: 0; font-size: 22px; }
        .logout-btn { background: #d9534f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; }
        .logout-btn:hover { background: #c9302c; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px 12px; text-align: left; font-size: 14px; }
        th { background: #003366; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .status-completed { color: #2e7d32; font-weight: bold; }
        .status-pending { color: #e65100; font-weight: bold; }
        .print-btn { background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .print-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>C.A.A.S.S Bukuru - Admin Screening Dashboard</h2>
            <a href="logout.php" class="logout-btn">Log Out</a>
        </div>

        <p>Welcome, Administrator. Below is the official roster of JSS1 screening candidates, their examination status, and score records.</p>

        <table>
            <thead>
                <tr>
                    <th>Reg Number</th>
                    <th>Candidate Name</th>
                    <th>Exam Status</th>
                    <th>Score</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidates as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['reg_number']); ?></td>
                    <td><?= htmlspecialchars($row['fullname']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'Completed'): ?>
                            <span class="status-completed">Completed</span>
                        <?php else: ?>
                            <span class="status-pending">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                            if ($row['score'] !== null) {
                                echo $row['score'] . ' / ' . $row['total_questions'] . ' (' . round(($row['score']/$row['total_questions'])*100, 1) . '%)';
                            } else {
                                echo 'N/A';
                            }
                        ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'Completed'): ?>
                            <a href="print_slip.php?reg=<?= urlencode($row['reg_number']); ?>" class="print-btn" target="_blank">Print Result Slip</a>
                        <?php else: ?>
                            <span style="color: #999; font-size: 12px;">Not Available</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>