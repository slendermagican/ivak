<?php
session_start();
include "../db/connection.php";

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit; // Stop further execution
}

$success_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Validate and sanitize input
    $user_id = $_SESSION["user_id"]; // Assuming you have a user session
    $description = htmlspecialchars($_POST["description"]);
    $feedback_type = htmlspecialchars($_POST["feedback_type"]);

    // Insert feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, description, feedback_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $description, $feedback_type);
    if ($stmt->execute()) {
        $success_message = "Feedback submitted successfully!";
    } else {
        $success_message = "Failed to submit feedback. Please try again.";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <main class="flex-grow">
        <div class="container mx-auto mt-8">
            <h1 class="text-2xl font-semibold mb-4">Feedback Form</h1>
            <?php if ($success_message !== "") : ?>
                <div class="mb-4 text-green-500"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="description" class="block">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50" class="w-full px-3 py-2 border rounded-md"><?php if (isset($_POST["description"])) {
                                                                                                                                    echo $_POST["description"];
                                                                                                                                } ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="feedback_type" class="block">Feedback Type:</label>
                    <select id="feedback_type" name="feedback_type" class="w-full px-3 py-2 border rounded-md">
                        <option value="problem">Problem</option>
                        <option value="request">Request</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit Feedback</button>
            </form>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>
</body>

</html>