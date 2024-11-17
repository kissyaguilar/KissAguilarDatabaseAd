<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $messageID = intval($_GET['id']);

    // DELETE QUERY
    $query = "DELETE FROM messages WHERE messageID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);

    if ($stmt->execute()) {
        header("Location: groupChat.php?success=Message deleted");
    } else {
        header("Location: groupChat.php?error=Failed to delete message");
    }

    $stmt->close();
} else {
    header("Location: groupChat.php?error=No message ID provided");
}

$conn->close();
?>