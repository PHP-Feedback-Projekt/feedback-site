<?php

require '../helper/Helper.php';
require '../requests/UserfeedbackRequest.php';
require '../database/database_connection.php';
//require '../controller/FeedbackController.php';

session_start();
$db = connectToDatabase();

if (!isset($_SESSION['user_id'])) {
    Redirect('../index.php', false);
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$action = $_POST['action'] ?? '';
$feedback_id = $_POST['feedbackID'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'logout') {
        session_destroy();
        Redirect('../index.php', false);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'bewerten') {
        $callback = handelfeedbackRequist($db);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'delete') {
      deleteFBFromDB($db,$feedback_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    label {
        display: block;
    }
    .felx-cont{
      display: flex;
      flex-direction: row;
    }
    .felx-item{
      border: 1px solid black;
    }
</style>

<body>

    <h1><?= "Herzlich willkommen $user_name mit der ID: $user_id" ?></h1>

    <?php $feedbacks = getfeedbacksfromdb($db);
        if (isset($feedbacks)) : ?>

    <div class="feedbacks">
      <div class="Flex-cont">

        <?php foreach ($feedbacks as $feedback) : ?>

          <div class="felx-item">

            <p> Der Benutzer mit der ID: <?= $feedback["user_id"] ?> </p>
            <p> hat am <?= $feedback['created_at'] ?> folgender bewrtung geschrieben</p>
            <p> <?= $feedback['feedback'] ?></p>
            <p> TEST: <?= $feedback['id'] ?></p>

            <form method="post">
              <div class="Delete">
                <input type="hidden" name="feedbackID" value="<?= $feedback['id']?>" >
                <button type="submit" value="delete" 
                name="action">löchen</button>
              </div>
            </form>

          </div>

        <?php endforeach; ?>

      </div>
    </div>
    <?php endif; ?>

    <div class="feedback_c">
        <form action="index.php" method="post">
            <div class="text_c">
                <label for="feedback">Ihre Bewrtung:</label>
                <textarea name="feedback" id="feedback" cols="30" rows="10"></textarea>
            </div>
            <input type="submit" name="action" value="bewerten">
        </form>
    </div>
    <?php if (isset($callback['feedback_error'])) { ?>
        <div class="alert">
            <p> <?= $callback['feedback_error']; ?> </p>
        </div>
    <?php }
    if (isset($callback['user_feedback'])) {  ?>
        <div class="message">
            <p>Ihre Bewrtung wurde gespeichert</p>
        </div>
    <?php } ?>

    <form action="index.php" method="post">
        <button type="submit" name="action" value="logout">Abmelden</button>
    </form>

</body>

</html>