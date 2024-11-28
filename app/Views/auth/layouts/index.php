<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/pupr.ico" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/app.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/pages/auth.css">
</head>

<body>

    <!-- <div class="blue-gradient-rg"></div>
    <div class="yellow-gradient-rg"></div>
    <div class="yellow-gradient-lf"></div>
    <div class="blue-gradient-lf"></div> -->

    <div id="auth">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.resetSuccess = <?= json_encode(session('reset_success')) ?>;
        window.resetFailed = <?= json_encode(session('reset_failed')) ?>;
        window.loginUrl = '<?= url_to('login') ?>';
    </script>
    <script src="<?= base_url(); ?>/assets/js/auth.js"></script>

</body>

</html>