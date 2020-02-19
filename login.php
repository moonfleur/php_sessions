<?php
require_once 'functions.php';

checkGuest();

if($_SERVER['REQUEST_METHOD'] == 'POST') :

    function validateData() {
        $errors = [];

        if(!isset($_POST['email']) || empty($_POST['email']) || strlen($_POST['email']) > 50)
            $errors[] = 'Невалідний E-mail';


        if(!isset($_POST['password']) || empty($_POST['password']) || strlen($_POST['password']) > 50 || strlen($_POST['password']) < 4)
            $errors[] = 'Невалідний пароль';

        if(!empty($errors))
            return ['status' => 'error', 'errors' => $errors];

        return ['status' => 'success'];
    }

    $validate_result = validateData();
    $errors = !empty($validate_result['errors']) ? $validate_result['errors'] : [];

    if($validate_result['status'] == 'success') {
        $dbh = getDBConnect();
        $query_obj = $dbh->prepare("SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1");
        $result = $query_obj->execute([
            'email' => $_POST['email'],
            'password' => md5($_POST['password'])
        ]);

        $user = $query_obj->fetch();

        if(!empty($user) && login($user['id'])) {
            header('Location: cabinet.php');
        } else {
            $errors[] = 'Невірний логін або пароль!';
        }
    }

endif;
?>

<?php include 'elements/header.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Вхід</div>

                    <div class="card-body">
                        <?php if(!empty($errors)) : ?>
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
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Вхід
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
