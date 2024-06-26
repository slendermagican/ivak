<?php
session_start();
include "../db/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$index = 0; //for Question number

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Check if the provided quiz_id exists in the quizzes table
    $quizQuery = "SELECT id FROM quizzes WHERE id = '$quiz_id'";
    $quizResult = mysqli_query($conn, $quizQuery);

    // Check if the query was successful
    if (!$quizResult) {
        die("Error: " . mysqli_error($conn));
    }

    // Fetch the quiz if it exists
    $quiz = mysqli_fetch_assoc($quizResult);

    // If the quiz does not exist, redirect to the error page
    if (!$quiz) {
        header("Location: error.php");
        exit();
    }

    // Fetch question data from the database
    $questionQuery = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
    $questionResult = mysqli_query($conn, $questionQuery);

    if (!$questionResult) {
        die("Error: " . mysqli_error($conn));
    }

    $questions = mysqli_fetch_all($questionResult, MYSQLI_ASSOC);
    $hasQuestions = !empty($questions);
} else {
    // Redirect to error.php if no question ID is provided
    header("Location: error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quizSubmit'])) {
    // Initialize quiz score
    $score = 0;
    $numberOfQuestions = 0;

    // Insert quiz result into quiz_results table
    $insertQuizResultQuery = "INSERT INTO quiz_results (user_id, quiz_id) VALUES ('$user_id', '$quiz_id')";
    mysqli_query($conn, $insertQuizResultQuery);
    $quiz_result_id = mysqli_insert_id($conn);

    // Calculate time taken to complete the quiz
    $startTime = $_POST['startTime'];
    $endTime = time();
    $timeToComplete = $endTime - $startTime;

    // Iterate through each question in the quiz
    foreach ($_POST as $question_id => $user_answer_index) {
        // Skip non-question parameters
        if ($question_id == 'quiz_id' || $question_id == 'quizSubmit' || $question_id == 'startTime') {
            continue;
        }

        // Fetch correct answer index from the database
        $answerQuery = "SELECT correct_answer_index FROM questions WHERE id = $question_id";
        $answerResult = mysqli_query($conn, $answerQuery);
        $correct_answer_index = mysqli_fetch_assoc($answerResult)['correct_answer_index'];

        // Determine if the user's answer is correct
        $is_correct = ($user_answer_index == $correct_answer_index);

        // Increment score if the answer is correct
        $numberOfQuestions++;
        if ($is_correct) {
            $score++;
        }

        // Insert question result into question_results table
        $insertOrUpdateQuery = "INSERT INTO question_results (quiz_result_id, question_id, user_answer_index, is_correct) 
                                VALUES ('$quiz_result_id', '$question_id', '$user_answer_index', '$is_correct')";
        mysqli_query($conn, $insertOrUpdateQuery);
    }

    // Calculate the percentage score
    $score = ($score / $numberOfQuestions) * 100;

    // Update the score and completion time in the quiz_results table
    $addScoreQuery = "UPDATE quiz_results SET score='$score', time_to_complete='$timeToComplete' WHERE id='$quiz_result_id'";
    mysqli_query($conn, $addScoreQuery);
    $_SESSION["quiz_id"] = $quiz_id;
    // Redirect to a page indicating successful quiz submission
    header("Location: quiz_result.php");
    // echo "<script>window.location.href = 'quiz_result.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow">

        <!-- Question Form -->
        <form id="quizForm" action="<?php htmlspecialchars("quizresults.php") ?>" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="startTime" value="<?php echo time(); ?>">
            <?php if ($hasQuestions): ?>
            <?php foreach ($questions as $question) {
                $index++; ?>
                <div class="max-w-3xl mx-auto mt-8 p-4 bg-white rounded-md shadow-md">
                    <h2 class="text-2xl font-bold"><?php echo "Question " . ($index); ?></h2>
                    <hr>
                    <h3 class="my-4 text-2xl"><?php echo htmlspecialchars($question['question_text']); ?></h3>
                    <img src="<?php echo htmlspecialchars($question['img_src']) ?>" alt="<?php echo htmlspecialchars($question['img_alt']) ?>" class="w-full object-cover rounded-md mb-2" style="max-height: 70vh;">
                    <div class="grid grid-cols-2 gap-4">
                        <?php for ($i = 1; $i <= 4; $i++) {
                            $isChecked = isset($_POST[$question['id']]) && $_POST[$question['id']] == $i;
                            $radioStyleClass = $isChecked ? 'bg-green-500 focus:bg-green-300 hover:bg-green-700' : 'bg-blue-500 focus:bg-blue-300 hover:bg-blue-700';
                        ?>
                            <input type="radio" id="question<?= $question['id'] ?>_<?= $i ?>" name="<?= $question['id'] ?>" value="<?= $i ?>" class="hidden" <?= $isChecked ? 'checked' : '' ?>>
                            <label for="question<?= $question['id'] ?>_<?= $i ?>" class="w-full py-2 px-4 flex items-center text-center justify-center text-white <?= $radioStyleClass ?> font-semibold rounded-md shadow-md focus:outline-none focus:ring focus:border-blue-300 transition duration-300 ease-in-out h-16">
                                <?= htmlspecialchars($question["answer{$i}"]); ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="flex justify-center mt-4 mb-4">
                <input type="submit" name="quizSubmit" class="w-2/5 py-2 rounded-md text-white bg-green-500 hover:bg-green-700 font-semibold shadow-md focus:outline-none focus:ring focus:border-blue-green transition duration-300 ease-in-out">
            </div>
            <?php else: ?>
                <div class="max-w-3xl mx-auto mt-8 p-4 bg-white rounded-md shadow-md text-center">
                    <h2 class="text-2xl font-bold">This quiz doesn't have any questions yet.</h2>
                    <div class="mt-4">
                        <a href="index.php" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-300">Go Home</a>
                    </div>
                </div>
            <?php endif; ?>
        </form>

    </main>

    <div class="fixed top-20 right-10 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg" id="timer">00:00:00</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener to the parent element of radio buttons
            document.getElementById('quizForm').addEventListener('change', function(event) {
                const target = event.target;
                if (target.type === 'radio') {
                    updateRadioStyle(target);
                }
            });

            // Function to update radio button style
            function updateRadioStyle(radioButton) {
                const siblings = Array.from(radioButton.parentElement.children);
                siblings.forEach(sibling => {
                    sibling.classList.remove('bg-green-500', 'focus:bg-green-300', 'hover:bg-green-700');
                    sibling.classList.add('bg-blue-500', 'focus:bg-blue-300', 'hover:bg-blue-700');
                });
                radioButton.nextElementSibling.classList.remove('bg-blue-500', 'focus:bg-blue-300', 'hover:bg-blue-700');
                radioButton.nextElementSibling.classList.add('bg-green-500', 'focus:bg-green-300', 'hover:bg-green-700');
            }

            // Timer functionality
            const timerElement = document.getElementById('timer');
            const startTime = <?php echo time(); ?>;

            function updateTimer() {
                const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
                const elapsedTime = currentTime - startTime;
                const hours = Math.floor(elapsedTime / 3600);
                const minutes = Math.floor((elapsedTime % 3600) / 60);
                const seconds = elapsedTime % 60;
                const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timerElement.textContent = formattedTime;
            }

            setInterval(updateTimer, 1000);
        });

        function validateForm() {
            let allAnswered = true; // Assume all questions are answered
            let alerted = false; // Flag to track if alert has been shown
            let scrollToFirstUnanswered = true; // Flag to determine if scrolling is needed

            // Iterate through each question
            const questions = document.querySelectorAll('.max-w-3xl');
            questions.forEach(question => {
                const radios = question.querySelectorAll('[type=radio]');
                let answered = false; // Assume no radio button is clicked for the current question

                // Check if any radio button is clicked for the current question
                radios.forEach(radio => {
                    if (radio.checked) {
                        answered = true; // At least one radio button is clicked for the current question
                    }
                });

                // If no radio button is clicked for the current question
                if (!answered) {
                    allAnswered = false; // At least one question is unanswered

                    // Scroll to the height of the first unanswered question relative to the viewport
                    if (scrollToFirstUnanswered) {
                        const rect = question.getBoundingClientRect();
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const offsetTop = rect.top + scrollTop;

                        // Scroll to the first unanswered question
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });

                        scrollToFirstUnanswered = false; // Set to false to prevent further scrolling
                    }

                    // Alert user to answer all questions (only once)
                    if (!alerted) {
                        alert("Please answer all questions before submitting.");
                        alerted = true; // Set the flag to true after the first alert
                    }
                }
            });
            // Prevent form submission if any question is unanswered
            return allAnswered;
        }
    </script>


    <?php include "../components/footer.php"; ?>

</body>

</html>