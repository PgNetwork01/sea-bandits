<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with your database connection
     //$conn = new mysqli('sql110.infinityfree.com', 'if0_37169209', 'cfrw4J7aKSIgE', 'if0_37169209_sea_auth');
     $conn = new mysqli('localhost', 'root', 'qwerty1234', 'sea_auth');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['profile_image'] = $user['profile_image']; // Assuming you have a user logo field
            $_SESSION['email'] = $user['email']; // Assuming you have a user email field
            $_SESSION['role'] = $user['role']; // Fetch and set the user's role

            // Check if the user is an admin
            if ($_SESSION['user_role'] == 'member') {
                header("Location: index.php");
                exit(); // Ensure the script stops after the redirect
            } else {
                header("Location: admin.php");
                exit(); // Ensure the script stops after the redirect
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
