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
    header("Location: subcategories_page.php");
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

<body class="bg-gray-100">

    <?php
    include "../components/header.php";
    ?>

    <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        <?php if (isset($quizzes) && is_array($quizzes) && count($quizzes) > 0) { ?>
            <?php foreach ($quizzes as $quiz) { ?>
                <a href="#">
                    <div class="bg-gray-200 p-4 rounded-md shadow-md clickable-thumbnail">
                        <h1 class="text-xl font-bold mb-2"><?= htmlspecialchars($quiz['quiz']) ?></h1>
                        <img src="<?= htmlspecialchars($quiz['img_src']) ?>" alt="<?= htmlspecialchars($quiz['img_alt']) ?>" class="w-full h-auto rounded-md mb-2">
                        <p class="text-gray-700"><?= htmlspecialchars($quiz['description']) ?></p>
                    </div>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="text-red-500">No quizzes data available for the selected subcategory.</p>
        <?php } ?>

    </main>

    <?php include "../components/footer.php";?>

</body>

</html>
