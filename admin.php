<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not an admin, redirect them to another page
    header("Location: index.php"); // Replace 'index.php' with the page you want to redirect to
    exit(); // Stop further script execution
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET last_active = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    // Insert the new post into the blog_posts table
    $sql = "INSERT INTO blog_posts (title, content, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $description);

    if ($stmt->execute()) {
        echo "Post added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <div id="admin-container">

        <div class="form">
            <h2>Admin Dashboard</h2>

            <h3>New Blog Post</h3>
            <form method="POST" action="admin.php">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" style="padding: 4px;" required><br>

                <label for="content">Content:</label><br>
                <textarea id="content" name="content" rows="10" style="padding: 4px;" required></textarea><br>
                
                <label for="description">Description: (MAX 15 words)</label><br>
                <textarea id="description" name="description" rows="10" style="padding: 4px;" rows="5" cols="50" required></textarea><br>
                <p id="wordCount">0 / 15 words</p>
                <br>
                <button type="submit">Add Post</button>
            </form>

            <div style="float: right;">
                <h2>Change User Role</h2>
                <form action="change_role.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                        <option value="member">Member</option>
                    </select>

                    <button type="submit">Change Role</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('description');
            const wordCountDisplay = document.getElementById('wordCount');
            const maxWords = 15;

            textarea.addEventListener('input', function () {
                const words = textarea.value.trim().split(/\s+/);
                const wordCount = words.filter(word => word.length > 0).length;

                if (wordCount > maxWords) {
                    textarea.value = words.slice(0, maxWords).join(" ");
                }

                wordCountDisplay.textContent = `${wordCount} / ${maxWords} words`;
            });
        });
    </script>
</body>

</html>