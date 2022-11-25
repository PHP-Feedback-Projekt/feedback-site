<?php

require 'database/database_connection.php';
require 'requests/UserRegisterRequest.php';

$db = connectToDatabase();

$errors = [];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Register Form
    if ($action === 'register') {

        $errors = handleRegisterRequest($db);
    }
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmelden</title>
</head>

<body>

    <h1>Registrieren</h1>

    <form action="index.php" method="post">

        <div class="form-group">
            <label for="name">Dein benutzername</label>
            <input type="text" name="name" id="name">

            <?php if (isset($errors['name'])) : ?>
                <div class="alert">
                    <?= $errors['name'] ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="form-group">
            <label for="email">Deine E-Mail</label>
            <input type="text" name="email" id="email">


            <?php if (isset($errors['email'])) : ?>
                <div class="alert">
                    <?= $errors['email'] ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="form-group">
            <label for="password">Dein Password</label>
            <input type="text" name="password" id="password">

            <?php if (isset($errors['password'])) : ?>
                <div class="alert">
                    <?= $errors['password'] ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="form-group">
            <label for="repeat_password">Dein Passwort wiederholen</label>
            <input type="text" name="repeat_password" id="repeat_password">

            <?php if (isset($errors['password'])) : ?>
                <div class="alert">
                    <?= $errors['password'] ?>
                </div>
            <?php endif; ?>

        </div>

        <button type="submit" name="action" value="register">Absenden</button>

    </form>

</body>

</html>