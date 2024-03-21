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
    $quiz = $_POST["quiz"];
    $img_src = $_POST["img_src"];
    $img_alt = $_POST["img_alt"];
    $description = $_POST["description"];
    $subcategory_id = $_POST["subcategory_id"];

    // Prepare and execute the SQL statement to insert data into the quizzes table
    $sql = "INSERT INTO quizzes (quiz, img_src, img_alt, description, subcategory_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssi", $quiz, $img_src, $img_alt, $description, $subcategory_id);
        if ($stmt->execute()) {
            $successMsg = "Quiz added successfully.";
        } else {
            $errorMsg = "Error adding quiz: " . $stmt->error;
        }
        // Close the statement
        $stmt->close();
    } else {
        $errorMsg = "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Quiz</title>
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

        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Add Quiz</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="quiz" class="block text-gray-700 text-sm font-bold mb-2">Quiz:</label>
                    <input type="text" name="quiz" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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
                    <label for="subcategory_id" class="block text-gray-700 text-sm font-bold mb-2">Subcategory:</label>
                    <select name="subcategory_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <?php
                        // Fetch subcategories from the database
                        $subcategoryQuery = "SELECT id, subcategory FROM subcategories";
                        $subcategoryResult = $conn->query($subcategoryQuery);

                        // Populate the dropdown with subcategory options
                        while ($row = $subcategoryResult->fetch_assoc()) {
                            $subcategoryId = $row['id'];
                            $subcategoryName = $row['subcategory'];
                            echo "<option value='$subcategoryId'>$subcategoryName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Quiz</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>