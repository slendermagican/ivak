<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$questionToEdit = '';
$errorMsg = '';
$successMsg = '';
$questionData = null;

// Fetch all existing quizzes from the database
$quizQuery = "SELECT quiz FROM quizzes";
$quizResult = mysqli_query($conn, $quizQuery);
$quizzes = array();
while ($row = mysqli_fetch_assoc($quizResult)) {
    $quizzes[] = $row['quiz'];
}

// Fetch all existing questions from the database
$questionQuery = "SELECT question_text FROM questions";
$questionResult = mysqli_query($conn, $questionQuery);
$questions = array();
while ($row = mysqli_fetch_assoc($questionResult)) {
    $questions[] = $row['question_text'];
}

// Process form submission to fetch question details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["questionToEdit"])) {
    $questionToEdit = mysqli_real_escape_string($conn, $_POST["questionToEdit"]);

    // Fetch question details based on question text
    $questionQuery = "SELECT * FROM questions WHERE question_text = '$questionToEdit'";
    $questionResult = mysqli_query($conn, $questionQuery);

    if (!$questionResult) {
        die("Error: " . mysqli_error($conn));
    }

    $questionData = mysqli_fetch_assoc($questionResult);
}

// Process form submission to update question details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateQuestion"])) {
    // Validate and sanitize form data
    $questionToEdit = mysqli_real_escape_string($conn, $_POST["questionToEdit"]);
    $newQuestionText = mysqli_real_escape_string($conn, $_POST["question_text"]);
    $newImgSrc = filter_var($_POST["img_src"], FILTER_SANITIZE_URL);
    $newImgAlt = mysqli_real_escape_string($conn, $_POST["img_alt"]);
    $newAnswer1 = mysqli_real_escape_string($conn, $_POST["answer1"]);
    $newAnswer2 = mysqli_real_escape_string($conn, $_POST["answer2"]);
    $newAnswer3 = mysqli_real_escape_string($conn, $_POST["answer3"]);
    $newAnswer4 = mysqli_real_escape_string($conn, $_POST["answer4"]);
    $newCorrectAnswerIndex = (int)$_POST["correct_answer_index"];
    $newQuiz = mysqli_real_escape_string($conn, $_POST["quiz"]);

    // Update question details in the database
    $updateQuery = "UPDATE questions SET question_text = '$newQuestionText',
    img_src = '$newImgSrc', 
    img_alt = '$newImgAlt', 
    answer1 = '$newAnswer1', 
    answer2 = '$newAnswer2', 
    answer3 = '$newAnswer3', 
    answer4 = '$newAnswer4', 
    correct_answer_index = $newCorrectAnswerIndex, 
    quiz_id = (SELECT id FROM quizzes WHERE quiz = '$newQuiz') 
    WHERE question_text = '$questionToEdit'";

    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'Question details updated successfully.';
        $questionData['question_text'] = $newQuestionText;
        $questionData['img_src'] = $newImgSrc;
        $questionData['img_alt'] = $newImgAlt;
        $questionData['answer1'] = $newAnswer1;
        $questionData['answer2'] = $newAnswer2;
        $questionData['answer3'] = $newAnswer3;
        $questionData['answer4'] = $newAnswer4;
        $questionData['correct_answer_index'] = $newCorrectAnswerIndex;
        $questionData['quiz_id'] = $newQuiz;
    } else {
        $errorMsg = 'Error updating question details: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Question</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="w-1/4">
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit Question</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="questionToEdit" class="block text-gray-700 text-sm font-bold mb-2">Question to Edit:</label>
                    <select name="questionToEdit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Question</option>
                        <?php foreach ($questions as $question) : ?>
                            <option value="<?php echo htmlspecialchars($question); ?>"><?php echo htmlspecialchars($question); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch Question Details</button>
                </div>
            </form>
            <?php if ($questionData) : ?>
                <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="question_text" class="block text-gray-700 text-sm font-bold mb-2">Question:</label>
                        <input type="text" name="question_text" value="<?php echo $questionData['question_text']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                        <input type="url" name="img_src" value="<?php echo $questionData['img_src']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                        <input type="text" name="img_alt" value="<?php echo $questionData['img_alt']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Answers:</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="answer1" value="<?php echo htmlspecialchars($questionData['answer1']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 1" required>
                            <input type="text" name="answer2" value="<?php echo htmlspecialchars($questionData['answer2']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 2" required>
                            <input type="text" name="answer3" value="<?php echo htmlspecialchars($questionData['answer3']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 3" required>
                            <input type="text" name="answer4" value="<?php echo htmlspecialchars($questionData['answer4']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 4" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="correct_answer_index" class="block text-gray-700 text-sm font-bold mb-2">Correct Answer Index:</label>
                        <input type="number" name="correct_answer_index" value="<?php echo $questionData['correct_answer_index']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="quiz" class="block text-gray-700 text-sm font-bold mb-2">Quiz:</label>
                        <select name="quiz" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <?php foreach ($quizzes as $quiz) : ?>
                                <option value="<?php echo htmlspecialchars($quiz); ?>" <?php if ($quiz === $questionData['quiz_id']) echo 'selected'; ?>><?php echo htmlspecialchars($quiz); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- hidden input for which question to be edited-->
                    <input type="hidden" name="questionToEdit" value="<?php echo htmlspecialchars($questionToEdit); ?>">

                    <div class="text-center">
                        <button type="submit" name="updateQuestion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Question</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>