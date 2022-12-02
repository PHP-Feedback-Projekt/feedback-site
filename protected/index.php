<?php

require '../helper/Helper.php';
require '../requests/UserfeedbackRequest.php';
require '../database/database_connection.php';
require '../controller/UserController.php';
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
  <link rel="stylesheet" href="../assets/style/style.css">
  <title>Feedback | Home</title>
</head>
<style>
  .feedback-text{
    /* width: 200px;
    height: 50px; */
    color: #2c3e50;
    font-size: 16px;
    border: 1px solid black;
    word-wrap: break-word; 
    padding: 16px;
    margin: 16px;
    background-color: #bdc3c7;
  }
</style>

<body>

  <nav>
    <div class="left clearfix">
      <span><?= htmlspecialchars("Herzlich willkommen $user_name" )?></span>
    </div>

    <div class="right clearfix">
      <form action="index.php" method="post">
        <button type="submit" name="action" value="logout">Abmelden</button>
      </form>
    </div>

  </nav>


  <?php $feedbacks = getfeedbacksfromdb($db);
  if (isset($feedbacks)) : ?>

    <div class="flex-container" style="margin-top: 5%;">
      <div class="grid-container">

        <?php foreach ($feedbacks as $feedback) : ?>
          <div class="grid-item item">

            <p style="font-size: 20px; font-weight: bold; color: #2c3e50;">Feedback von <?= htmlspecialchars(getUserByID($db, $feedback["user_id"])['name']) ?></p>

            <div style="border-bottom: black 2px solid;">
              <div class="flex-container">
                <span class="date"><?= htmlspecialchars($feedback['created_at']) ?></span>
              </div>

              <div class="stars" style="margin-top: 10px;">
                <?php $dynamic_class =  ($feedback['stars'] >= 1) ? 'checked' : ''; ?>
                <span class="fa fa-star <?= htmlspecialchars($dynamic_class) ?>"></span>
                <?php $dynamic_class =  ($feedback['stars'] >= 2) ? 'checked' : ''; ?>
                <span class="fa fa-star <?= htmlspecialchars($dynamic_class) ?>"></span>
                <?php $dynamic_class =  ($feedback['stars'] >= 3) ? 'checked' : ''; ?>
                <span class="fa fa-star <?= htmlspecialchars($dynamic_class) ?>"></span>
                <?php $dynamic_class =  ($feedback['stars'] >= 4) ? 'checked' : ''; ?>
                <span class="fa fa-star <?= htmlspecialchars($dynamic_class) ?>"></span>
                <?php $dynamic_class =  ($feedback['stars'] >= 5) ? 'checked' : ''; ?>
                <span class="fa fa-star <?= htmlspecialchars($dynamic_class) ?>"></span>
              </div>

              <p class="feedback-text"><?= htmlspecialchars($feedback['feedback']) ?></p>


            </div>


            <?php $comments = get_all_comments_from_db($db, $feedback['id']);
            if ($comments) : ?>

              <div class="flex-container" style="flex-direction: column;">

                <?php foreach ($comments as $comment) : ?>


                  <div class="flex-container">
                    <div class="dialogbox">
                      <div class="body">
                        <span class="tip tip-up"></span>
                        <div class="message">
                          <span><?= htmlspecialchars($comment['comment']) ?></span>

                          <div class="delete">
                            <div class="flex-container" style="height: 30px;">

                              <div class="flex-item-50" style="margin: 0; padding: 0;">
                                <p style="font-size: 14px; font-weight: bold;">@ <?= htmlspecialchars(getUserByID($db, $comment['user_id'])['name']) ?></p>
                              </div>

                              <div class="flex-item-50" style="margin: 0; padding: 0;">
                                <form method="post">
                                  <div class="delete">
                                    <input type="hidden" name="commentID" value="<?= htmlspecialchars($comment['id']) ?>">
                                    <button type="submit" value="delete_comment" name="action" style="background-color: transparent; outline: 0; border: 0; cursor: pointer;"><i class="fa fa-trash" style="color:#e74c3c; font-size: 20px;"></i></button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>


                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <!-- Comment Form -->

            <div class="flex-container">
              <form method="post">

                <div class="form-group" style=" margin: 0">
                  <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['id'] )?>">
                  <textarea name="comment" cols="5" rows="2" placeholder="Ihre Kommentar" style="min-width: 250px;"></textarea>
                </div>

                <div class="form-group" style="    margin: 0">
                  <button type="submit" value="savecomment" name="action" class="default-btn">Kommentar Speichern</button>
                </div>

              </form>
            </div>


            <div class="flex-container" style="margin-top: 20px;">
              <div class="flex-container" style="width: 80%;">

                <div class="flex-item-50">
                  <?php if ($user_id == $feedback["user_id"]) : ?>
                    <form method="post">
                      <div class="delete">
                        <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['id']) ?>">
                        <button type="submit" value="delete" name="action" style="background-color: #e74c3c; color: #ecf0f1; font-weight: bold; border: 0; padding: 10px;">Löschen</button>
                      </div>
                    </form>
                  <?php endif; ?>
                </div>

                <div class="flex-item-50">
                  <!-- Like, Delete feedback -->


                  <?php if (!has_User_liked_this_fb($db, $feedback['id'], $user_id)) : ?>
                    <form method="post">
                      <div class="like">
                        <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['id'] )?>">
                        <span style="display: block; font-size: 12px;"> <?= htmlspecialchars(getlikesanzahl($db, $feedback["id"])) ?> gefällt dieser Beitrag</span>
                        <button type="submit" value="like" name="action" style="background-color: transparent; border: 0; cursor: pointer;"><img src="../assets/img/Like.png" alt="" srcset="" height="25" width="25"></button>
                      </div>
                    </form>
                  <?php endif; ?>

                  <?php if (has_User_liked_this_fb($db, $feedback['id'], $user_id)) : ?>
                    <form method="post">
                      <div class="dislike">
                        <input type="hidden" name="feedbackID" value="<?= htmlspecialchars($feedback['id'] )?>">
                        <span style="display: block; font-size: 12px;"><?= htmlspecialchars(getlikesanzahl($db, $feedback["id"]) === 1 ? 'Dir gefällt dieser Beitrag': 'Dir und' .  getlikesanzahl($db, $feedback["id"]) . ' gefällt dieser Beitrag') ?></span>
                        <button type="submit" value="dislike" name="action" style="background-color: transparent; border: 0; cursor: pointer;"><img src="../assets/img/Dislike.png" alt="" srcset="" height="25" width="25"></button>
                      </div>
                    </form>
                  <?php endif; ?>
                </div>

              </div>
            </div>



          </div>

        <?php endforeach; ?>
      </div>

    </div>

  <?php endif; ?>

  <div class="flex-container" style="margin-top: 20px;">
    <div class="item">
      <h3>Bewertung abgeben</h3>
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
            <div class="form-group">
              <label for="feedback">Ihre Bewertung:</label>
              <textarea name="feedback" id="feedback" cols="30" rows="10" placeholder="Bewertung Schreiben"></textarea>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" name="action" value="bewerten" class="default-btn">Absenden</button>
          </div>
        </form>
      </div>
      <?php if (isset($callback['feedback_error'])) { ?>
        <div class="alert">
          <p style="font-size: 15px; font-weight: bold;"> <?= htmlspecialchars($callback['feedback_error'])?> </p>
        </div>
      <?php }
      if (isset($callback['user_feedback'])) {  ?>
        <div class="message">
          <p style="font-size: 15px; font-weight: bold;">Ihre Bewertung wurde gespeichert</p>
        </div>
      <?php } ?>
    </div>
  </div>


</body>

</html>