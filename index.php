<?php

session_start();
require 'database/database_connection.php';
include 'controller/UserController.php';
require 'requests/UserRegisterRequest.php';
require 'requests/UserLoginRequest.php';
require 'helper/Helper.php';

$db = connectToDatabase();


$errors = [];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Register Form
    if ($action === 'register') {
        $callback = handleRegisterRequest($db);

        if (isset($callback['user_name']) && isset($callback['user_id'])) {
            $ok = true;
            loginUser($callback);
        }
    }

    if ($action === 'login') {
        $callback = handleLoginRequest($db);

        if (isset($callback['user_name']) && isset($callback['user_id'])) {
            loginUser($callback);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <title>Anmelden</title>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'font-family: 'Roboto', sans-serif;';
        }

        .wrapper {
            background: lightgray;
        }

        .form-group {
            margin: 30px 10px;
        }

        .form-group>label {
            display: block;
        }

        .form-group input {
            margin: 5px 0;
        }

        .alert {
            color: red;
        }

        .flex-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            justify-content: center;
            align-items: center;
            align-content: center;
        }

        .flex-item-50 {

            padding: 5px;
            flex: 50%;
            margin: 25px;

        }

        .parent {
            display: grid;
        }

        .child {
            margin: auto;
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

        button {
            margin-top: 20px;
            padding: 12px;
            border: 0;
            background-color: #2c3e50;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        input {
            background-color: #bdc3c7;
            border: 0;
            padding: 12px;
            min-width: 300px;
        }

        input:focus{
            outline: 0;
            color: #2c3e50;
            font-weight: bold;
        }

    </style>

</head>

<body>

    <div class="flex-container" style="margin-top: 5%;">


        <div class="flex-container" style=" width: 60%;">

            <div class="flex-item-50 item">
                <h1>Registrieren</h1>
                <div class="flex-container">

                    <form action="index.php" method="post">

                        <div class="form-group">
                            <label for="name">Dein benutzername</label>
                            <input type="text" name="name" id="name">

                            <?php if (isset($callback['error_register_name'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_register_name'] ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="email">Deine E-Mail</label>
                            <input type="text" name="email" id="email">


                            <?php if (isset($callback['error_register_email'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_register_email'] ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="password">Dein Password</label>
                            <input type="text" name="password" id="password">

                            <?php if (isset($callback['error_register_password'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_register_password'] ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="repeat_password">Dein Passwort wiederholen</label>
                            <input type="text" name="repeat_password" id="repeat_password">

                            <?php if (isset($callback['error_register_password'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_register_password'] ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <button type="submit" name="action" value="register">Absenden</button>
                        </div>

                    </form>

                    <?php if (isset($ok)) : ?>
                        <div class="alert">
                            Du hast dich erfolgreich registriert!
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="flex-item-50 item">
                <h1>Anmelden</h1>
                <div class="flex-container">
                    <form action="index.php" method="post">

                        <div class="form-group">
                            <label for="email">Deine E-Mail</label>
                            <input type="text" name="email" id="email">


                            <?php if (isset($callback['error_login_email'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_login_email'] ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="password">Dein Password</label>
                            <input type="text" name="password" id="password">

                            <?php if (isset($callback['error_login_password'])) : ?>
                                <div class="alert">
                                    <?= $callback['error_login_password'] ?>
                                </div>
                            <?php endif; ?>

                        </div>


                        <div class="form-group">
                            <button type="submit" name="action" value="login">Absenden</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>


    </div>


</body>

</html>