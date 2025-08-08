<?php
session_start();
include('includes/db.php'); // Include your database connection file

// Redirect to login page if the session is not set
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

// Retrieve the username from the session
$username = $_SESSION['username'];

// Prepare and execute the query to fetch user details
$sql = "SELECT profile_image, bio, email, location FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $profileImage = htmlspecialchars($user['profile_image']);
    $bio = htmlspecialchars($user['bio']);
    $email = htmlspecialchars($user['email']);
    $location = htmlspecialchars($user['location']);
} else {
    $profileImage = 'default_logo.png'; // Fallback if no profile image is found
    $bio = 'This user has not added a bio yet.';
    $email = 'Not provided';
    $location = 'Not provided';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?> - Sea Bandits Profile</title>
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
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 10px;
            padding: 20px;
        }
        .grid-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .item1 {
            grid-column: 1 / 2;
        }
        .item2, .item3, .item4 {
            grid-column: 2 / 3;
        }
        .grid-item h2 {
            margin-top: 0;
        }
        .settings-button {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .settings-button:hover {
            background-color: #45a049;
        }
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .settings-form label {
            display: block;
            margin-top: 10px;
        }
        .settings-form input[type="text"],
        .settings-form input[type="email"],
        .settings-form input[type="password"],
        .settings-form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .settings-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .settings-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Logo" />
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p><?php echo $bio; ?></p>
        <button id="accountSettingsBtn" class="settings-button">Account Settings</button>
    </div>

    <div class="grid-container">
        <!-- User Details Section -->
        <div class="grid-item item1">
            <h2>Account Details</h2>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Bio:</strong> <?php echo $bio; ?></p>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid-item item2">
            <h2>Recent Activity</h2>
            <p>No recent activity.</p> <!-- Placeholder for dynamic content -->
        </div>

        <!-- User Posts Section -->
        <div class="grid-item item3">
            <h2>Your Posts</h2>
            <p>You have not posted anything yet.</p> <!-- Placeholder for dynamic content -->
        </div>

        <!-- Achievements Section -->
        <div class="grid-item item4">
            <h2>Achievements</h2>
            <p>No achievements unlocked.</p> <!-- Placeholder for dynamic content -->
        </div>
    </div>

    <!-- The Modal -->
    <div id="accountSettingsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Account Settings</h2>
            <form method="POST" action="update_account.php" enctype="multipart/form-data" class="settings-form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">

                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="5" cols="50"><?php echo htmlspecialchars($bio); ?></textarea>

                <label for="logo">Profile Image:</label>
                <input type="file" id="logo" name="logo">

                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("accountSettingsModal");

        // Get the button that opens the modal
        var btn = document.getElementById("accountSettingsBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
