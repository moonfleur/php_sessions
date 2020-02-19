<?php
require_once 'functions.php';

checkGuest(); // заборонити залогіненим користувачам заходити на цю сторінку!

if($_SERVER['REQUEST_METHOD'] == 'POST') :
    function validateData() {
        $errors = [];

        if(!isset($_POST['login']) || empty($_POST['login']) || strlen($_POST['login']) > 50) {
            $errors[] = 'Невалідний логін';
        }

        if(!isset($_POST['email']) || empty($_POST['email']) || strlen($_POST['email']) > 50) {
            $errors[] = 'Невалідний E-mail';
        } else {
            $dbh = getDBConnect();
            $prepare_query = $dbh->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $result = $prepare_query->execute(['email' => $_POST['email']]);
            $user = $prepare_query->fetch();

            if(!empty($user)) {
                $errors[] = 'Користувач з таким E-mail вже існує!';
            }
        }

        if(!isset($_POST['password']) || empty($_POST['password']) || strlen($_POST['password']) > 50 || strlen($_POST['password']) < 4) {
            $errors[] = 'Невалідний пароль';
        }

        if(!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        return ['status' => 'success'];
    }

    $validate_result = validateData();
    $errors = !empty($validate_result['errors']) ? $validate_result['errors'] : [];

    if($validate_result['status'] == 'success') {
        $dbh = getDBConnect();
        $query_obj = $dbh->prepare("INSERT INTO users (login, email, password) VALUES (:login, :email, :password)");
        $registration_result = $query_obj->execute([
            'login' => $_POST['login'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password'])
        ]);

        $new_user_id = $dbh->lastInsertId();

        if(login($new_user_id)) {
            header('Location: cabinet.php');
        }
    }

endif;
?>

    <?php include 'elements/header.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Реєстрація</div>

                    <div class="card-body">
                        <?php if(isset($errors) && !empty($errors)) : ?>
                            <?php foreach ($errors as $error) :?>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger message_notification alert-dismissible" role="alert">
                                            <span class='notification_text'><?php echo $error; ?></span>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Логін</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="login" required autocomplete="name" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Реєстрація
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'elements/footer.php' ?>
