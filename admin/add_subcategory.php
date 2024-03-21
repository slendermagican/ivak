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
    $subcategory = $_POST["subcategory"];
    $img_src = $_POST["img_src"];
    $img_alt = $_POST["img_alt"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];

    // Prepare and execute the SQL statement to insert data into the subcategories table
    $sql = "INSERT INTO subcategories (subcategory, img_src, img_alt, description, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $subcategory, $img_src, $img_alt, $description, $category_id);

    if ($stmt->execute()) {
        $successMsg = "Subcategory added successfully.";
    } else {
        $errorMsg = "Error adding subcategory: " . $stmt->error;
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
    <title>Admin Dashboard - Add Subcategory</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Add Subcategory</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="subcategory" class="block text-gray-700 text-sm font-bold mb-2">Subcategory:</label>
                    <input type="text" name="subcategory" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <?php
                        // Fetch categories from the database
                        $categoryQuery = "SELECT id, category FROM categories";
                        $categoryResult = $conn->query($categoryQuery);

                        // Populate the dropdown with category options
                        while ($row = $categoryResult->fetch_assoc()) {
                            $categoryId = $row['id'];
                            $categoryName = $row['category'];
                            echo "<option value='$categoryId'>$categoryName</option>";
                            echo "<script> console.log($categoryId);</script>";
                            echo "<script> console.log($categoryName);</script>";
                        }
                        ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Subcategory</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>