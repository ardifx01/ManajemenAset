<?= $this->extend('home/layouts/index'); ?>

<?= $this->section('content'); ?>

<header class="masthead" id="beranda">
    <div class="container position-relative">
        <div class="logo-container">
            <div class="logo-box">
                <img src="assets2/img/linkicon.png" alt="Asset Icon" class="link-icon">
            </div>
            <span class="shortlink-url">aset.pu.go.id/<span class="highlight">manajemen</span></span>
            <img src="assets2/img/cursoricon.png" alt="Click" class="hand-icon">
        </div>
        <div class="content-box">
            <h1 class="masthead-heading">
                Selamat Datang di <span class="highlight-sl">Sistem Manajemen Aset</span>
            </h1>
            <p class="masthead-subheading">Kelola Aset PU: Efisien, Terstruktur, dan Terintegrasi!</p>
        </div>
        <br>
        <br>
        <br>
        <br>
        <a class="btn btn-primary btn-xl text-uppercase text-white"
            href="<?= logged_in() ? base_url('homepage') : base_url('login'); ?>">Mulai</a>
    </div>
    <br>
    <br>
    <br>
    <p class="version-text text-dark">Beta Version 1.0</p>
</header>

<section class="page-section" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">Layanan Kami</h2>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center gradient-card">
                    <div class="card-body">
                        <i class="bi bi-box-seam fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Inventarisasi Aset</h4>
                        <p class="card-text">Kelola dan pantau seluruh aset dengan mudah.
                            Sistem inventarisasi yang terstruktur membantu Anda melacak dan mengelola
                            setiap aset dengan efisien.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center gradient-card">
                    <div class="card-body">
                        <i class="bi bi-graph-up fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Monitoring Aset</h4>
                        <p class="card-text">Pantau kondisi dan status aset secara real-time.
                            Dapatkan informasi terkini tentang penggunaan dan pemeliharaan aset.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center gradient-card">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Pelaporan Aset</h4>
                        <p class="card-text">Buat dan kelola laporan aset dengan mudah.
                            Dapatkan insight mendalam tentang kondisi aset melalui sistem
                            pelaporan yang komprehensif dan terintegrasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section" id="faq">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">FAQ</h2>
        </div>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Apa itu Sistem Manajemen Aset?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Sistem Manajemen Aset adalah platform terpadu untuk mengelola seluruh aset organisasi.
                            Sistem ini memungkinkan pencatatan, pemantauan, dan pengelolaan aset secara efisien
                            dan terstruktur. Dengan sistem ini, pengelolaan aset menjadi lebih transparan,
                            akuntabel, dan mudah dilacak.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Bagaimana Cara Melakukan Inventarisasi Aset?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Inventarisasi aset dapat dilakukan dengan mudah melalui sistem ini.
                            Anda cukup memasukkan data aset seperti nama, kategori, lokasi, dan
                            spesifikasi lainnya. Sistem akan secara otomatis mencatat dan menyimpan
                            informasi tersebut dalam database yang terstruktur.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apa Manfaat Monitoring Aset?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Monitoring aset memberikan beberapa manfaat penting:</p>
                        <ul>
                            <li>Pemantauan kondisi aset secara real-time</li>
                            <li>Pencegahan kerusakan dan pemeliharaan preventif</li>
                            <li>Optimalisasi penggunaan dan pemanfaatan aset</li>
                            <li>Perencanaan penggantian dan pembaruan aset yang lebih baik</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Bagaimana Sistem Pelaporan Aset Bekerja?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Sistem pelaporan aset menyediakan berbagai fitur untuk membuat laporan
                            yang komprehensif tentang kondisi dan status aset. Anda dapat membuat
                            laporan berkala, laporan kondisi aset, laporan pemeliharaan, dan
                            berbagai jenis laporan lainnya sesuai kebutuhan. Semua laporan dapat
                            diakses secara mudah dan dapat diekspor dalam berbagai format untuk
                            keperluan dokumentasi dan audit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>