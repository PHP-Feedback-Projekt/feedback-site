<?php

require '../helper/Helper.php';

session_start();


if(!isset($_SESSION['user_id'])) {
    Redirect('../index.php', false);
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'logout') {
        session_destroy();
        Redirect('../index.php', false);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<h1><?= "Herzlich willkommen $user_name mit der ID: $user_id" ?></h1>


<form action="index.php" method="post">
    <button type="submit" name="action" value="logout">Abmelden</button>
</form>

</body>
</html>