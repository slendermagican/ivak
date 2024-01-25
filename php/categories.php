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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php
    include "../components/header.php";
    ?>

    <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        <?php if (isset($categories) && is_array($categories) && count($categories) > 0) { ?>

            <?php foreach ($categories as $category) { ?>
                <a href="<?php echo 'subcategories.php?category_id=' . $category['id']; ?>">
                    <div class="bg-gray-200 p-4 rounded-md shadow-md">
                        <h1 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($category['category']) ?></h1>
                        <img src="<?php echo htmlspecialchars($category['img_src']) ?>" alt="<?php echo htmlspecialchars($category['img_alt']) ?>" class="w-full h-auto rounded-md mb-2">
                        <p class="text-gray-700"><?php echo $category['description'] ?></p>
                    </div>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="text-red-500">No category data available.</p>
        <?php } ?>

    </main>

    <?php include "../components/footer.php";?>

</body>

</html>
