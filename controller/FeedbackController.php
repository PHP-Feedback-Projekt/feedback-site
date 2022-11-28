<?php

function createfeedback($db, $feedbacktxt, $user_id)
{
  $sql = "INSERT INTO feedbacks (user_id , feedback, created_at) VALUES
   (:user_id , :feedbacktxt, :created_at)";
   var_dump($user_id);
  $stm = $db->prepare($sql);
  $result = $stm->execute([
    ':user_id' => $user_id,
    ':feedbacktxt' => $feedbacktxt,
    ':created_at'  => date("y-m-d")
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

function deleteFBFromDB($db,$id)
{
  $sql = 'DELETE FROM feedbacks WHERE id LIKE :feedback_id';
  $stm = $db->prepare($sql);
  $stm->execute([
    ':feedback_id' => $id, 
  ]);

}
// function deleteUser($db, $id)
// {
//     $sql = 'DELETE FROM users WHERE id = :user_id';
//     $stmt = $db->prepare($sql);
//     $stmt->execute([
//         ':user_id' => $id,
//     ]);
// }