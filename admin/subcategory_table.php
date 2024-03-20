<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Fetch data from both subcategories and categories tables
$subCategoryQuery = "SELECT subcategories.*, categories.category AS category_name
                    FROM subcategories
                    LEFT JOIN categories ON subcategories.category_id = categories.id";

$subCategoryResult = mysqli_query($conn, $subCategoryQuery);

if (!$subCategoryResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all subcategory data into an associative array
$subcategories = mysqli_fetch_all($subCategoryResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategory Table</title>
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
        <section class="flex-grow p-4 w-3/4 bg-white rounded-2xl overflow-x-auto shadow-md">
            <h1 class="text-3xl font-bold mb-4 text-gray-800 text-center">Subcategories Table</h1>
            <div class="overflow-x-auto">
                <?php if (isset($subcategories) && is_array($subcategories) && count($subcategories) > 0) : ?>
                    <table id="subcategoriesTable" class="min-w-full table-auto border border-gray-300 bg-white shadow-md rounded-md overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Subcategory</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Image</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Alt Text</th>
                                <th class="p-3 font-bold uppercase text-gray-600 border-r text-center">Description</th>
                                <th class="p-3 font-bold uppercase text-gray-600 text-center">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subcategories as $subcategory) : ?>
                                <tr class="border-b hover:bg-gray-100 transition duration-300">
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $subcategory['subcategory']; ?></td>
                                    <td class="p-3 text-center border-r">
                                        <img src="<?php echo $subcategory['img_src']; ?>" alt="<?php echo $subcategory['img_alt']; ?>" class="max-w-full h-auto mx-auto rounded-lg" style="max-width: 150px; max-height: 150px;">
                                    </td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $subcategory['img_alt']; ?></td>
                                    <td class="p-3 text-gray-800 border-r text-center"><?php echo $subcategory['description']; ?></td>
                                    <td class="p-3 text-gray-800 text-center"><?php echo $subcategory['category_name']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-red-500">No subcategory data available.</p>
                <?php endif; ?>
            </div>
            <script>
                $(document).ready(function() {
                    $('#subcategoriesTable').DataTable();
                });
            </script>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>