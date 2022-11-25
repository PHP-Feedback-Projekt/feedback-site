<?php

require '../database/database_connection.php';

function createUser($db, $name, $email, $password) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (:user_name, :user_email, :user_password)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_name' => $name,
        ':user_email' => $email,
        ':user_password' => $password_hash
    ]);

}

function deleteUser($db, $id){
    $sql = 'DELETE FROM users WHERE id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_id' => $id,
    ]);
}
