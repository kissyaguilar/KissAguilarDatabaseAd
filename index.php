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
    <div class="chat-container"
         <!-- Main Header -->
        <div class="main-header">
            EmotiChat
        </div>

        <!-- Header -->
        <div class="header">
            <div class="group-chat-info">
                <div class="group-chat-photo"></div>
                <div class="group-chat-name">Emotions</div>
            </div>
            <div class="header-icons">
                <div class="icon call"></div>
                <div class="icon video"></div>
                <div class="icon more"></div>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="messages-section">
            
            <?php
                include 'connect.php';
                

                ?>
        </div>



</body>
</html>
