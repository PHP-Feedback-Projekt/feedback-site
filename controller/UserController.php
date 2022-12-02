<?php

function createUser($db, $name, $email, $password)
{
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (:user_name, :user_email, :user_password)";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([
        ':user_name' => $name,
        ':user_email' => $email,
        ':user_password' => $password_hash
    ]);

    return $result;
}

function deleteUser($db, $id)
{
    $sql = 'DELETE FROM users WHERE id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_id' => $id,
    ]);
}


function getUserByEmail($db, $user_email) {
    $sql = 'SELECT * FROM users WHERE email = :user_email';
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_email' => $user_email,
    ]);
    $user = $stmt->fetch();

    var_dump($user);
    

    return $user;
}

function getUserByID($db, $user_id) {
    $sql = 'SELECT * FROM users WHERE id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
    ]);
    $user = $stmt->fetch();
    

    return $user;
}


function loginUser($callback){
    $user_id= $callback['user_id'];
    $user_name = $callback['user_name'];
    $user_email= $callback['user_email'];

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;

    Redirect('protected/index.php', false);
}