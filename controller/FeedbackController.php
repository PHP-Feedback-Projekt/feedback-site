<?php

function createfeedback($db, $feedbacktxt, $user_id , $stars)
{
  $sql = "INSERT INTO feedbacks
   (user_id , feedback, created_at , updated_at, stars)
    VALUES
   (:user_id , :feedbacktxt, :created_at, :updated_at, :stars)";

  $stm = $db->prepare($sql);
  $result = $stm->execute([
    ':user_id' => $user_id,
    ':feedbacktxt' => $feedbacktxt,
    ':created_at'  => date("y-m-d-h:i:S"),
    ':updated_at'  => date("y-m-d-h:i:S"),
    ':stars' => $stars
  ]);
  return $result;
}

function getfeedbacksfromdb($db)
{
  $sql = "SELECT * FROM feedbacks";
  $stmt = $db->query($sql);
  $feedbacks = $stmt->fetchall();

  return $feedbacks;
}

function deleteFBFromDB($db, $feedback_id)
{
  $sql = 'DELETE FROM feedbacks WHERE id LIKE :feedback_id';
  $stm = $db->prepare($sql);
  $stm->execute([
    ':feedback_id' => $feedback_id, 
  ]);

}

function addlike($db, $feedback_id, $user_id, $liked)
{
  $sql = "INSERT INTO likes
  (feedback_id, user_id , liked)
  VALUES
  (:feedback_id, :user_id, :liked)";

  $stm = $db->prepare($sql);
  $result = $stm->execute([
    ':feedback_id' => $feedback_id,
    ':user_id' => $user_id,
    ':liked'  => $liked,
  ]);
  return $result;
}

function has_User_liked_this_fb($db,$feedback_id,$user_id)
{
  $sql = "SELECT * 
  FROM likes 
  WHERE feedback_id = :feedback_id AND user_id = :user_id AND liked = :liked";
  
  $stm = $db->prepare($sql);
  $stm->execute([
    ':feedback_id' => $feedback_id,
    ':user_id' => $user_id,
    ':liked' => 1,
  ]);

  $feedback = $stm->fetch();
  if(!$feedback)
    return false;
  else
    return true;
}

function Updatelikestatus($db,$feedback_id,$user_id,$liked)
{
  $sql = " UPDATE likes 
  SET liked = :liked
  WHERE feedback_id = :feedback_id AND user_id = :user_id";

  $stm = $db->prepare($sql);
  $result = $stm->execute([
    ':feedback_id' => $feedback_id,
    ':user_id' => $user_id,
    ':liked'  => $liked,

  ]);
  return $result;
}

function row_exist($db,$feedback_id,$user_id)
{
  $sql = "SELECT * 
  FROM likes 
  WHERE feedback_id = :feedback_id AND user_id = :user_id";
  
  $stm = $db->prepare($sql);
  $stm->execute([
    ':feedback_id' => $feedback_id,
    ':user_id' => $user_id,
  ]);

  $feedback = $stm->fetch();
  if(!$feedback)
    return false;
  else
    return true;
}

function getlikesanzahl ($db, $feedback_id)
{
  $sql = "SELECT liked 
  FROM likes
  WHERE liked = :liked AND feedback_id = :feedback_id";

  $stm = $db->prepare($sql);
  $stm->execute([
    ':liked' => 1,
    ':feedback_id' => $feedback_id,
  ]);

  //var_dump($result);
  return count($stm->fetchall());
}

function add_New_comment_to_the_db($db, $user_id, $feedback_id, $commenttxt)
{
   $sql = "INSERT INTO comments
   ( feedback_id , user_id , comment, created_at , updated_at)
    VALUES
   (:feedback_id ,:user_id , :comment, :created_at, :updated_at)";

  $stm = $db->prepare($sql);
  $result = $stm->execute([
    ':feedback_id' => $feedback_id,
    ':user_id' => $user_id,
    ':comment' => $commenttxt,
    ':created_at'  => date("y-m-d-h:i:S"),
    ':updated_at'  => date("y-m-d-h:i:S"),
  ]);
  return $result;
}

function get_all_comments_from_db($db, $feedback_id)
{
  $sql = "SELECT id,comment
  FROM comments
  WHERE feedback_id = $feedback_id";

  $stmt = $db->query($sql);
  $comments = $stmt->fetchall();

  return $comments;
}
function delete_comment_from_db($db, $comment_id)
{
  $sql = 'DELETE FROM comments WHERE id = :comment_id';
  $stm = $db->prepare($sql);
  $stm->execute([
    ':comment_id' => $comment_id, 
  ]);
}



