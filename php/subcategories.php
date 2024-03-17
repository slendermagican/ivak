<?php
session_start();
include "../db/connection.php";

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $subcategoriesQuery = "SELECT * FROM subcategories WHERE category_id = $category_id";
    $subcategoriesResult = mysqli_query($conn, $subcategoriesQuery);

    if (!$subcategoriesResult) {
        die("Error: " . mysqli_error($conn));
    }

    $subcategories = mysqli_fetch_all($subcategoriesResult, MYSQLI_ASSOC);
} else {
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-screen w-screen">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Categories</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col h-screen overflow-x-hidden">

    <?php
    include "../components/header.php";
    ?>

    <main class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 flex-grow">

        <?php if (isset($subcategories) && is_array($subcategories) && count($subcategories) > 0) { ?>
            <?php foreach ($subcategories as $subcategory) { ?>
                <a href="<?php echo 'quizzes.php?subcategory_id=' . $subcategory['id']; ?>">
                    <div class="bg-white p-4 rounded-md shadow-md mb-4 transition transform hover:scale-105 hover:bg-gray-100">
                        <h1 class="text-2xl font-bold mb-2 text-gray-800"><?= htmlspecialchars($subcategory['subcategory']) ?></h1>
                        <img src="<?= htmlspecialchars($subcategory['img_src']) ?>" alt="<?= htmlspecialchars($subcategory['img_alt']) ?>" class="w-full h-64 object-cover rounded-md mb-2">
                        <p class="text-gray-700"><?= htmlspecialchars($subcategory['description']) ?></p>
                    </div>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="text-red-500">No subcategory data available for the selected category.</p>
        <?php } ?>

    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>