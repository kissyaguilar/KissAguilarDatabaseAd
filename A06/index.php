<?php
// Include db connection
include 'connect.php';

// Fetch
$groupChatID = isset($_GET['groupChatID']) ? intval($_GET['groupChatID']) : 1; // Default to 1 
$senderID = isset($_SESSION['senderID']) ? intval($_SESSION['senderID']) : 8; // Default 8 (can change)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/logoTab.png" type="image/png">
    <title>EmotiChat Group Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            font-size: 15px;
        }

        /* Chat Container */
        .chat-container {
            border-radius: 15px;
            padding: 10px;
            width: 100%;
            margin: auto;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f0c4ff;
            flex-grow: 1;
            box-sizing: border-box;
        }

        /* Main Header */
        .main-header {
            font-size: 20px;
            font-weight: bold;
            color: white;
            background-color: #a65ac9;
            text-align: center;
        }

        .message-input {
            flex: 1;
            padding: 8px 12px;
            border-radius: 20px;
            border: none;
            outline: none;
        }

        .main-header img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .main-header h1 {
            font-size: 15px;
            margin: 0;
        }

        /* Group Chat Header */
        .header {
            display: flex;
            font-size: 1em;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            background-color: #d39de6;
        }

        .group-chat-info {
            display: flex;
            align-items: center;
        }

        .group-chat-photo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .group-chat-name {
            font-size: 20px;
            font-weight: bold;
        }

        .header-icons {
            display: flex;
            gap: 10px;
        }

        .header-icons .icon {
            width: 30px;
            height: 30px;
            background-size: cover;
            background-repeat: no-repeat;
            cursor: pointer;
        }

        /* Messages Section */
        .messages-section {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: #f4f4f4;
            background-size: cover;
        }

        .message {
            display: flex;
            align-items: flex-start;
            margin: 10px 0;
        }

        .message-left {
            justify-content: flex-start;
        }

        .message-right {
            justify-content: flex-end;
            text-align: right;
        }

        .profile-picture img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message-content {
            max-width: 70%;
            background-color: #9062ff;
            color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }


        .message-right .message-content {
            background-color: #4B0082;
            color: white;

        }

        .message-content .name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-section {
            padding: 10px;
        }

        /* Form Style */
        .input-section form {
            display: flex;
            align-items: center;
            width: 100%;
        }

        /* Icons */
        .input-icons {
            margin-right: 10px;
            display: flex;
            gap: 10px;
        }

        .input-icons .icon {
            width: 25px;
            height: 25px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
        }

        /* Input Field */
        #messageInput {
            flex-grow: 1;
            padding: 10px;
            border-radius: 25px;
            border: 1px solid #ddd;
            font-size: 16px;
            outline: none;
        }

        /* Send Button */
        button.icon.send {
            background-color: #9062ff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-left: 10px;
            background-position: center;
            background-size: cover;
            cursor: pointer;
        }

        /* Hover Effect for Icons */
        .input-icons .icon:hover {
            transform: scale(1.1);
            background-color: rgba(238, 191, 235, 0.7);
            cursor: pointer;
        }

        .input-section button:hover {
            transform: scale(1.1);
            background-color: rgba(138, 43, 226, 0.7);
        }

        .datetime {
            font-size: 0.8em;
            color: white;
            text-align: right;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            font-size: 12px;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="chat-container">
        <!-- Main Header -->
        <div class="main-header"
            style="text-align: center; display: flex; flex-direction: column; align-items: center;">
            <img src="images/logo.png" alt="EmotiChat Logo" style="max-width: auto; height: 40px;">
            <h1 style="font-size: 25px;">EmotiChat</h1>
        </div>

        <!-- Group Chat Header -->
        <div class="header">
            <div class="group-chat-info">
                <div class="group-chat-photo">
                    <?php
                    // Fetch group chat details
                    // SELECT QUERY
                    $queryGroupChat = "SELECT name, picture, theme, voiceCall, videoChat, more, attachment, gallery, gif, sendMessage 
                   FROM groupChats WHERE groupChatID = ?";

                    $stmt = $conn->prepare($queryGroupChat);
                    $stmt->bind_param("i", $groupChatID);
                    $stmt->execute();
                    $resultGroupChat = $stmt->get_result();


                    if ($row = $resultGroupChat->fetch_assoc()) {
                        $groupName = htmlspecialchars($row['name']);
                        $groupPicture = htmlspecialchars($row['picture']);
                        $theme = htmlspecialchars($row['theme']);

                        $icons = array();
                        $icons['voiceCall'] = htmlspecialchars($row['voiceCall']);
                        $icons['videoChat'] = htmlspecialchars($row['videoChat']);
                        $icons['more'] = htmlspecialchars($row['more']);
                        $icons['attachment'] = htmlspecialchars($row['attachment']);
                        $icons['gallery'] = htmlspecialchars($row['gallery']);
                        $icons['gif'] = htmlspecialchars($row['gif']);
                        $icons['sendMessage'] = htmlspecialchars($row['sendMessage']);

                        echo "<img src='images/" . $groupPicture . "' alt='Group Chat Picture'>";
                    }
                    ?>
                </div>
                <div class="group-chat-name"><?php echo $groupName; ?></div>

            </div>

            <div class="header-icons">
                <?php foreach (['voiceCall', 'videoChat', 'more'] as $icon): ?>
                    <div class='icon' style='background-image: url(images/<?php echo htmlspecialchars($icons[$icon]); ?>);'>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="messages-section" style="background-image: url('images/<?php echo $theme; ?>');">
            <?php
            // SELECT QUERY for messages
            $queryMessages = "SELECT m.messageID, m.message, m.senderID, u.memberName, u.profilePicture, m.dateTime 
    FROM messages m 
    JOIN gcMembers u ON m.senderID = u.userID 
    WHERE m.groupChatID = ?";

            $stmt = $conn->prepare($queryMessages);
            $stmt->bind_param("i", $groupChatID);
            $stmt->execute();
            $resultMessages = $stmt->get_result();

            while ($message = $resultMessages->fetch_assoc()) {
                $messageClass = ($message['senderID'] == $senderID) ? 'message-right' : 'message-left';
                echo "<div class='message $messageClass'>";
                if ($message['senderID'] != $senderID) {
                    echo "<div class='profile-picture'><img src='" . htmlspecialchars($message['profilePicture']) . "'></div>";
                }
                echo "<div class='message-content'>";
                if ($message['senderID'] != $senderID) {
                    echo "<div class='name'>" . htmlspecialchars($message['memberName']) . "</div>";
                }
                echo "<div class='text'>" . htmlspecialchars($message['message']) . "</div>";

                // Displaying DateTime
                echo "<div class='dateTime' style='font-size: 0.8em; color: white; text-align: right;'>";
                echo date('h:i A', strtotime($message['dateTime']));
                echo "</div>";

                // Delete Button (direct PHP call to delete)
                if ($message['senderID'] == $senderID) {  // Only allow the sender to delete the message
                    echo "<a href='deleteMessage.php?id=" . $message['messageID'] . "&groupChatID=$groupChatID' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this message?\")'>Delete</a>";
                }
                echo "</div>"; // Close message-content div
                echo "</div>"; // Close message div
            }
            ?>
        </div>



        <!-- Input Section -->
        <div class="input-section">
            <form action="insertMessage.php" method="POST" onsubmit="return validateMessage();">
                <!-- Icons -->
                <div class="input-icons">
                    <?php foreach (['attachment', 'gallery', 'gif'] as $icon): ?>
                        <div class="icon"
                            style="background-image: url('images/<?php echo htmlspecialchars($icons[$icon]); ?>');"></div>
                    <?php endforeach; ?>
                </div>

                <!-- Input Field -->
                <input type="text" id="messageInput" name="message" placeholder="Type a message..." required>

                <!-- Send Button -->
                <button type="submit" class="icon send"
                    style="background-image: url('images/<?php echo htmlspecialchars($icons['sendMessage']); ?>');"></button>

                <!-- Hidden Inputs -->
                <input type="hidden" name="groupChatID" value="<?php echo $groupChatID; ?>">
                <input type="hidden" name="senderID" value="<?php echo $senderID; ?>">
            </form>
        </div>

        <script>
            function validateMessage() {
                const message = document.getElementById('messageInput').value.trim();
                if (message === "") {
                    alert("Please type a message before sending.");
                    return false;
                }
                return true;
            }
        </script>