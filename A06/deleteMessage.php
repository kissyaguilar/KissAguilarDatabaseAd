<?php
include 'connect.php';

if (isset($_GET['id']) && isset($_GET['groupChatID'])) {
    $messageID = intval($_GET['id']);
    $groupChatID = intval($_GET['groupChatID']);

    // DELETE QUERY
    $query = "DELETE FROM messages WHERE messageID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);

    if ($stmt->execute()) {
        header("Location: index.php?groupChatID=$groupChatID&success=Message+deleted");
    } else {
        header("Location: index.php?groupChatID=$groupChatID&error=Failed+to+delete+message");
    }

    $stmt->close();
} else {
    header("Location: index.php?error=Invalid+request");
}

exit;
