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
$chatUsername = $_GET['username'];

// Check if chatUsername is valid
if (empty($chatUsername)) {
    echo "Invalid user.";
    exit();
}

// Fetch chat history
$sql = "SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $currentUsername, $chatUsername, $chatUsername, $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($chatUsername); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .chat-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .messages {
            height: 300px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .message {
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .message.sent {
            background-color: #e1ffc7;
            text-align: right;
        }
        .message.received {
            background-color: #fff;
        }
        .message-input {
            display: flex;
            gap: 10px;
        }
        .message-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .message-input button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message-input button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo ($message['sender'] == $currentUsername) ? 'sent' : 'received'; ?>">
                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                    <small><?php echo htmlspecialchars($message['sent_at']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="post" action="send_message.php">
            <input type="hidden" name="receiver" value="<?php echo htmlspecialchars($chatUsername); ?>">
            <div class="message-input">
                <input type="text" name="message" required placeholder="Type a message...">
                <button type="submit">Send</button>
            </div>
        </form>
    </div>
</body>
</html>
