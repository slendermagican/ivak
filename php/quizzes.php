<?php
session_start();
include "../db/connection.php";

if (isset($_GET['subcategory_id'])) {
    $subcategory_id = $_GET['subcategory_id'];

    $quizzesQuery = "SELECT * FROM quizzes WHERE subcategory_id = $subcategory_id";
    $quizzesResult = mysqli_query($conn, $quizzesQuery);

    if (!$quizzesResult) {
        die("Error: " . mysqli_error($conn));
    }

    $quizzes = mysqli_fetch_all($quizzesResult, MYSQLI_ASSOC);
} else {
    header("Location: subcategories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col h-screen overflow-x-hidden">

    <?php
    include "../components/header.php";
    ?>

    <main class="flex-grow px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (isset($quizzes) && is_array($quizzes) && count($quizzes) > 0) { ?>
                <?php foreach ($quizzes as $quiz) { ?>
                    <a href="<?php echo 'questions.php?quiz_id=' . $quiz['id']; ?>" class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 transform hover:scale-105 hover:shadow-xl">
                        <div class="p-4">
                            <h1 class="text-2xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($quiz['quiz']) ?></h1>
                            <img src="<?= htmlspecialchars($quiz['img_src']) ?>" alt="<?= htmlspecialchars($quiz['img_alt']) ?>" class="w-full h-72 object-cover rounded-md mb-2">
                            <p class="text-gray-700"><?= htmlspecialchars($quiz['description']) ?></p>
                        </div>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <p class="text-red-500 text-center">No quizzes found.</p>
            <?php } ?>
        </div>

    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>