<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand-img" href="#"><img src="../assets2/img/LogoPUPR.png" alt="PUPR Logo"></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0 ">
                <li class="nav-item"><a class="nav-link fw-bold text-dark"
                        href="<?= base_url('#beranda'); ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="<?= base_url('#services'); ?>">Layanan
                        Kami</a></li>
                <li class="nav-item"><a class="nav-link fw-bold" href="<?= base_url('#faq'); ?>">FAQ</a></li>

                <?php if (service('authentication')->check()): ?>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="<?= base_url('logout'); ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item btn btn-primary"><a class="nav-link text-white fw-bold"
                            href="<?= base_url('login'); ?>">Masuk</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>