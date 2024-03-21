<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$subcategoryToEdit = '';
$errorMsg = '';
$successMsg = '';
$subcategoryData = null;

// Fetch all existing categories from the database
$subcategoryQuery = "SELECT subcategory FROM subcategories";
$subcategoryResult = mysqli_query($conn, $subcategoryQuery);
$subcategories = array();
while ($row = mysqli_fetch_assoc($subcategoryResult)) {
    $subcategories[] = $row['subcategory'];
}

// Process form submission to fetch category details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subcategoryToEdit"])) {
    $subcategoryToEdit = mysqli_real_escape_string($conn, $_POST["subcategoryToEdit"]);

    // Fetch category details based on category name
    $subcategoryQuery = "SELECT * FROM subcategories WHERE subcategory = '$subcategoryToEdit'";
    $subcategoryResult = mysqli_query($conn, $subcategoryQuery);

    if (!$subcategoryResult) {
        die("Error: " . mysqli_error($conn));
    }

    $subcategoryData = mysqli_fetch_assoc($subcategoryResult);
}

// Process form submission to update category details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateSubcategory"])) {
    // Validate and sanitize form data
    $subcategoryToEdit = mysqli_real_escape_string($conn, $_POST["subcategoryToEdit"]);
    $newsubCategory = mysqli_real_escape_string($conn, $_POST["subcategory"]);
    $newImgSrc = filter_var($_POST["img_src"], FILTER_SANITIZE_URL);
    $newImgAlt = mysqli_real_escape_string($conn, $_POST["img_alt"]);
    $newDescription = mysqli_real_escape_string($conn, $_POST["description"]);

    // Update category details in the database
    $updateQuery = "UPDATE subcategories SET subcategory = '$newsubCategory', img_src = '$newImgSrc', img_alt = '$newImgAlt', description = '$newDescription' WHERE subcategory = '$subcategoryToEdit'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'Subcategory details updated successfully.';
        $subcategoryData['subcategory'] = $newsubCategory;
        $subcategoryData['img_src'] = $newImgSrc;
        $subcategoryData['img_alt'] = $newImgAlt;
        $subcategoryData['description'] = $newDescription;
    } else {
        $errorMsg = 'Error updating subcategory details: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Subcategory</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit Subcategory</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

               
                <div class="mb-4">
                    <label for="subategoryToEdit" class="block text-gray-700 text-sm font-bold mb-2">Subcategory to Edit:</label>
                    <select name="subcategoryToEdit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Subcategory</option>
                        <?php foreach ($subcategories as $subcategory) : ?>
                            <option value="<?php echo htmlspecialchars($subcategory); ?>"><?php echo htmlspecialchars($subcategory); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch Subcategory Details</button>
                </div>
            </form>

            <?php if ($subcategoryData) : ?>
                <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="subcategory" class="block text-gray-700 text-sm font-bold mb-2">Subcategory:</label>
                        <input type="text" name="subcategory" value="<?php echo $subcategoryData['subcategory']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                        <input type="url" name="img_src" value="<?php echo $subcategoryData['img_src']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                        <input type="text" name="img_alt" value="<?php echo $subcategoryData['img_alt']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                        <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo $subcategoryData['description']; ?></textarea>
                    </div>
                    <!-- hidden input for which category to be edited-->
                    <input type="hidden" name="subcategoryToEdit" value="<?php echo htmlspecialchars($subcategoryToEdit); ?>">

                    <div class="text-center">
                        <button type="submit" name="updateSubcategory" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Subcategory</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
