<?php
session_start();
include "../db/connection.php";

$categoriesQuery = "SELECT * FROM categories";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

if (!$categoriesResult) {
    die("Error: " . mysqli_error($conn));
}

$categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" class="h-screen w-screen">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Categories</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen overflow-x-hidden">

    <?php
    include "../components/header.php";
    ?>

    <main class="flex-grow px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (isset($categories) && is_array($categories) && count($categories) > 0) { ?>
                <?php foreach ($categories as $category) { ?>
                    <a href="<?php echo 'subcategories.php?category_id=' . $category['id']; ?>" class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 transform hover:scale-105 hover:shadow-xl">
                        <div class="p-4">
                            <h1 class="text-2xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($category['category']) ?></h1>
                            <img src="<?= htmlspecialchars($category['img_src']) ?>" alt="<?= htmlspecialchars($category['img_alt']) ?>" class="w-full h-72 object-cover rounded-md mb-2">
                            <p class="text-gray-700"><?= htmlspecialchars($category['description']) ?></p>
                        </div>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <p class="text-red-500 text-center">No categories found.</p>
            <?php } ?>
        </div>

    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>