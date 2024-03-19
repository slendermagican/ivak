<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    echo '<script>window.location.href = "../../ivak/php/index.php";</script>';
    exit();
}

// Initialize variables
$categoryToRemove = '';
$errorMsg = '';
$successMsg = '';

// Fetch all existing categories from the database
$categoryQuery = "SELECT category FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
$categories = array();
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $categories[] = $row['category'];
}

// Process form submission to remove category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["categoryToRemove"])) {
    // Retrieve and sanitize category name to remove
    $categoryToRemove = mysqli_real_escape_string($conn, $_POST["categoryToRemove"]);

    // Execute SQL query to delete category
    $deleteQuery = "DELETE FROM categories WHERE category = '$categoryToRemove'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $successMsg = 'Category removed successfully.';
    } else {
        $errorMsg = 'Error removing category: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Remove Category</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Remove Category</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="categoryToRemove" class="block text-gray-700 text-sm font-bold mb-2">Category to Remove:</label>
                    <select name="categoryToRemove" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Remove Category</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
