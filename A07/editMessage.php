<?php
include 'connect.php';

$messageID = isset($_GET['id']) ? $_GET['id'] : 0;

//get message query
$getMessQuery = "SELECT message FROM messages WHERE messageID = $messageID";
$getMessResult = executeQuery($getMessQuery);

if (mysqli_num_rows($getMessResult) > 0) {
    $message = mysqli_fetch_assoc($getMessResult)['message'];
} else {
    die("Message not found.");
}

if ($_POST) {
    $updatedMessage = trim($_POST['message']);

    if (!empty($updatedMessage)) {
        $editedMessage = mysqli_real_escape_string($conn, $updatedMessage);

        // UPDATE QUERY
        $updateQuery = "UPDATE messages SET message = '$editedMessage', dateTime = NOW() WHERE messageID = $messageID";
        if (executeQuery($updateQuery)) {
            header("Location: index.php?groupChatID=" . $_POST['groupChatID']);
        } else {
            echo "Failed to update the message.";
        }
    } else {
        echo "<div class='alert alert-danger'>Message cannot be empty or blank spaces only.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmotiChat Edit Message</title>
    <link rel="shortcut icon" href="images/logoTab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<style>
    body {
        background-color: #d39de6;
    }

    .container {
        background-color: #a65ac9;
        padding: 20px;
        border-radius: 10px;
    }

    .btn-primary,
    .btn-secondary {
        background-color: purple;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        background-color: #bf77f6;
    }
</style>

<body>
    <div class="container mt-5">
        <h2>EmotiChat Edit Message</h2>
        <form method="POST" action="editMessage.php?id=<?php echo $messageID; ?>">
            <input type="hidden" name="groupChatID"
                value="<?php echo isset($_GET['groupChatID']) ? $_GET['groupChatID'] : 1; ?>">
            <div class="mb-3">
                <textarea name="message" class="form-control" rows="3"
                    required><?php echo htmlspecialchars($message); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="index.php?groupChatID=<?php echo isset($_GET['groupChatID']) ? $_GET['groupChatID'] : 1; ?>"
                class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>

</html>