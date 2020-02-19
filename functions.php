<?php

global $dbh;

init();

function init() {
    session_start();
}

function getDBConnect($host = null, $dbname = null, $user = null, $pass = null) {
    $host = $host ? $host : 'localhost';
    $dbname = $dbname ? $dbname : 'sessions';
    $user = $user ? $user : 'root';
    $pass = $pass ? $pass : '';
    $params = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    try {
        $dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass, $params);
    } catch (PDOException $e) {
        die('Не вдалось підключитись до бази данних!');
    }

    return $dbh;
}

function login($user_id) {
    $dbh = getDBConnect();

    $prepare_query = $dbh->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $result = $prepare_query->execute(['id' => $user_id]);

    $user = $prepare_query->fetch();

    if(!empty($user)) {
        $_SESSION['auth_user'] = $user;

        return true;
    }

    return false;
}
// заборонити НЕ залогіненим користувачам заходити на цю сторінку!
function checkLogin() {
    if(!isset($_SESSION['auth_user']) || empty($_SESSION['auth_user'])) {
        header('Location: login.php'); // перенаправляємо на сторінку логіна
    }
}
// заборонити залогіненим користувачам заходити на цю сторінку!
function checkGuest() {
    if(isset($_SESSION['auth_user']) && !empty($_SESSION['auth_user'])) {
        header('Location: cabinet.php'); // перенаправляємо в кабінет
    }
}