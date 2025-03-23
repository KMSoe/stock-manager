
<?php
    include_once "../views/partials/header.php";
?>

<div class="container-fluid">
    <div class="row flex-nowrap">
    <?php
        include_once "../views/partials/sidebar.php";
    ?>
        <div class="col py-3">
           <h1>Welcome Back, <?= $user['name'] ?? '' ?>(<?= $user['role_name'] ?>)</h1>
        </div>
    </div>
</div>
<?php
    include_once "../views/partials/footer.php";
?>