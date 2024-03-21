<?php
session_start();
include "../db/connection.php";

$resetFeedback = "";

if (isset($_POST["reset_request"])) {
    $loginIdentifier = mysqli_real_escape_string($conn, $_POST["identifier"]);

    // Check if the provided identifier (username or email) exists in the database
    $checkQuery = "SELECT * FROM users WHERE username='$loginIdentifier' OR email='$loginIdentifier'";
    $result = mysqli_query($conn, $checkQuery);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique token (you can use a more secure method)
        $token = md5(uniqid(rand(), true));

        // Store the token in the database
        $updateQuery = "UPDATE users SET reset_token='$token' WHERE username='$loginIdentifier' OR email='$loginIdentifier'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            die("Error: " . mysqli_error($conn));
        }

        // Redirect to the reset password page with the token
        header("Location: reset_password.php?token=$token");
        exit();
    } else {
        $resetFeedback = "No account found with the provided username or email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>

<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <main class="container flex-grow mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md">
        <h1 class="text-3xl font-bold mb-4">Forgot Password</h1>
        <p class="text-gray-600 mb-8">Please enter your username or email to reset your password.</p>

        <!-- Display reset feedback here -->
        <?php if (!empty($resetFeedback)) : ?>
            <div class="mb-4 text-red-500">
                <?php echo $resetFeedback; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="identifier" class="block text-gray-700 font-bold mb-2">Username or Email:</label>
                <input type="text" id="identifier" name="identifier" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <input type="submit" name="reset_request" value="Reset Password" class="bg-blue-500 text-white p-2 rounded">
            </div>
        </form>

        <div class="text-center">
            <p>Remember your password? <a href="login.php" class="text-blue-500">Login here</a></p>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>
</body>

</html>