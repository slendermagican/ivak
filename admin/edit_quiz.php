<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$quizToEdit = '';
$errorMsg = '';
$successMsg = '';
$quizData = null;

// Fetch all existing quizzes from the database
$quizQuery = "SELECT quiz FROM quizzes";
$quizResult = mysqli_query($conn, $quizQuery);
$quizzes = array();
while ($row = mysqli_fetch_assoc($quizResult)) {
    $quizzes[] = $row['quiz'];
}

// Process form submission to fetch quiz details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["quizToEdit"])) {
    $quizToEdit = mysqli_real_escape_string($conn, $_POST["quizToEdit"]);

    // Fetch quiz details based on quiz name
    $quizQuery = "SELECT * FROM quizzes WHERE quiz = '$quizToEdit'";
    $quizResult = mysqli_query($conn, $quizQuery);

    if (!$quizResult) {
        die("Error: " . mysqli_error($conn));
    }

    $quizData = mysqli_fetch_assoc($quizResult);
}

// Process form submission to update quiz details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateQuiz"])) {
    // Validate and sanitize form data
    $quizToEdit = mysqli_real_escape_string($conn, $_POST["quizToEdit"]);
    $newQuiz = mysqli_real_escape_string($conn, $_POST["quiz"]);
    $newImgSrc = filter_var($_POST["img_src"], FILTER_SANITIZE_URL);
    $newImgAlt = mysqli_real_escape_string($conn, $_POST["img_alt"]);
    $newDescription = mysqli_real_escape_string($conn, $_POST["description"]);

    // Update quiz details in the database
    $updateQuery = "UPDATE quizzes SET quiz = '$newQuiz', img_src = '$newImgSrc', img_alt = '$newImgAlt', description = '$newDescription' WHERE quiz = '$quizToEdit'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'Quiz details updated successfully.';
        $quizData['quiz'] = $newQuiz;
        $quizData['img_src'] = $newImgSrc;
        $quizData['img_alt'] = $newImgAlt;
        $quizData['description'] = $newDescription;
    } else {
        $errorMsg = 'Error updating quiz details: ' . mysqli_error($conn);
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
        <aside class="bg-gray-200 p-4 w-1/4 rounded-2xl shadow-md overflow-hidden">
            <!-- Your navigation content goes here -->
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
    <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit Quiz</h1>

    <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <?php if (!empty($errorMsg)) : ?>
            <p class="text-red-500"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

        <?php if (!empty($successMsg)) : ?>
            <p class="text-green-500"><?php echo $successMsg; ?></p>
        <?php endif; ?>

        <div class="mb-4">
            <label for="quizToEdit" class="block text-gray-700 text-sm font-bold mb-2">Quiz to Edit:</label>
            <select name="quizToEdit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select Quiz</option>
                <?php foreach ($quizzes as $quiz) : ?>
                    <option value="<?php echo htmlspecialchars($quiz); ?>"><?php echo htmlspecialchars($quiz); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch Quiz Details</button>
        </div>
    </form>

    <?php if ($quizData) : ?>
        <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="quiz" class="block text-gray-700 text-sm font-bold mb-2">Quiz:</label>
                <input type="text" name="quiz" value="<?php echo $quizData['quiz']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="img_src" class="block text-gray-700 text-sm font-bold mb-2">Image Source:</label>
                <input type="url" name="img_src" value="<?php echo $quizData['img_src']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="img_alt" class="block text-gray-700 text-sm font-bold mb-2">Image Alt Text:</label>
                <input type="text" name="img_alt" value="<?php echo $quizData['img_alt']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo $quizData['description']; ?></textarea>
            </div>

            <!-- hidden input for which quiz to be edited-->
            <input type="hidden" name="quizToEdit" value="<?php echo htmlspecialchars($quizToEdit); ?>">

            <div class="text-center">
                <button type="submit" name="updateQuiz" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Quiz</button>
            </div>
        </form>
    <?php endif; ?>
</section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>