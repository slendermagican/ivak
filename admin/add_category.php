<?php
session_start();
include "../db/connection.php";

// Initialize error and success message variables
$errorMsg = '';
$successMsg = '';

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $category = $_POST["category"];
    $img_src = $_POST["img_src"];
    $img_alt = $_POST["img_alt"];
    $description = $_POST["description"];

    // Prepare and execute the SQL statement to insert data into the categories table
    $sql = "INSERT INTO categories (category, img_src, img_alt, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $category, $img_src, $img_alt, $description);

    if ($stmt->execute()) {
        $successMsg = "Category added successfully.";
    } else {
        $errorMsg = "Error adding category: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Category</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="w-1/4">
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Add Category</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <input type="text" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                    <input type="url" name="img_src" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                    <input type="text" name="img_alt" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Category</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>