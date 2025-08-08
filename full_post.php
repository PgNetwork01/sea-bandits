<?php
// Include the database connection file
include('includes/db.php');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET last_active = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

// Get the post ID from the URL
$post_id = $_GET['id'];

// Prepare a SQL query to fetch the full content
$sql = "SELECT title, content FROM blog_posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the post exists
if ($result->num_rows > 0) {
    // Fetch the title and content
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $content = $row['content'];
} else {
    $title = "Post not found";
    $content = "";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
   <style>
    body {
        color: white;
        background-color: #111;
    }
   </style>
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <p><?php echo $content; ?></p>
</body>
</html>
