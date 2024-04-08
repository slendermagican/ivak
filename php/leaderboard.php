<?php
session_start();
include "../db/connection.php";

// SQL query to retrieve the best result of each user for each quiz(best result= highest score after that lowest time_to_comlete)
$sql = "
SELECT qr.*, u.username, q.quiz, q.img_src AS quiz_img, sc.subcategory, sc.img_src AS subcategory_img, c.category, c.img_src AS category_img
FROM (
    SELECT qr1.*
    FROM quiz_results qr1
    LEFT JOIN quiz_results qr2 ON qr1.user_id = qr2.user_id AND qr1.quiz_id = qr2.quiz_id AND ((qr1.score < qr2.score) OR (qr1.score = qr2.score AND qr1.time_to_complete > qr2.time_to_complete) OR (qr1.score = qr2.score AND qr1.time_to_complete = qr2.time_to_complete AND qr1.timestamp > qr2.timestamp))
    WHERE qr2.user_id IS NULL
) qr
INNER JOIN users u ON qr.user_id = u.id
INNER JOIN quizzes q ON qr.quiz_id = q.id
INNER JOIN subcategories sc ON q.subcategory_id = sc.id
INNER JOIN categories c ON sc.category_id = c.id
ORDER BY u.username, q.quiz, qr.score DESC, qr.time_to_complete ASC, qr.timestamp ASC
";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="/styles/style.css">

    <!-- Libraries for the sortability of the table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#leaderboard').DataTable(); // Initialize DataTable
        });
    </script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>
    <main class="flex-grow">
        <div class="container mx-auto mt-8">
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
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td class='border px-4 py-2'><?= $row['username'] ?></td>
                            <td class='border px-4 py-2'><img src='<?= $row['category_img'] ?>' alt='Category Icon' class='w-16 h-9 mr-2 inline-block align-middle'><?= $row['category'] ?></td>
                            <td class='border px-4 py-2'><img src='<?= $row['subcategory_img'] ?>' alt='Subcategory Icon' class='w-16 h-9 mr-2 inline-block align-middle'><?= $row['subcategory'] ?></td>
                            <td class='border px-4 py-2'><img src='<?= $row['quiz_img'] ?>' alt='Quiz Icon' class='w-16 h-9 mr-2 inline-block align-middle'><?= $row['quiz'] ?></td>
                            <td class='border px-4 py-2'><?= $row['score'] ?></td>
                            <td class='border px-4 py-2'><?= $row['time_to_complete'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include "../components/footer.php"; ?>
</body>

</html>