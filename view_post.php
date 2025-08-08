<?php
session_start();
include('includes/db.php'); // Include your database connection file

if (!isset($_GET['id'])) {
    header("Location: community.php");
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

$post_id = $_GET['id'];
$sql = "SELECT p.title, p.description, p.content, p.image, u.username, u.profile_image FROM posts p JOIN users u ON p.username = u.username WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Post not found.";
    exit();
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: white;
            margin: 0;
            padding: 20px;
        }
        .post-header {
            display: flex;
            align-items: center;
        }
        .post-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .post-content {
            margin-top: 20px;
        }
        .post-content img {
            width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="post-header">
        <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" alt="User Image">
        <h2><?php echo htmlspecialchars($post['username']); ?> - <?php echo htmlspecialchars($post['title']); ?></h2>
    </div>
    <div class="post-content">
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <?php if (!empty($post['image'])): ?>
            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
        <?php endif; ?>
    </div>
</body>
</html>
