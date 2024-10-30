<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmotiChat Group Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="chat-container">
        <!-- Main Header -->
        <div class="main-header">
            EmotiChat
        </div>

        <!-- Header -->
        <div class="header">
            <div class="group-chat-info">
                <div class="group-chat-photo">
                    <?php
                        include 'connect.php';
                        
                        // Fetch Group Chat Picture
                        $groupChatID = 1;
                        $queryGroupChat = "SELECT picture, theme, voiceCall, videoChat, more, attachment, gallery, GIF FROM groupChats WHERE groupChatID = $groupChatID"; 
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
            <?php
                // Group Chat Messages
                $queryMessages = "SELECT * FROM messages WHERE groupChatID = $groupChatID";
                $resultMessages = executeQuery($queryMessages);

                if ($resultMessages && mysqli_num_rows($resultMessages) > 0) {
                    while ($message = mysqli_fetch_assoc($resultMessages)) {
                        $senderID = $message['senderID'];

                        // Get Sender Infos
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

                        echo "<div class='message'>";
                        echo "<div class='profile-picture'><img src='" . htmlspecialchars($profilePicture) . "' alt='" . htmlspecialchars($senderName) . "'></div>";
                        echo "<div class='message-content'>";
                        echo "<div class='name'>" . htmlspecialchars($senderName) . "</div>";
                        echo "<div class='text'>" . htmlspecialchars($message['message']) . "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No messages available.</p>";
                }

                mysqli_close($conn);
            ?>
        </div>

        <!-- Input Section (Typing display - does not insert) -->
        <div class="input-section">
            <div class="input-icons">
            <?php
            echo "<div class='icon attachment' style='background-image: url(images/" . htmlspecialchars($groupChatData['attachment']) . ");'></div>";
            echo "<div class='icon gallery' style='background-image: url(images/" . htmlspecialchars($groupChatData['gallery']) . ");'></div>";
            echo "<div class='icon GIF' style='background-image: url(images/" . htmlspecialchars($groupChatData['GIF']) . ");'></div>";
            ?>
              
            </div>
            <input type="text" class="message-input" placeholder="Aa">
            <div class="icon send"></div>
        </div>
    </div>
</body>

</html>