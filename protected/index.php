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
$dynamic_class = '';
$like = false;
$commenttxt =  $_POST['comment'] ?? '';
$comment_id = $_POST['commentID'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($action === 'logout') {
    session_destroy();
    Redirect('../index.php', false);
  }

  if ($action === 'bewerten')
    $callback = handelfeedbackRequist($db);

  if ($action === 'delete')
    deleteFBFromDB($db, $feedback_id);

  if ($action === 'like') {
    $like = true;
    if (!row_exist($db, $feedback_id, $user_id))
      addlike($db, $feedback_id, $user_id, 1);
    if (!has_User_liked_this_fb($db, $feedback_id, $user_id))
      Updatelikestatus($db, $feedback_id, $user_id, 1);
  }

  if ($action === 'dislike') {
    $like = false;
    if (has_User_liked_this_fb($db, $feedback_id, $user_id))
      Updatelikestatus($db, $feedback_id, $user_id, 0);
  }

  if ($action === 'savecomment')
    add_New_comment_to_the_db($db, $user_id, $feedback_id, $commenttxt);

  if ($action === 'delete_comment')
    delete_comment_from_db($db, $comment_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/style/stars.css">
  <title>Document</title>
</head>
<style>
  label {
    display: block;
  }

  .felx-cont {
    display: flex;
    flex-direction: row;
  }

  .felx-item {
    border: 1px solid black;
  }

  .checked {
    color: orange;
  }

  .feedback-area {
    border: 1px solid grey;
  }

  .old-comments {
    border: 1px solid red;
  }

  .grid-container {
    display: grid;
    grid-template-columns: auto auto auto;
    background-color: #2196F3;
    padding: 20px;
    width: 80%;
  }

  .grid-item {
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 0, 0, 0.8);
    padding: 20px;
    margin: 5px;
    font-size: 30px;
    text-align: center;
  }

  .flex-container {
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
    align-items: center;
    align-content: center;
  }

  .item {
    background-color: white;
    -webkit-box-shadow: 0px 0px 25px -1px rgba(0, 0, 0, 0.47);
    box-shadow: 0px 0px 25px -1px rgba(0, 0, 0, 0.47);
    text-align: center;
    border-bottom: #2c3e50 4px solid;
    border-radius: 5px;
    font-family: 'Roboto', sans-serif;
  }

  .comment-block {
    display: flex;
    align-items: flex-start;
  }

  .comment-block .comment-image {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    opacity: 0;
    transform: scale(0.1);
    -webkit-animation: popup 0.5s cubic-bezier(1, 0, 0, 1.5) forwards;
    animation: popup 0.5s cubic-bezier(1, 0, 0, 1.5) forwards;
  }

  .comment-block .comment-dialog {
    background-color: #2c3e50;
    color: #fff;
    padding: 1rem;
    max-width: 300px;
    position: relative;
    margin-left: 1.5rem;
    opacity: 0;
    transform: scale(0.5);
    -webkit-animation: popup 0.4s cubic-bezier(0.55, 0, 0, 1.5) 0.4s forwards;
    animation: popup 0.4s cubic-bezier(0.55, 0, 0, 1.5) 0.4s forwards;
  }

  .comment-block .comment-dialog::before,
  .comment-block .comment-dialog::after {
    content: "";
    position: absolute;
  }

  .comment-block .comment-dialog::before {
    top: -0.45rem;
    left: 0;
    width: 100%;
    height: 0.5rem;
    background-color: #2c3e50;
    -webkit-clip-path: polygon(100% 0%, 0% 100%, 100% 100%);
    clip-path: polygon(100% 0%, 0% 100%, 100% 100%);
  }

  .comment-block .comment-dialog::after {
    top: 1rem;
    left: -1rem;
    border: 0.5rem solid transparent;
    border-right-color: #2c3e50;
    border-top-color: #2c3e50;
  }

  .comment-block .username {
    margin: 0 0 0.8rem;
  }

  .comment-block .text {
    margin: 0;
    font-size: 15px;
  }



  @-webkit-keyframes popup {
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  @keyframes popup {
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .item p {
    font-size: 20px;
  }
</style>

<body>

  <h1><?= "Herzlich willkommen $user_name mit der ID: $user_id" ?></h1>


  <?php $feedbacks = getfeedbacksfromdb($db);
  if (isset($feedbacks)) : ?>

    <div class="flex-container">
      <div class="grid-container">

        <?php foreach ($feedbacks as $feedback) : ?>

          <div class="grid-item item">

            <h5><?= $feedback["user_id"] ?> Feedback</h5>
            <p> hat am <?= $feedback['created_at'] ?> folgender bewrtung geschrieben</p>
            <p><?= $feedback['feedback'] ?></p>
            <p> Der Likes Anzahl ist <?= getlikesanzahl($db, $feedback["id"]) ?> </p>

            <div class="stars">
              <?php $dynamic_class =  ($feedback['stars'] >= 1) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 2) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 3) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 4) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 5) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
            </div>


            <?php $comments = get_all_comments_from_db($db, $feedback['id']);
            if ($comments) : ?>

            <div class="flex-container">


            <?php foreach ($comments as $comment) : ?>

              <div class="comment-block" style="margin-top: 20px;">
                <div class="comment-dialog">
                  <p class="text"><?= $comment['comment'] ?></p>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>

    </div>

  <?php endif; ?>


  <?php $feedbacks = getfeedbacksfromdb($db);
  if (isset($feedbacks)) : ?>
    <div class="feedbacks">
      <div class="Flex-cont">

        <?php foreach ($feedbacks as $feedback) : ?>

          <div class="felx-item">

            <p> Der Benutzer mit der ID: <?= $feedback["user_id"] ?> </p>
            <p> hat am <?= $feedback['created_at'] ?> folgender bewrtung geschrieben</p>
            <p> Der Likes Anzahl ist <?= getlikesanzahl($db, $feedback["id"]) ?> </p>

            <div class="stars">
              <?php $dynamic_class =  ($feedback['stars'] >= 1) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 2) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 3) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 4) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
              <?php $dynamic_class =  ($feedback['stars'] >= 5) ? 'checked' : ''; ?>
              <span class="fa fa-star <?= $dynamic_class ?>"></span>
            </div>

            <article class="feedback-area"><?= $feedback['feedback'] ?></article>
            <?php $comments = get_all_comments_from_db($db, $feedback['id']);
            if ($comments) : ?>
              <div class="old-comments">
                <?php foreach ($comments as $comment) : ?>
                  <div class="old-comment"> <?= $comment['comment'] ?></div>
                  <form method="post">
                    <div class="delete">
                      <input type="hidden" name="commentID" value="<?= $comment['id'] ?>">
                      <button type="submit" value="delete_comment" name="action">Löchen</button>
                    </div>
                  </form>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <form method="post">
              <input type="hidden" name="feedbackID" value="<?= $feedback['id'] ?>">
              <textarea name="comment" cols="30" rows="10" placeholder="Ihre Kommentar"></textarea>
              <button type="submit" value="savecomment" name="action">save</button>
            </form>



            <?php if ($user_id == $feedback["user_id"]) : ?>
              <form method="post">
                <div class="delete">
                  <input type="hidden" name="feedbackID" value="<?= $feedback['id'] ?>">
                  <button type="submit" value="delete" name="action">Löchen</button>
                </div>
              </form>
            <?php endif; ?>

            <?php if (!has_User_liked_this_fb($db, $feedback['id'], $user_id)) : ?>
              <form method="post">
                <div class="like">
                  <input type="hidden" name="feedbackID" value="<?= $feedback['id'] ?>">
                  <button type="submit" value="like" name="action">like</button>
                </div>
              </form>
            <?php endif; ?>

            <?php if (has_User_liked_this_fb($db, $feedback['id'], $user_id)) : ?>
              <form method="post">
                <div class="dislike">
                  <input type="hidden" name="feedbackID" value="<?= $feedback['id'] ?>">
                  <button type="submit" value="dislike" name="action">dislike</button>
                </div>
              </form>
            <?php endif; ?>


          </div>
      </div>
    <?php endforeach; ?>
    </div>
    </div>
  <?php endif; ?>

  <div class="feedback_c">
    <form action="index.php" method="post">

      <div class="stars_cont">
        <input disabled checked class="rating__input rating__input--none" name="rating" id="rating-none" value="0" type="radio">
        <label class="rating__label" for="rating-1">
          <i class="rating__icon rating__icon--star fa fa-star"></i>
        </label>
        <input class="rating__input" type="radio" name="rating" value="1" id="rating-1">

        <label class="rating__label" for="rating-2">
          <i class="rating__icon rating__icon--star fa fa-star"></i>
        </label>
        <input class="rating__input" type="radio" name="rating" value="2" id="rating-2">

        <label class="rating__label" for="rating-3">
          <i class="rating__icon rating__icon--star fa fa-star"></i>
        </label>
        <input class="rating__input" type="radio" name="rating" value="3" id="rating-3">

        <label class="rating__label" for="rating-4">
          <i class="rating__icon rating__icon--star fa fa-star"></i>
        </label>
        <input class="rating__input" type="radio" name="rating" value="4" id="rating-4">

        <label class="rating__label" bel for="rating-5">
          <i class="rating__icon rating__icon--star fa fa-star"></i>
        </label>
        <input class="rating__input" type="radio" name="rating" value="5" id="rating-5">
      </div>

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