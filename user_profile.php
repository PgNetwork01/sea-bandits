<?php
session_start();
include('includes/db.php'); // Include your database connection file

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET last_active = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}



$currentUsername = $_SESSION['username'];
$viewUsername = $_GET['username'];

// Fetch user details
$sql = "SELECT profile_image, bio, email, location FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $viewUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $profileImage = htmlspecialchars($user['profile_image']);
    $bio = htmlspecialchars($user['bio']);
    $email = htmlspecialchars($user['email']);
    $location = htmlspecialchars($user['location']);
} else {
    echo "User not found.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($viewUsername); ?>'s Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .profile-header {
            text-align: center;
            padding: 20px;
            background-color: #2196F3;
            color: white;
        }
        .profile-header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }
        .profile-content {
            padding: 20px;
            background-color: #fff;
            margin: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .chat-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .chat-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="profile-header">
        <img src="<?php echo $profileImage; ?>" alt="Profile Image">
        <h1><?php echo htmlspecialchars($viewUsername); ?></h1>
    </div>

    <div class="profile-content">
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Location:</strong> <?php echo $location; ?></p>
        <p><strong>Bio:</strong> <?php echo $bio; ?></p>
        <a href="chat.php?username=<?php echo urlencode($viewUsername); ?>" class="chat-button">Chat with <?php echo htmlspecialchars($viewUsername); ?></a>
    </div>
</body>
</html>
