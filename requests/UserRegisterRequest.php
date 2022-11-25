<?php

require './controller/UserController.php';

function handleRegisterRequest($db)
{
    $user_name = $_POST['name'] ?? '';
    $user_email = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? '';
    $user_repeat_password = $_POST['repeat_password'] ?? '';


    //validate name
    if ($user_name === '') {
        $error['name'] = 'Bitte gebe einen gültigen Benutzername ein!';
    }

    //validate email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Bitte gebe eine Email an!';
    }

    if ($user_email === '') {
        $error['email'] = 'Bitte gebe eine Email an!';
    }

    //validate password
    if (mb_strlen($user_password) < 8) {
        $error['password'] = 'Das Passwort muss mindestens 8 Zeichen enthalten';
    }

    if ($user_password !== $user_repeat_password) {
        $error['password'] = 'Deine Passwörter stimmen nicht überein!';
    }

    if ($user_password === '') {
        $error['password'] = 'Bitte gebe ein Password an';
    }

    if (!$error) {

        createUser($db, $user_name, $user_email, $user_password);
    }
    return $error;
}
