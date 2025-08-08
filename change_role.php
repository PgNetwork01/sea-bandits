<?php
session_start();
include 'includes/db.php';

if (isset($_POST['username']) && isset($_POST['role'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE username = ?");
    $stmt->bind_param("ss", $role, $username);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin.php");
exit();
?>
