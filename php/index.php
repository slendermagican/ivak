<?php
session_start();
include "../db/connection.php";

// Check if a search query is provided
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Construct the SQL query with search filter
$quizzesQuery = "SELECT q.*, c.category, sc.subcategory 
                FROM quizzes q
                INNER JOIN subcategories sc ON q.subcategory_id = sc.id
                INNER JOIN categories c ON sc.category_id = c.id
                WHERE q.quiz LIKE '%$searchQuery%'
                OR c.category LIKE '%$searchQuery%'
                OR sc.subcategory LIKE '%$searchQuery%'"; // Add search filter for category and subcategory

$quizzesResult = mysqli_query($conn, $quizzesQuery);

if (!$quizzesResult) {
    die("Error: " . mysqli_error($conn));
}

$quizzes = mysqli_fetch_all($quizzesResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex flex-col h-screen bg-gray-100">
    <?php include "../components/header.php"; ?>

    <main class="flex-grow px-4 py-8">
    <div class="container mx-auto text-center mb-4 w-full">
        <form action="" method="get" class="inline-block">
            <input type="text" name="search" placeholder="Search quizzes, categories, or subcategories" value="<?= htmlspecialchars($searchQuery) ?>" class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring focus:border-blue-300 w-96">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (isset($quizzes) && is_array($quizzes) && count($quizzes) > 0) { ?>
            <?php foreach ($quizzes as $quiz) { ?>
                <a href="<?php echo 'questions.php?quiz_id=' . $quiz['id']; ?>" class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 transform hover:scale-105 hover:shadow-xl">
                    <div class="p-4">
                        <h1 class="text-2xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($quiz['quiz']) ?></h1>
                        <div class="text-sm text-gray-600 mb-2">Category: <span class="text-blue-600"><?= htmlspecialchars($quiz['category']) ?></span></div>
                        <div class="text-sm text-gray-600 mb-4">Subcategory: <span class="text-green-600"><?= htmlspecialchars($quiz['subcategory']) ?></span></div>
                        <img src="<?= htmlspecialchars($quiz['img_src']) ?>" alt="<?= htmlspecialchars($quiz['img_alt']) ?>" class="w-full h-48 object-cover rounded-md mb-2">
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