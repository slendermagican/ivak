<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
    <!-- Aside (Navigation on the left) -->
    <aside class="bg-gray-200 p-4 w-1/4 rounded-2xl shadow-md overflow-hidden"> <!-- Adjusted class here -->
        <!-- Your navigation content goes here -->
        <?php include "../components/admin_nav.php"; ?>
    </aside>

    <!--Section (Content on the right)-->
    <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Admin Dashboard</h1>
        
    </section>
</main>

    <?php include "../components/footer.php"; ?>

</body>

</html>