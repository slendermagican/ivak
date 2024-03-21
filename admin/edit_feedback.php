<?php
session_start();
include "../db/connection.php";

// Authenticate user and check if they are an admin
if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$feedbackToEdit = '';
$errorMsg = '';
$successMsg = '';
$feedbackData = null;

// Fetch all users from the database
$userQuery = "SELECT id, username FROM users";
$userResult = mysqli_query($conn, $userQuery);
$users = array();
while ($row = mysqli_fetch_assoc($userResult)) {
    $users[] = $row;
}

// Fetch all existing feedbacks from the database
$feedbackQuery = "SELECT id, description, user_id FROM feedback";
$feedbackResult = mysqli_query($conn, $feedbackQuery);
$feedbacks = array();
while ($row = mysqli_fetch_assoc($feedbackResult)) {
    $feedbacks[] = $row;
}

// Process form submission to fetch feedback details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feedbackToEdit"])) {
    $feedbackToEdit = mysqli_real_escape_string($conn, $_POST["feedbackToEdit"]);

    // Fetch feedback details based on feedback ID
    $feedbackQuery = "SELECT * FROM feedback WHERE id = '$feedbackToEdit'";
    $feedbackResult = mysqli_query($conn, $feedbackQuery);

    if (!$feedbackResult) {
        die("Error: " . mysqli_error($conn));
    }

    $feedbackData = mysqli_fetch_assoc($feedbackResult);
}

// Process form submission to update feedback details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateFeedback"])) {
    // Validate and sanitize form data
    $feedbackId = mysqli_real_escape_string($conn, $_POST["feedbackId"]);
    $newDescription = mysqli_real_escape_string($conn, $_POST["description"]);
    $newFeedbackType = mysqli_real_escape_string($conn, $_POST["feedback_type"]);
    $newUserId = mysqli_real_escape_string($conn, $_POST["user_id"]);

    // Update feedback details in the database
    $updateQuery = "UPDATE feedback SET description = '$newDescription', feedback_type = '$newFeedbackType', user_id = '$newUserId' WHERE id = '$feedbackId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'Feedback details updated successfully.';
        $feedbackData['description'] = $newDescription;
        $feedbackData['feedback_type'] = $newFeedbackType;
        $feedbackData['user_id'] = $newUserId;
    } else {
        $errorMsg = 'Error updating feedback details: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Feedback</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit Feedback</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="feedbackToEdit" class="block text-gray-700 text-sm font-bold mb-2">Select Feedback:</label>
                    <select name="feedbackToEdit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Feedback</option>
                        <?php foreach ($feedbacks as $feedback) : ?>
                            <option value="<?php echo htmlspecialchars($feedback['id']); ?>"><?php echo htmlspecialchars($feedback['description']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch Feedback Details</button>
                </div>
            </form>

            <?php if ($feedbackData) : ?>
                <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                        <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required><?php echo $feedbackData['description']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="feedback_type" class="block text-gray-700 text-sm font-bold mb-2">Feedback Type:</label>
                        <select name="feedback_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="problem" <?php echo ($feedbackData['feedback_type'] === 'problem') ? 'selected' : ''; ?>>Problem</option>
                            <option value="request" <?php echo ($feedbackData['feedback_type'] === 'request') ? 'selected' : ''; ?>>Request</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Associated User:</label>
                        <select name="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select User</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?php echo htmlspecialchars($user['id']); ?>" <?php echo ($user['id'] === $feedbackData['user_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($user['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Hidden input for which feedback to be edited -->
                    <input type="hidden" name="feedbackId" value="<?php echo htmlspecialchars($feedbackToEdit); ?>">

                    <div class="text-center">
                        <button type="submit" name="updateFeedback" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Feedback</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
