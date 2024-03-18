<?php
session_start();
include "../db/connection.php"; // Assuming this file contains the database connection

// Fetch leaderboard data from the database
$query = "SELECT users.username, quiz_results.score, quiz_results.time_to_complete, quizzes.quiz, subcategories.subcategory, categories.category, 
          quizzes.img_src AS quiz_img, subcategories.img_src AS subcategory_img, categories.img_src AS category_img
          FROM quiz_results
          INNER JOIN users ON quiz_results.user_id = users.id
          INNER JOIN quizzes ON quiz_results.quiz_id = quizzes.id
          INNER JOIN subcategories ON quizzes.subcategory_id = subcategories.id
          INNER JOIN categories ON subcategories.category_id = categories.id
          ORDER BY quiz_results.score DESC, quiz_results.time_to_complete ASC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">

    <!-- Libraries for the sortability of the table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function () {
            $('#leaderboard').DataTable(); // Initialize DataTable
        });
    </script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php
    include "../components/header.php";
    ?>

    <main class="flex-grow">
        <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
            <h1 class="text-3xl font-semibold mb-4">Leaderboard</h1>
            <div class="overflow-x-auto">
                <table id="leaderboard" class="stripe hover" style="width:100%">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Subcategory</th>
                            <th class="px-4 py-2">Quiz</th>
                            <th class="px-4 py-2">Score</th>
                            <th class="px-4 py-2">Time to Complete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='border px-4 py-2'>{$row['username']}</td>";
                            echo "<td class='border px-4 py-2'>{$row['category']}</td>";
                            echo "<td class='border px-4 py-2'>{$row['subcategory']}</td>";
                            echo "<td class='border px-4 py-2'>{$row['quiz']}</td>";
                            echo "<td class='border px-4 py-2'>{$row['score']}</td>";
                            echo "<td class='border px-4 py-2'>{$row['time_to_complete']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php
    include "../components/footer.php";
    ?>
</body>

</html>
