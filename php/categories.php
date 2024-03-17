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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col h-screen overflow-x-hidden">

    <?php
    include "../components/header.php";
    ?>


    <main class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 flex-grow">

        <?php if (isset($categories) && is_array($categories) && count($categories) > 0) { ?>

            <?php foreach ($categories as $category) { ?>
                <a href="<?php echo 'subcategories.php?category_id=' . $category['id']; ?>">
                    <div class="bg-white p-4 rounded-md shadow-md mb-4 transition transform hover:scale-105 hover:bg-gray-100">
                        <h1 class="text-2xl font-bold mb-2 text-gray-800"><?php echo htmlspecialchars($category['category']) ?></h1>
                        <img src="<?php echo htmlspecialchars($category['img_src']) ?>" alt="<?php echo htmlspecialchars($category['img_alt']) ?>" class="w-full h-64 object-cover rounded-md mb-2">
                        <p class="text-gray-700"><?php echo $category['description'] ?></p>
                    </div>
                </a>
            <?php } ?>
            
        <?php } else { ?>
            <p class="text-red-500">No category data available.</p>
        <?php } ?>

    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>