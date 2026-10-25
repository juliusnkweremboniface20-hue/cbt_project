<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Handle Form Submission for Adding a New Question
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $subject = trim($_POST['subject']);
    $question_text = trim($_POST['question_text']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_answer = trim($_POST['correct_answer']);

    if (!empty($subject) && !empty($question_text) && !empty($correct_answer)) {
        $stmt = $pdo->prepare("INSERT INTO questions (subject, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$subject, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer]);
        $message = "Question added successfully!";
    } else {
        $message = "Please fill in all required fields!";
    }
}

// Handle Question Deletion
if (isset($_GET['delete'])) {
    $q_id = intval($_GET['delete']);
    $del = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $del->execute([$q_id]);
    header("Location: admin_questions.php");
    exit();
}

// Fetch all questions
$questions = $pdo->query("SELECT * FROM questions ORDER BY subject, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Questions - C.A.A.S.S Bukuru</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 0; background: #f4f6f9; }
        .navbar { background: #003366; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h2 { margin: 0; font-size: 20px; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; background: rgba(255,255,255,0.1); padding: 8px 12px; border-radius: 4px; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { padding: 30px; display: flex; gap: 30px; }
        .form-box { background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); width: 400px; }
        .table-box { background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); flex: 1; overflow-x: auto; }
        input, select, textarea { width: 100%; padding: 8px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #003366; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background: #002244; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-size: 13px; }
        th { background: #003366; color: white; }
        .delete-btn { color: red; text-decoration: none; font-weight: bold; }
        .msg { background: #e6f9e6; color: green; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>C.A.A.S.S Bukuru - Question Bank Management</h2>
        <div class="nav-links">
            <a href="admin_dashboard.php">View Results</a>
            <a href="admin_questions.php">Manage Questions</a>
            <a href="admin_logout.php" style="background: #cc0000;">Log Out</a>
        </div>
    </div>

    <div class="container">
        <div class="form-box">
            <h3>Add New Question</h3>
            <?php if($message): ?><div class="msg"><?= $message; ?></div><?php endif; ?>
            <form method="POST">
                <label>Select Subject:</label>
                <select name="subject" required>
                    <option value="Mathematics">Mathematics</option>
                    <option value="English">English Language</option>
                </select>

                <label>Question Text:</label>
                <textarea name="question_text" rows="3" required placeholder="Type the question here..."></textarea>

                <label>Option A:</label>
                <input type="text" name="option_a" required>

                <label>Option B:</label>
                <input type="text" name="option_b" required>

                <label>Option C:</label>
                <input type="text" name="option_c" required>

                <label>Option D:</label>
                <input type="text" name="option_d" required>

                <label>Correct Answer:</label>
                <select name="correct_answer" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <button type="submit" name="add_question">Save Question</button>
            </form>
        </div>

        <div class="table-box">
            <h3>Existing Question Bank (<?= count($questions); ?> Questions)</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Question</th>
                        <th>Correct</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($questions) == 0): ?>
                        <tr><td colspan="5" style="text-align: center;">No questions available.</td></tr>
                    <?php else: ?>
                        <?php foreach($questions as $q): ?>
                            <tr>
                                <?= htmlspecialchars($q['id']); ?>
                                <td><b><?= htmlspecialchars($q['subject']); ?></b></td>
                                <td><?= htmlspecialchars(substr($q['question_text'], 0, 50)); ?>...</td>
                                <td><b style="color: green;"><?= htmlspecialchars($q['correct_answer']); ?></b></td>
                                <td><a href="admin_questions.php?delete=<?= $q['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

