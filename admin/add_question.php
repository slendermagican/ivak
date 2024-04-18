<?php
session_start();
include "../db/connection.php";

// Initialize error and success message variables
$errorMsg = '';
$successMsg = '';

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = $_POST["question_text"];
    $img_src = $_POST["img_src"];
    $img_alt = $_POST["img_alt"];
    $answer1 = $_POST["answer1"];
    $answer2 = $_POST["answer2"];
    $answer3 = $_POST["answer3"];
    $answer4 = $_POST["answer4"];
    $correct_answer_index = $_POST["correct_answer_index"];
    $quiz_id = $_POST["quiz_id"];

    
    $sql = "INSERT INTO questions (question_text, img_src, img_alt, answer1,
     answer2, answer3, answer4, correct_answer_index, quiz_id) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssssi", $question_text, $img_src, $img_alt,
         $answer1, $answer2, $answer3, $answer4, $correct_answer_index, $quiz_id);
        if ($stmt->execute()) {
            $successMsg = "Question added successfully.";
        } else {
            $errorMsg = "Error adding question: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMsg = "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Question</title>
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

        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Add Question</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="question_text" class="block text-gray-700 text-sm font-bold mb-2">Question:</label>
                    <textarea name="question_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                    <input type="url" name="img_src" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                    <input type="text" name="img_alt" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2">Answers:</label>
                    <div class=" grid grid-cols-2 gap-2">
                        <input type="text" name="answer1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 1" required>
                        <input type="text" name="answer2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 2" required>
                        <input type="text" name="answer3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 3" required>
                        <input type="text" name="answer4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer 4" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="correct_answer_index" class="block text-gray-700 text-sm font-bold mb-2">Correct Answer Index:</label>
                    <input type="number" name="correct_answer_index" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1" max="4" required>
                </div>

                <div class="mb-4">
                    <label for="quiz_id" class="block text-gray-700 text-sm font-bold mb-2">Quiz:</label>
                    <select name="quiz_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <?php
                        // Fetch quizzes from the database
                        $quizQuery = "SELECT id, quiz FROM quizzes";
                        $quizResult = $conn->query($quizQuery);

                        // Populate the dropdown with quiz options
                        while ($row = $quizResult->fetch_assoc()) {
                            $quizId = $row['id'];
                            $quizName = $row['quiz'];
                            echo "<option value='$quizId'>$quizName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Question</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>