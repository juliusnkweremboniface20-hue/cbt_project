<?php
session_start();
require_once 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch questions grouped or ordered by subject from the database
try {
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY subject ASC");
    $questions = $stmt->fetchAll();
} catch (PDOException $e) {
    $questions = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CBT Examination - C.A.A.S.S Bukuru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 750px; background: white; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #333; font-size: 22px; }
        .candidate-info { font-size: 14px; color: #555; margin-top: 5px; }
        
        /* Subject Header Styling */
        .subject-section { background: #007bff; color: white; padding: 10px 15px; font-size: 18px; font-weight: bold; border-radius: 4px; margin-top: 30px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        
        .question-box { margin-bottom: 20px; padding: 15px; background: #fdfdfd; border: 1px solid #ddd; border-radius: 6px; }
        .question-title { font-weight: bold; color: #333; margin-bottom: 10px; }
        label { display: block; margin: 8px 0; cursor: pointer; color: #444; }
        button { background: #28a745; color: white; border: none; padding: 12px 25px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; display: block; width: 100%; margin-top: 30px; }
        button:hover { background: #218838; }
        .no-q { text-align: center; color: #777; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>C.A.A.S.S Bukuru JSS1 Entrance Examination</h2>
            <div class="candidate-info">
                Candidate: <strong><?php echo htmlspecialchars($_SESSION['student_name']); ?></strong> 
                (<span><?php echo htmlspecialchars($_SESSION['reg_number']); ?></span>)
            </div>
        </div>

        <!-- Form points to result.php to process and display the score slip -->
        <form action="result.php" method="POST">
            <?php if (!empty($questions)): ?>
                <?php 
                $current_subject = "";
                $i = 1; 
                foreach ($questions as $q): 
                    // Check if the subject changes to print a new section header
                    if ($current_subject != $q['subject']):
                        $current_subject = $q['subject'];
                        $i = 1; // Reset question numbering for each new subject
                ?>
                        <div class="subject-section">SECTION: <?php echo htmlspecialchars($current_subject); ?></div>
                <?php endif; ?>

                    <div class="question-box">
                        <div class="question-title">Q<?php echo $i++; ?>. <?php echo htmlspecialchars($q['question_text']); ?></div>
                        
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="A" required> A) <?php echo htmlspecialchars($q['option_a']); ?></label>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="B"> B) <?php echo htmlspecialchars($q['option_b']); ?></label>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="C"> C) <?php echo htmlspecialchars($q['option_c']); ?></label>
                        <label><input type="radio" name="question_<?php echo $q['id']; ?>" value="D"> D) <?php echo htmlspecialchars($q['option_d']); ?></label>
                    </div>
                <?php endforeach; ?>
                
                <button type="submit">Submit Examination</button>
            <?php else: ?>
                <div class="no-q">No exam questions found in the database. Please add questions specifying their subjects.</div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>