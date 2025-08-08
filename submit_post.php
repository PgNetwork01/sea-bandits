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

$username = $_SESSION['username'];
$title = $_POST['title'];
$description = $_POST['description'];
$content = $_POST['content']; // Full content
$imagePath = null;

if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] == 0) {
    $target_dir = "uploads/";
    $original_filename = basename($_FILES["postImage"]["name"]);
    $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

    $unique_filename = uniqid() . "_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $unique_filename;

    if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file)) {
        $imagePath = $target_file;
    }
}

$sql = "INSERT INTO posts (username, title, description, content, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $title, $description, $content, $imagePath);

if ($stmt->execute()) {
    header("Location: community.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
