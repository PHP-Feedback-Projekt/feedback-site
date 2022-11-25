<?php


function handleRegisterRequest($db)
{

    $error = [];

    $user_name = $_POST['name'] ?? '';
    $user_email = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? '';
    $user_repeat_password = $_POST['repeat_password'] ?? '';


    //validate name
    if ($user_name === '') {
        $error['error_register_name'] = 'Bitte gebe einen gültigen Benutzername ein!';
    }

    //validate email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error['error_register_email'] = 'Bitte gebe eine Email an!';
    }

    if ($user_email === '') {
        $error['error_register_email'] = 'Bitte gebe eine Email an!';
    }

    //validate password
    if (mb_strlen($user_password) < 8) {
        $error['error_register_password'] = 'Das Passwort muss mindestens 8 Zeichen enthalten';
    }

    if ($user_password !== $user_repeat_password) {
        $error['error_register_password'] = 'Deine Passwörter stimmen nicht überein!';
    }

    if ($user_password === '') {
        $error['error_register_password'] = 'Bitte gebe ein Password an';
    }
    

    $user = getUserByEmail($db, $user_email);


    if($user && $user_email === $user['email']){
        $error['error_register_email'] = 'Es existiert bereits ein Account mit dieser E-Mail';
    }

    if (!$error) {

        createUser($db, $user_name, $user_email, $user_password);

        $user = getUserByEmail($db, $user_email);

        $callback = [
            "user_id" => $user['id'],
            "user_name" => $user_name,
            "user_email" => $user_email,
        ];


        return $callback;

    }
    return $error;
}
