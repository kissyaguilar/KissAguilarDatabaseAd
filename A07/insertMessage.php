<?php
include 'connect.php';

if (isset($_POST['sendMessage'])) {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $groupChatID = isset($_POST['groupChatID']) ? $_POST['groupChatID'] : 0;

    if (isset($_POST['senderID'])) {
        $senderID = $_POST['senderID'];
    } else {
        header("Location: index.php?error=missingSenderID");
    }
    if (empty($message)) {
        header("Location: index.php?groupChatID=" . $groupChatID . "&error=emptyMessage");
        exit;
    }

    // INSERT QUERY
    $queryInsert = "INSERT INTO messages (groupChatID, senderID, message, dateTime) VALUES ('"
        . $groupChatID . "', '"
        . $senderID . "', '"
        . $message . "', NOW())";

    $resultInsert = executeQuery($queryInsert);

    if ($resultInsert) {
        header("Location: index.php?groupChatID=" . $groupChatID);
    } else {
        header("Location: index.php?groupChatID=" . $groupChatID . "&error=failedInsert");
    }
    exit;
}
?>