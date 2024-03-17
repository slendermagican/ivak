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

    <main class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 flex-grow">

        <?php if (isset($quizzes) && is_array($quizzes) && count($quizzes) > 0) { ?>
            <?php foreach ($quizzes as $quiz) { ?>
                <a href="<?php echo 'questions.php?quiz_id=' . $quiz['id']; ?>">
                    <div class="bg-white p-4 rounded-md shadow-md mb-4 transition transform hover:scale-105 hover:bg-gray-100">
                        <h1 class="text-2xl font-bold mb-2 text-gray-800"><?= htmlspecialchars($quiz['quiz']) ?></h1>
                        <img src="<?= htmlspecialchars($quiz['img_src']) ?>" alt="<?= htmlspecialchars($quiz['img_alt']) ?>" class="w-full h-64 object-cover rounded-md mb-2">
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
