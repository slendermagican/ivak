<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Fetch all feedback data from the database along with usernames
$feedbackQuery = "SELECT feedback.id, users.username, feedback.description, feedback.feedback_type, feedback.timestamp 
                  FROM feedback 
                  INNER JOIN users ON feedback.user_id = users.id";
$feedbackResult = mysqli_query($conn, $feedbackQuery);

if (!$feedbackResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all feedback data into an associative array
$feedbacks = mysqli_fetch_all($feedbackResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Feedback Table</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

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
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Feedback Table</h1>
            <div class="overflow-x-auto">
                <?php if (isset($feedbacks) && is_array($feedbacks) && count($feedbacks) > 0) : ?>
                    <table id="feedbackTable" class="min-w-full table-auto border border-gray-300 bg-white shadow-md rounded-md overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">ID</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Username</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Description</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Feedback Type</th>
                                <th class="p-3 font-bold uppercase text-gray-600 text-center">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedbacks as $feedback) : ?>
                                <tr class="border-b hover:bg-gray-100 transition duration-300">
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $feedback['id']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $feedback['username']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $feedback['description']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $feedback['feedback_type']; ?></td>
                                    <td class="p-3 text-gray-800 text-center"><?php echo $feedback['timestamp']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-red-500">No feedback data available.</p>
                <?php endif; ?>
            </div>
            <script>
                $(document).ready(function() {
                    $('#feedbackTable').DataTable();
                });
            </script>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>