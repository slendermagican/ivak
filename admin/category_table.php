<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    echo '<script>window.location.href = "../../ivak/php/index.php";</script>';
    exit();
}

// Fetch all category data from the database
$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);

if (!$categoryResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all category data into an associative array
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category table</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Categories Table</h1>
            <div class="overflow-x-auto">
                <?php if (isset($categories) && is_array($categories) && count($categories) > 0) : ?>
                    <table id="categoriesTable" class="min-w-full table-auto border border-gray-300 bg-white shadow-md rounded-md overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Category</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Image</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Alt Text</th>
                                <th class="p-3 font-bold uppercase text-gray-600 text-center">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category) : ?>
                                <tr class="border-b hover:bg-gray-100 transition duration-300">
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $category['category']; ?></td>
                                    <td class="p-3 text-center border-r">
                                        <img src="<?php echo $category['img_src']; ?>" alt="<?php echo $category['img_alt']; ?>" class="max-w-full h-auto mx-auto rounded-lg" style="max-width: 150px; max-height: 150px;">
                                    </td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $category['img_alt']; ?></td>
                                    <td class="p-3 text-gray-800 text-center"><?php echo $category['description']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-red-500">No category data available.</p>
                <?php endif; ?>
            </div>
            <script>
                $(document).ready(function () {
                    $('#categoriesTable').DataTable();
                });
            </script>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
