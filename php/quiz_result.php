<?php
session_start();
include "../db/connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the quiz_id is provided
if (!isset($_SESSION['quiz_id'])) {
    header("Location: index.php");
    exit();
}

$quiz_id = $_SESSION['quiz_id'];
$user_id = $_SESSION['user_id'];


// Fetch the last quiz ID taken by the user
$lastQuizQuery = "SELECT id FROM quiz_results WHERE user_id = $user_id AND quiz_id=$quiz_id ORDER BY timestamp DESC LIMIT 1";
$lastQuizResult = mysqli_query($conn, $lastQuizQuery);
if (!$lastQuizResult) {
    die("Error: " . mysqli_error($conn));
}
$lastQuiz = mysqli_fetch_assoc($lastQuizResult);
if (!$lastQuiz) {
    echo "No quiz results found for the user.";
    exit();
}
$quizResultId = $lastQuiz["id"];


// Fetch questionAnswers from question_results and store all rows in an array
$questionResultQuery = "SELECT * FROM question_results WHERE quiz_result_id=$quizResultId";
$questionResultResult = mysqli_query($conn, $questionResultQuery);
if (!$questionResultResult) {
    die("Error: " . mysqli_error($conn));
}
$questionResults = mysqli_fetch_all($questionResultResult, MYSQLI_ASSOC);


// Fetch quiz id from the database
$quizQuery = "SELECT id FROM quizzes WHERE id = '$quiz_id'";
$quizResult = mysqli_query($conn, $quizQuery);
if (!$quizResult) {
    die("Error: " . mysqli_error($conn));
}
$quiz = mysqli_fetch_assoc($quizResult);



// Fetch question data from the database
$questionQuery = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
$questionResult = mysqli_query($conn, $questionQuery);
if (!$questionResult) {
    die("Error: " . mysqli_error($conn));
}
$questions = mysqli_fetch_all($questionResult, MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script><!--Version 3.4 for border colors-->

</head>

<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <h1 class="text-3xl font-bold text-center mt-8">Quiz Result</h1>
    <p class="text-center text-gray-600">Quiz ID: <?= $quiz_id ?></p>


    <?php
for ($i = 0; $i < count($questions); $i++) {
    $question = $questions[$i];
    $questionResult = $questionResults[$i];
    
    // Questions
    echo "Question ID: " . $question['id'] . "<br>";
    echo "Question Text: " . $question['question_text'] . "<br>";
    echo "Image Source: " . $question['img_src'] . "<br>";
    echo "Image Alt: " . $question['img_alt'] . "<br>";
    echo "Answer 1: " . $question['answer1'] . "<br>";
    echo "Answer 2: " . $question['answer2'] . "<br>";
    echo "Answer 3: " . $question['answer3'] . "<br>";
    echo "Answer 4: " . $question['answer4'] . "<br>";
    echo "Correct Answer Index: " . $question['correct_answer_index'] . "<br>";
    echo "Quiz ID: " . $question['quiz_id'] . "<br>";
    
    // Results
    echo "Question Result ID: " . $questionResult['id'] . "<br>";
    echo "Quiz Result ID: " . $questionResult['quiz_result_id'] . "<br>";
    echo "User Answer Index: " . $questionResult['user_answer_index'] . "<br>";
    echo "Is Correct: " . ($questionResult['is_correct'] ? 'Yes' : 'No') . "<br>";
    
    echo "</br>"; 
}
?>
        

    <?php include "../components/footer.php"; ?>
</body>

</html>