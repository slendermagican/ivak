<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Fetch all user data from the database
$userQuery = "SELECT * FROM users";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all user data into an associative array
$users = mysqli_fetch_all($userResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Libraries for the sortability of the table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="bg-gray-200 p-4 w-1/4 rounded-2xl shadow-md overflow-hidden">
            <!-- Your navigation content goes here -->
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Users Table</h1>
            <div class="overflow-x-auto">
                <?php if (isset($users) && is_array($users) && count($users) > 0) : ?>
                    <table id="usersTable" class="min-w-full table-auto border border-gray-300 bg-white shadow-md rounded-md overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Username</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Password</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Email</th>
                                <th class="p-3 font-bold uppercase text-gray-600 text-center">Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr class="border-b hover:bg-gray-100 transition duration-300">
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $user['username']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $user['password']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $user['email']; ?></td>
                                    <td class="p-3 text-gray-800 text-center">
                                        <span class="inline-block px-2 py-1 rounded-full text-white <?php echo ($user['isAdmin'] == 1) ? 'bg-green-500' : 'bg-red-500'; ?>">
                                            <?php echo ($user['isAdmin'] == 1) ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-red-500">No user data available.</p>
                <?php endif; ?>
            </div>
            <script>
                $(document).ready(function () {
                    $('#usersTable').DataTable();
                });
            </script>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
