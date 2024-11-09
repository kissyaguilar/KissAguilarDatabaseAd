<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/logoTab.png" type="image"> <!-- for logo -->
    <title>EmotiChat Group Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="chat-container">
        <!-- Main Header -->
        <div class="main-header"
            style="text-align: center; display: flex; flex-direction: column; align-items: center; padding: 10px;">
            <img src="images/logo.png" alt="EmotiChat Logo" style="max-width: auto; height: 50px;">
            <h1 style="font-size: 50px;">EmotiChat</h1>
        </div>

        <!-- Header Section -->
        <div class="header">
            <div class="group-chat-info">
                <div class="group-chat-photo">
                    <?php
                    include 'connect.php';

                    // Fetch Group Chat Picture (SELECT QUERY)
                    $groupChatID = 1;
                    $queryGroupChat = "SELECT picture, theme, voiceCall, videoChat, more, attachment, gallery, gif, sendMessage FROM groupChats WHERE groupChatID = $groupChatID";
                    $resultGroupChat = executeQuery($queryGroupChat);

                    if ($resultGroupChat && mysqli_num_rows($resultGroupChat) > 0) {
                        $groupChatData = mysqli_fetch_assoc($resultGroupChat);
                        $groupChatPicture = $groupChatData['picture'];
                        $theme = $groupChatData['theme'];
                        echo "<img src='images/" . htmlspecialchars($groupChatPicture) . "' alt='Group Chat Picture'>";
                    } else {
                        echo "<img src='images/default-group.png' alt='Default Group Chat Picture'>";
                    }
                    ?>
                </div>
                <div class="group-chat-name">Emotions</div>
            </div>
            <div class="header-icons">
                <?php
                echo "<div class='icon call' style='background-image: url(images/" . htmlspecialchars($groupChatData['voiceCall']) . ");'></div>";
                echo "<div class='icon videoChat' style='background-image: url(images/" . htmlspecialchars($groupChatData['videoChat']) . ");'></div>";
                echo "<div class='icon more' style='background-image: url(images/" . htmlspecialchars($groupChatData['more']) . ");'></div>";
                ?>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="messages-section" style="background-image: url('images/<?php echo htmlspecialchars($theme); ?>');">
            <!-- Left Messages (Other Users) -->
            <div class="left-messages">
                <?php
                // Display messages from other users
                $queryMessages = "SELECT * FROM messages WHERE groupChatID = $groupChatID";
                $resultMessages = executeQuery($queryMessages);
                $lastSenderID = null;  // To track the sender of the last message
                
                if ($resultMessages && mysqli_num_rows($resultMessages) > 0) {
                    while ($message = mysqli_fetch_assoc($resultMessages)) {
                        $senderID = $message['senderID'];
                        $querySender = "SELECT memberName, profilePicture FROM gcMembers WHERE userID = $senderID";
                        $resultSender = executeQuery($querySender);

                        if ($resultSender && mysqli_num_rows($resultSender) > 0) {
                            $senderData = mysqli_fetch_assoc($resultSender);
                            $senderName = $senderData['memberName'];
                            $profilePicture = $senderData['profilePicture'];
                        } else {
                            $senderName = "Unknown";
                            $profilePicture = "images/default.png";
                        }

                        if ($senderID != 8) { // Display other users' messages (left-aligned)
                            echo "<div class='message message-left'>";
                            echo "<div class='profile-picture'><img src='" . htmlspecialchars($profilePicture) . "' alt='" . htmlspecialchars($senderName) . "'></div>";
                            echo "<div class='message-content'>";
                            echo "<div class='name'>" . htmlspecialchars($senderName) . "</div>";
                            echo "<div class='text'>" . htmlspecialchars($message['message']) . "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        $lastSenderID = $senderID;  // Update the last sender
                    }
                }
                ?>
            </div>

            <!-- Right Messages (User with ID 8) -->
            <div class="right-messages">
                <?php
                // Fetch and display messages from current user (sender 8 - Kissy)
                mysqli_data_seek($resultMessages, 0);
                $lastMessageID = null;

                while ($message = mysqli_fetch_assoc($resultMessages)) {
                    $senderID = $message['senderID'];
                    if ($senderID == 8) {
                        $messageText = htmlspecialchars($message['message']);
                        $isLongMessage = strlen($messageText) > 50; // Long message detection
                
                        $messageStyle = $isLongMessage ?
                            "background-color: #4b0082; color: white; padding: 10px; border-radius: 10px; max-width: 70%; margin-right: 10px; word-wrap: break-word;" :
                            "background-color: #4b0082; color: white; padding: 10px; border-radius: 10px; max-width: 70%; margin-right: 10px;";

                        echo "<div class='message message-right' style='display: flex; justify-content: flex-end;'>";
                        echo "<div class='message-content' style='$messageStyle'>";
                        echo "<div class='text'>" . $messageText . "</div>";
                        echo "</div>";
                        echo "</div>";

                        $lastMessageID = $message['messageID'];
                    }
                }

                //    "Sent" label
                if ($lastSenderID == 8 && $lastMessageID) {
                    echo "<div class='message-status' style='font-size: 1.0em; text-align: right; padding-right: 10px;'>Sent</div>";
                }

                mysqli_close($conn);
                ?>
            </div>

            <div class="input-section">
                <form action="insertMessage.php" method="POST" id="messageForm" class="d-flex align-items-center w-100"
                    onsubmit="return validateMessage();">

                    <div class="input-icons d-flex align-items-center">
                        <?php
                        echo "<div class='icon attachment' style='background-image: url(images/" . htmlspecialchars($groupChatData['attachment']) . ");'></div>";
                        echo "<div class='icon gallery' style='background-image: url(images/" . htmlspecialchars($groupChatData['gallery']) . ");'></div>";
                        echo "<div class='icon gif' style='background-image: url(images/" . htmlspecialchars($groupChatData['gif']) . ");'></div>";
                        ?>
                    </div>

                    <input type="text" name="message" id="messageInput" class="message-input flex-grow-1 mx-2"
                        placeholder="Type a message..." required>

                    <button type="submit" class="icon send"
                        style="background-image: url(images/<?php echo htmlspecialchars($groupChatData['sendMessage']); ?>); border: none;"></button>

                    <input type="hidden" name="groupChatID" value="1">
                    <input type="hidden" name="senderID" value="8">
                </form>
            </div>
        </div>
        <script>
            document.getElementById('messageForm').addEventListener('submit', function (event) {
                var messageInput = document.getElementById('messageInput').value.trim();

                if (messageInput.length === 0) {
                    alert("Please enter a message before sending.");
                    event.preventDefault();
                    return false;
                }
            });
        </script>

    </div>
</body>

</html>