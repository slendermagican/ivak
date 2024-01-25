<!-- subcategories_page.php -->
<?php
session_start();
include "../db/connection.php";
// Fetch all subcategories data from the database based on the selected categoryI
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    echo $category;

    $subcategoriesQuery = "SELECT * FROM subcategories WHERE category = '$category'";
    $subcategoriesResult = mysqli_query($conn, $subcategoriesQuery);

    if (!$subcategoriesResult) {
        die("Error: " . mysqli_error($conn));
    }

    $subcategories = mysqli_fetch_all($subcategoriesResult, MYSQLI_ASSOC);
} else {
    // Redirect or handle the case when no category is provided
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategory</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php
    include "../components/header.php";
    ?>

    <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        <?php if (isset($subcategories) && is_array($subcategories) && count($subcategories) > 0) : ?>

            <<?php foreach ($subcategories as $subcategory) : ?>\
                <a href="<?php echo 'subcathegories.php?category=' . $subcategory['category']; ?>">
                    <div class="bg-gray-200 p-4 rounded-md shadow-md clickable-thumbnail">
                        <h1 class="text-xl font-bold mb-2"><?= htmlspecialchars( $subcategory['category']) ?></h1>
                        <img src="<?= htmlspecialchars($subcategory['img_src']) ?>" alt="<?= htmlspecialchars($subcategory['img_alt']) ?>" class="w-full h-auto rounded-md mb-2">
                        <p class="text-gray-700"><?= htmlspecialchars($subcategory['description']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>

        <?php else : ?>
            <p class="text-red-500">No subcategory data available for the selected category.</p>
        <?php endif; ?>

    </main>

    <?php
    include "../components/footer.php";
    ?>

</body>

</html>