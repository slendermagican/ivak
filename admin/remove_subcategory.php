<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$subcategoryToRemove = '';
$errorMsg = '';
$successMsg = '';

// Fetch all existing subcategories from the database
$subcategoryQuery = "SELECT id, subcategory FROM subcategories";
$subcategoryResult = mysqli_query($conn, $subcategoryQuery);
$subcategories = array();
while ($row = mysqli_fetch_assoc($subcategoryResult)) {
    $subcategories[$row['id']] = $row['subcategory'];
}

// Process form submission to remove subcategory
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subcategoryToRemove"])) {
    // Retrieve and sanitize subcategory ID to remove
    $subcategoryToRemove = mysqli_real_escape_string($conn, $_POST["subcategoryToRemove"]);

    // Execute SQL query to delete subcategory
    $deleteQuery = "DELETE FROM subcategories WHERE id = '$subcategoryToRemove'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $successMsg = 'Subcategory removed successfully.';
    } else {
        $errorMsg = 'Error removing subcategory: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Remove Subcategory</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Remove Subcategory</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="subcategoryToRemove" class="block text-gray-700 text-sm font-bold mb-2">Subcategory to Remove:</label>
                    <select name="subcategoryToRemove" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Subcategory</option>
                        <?php foreach ($subcategories as $id => $subcategory) : ?>
                            <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($subcategory); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Remove Subcategory</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>