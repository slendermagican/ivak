<?php
session_start();
include "../db/connection.php";

// Initialize error variables
$emailError = $usernameError = "";

if (isset($_POST["register"])) {
    $user = mysqli_real_escape_string($conn, $_POST["username"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Check if the username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username='$user' OR email='$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        // Check which field (email or username) is causing the conflict
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['username'] === $user) {
                $usernameError = "Username already exists. Please choose a different one.";
            }
            if ($row['email'] === $email) {
                $emailError = "Email already exists. Please use a different one.";
            }
        }
    } else {
        // Uncomment the following lines for password hashing
        // $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
        
        // Username and email are unique, proceed with the registration
        $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$user', '$pass', '$email')";
        $result = mysqli_query($conn, $insertQuery);

        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }

        $isAdminQuery = "SELECT isAdmin FROM users WHERE username='$user'";
        $result = mysqli_query($conn, $isAdminQuery);

        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }

        //not saving the passoword in the session because it's a sensitive data
        $_SESSION["isAdmin"] = mysqli_fetch_assoc($result)["isAdmin"];
        $_SESSION["username"] = $user;
        // $_SESSION["password"] = $pass;
        $_SESSION["email"] = $email;

        echo "Registration successful!";

        // header("Location: index.php"); прави проблеми на някои браузъри
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <?php
    include "../components/header.php";
    ?>

    <main class="container mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md flex-1">
        <form action="register.php" method="post">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded">
                <p class="text-red-500"><?php echo $emailError; ?></p>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded">
                <p class="text-red-500"><?php echo $usernameError; ?></p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded">
            </div>
            <div>
                <input type="submit" name="register" value="Register" class="bg-green-500 text-white p-2 rounded">
            </div>
            
            <!-- Message for existing users -->
            <div class="mt-4">
                <p>Already have an account? <a href="login.php" class="text-blue-500">Login here</a>.</p>
            </div>
        </form>
    </main>

    <?php
    include "../components/footer.php";
    ?>
</body>

</html>

