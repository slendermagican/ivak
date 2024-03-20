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

    <main class="flex-grow px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (isset($subcategories) && is_array($subcategories) && count($subcategories) > 0) { ?>
                <?php foreach ($subcategories as $subcategory) { ?>
                    <a href="<?php echo 'quizzes.php?subcategory_id=' . $subcategory['id']; ?>" class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 transform hover:scale-105 hover:shadow-xl">
                        <div class="p-4">
                            <h1 class="text-2xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($subcategory['subcategory']) ?></h1>
                            <img src="<?= htmlspecialchars($subcategory['img_src']) ?>" alt="<?= htmlspecialchars($subcategory['img_alt']) ?>" class="w-full h-72 object-cover rounded-md mb-2">
                            <p class="text-gray-700"><?= htmlspecialchars($subcategory['description']) ?></p>
                        </div>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <p class="text-red-500 text-center">No subcategories found.</p>
            <?php } ?>
        </div>

    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>