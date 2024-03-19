<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    echo '<script>window.location.href = "../../ivak/php/index.php";</script>';
    exit();
}

// Initialize variables
$categoryToEdit = '';
$errorMsg = '';
$successMsg = '';
$categoryData = null;

// Fetch all existing categories from the database
$categoryQuery = "SELECT category FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
$categories = array();
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $categories[] = $row['category'];
}

// Process form submission to fetch category details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["categoryToEdit"])) {
    $categoryToEdit = mysqli_real_escape_string($conn, $_POST["categoryToEdit"]);

    // Fetch category details based on category name
    $categoryQuery = "SELECT * FROM categories WHERE category = '$categoryToEdit'";
    $categoryResult = mysqli_query($conn, $categoryQuery);

    if (!$categoryResult) {
        die("Error: " . mysqli_error($conn));
    }

    $categoryData = mysqli_fetch_assoc($categoryResult);
}

// Process form submission to update category details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateCategory"])) {
    // Validate and sanitize form data
    $categoryToEdit = mysqli_real_escape_string($conn, $_POST["categoryToEdit"]);
    $newCategory = mysqli_real_escape_string($conn, $_POST["category"]);
    $newImgSrc = filter_var($_POST["img_src"], FILTER_SANITIZE_URL);
    $newImgAlt = mysqli_real_escape_string($conn, $_POST["img_alt"]);
    $newDescription = mysqli_real_escape_string($conn, $_POST["description"]);

    // Update category details in the database
    $updateQuery = "UPDATE categories SET category = '$newCategory', img_src = '$newImgSrc', img_alt = '$newImgAlt', description = '$newDescription' WHERE category = '$categoryToEdit'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'Category details updated successfully.';
        $categoryData['category'] = $newCategory;
        $categoryData['img_src'] = $newImgSrc;
        $categoryData['img_alt'] = $newImgAlt;
        $categoryData['description'] = $newDescription;
    } else {
        $errorMsg = 'Error updating category details: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Category</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit Category</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

               
                <div class="mb-4">
                    <label for="categoryToEdit" class="block text-gray-700 text-sm font-bold mb-2">Category to Remove:</label>
                    <select name="categoryToEdit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch Category Details</button>
                </div>
            </form>

            <?php if ($categoryData) : ?>
                <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                        <input type="text" name="category" value="<?php echo $categoryData['category']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                        <input type="url" name="img_src" value="<?php echo $categoryData['img_src']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                        <input type="text" name="img_alt" value="<?php echo $categoryData['img_alt']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                        <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo $categoryData['description']; ?></textarea>
                    </div>
                    <!-- hidden input for which category to be edited-->
                    <input type="hidden" name="categoryToEdit" value="<?php echo htmlspecialchars($categoryToEdit); ?>">

                    <div class="text-center">
                        <button type="submit" name="updateCategory" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Category</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
