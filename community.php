<?php
session_start();
include('includes/db.php');

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
$sql = "SELECT username, profile_image FROM users WHERE username != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$sql = "SELECT username, profile_image, last_active FROM users WHERE username != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
$onlineThreshold = 2 * 60; // 5 minutes in seconds

while ($row = $result->fetch_assoc()) {
    $row['is_online'] = (time() - strtotime($row['last_active']) <= $onlineThreshold);
    $users[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sea Bandits - Community</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            margin: 0;
            padding: 0;
        }

        .user-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px;
            flex-direction: column;
            width: max-content;
        }

        .user-card {
            background-color: #3636368c;
            border-radius: 5px;
            padding: 14px;
            width: 200px;
            text-align: center;
            display: flex;
            height: 20px;
            align-items: center;
            color: white;
        }

        .user-card:hover {
            cursor: pointer;
        }

        .user-image-container {
            position: relative;
            display: inline-block;
        }

        .user-card img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            padding: 6px;
        }

        .online-indicator {
            position: absolute;
            bottom: 10px;
            right: 6px;
            width: 10px;
            height: 10px;
            background-color: #4CAF50;
            /* Green color */
            border-radius: 50%;
            border: 2px solid white;
            /* Add a white border around the green circle */
        }

        .user-card h3 {
            margin: 10px 0;
            margin-left: 10px;
            /* Added margin to space out the text */
        }

        .view-profile {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .view-profile:hover {
            background-color: #45a049;
        }

        .post-form {
            background-color: #1a1a1ad6;
            padding: 20px;
            border-radius: 5px;
            margin: 20px auto;
            width: 300px;
            color: white;
            display: none;
        }

        .post-form input[type="text"],
        .post-form textarea {
            width: 94%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        .post-form input[type="file"] {
            margin: 10px 0;
            color: white;
        }

        .post-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .post-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .post-list {
            margin: 20px;
            background-color: #282828;
            padding: 20px;
            border-radius: 5px;
        }

        .post-item {
            margin-bottom: 20px;
            padding: 20px;
            border-bottom: 1px solid #444;
        }

        .post-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-item h3,
        .post-item p {
            color: white;
        }

        .grid {
            display: grid;
            grid-template-columns: auto auto;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div onclick="togglePostForm()" style="background-color: #4CAF50;
    border-radius: 30px;
    width: max-content;
    padding: 18px 18px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 999999; position:sticky; top: 80%; float:right; margin-right:4px;"><span class="material-symbols-outlined" style="color: white;">
            edit
        </span></div>

    <div class="post-form" style="top:30%; z-index:100000; position:sticky;">
        <form action="submit_post.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <textarea name="content" placeholder="Write your full content here..." required></textarea> <!-- Full content textarea -->
            <input type="file" name="postImage" accept="image/*">
            <input type="submit" value="Post">
        </form>
    </div>

    <div class="grid">
        <div class="user-list">
            <h2 style="color: white;">Users Online</h2>
            <hr width="100%">
            <?php foreach ($users as $user): ?>
                <div onclick="window.location.href='user_profile.php?username=<?php echo urlencode($user['username']); ?>';" class="user-card">
                    <div style="position: relative;">
                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image">
                        <?php if ($user['is_online']): ?>
                            <div style="position: absolute; bottom: 10px; right: 6px; background: #00bb06; border-radius: 50%; width: 10px; height: 10px; z-index: 1;"></div>
                            <div style="position: absolute;
    bottom: 8px;
    right: 4px;
    background: #2a2a2ae0;
    border-radius: 50%;
    width: 14px;
    height: 14px;
    z-index: 0;"></div>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- position: absolute;
    bottom: 8px;
    right: 4px;
    background: #3fff00;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    z-index: -1;
    box-shadow: 0px 0px 5px 0px #3fff00; -->
        <!-- Form to Post a New Message -->


        <!-- Display Posts -->
        <div class="post-list">
            <h2 style="color: white;">Community Posts</h2>
            <?php
            include('includes/db.php'); // Include your database connection file
            $sql = "SELECT p.id, p.title, p.description, u.username, u.profile_image FROM posts p JOIN users u ON p.username = u.username ORDER BY p.created_at DESC";
            $result = $conn->query($sql);

            while ($post = $result->fetch_assoc()):
            ?>
                <div class="post-item">
                    <div>
                        <img src="<?php echo htmlspecialchars($post['profile_image']); ?>" alt="User Image">
                        <h3><?php echo htmlspecialchars($post['username']); ?> - <?php echo htmlspecialchars($post['title']); ?></h3>
                    </div>
                    <p><?php echo htmlspecialchars($post['description']); ?></p>
                    <a href="view_post.php?id=<?php echo $post['id']; ?>" style="color: #4CAF50;">Read More</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function togglePostForm() {
            const postForm = document.querySelector('.post-form');
            if (postForm.style.display === 'none' || postForm.style.display === '') {
                postForm.style.display = 'flex';
            } else {
                postForm.style.display = 'none';
            }
        }
    </script>
</body>

</html>