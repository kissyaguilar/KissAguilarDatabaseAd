<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data 
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $groupChatID = intval($_POST['groupChatID']);
    $senderID = intval($_POST['senderID']);

    $stmt = $conn->prepare("INSERT INTO messages (message, groupChatID, senderID) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $message, $groupChatID, $senderID);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>