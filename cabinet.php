<?php

require_once 'functions.php';

checkLogin();
?>

<?php include 'elements/header.php' ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Кабінет</div>

                <div class="card-body">

                    <h2>Привіт <?php echo $_SESSION['auth_user']['login']?></h2>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'elements/footer.php' ?>
