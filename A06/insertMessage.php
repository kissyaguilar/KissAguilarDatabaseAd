<?php

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);
    $groupChatID = isset($_POST['groupChatID']) ? intval($_POST['groupChatID']) : 1;
    $senderID = isset($_POST['senderID']) ? intval($_POST['senderID']) : 8;

    if (empty($message)) {
        header("Location: index.php?error=emptyMessage");
        exit;
    }

    $query = "INSERT INTO messages (groupChatID, senderID, message, dateTime) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $groupChatID, $senderID, $message);
    $stmt->execute();

    // iis means int, int, string parameters

    // Redirect back to the chat page after insertion
    header("Location: index.php?groupChatID=$groupChatID");
    exit;
}
?>