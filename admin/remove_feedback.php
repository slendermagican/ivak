<?php
session_start();
include "../db/connection.php";

// Authenticate user and check if they are an admin
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$errorMsg = '';
$successMsg = '';
$feedbacks = [];

// Fetch all existing feedbacks from the database
$feedbackQuery = "SELECT id, description FROM feedback";
$feedbackResult = mysqli_query($conn, $feedbackQuery);
if ($feedbackResult) {
    while ($row = mysqli_fetch_assoc($feedbackResult)) {
        $feedbacks[] = $row;
    }
} else {
    $errorMsg = 'Error fetching feedbacks: ' . mysqli_error($conn);
}

// Process form submission to remove feedback
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feedbackToRemove"])) {
    $feedbackToRemove = mysqli_real_escape_string($conn, $_POST["feedbackToRemove"]);

    // Remove feedback from the database
    $deleteQuery = "DELETE FROM feedback WHERE id = '$feedbackToRemove'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $successMsg = 'Feedback removed successfully.';
        // Remove the feedback from the feedbacks array to reflect the change immediately
        foreach ($feedbacks as $key => $feedback) {
            if ($feedback['id'] == $feedbackToRemove) {
                unset($feedbacks[$key]);
                break;
            }
        }
    } else {
        $errorMsg = 'Error removing feedback: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Remove Feedback</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="w-1/4">
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Remove Feedback</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="feedbackToRemove" class="block text-gray-700 text-sm font-bold mb-2">Select Feedback to Remove:</label>
                    <select name="feedbackToRemove" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Feedback</option>
                        <?php foreach ($feedbacks as $feedback) : ?>
                            <option value="<?php echo htmlspecialchars($feedback['id']); ?>"><?php echo htmlspecialchars($feedback['description']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Remove Feedback</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>