<?php
session_start();
include('includes/db.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Initialize an array to hold error messages
$errors = [];

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Update email
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            $sql = "UPDATE users SET email = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $username);
            if (!$stmt->execute()) {
                $errors[] = "Failed to update email.";
            }
            $stmt->close();
        }
    }

    // Update password
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $username);
        if (!$stmt->execute()) {
            $errors[] = "Failed to update password.";
        }
        $stmt->close();
    }

    // Update bio
    if (isset($_POST['bio']) && !empty($_POST['bio'])) {
        $bio = htmlspecialchars($_POST['bio']);
        $sql = "UPDATE users SET bio = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $bio, $username);
        if (!$stmt->execute()) {
            $errors[] = "Failed to update bio.";
        }
        $stmt->close();
    }

    // Update profile image
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $targetDir = "uploads/"; // Directory where images will be uploaded
        $targetFile = $targetDir . basename($_FILES["logo"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (5MB max)
            if ($_FILES["logo"]["size"] <= 5000000) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                        // Update database with new profile image path
                        $sql = "UPDATE users SET profile_image = ? WHERE username = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $targetFile, $username);
                        if (!$stmt->execute()) {
                            $errors[] = "Failed to update profile image.";
                        }
                        $stmt->close();
                    } else {
                        $errors[] = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                $errors[] = "Sorry, your file is too large.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    }

    // If there are no errors, redirect to the profile page
    if (empty($errors)) {
        header("Location: profile.php");
        exit();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
</head>
<body>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>
</html>
