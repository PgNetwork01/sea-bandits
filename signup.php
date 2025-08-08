<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Database connection
   $conn = new mysqli('sql110.infinityfree.com', 'if0_37169209', 'cfrw4J7aKSIgE', 'if0_37169209_sea_auth');
    //$conn = new mysqli('localhost', 'root', 'qwerty1234', 'sea_auth');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Sorry, that username or email is already taken.";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    // Check if the file was uploaded without errors
    if (isset($_FILES["profileImage"]) && $_FILES["profileImage"]["error"] == 0) {
        $target_dir = "uploads/";
        $original_filename = basename($_FILES["profileImage"]["name"]);
        $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        // Create a unique filename using a timestamp and a random string
        $unique_filename = uniqid() . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        // Check if file is an actual image
        $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (5MB maximum)
            if ($_FILES["profileImage"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $conn->close();
                exit;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $conn->close();
                exit;
            }

            // Try to upload the file with the unique filename
            if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars($unique_filename) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                $conn->close();
                exit;
            }
        } else {
            echo "File is not an image.";
            $conn->close();
            exit;
        }
    } else {
        echo "Error: " . $_FILES["profileImage"]["error"];
        $conn->close();
        exit;
    }

    // Insert user into the database
    $sql = "INSERT INTO users (username, email, password, profile_image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $password, $target_file);

    if ($stmt->execute()) {
        echo "Signup successful! Welcome, " . htmlspecialchars($username) . ".";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
