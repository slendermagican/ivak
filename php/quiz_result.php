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
//Clear the session from the quiz_id
unset($_SESSION["quiz_id"]);


// Fetch the last quiz ID taken by the user
$lastQuizQuery = "SELECT id, score, time_to_complete FROM quiz_results WHERE user_id = $user_id AND quiz_id=$quiz_id ORDER BY timestamp DESC LIMIT 1";
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col h-full">
    <?php include "../components/header.php"; ?>

    <div class="flex-grow mb-8">
        <h1 class="text-3xl font-bold text-center mt-8">Quiz Result</h1>
        <p class="text-center text-gray-600">You scored: <?= $lastQuiz["score"] ?>%</p>
        <p class="text-center text-gray-600">It took you : <?= $lastQuiz["time_to_complete"] ?> in seconds</p>

        <?php foreach ($questions as $i => $question): ?>
            <?php $questionResult = $questionResults[$i]; ?>
            <div class='max-w-3xl mx-auto mt-8 p-4 bg-white rounded-md shadow-md border-2 border-solid <?= $questionResult['is_correct'] == 0 ? 'border-rose-500' : 'border-lime-500' ?>' >
                <h2 class='text-2xl font-bold'>Question <?= htmlspecialchars($i+1) ?></h2>
                <hr>
                <h3 class='my-4 text-2xl'><?= htmlspecialchars($question['question_text']) ?></h3>
                <img src="<?= htmlspecialchars($question['img_src']) ?>" alt="<?= htmlspecialchars($question['img_alt']) ?>" class='w-full object-cover rounded-md mb-2' style='max-height: 70vh;'>

                <div class='grid grid-cols-2 gap-4'>
                    <?php for ($j = 1; $j <= 4; $j++): ?>
                        <input type="radio" id="question<?= $question['id'] ?>_<?= $j ?>" name="<?= $question['id'] ?>" value="<?= $j ?>" style="display: none;">
                        <label for="question<?= $question['id'] ?>_<?= $j ?>" class="w-full px-4 py-2 rounded-md relative pointer text-white text-center <?= ($questionResult['user_answer_index'] == $j && $questionResult['is_correct'] == 0) ? 'bg-red-500 hover:bg-red-700 focus:border-red-300' : '' ?> <?= ($question['correct_answer_index'] == $j) ? 'bg-green-500 hover:bg-green-700 focus:border-green-300' : '' ?> bg-blue-500 hover:bg-blue-700 focus:border-blue-300 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring transition duration-300 ease-in-out"><?= htmlspecialchars($question["answer{$j}"]) ?></label>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="flex justify-evenly mt-8">
            <a href="index.php"><button class="max-w-2/5 px-4 py-2 rounded-md relative pointer text-white text-center bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring focus:border-blue-300 transition duration-300 ease-in-out">Go home</button></a>
        </div>

    </div>

    <?php include "../components/footer.php"; ?>
</body>
</html>
