
<?php
require "../controller/FeedbackController.php";

function handelfeedbackRequist($db)
{
  $error = [];
  $user_feedback = $_POST['feedback'] ?? '';
  $user_id = $_SESSION['user_id'];

  if (mb_strlen($user_feedback) < 2)
    $error['feedback_error'] = 'Die Eingabe soll minsestens Zwei buchstabe haben';

  if (hasbadword($user_feedback))
    $error['feedback_error'] = 'Bitte keine böse wörte benutzen du hund';

  if (!$error) {
    createfeedback($db, $user_feedback, $user_id);
    $callback = [
      "user_feedback" => $user_feedback,
      "user_id" => $user_id
    ];

    return $callback;
  }
  return $error;
}

