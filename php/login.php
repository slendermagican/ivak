<?php
session_start();
include "../db/connection.php";

$loginFeedback = "";

if (isset($_POST["login"])) {
    $loginIdentifier = $_POST["identifier"];
    $password = $_POST["password"];

    // Prepare statement to prevent SQL injection
    $loginQuery = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = mysqli_prepare($conn, $loginQuery);
    mysqli_stmt_bind_param($stmt, "ss", $loginIdentifier, $loginIdentifier);
    mysqli_stmt_execute($stmt);

    // Get result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $userData["password"])) {
            // Password is correct, set session variables
            $_SESSION["isAdmin"] = $userData["isAdmin"];
            $_SESSION["user_id"] = $userData["id"];

            session_write_close();

            // Redirect to index.php after successful login
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect
            $loginFeedback = "Invalid password. Please try again.";
        }
    } else {
        // Username or email not found
        $loginFeedback = "Invalid username/email. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <main class="container mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md flex-1">
    <h1 class="text-3xl font-bold mb-4">Login</h1>
    <p class="text-gray-600 mb-8">Login to access your account.</p>
        <!-- Display login feedback here -->
        <?php if (!empty($loginFeedback)) : ?>
            <div class="mb-4 text-red-500">
                <?php echo $loginFeedback; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="identifier" class="block text-gray-700 font-bold mb-2">Username or Email:</label>
                <input type="text" id="identifier" name="identifier" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded">
            </div>
            <div class="flex justify-between items-center mb-4">
                <div>
                    <a href="forgot_password.php" class="text-blue-500">Forgotten Password?</a>
                </div>
                <div>
                    <input type="submit" name="login" value="Login" class="bg-blue-500 text-white p-2 w-24 rounded">
                </div>
            </div>
        </form>

        <div class="text-center">
            <p>Don't have an account? <a href="register.php" class="text-blue-500">Create one</a></p>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>
</body>
</html>
