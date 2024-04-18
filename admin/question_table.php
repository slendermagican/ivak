<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Fetch data from questions table along with their respective quizzes, subcategories, and categories
$questionQuery = "SELECT questions.*, quizzes.quiz AS quiz_name,
subcategories.subcategory AS subcategory_name,
categories.category AS category_name
FROM questions
INNER JOIN quizzes ON questions.quiz_id = quizzes.id
INNER JOIN subcategories ON quizzes.subcategory_id = subcategories.id
INNER JOIN categories ON subcategories.category_id = categories.id";

$questionResult = mysqli_query($conn, $questionQuery);

if (!$questionResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all question data into an associative array
$questions = mysqli_fetch_all($questionResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Question Table</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Libraries for the sortability of the table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="w-1/4">
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 bg-gray-200 rounded-2xl overflow-x-auto shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-gray-800 text-center">Questions Table</h1>
            <div class="overflow-x-auto">
                <?php if (isset($questions) && is_array($questions) && count($questions) > 0) : ?>
                    <table id="questionsTable" class="min-w-full table-auto border border-gray-300 bg-white shadow-md rounded-md overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Question</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Image</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Alt Text</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Answers</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Quiz</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Subcategory</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $question) : ?>
                                <tr class="border-b hover:bg-gray-50 transition duration-300">
                                    <td class="p-3 text-gray-800 border-r"><?php echo $question['question_text']; ?></td>
                                    <td class="p-3 text-center border-r">
                                        <img src="<?php echo $question['img_src']; ?>" alt="<?php echo $question['img_alt']; ?>" class="max-w-full h-auto mx-auto rounded-lg" style="max-width: 150px; max-height: 150px;">
                                    </td>
                                    <td class="p-3 text-gray-800 border-r"><?php echo $question['img_alt']; ?></td>
                                    <td class="p-3 text-gray-800 border-r">
                                        <ul class="list-disc list-inside">
                                            <?php foreach (['answer1', 'answer2', 'answer3', 'answer4'] as $answerKey) : ?>
                                                <li <?php if ($question['correct_answer_index'] == substr($answerKey, -1)) echo 'class="text-green-600 font-bold"'; ?>>
                                                    <?php echo $question[$answerKey]; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <td class="p-3 text-gray-800 border-r"><?php echo $question['quiz_name']; ?></td>
                                    <td class="p-3 text-gray-800 border-r"><?php echo $question['subcategory_name']; ?></td>
                                    <td class="p-3 text-gray-800 border-r"><?php echo $question['category_name']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-red-500">No question data available.</p>
                <?php endif; ?>
            </div>
            <script>
                $(document).ready(function() {
                    $('#questionsTable').DataTable();
                });
            </script>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>