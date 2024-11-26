<?php
include 'connect.php';

if (isset($_GET['id']) && isset($_GET['groupChatID'])) {
    $messageID = $_GET['id'];
    $groupChatID = $_GET['groupChatID'];

    //DELETE QUERY
    $queryDelete = "DELETE FROM messages WHERE messageID = $messageID";
    $resultDelete = executeQuery($queryDelete);

    if ($resultDelete) {
        header("Location: index.php?groupChatID=" . $groupChatID . "&success=Message+deleted");
    } else {
        header("Location: index.php?groupChatID=" . $groupChatID . "&error=Failed+to+delete+message");
    }
} else {
    header("Location: index.php?error=Invalid+request");
}
?>