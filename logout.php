<?php

require_once 'functions.php';

checkGuest();

unset($_SESSION['auth_user']);

header('Location: index.php');