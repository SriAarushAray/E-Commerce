<?php
// Configuration
$db_host = 'localhost:3306';
$db_username = 'root';
$db_password = '';
$db_name = 'user_database';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Register user
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password!= $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password_hash);
        $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Redirect to login page
        header("Location: login.php");
        exit;
    }
}

// Login user
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Retrieve user from database
        $sql = "SELECT * FROM users WHERE username =?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful, redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}

// Close connection
$conn->close();
?>

<!-- Registration form -->
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h2>Register</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password"><br><br>
    <input type="submit" name="register" value="Register">
    <?php if (isset($error)) { echo "<p>$error</p>"; }?>
</form>

<!-- Login form -->
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h2>Login</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
    <?php if (isset($error)) { echo "<p>$error</p>"; }?>
</form>