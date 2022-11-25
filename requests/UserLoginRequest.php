<?php


function handleLoginRequest($db)
{

    $error = [];
    $user_email = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? '';

    //validate email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error['error_login_email'] = 'Bitte gebe eine Email an!';
    }

    if ($user_email === '') {
        $error['error_login_email'] = 'Bitte gebe eine Email an!';
    }

    //Validate password
    if ($user_password === '') {
        $error['error_login_password'] = 'Bitte gebe ein Password an';
    }
    
    $user = getUserByEmail($db, $user_email);

    if($user && password_verify($user_password, $user['password'])){
        if(!$error) {
            $callback = [
                "user_id" => $user['id'],
                "user_name" => $user['name'],
                "user_email" => $user_email,
            ];
    
    
            return $callback;
        }
    }else{
        $error['error_login_email'] = 'Bitte überprüfe deine Anmeldedaten!'; 
    }

    return $error;

}