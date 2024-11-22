<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Welcome to Shortlink</title>
    <link rel="icon" type="image/x-icon" href="assets/pupr.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js2/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css2" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet"
        type="text/css2" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css2/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>

<body id="page-top">

    <div class="blue-gradient-hd"></div>
    <div class="yellow-gradient-hd"></div>

    <?= $this->include('home/layouts/navbar'); ?>

    <div id="main">
        <div class="blue-gradient-lk"></div>
        <div class="yellow-gradient-lk"></div>
        <?= $this->renderSection('content'); ?>
    </div>

    <?= $this->include('home/layouts/footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="<?= base_url(); ?>/js2/scripts.js"></script>

    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>

</body>

</html>