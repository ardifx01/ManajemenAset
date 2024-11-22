<?= $this->extend('auth/layouts/index') ?>

<?= $this->section('content') ?>

<title>Sign Up</title>

<div class="container-register">

    <h1 class="auth-title text-center">Daftar</h1>
    <p class="auth-subtitle mb-5 text-center">Silahkan masukkan data dan informasi Anda</p>

    <form action="<?= route_to('register') ?>" method="post" class="users" id="registerForm">
        <?= csrf_field() ?>

        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php if (session('errors.fullname')): ?>is-invalid<?php endif ?>"
                name="fullname" placeholder="Fullname" value="<?= old('fullname') ?>">
            <label for="floatingInput">Fullname</label>
            <div class="invalid-feedback">
                <?= session('errors.fullname') ?>
            </div>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control <?php if (session('errors.username')): ?>is-invalid<?php endif ?>"
                name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
            <label for="floatingInput">Username</label>
            <div class="invalid-feedback">
                <?= session('errors.username') ?>
            </div>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>"
                value="<?= old('email') ?>">
            <label for="floatingInput">Email</label>
            <div class="invalid-feedback">
                <?= session('errors.email') ?>
            </div>
        </div>

        <div class="form-floating mb-3">
            <select class="form-control <?php if (session('errors.unit_organisasi')): ?>is-invalid<?php endif ?>"
                name="unit_organisasi">
                <option value="" class="text-muted" disabled selected></option>
                <option value="Setjen">Sekretariat Jenderal</option>
                <option value="Itjen">Inspektorat Jenderal</option>
                <option value="Ditjen Sumber Daya Air">Direktorat Jenderal Sumber Daya Air</option>
                <option value="Ditjen Bina Marga">Direktorat Jenderal Bina Marga</option>
                <option value="Ditjen Cipta Karya">Direktorat Jenderal Cipta Karya</option>
                <option value="Ditjen Perumahan">Direktorat Jenderal Perumahan</option>
                <option value="Ditjen Bina Konstruksi">Direktorat Jenderal Bina Konstruksi</option>
                <option value="Ditjen Pembiayaan Infrastruktur Pekerjaan Umum dan Perumahan">Direktorat
                    Jenderal Pembiayaan Infrastruktur Pekerjaan Umum dan
                    Perumahan</option>
                <option value="BPIW">Badan Pengembangan Infrastruktur Wilayah</option>
                <option value="BPSDM">Badan Pengembangan Sumber Daya Manusia</option>
                <option value="BPJT">Badan Pengatur Jalan Tol</option>
            </select>
            <label for="floatingInput" class="text-muted">Pilih Unit Organisasi</label>
            <div class="invalid-feedback">
                <?= session('errors.unit_organisasi') ?>
            </div>
        </div>

        <div class="form-floating mb-3">
            <select class="form-control <?php if (session('errors.unit_kerja')): ?>is-invalid<?php endif ?>"
                name="unit_kerja">
            </select>
            <label for="floatingInput" class="text-muted">Pilih Unit Kerja</label>
            <div class="invalid-feedback">
                <?= session('errors.unit_kerja') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="password"
                        class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                        name="password" placeholder="Masukkan Kata Sandi" autocomplete="off">
                    <label for="floatingInput" class="text-muted">Masukkan Kata Sandi</label>
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="password"
                        class="form-control <?php if (session('errors.pass_confirm')): ?>is-invalid<?php endif ?>"
                        name="pass_confirm" placeholder="Ulangi Kata Sandi" autocomplete="off">
                    <label for="floatingInput" class="text-muted">Konfirmasi Kata Sandi</label>
                    <div class="invalid-feedback">
                        <?= session('errors.pass_confirm') ?>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-xl shadow-lg">Daftar</button>
    </form>

    <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>Sudah memiliki akun? <a href="<?= url_to('login') ?>">Masuk</a>
        </p>
    </div>

</div>

<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
    style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="modal fade" id="registerSuccessModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                    data-bs-dismiss="modal"></button>
                <div>
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h5 class="modal-title mb-3">Registrasi Berhasil</h5>
                <p id="registerSuccessMessage"></p>
                <button type="button" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerErrorModal" tabindex="-1" aria-labelledby="registerErrorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div>
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                </div>
                <h5 class="modal-title mb-3" id="registerErrorModalLabel">Registrasi Gagal</h5>
                <div id="registerErrorMessage"></div>
                <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <img src="../assets2/img/LogoPUPR.png" alt="PUPR Logo" class="pupr-logo">
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="termsModalLabel">Syarat & Ketentuan Shortlink</h5>
                <p>Shortlink merupakan layanan yang dikelola oleh Pusat Data dan Teknologi Informasi, Kementerian
                    Pekerjaan Umum dan Perumahan Rakyat. Layanan ini dirancang untuk memudahkan pengguna dalam
                    memperpendek URL yang panjang, sehingga lebih mudah dibagikan dan diakses. Dengan menggunakan
                    layanan Shortlink, Anda menyetujui untuk mematuhi dan terikat oleh ketentuan penggunaan yang telah
                    ditetapkan. Kami berkomitmen untuk menyediakan layanan yang handal dan aman bagi Anda, serta
                    memastikan bahwa setiap pengguna memahami dan mematuhi aturan yang berlaku.</p>
                <p>Namun, penting untuk diingat bahwa penggunaan layanan Shortlink berarti Anda harus setuju dengan
                    semua ketentuan yang berlaku. Ketentuan ini mencakup berbagai aspek, mulai dari keamanan dan privasi
                    data hingga larangan penyalahgunaan layanan. Jika Anda tidak setuju dengan ketentuan ini, maka Anda
                    tidak diperkenankan menggunakan layanan Shortlink. Kami sangat menghargai kepatuhan Anda terhadap
                    aturan ini, karena ini adalah bagian dari upaya kami untuk menciptakan lingkungan digital yang aman
                    dan teratur bagi semua pengguna. Kami percaya bahwa dengan memahami dan mematuhi ketentuan ini, Anda
                    dapat memanfaatkan layanan Shortlink secara optimal dan bertanggung jawab.</p>
                <h6>1. Penggunaan</h6>
                <ol type="a">
                    <li>Pengguna harus mendaftarkan akun dengan email kedinasan <a href="">@pu.go.id</a>
                    <li>Pengguna harus mendaftarkan akun dengan informasi yang akurat dan benar.</li>
                    </li>
                    <li>Pengguna merupakan karyawan/karyawati Kementerian PUPR.</li>
                </ol>
                <h6>2. Perubahan Ketentuan</h6>
                <ol type="a">
                    <p>Kami berhak untuk mengubah ketentuan penggunaan kapan saja. Perubahan akan diberlakukan secara
                        berkala.</p>
                </ol>
                <h6>3. Larangan Pengunaan Layanan</h6>
                <ol type="a">
                    <li>Membuat atau membagikan URL yang mengandung konten ilegal, menyesatkan, atau tidak pantas.</li>
                    <li>Menggunakan layanan untuk spam, phising, atau aktivitas berbahaya lainnya.</li>
                    <li>Mendistribusikan virus, malware, atau perangkat lunak berbahaya lainnya.</li>
                </ol>
                <h6>Hubungi Kami</h6>
                <ol type="a">
                    <p>Jika Anda memiliki pertanyaan atau masalah terkait layanan ini, silahkan hubungi kami
                        di <a href="mailto:info.shortlink@pu.go.id">info.shortlink@pu.go.id</a></p>
                </ol>
                <br>
                <h5 class="modal-title" id="termsModalLabel">Kebijakan Privasi</h5>
                <p>Kami sangat menghargai privasi dan keamanan informasi pribadi Anda, dan kami berkomitmen untuk
                    melindungi data yang Anda percayakan kepada kami. Kebijakan Privasi ini merupakan wujud komitmen
                    kami dalam menjaga kerahasiaan dan keamanan informasi pribadi yang Anda berikan saat menggunakan
                    layanan Shortlink. Kami memahami betapa pentingnya privasi bagi Anda, dan kami ingin memastikan
                    bahwa Anda merasa aman dan percaya diri saat menggunakan layanan kami.
                    Dalam Kebijakan Privasi ini, kami akan menjelaskan secara rinci bagaimana kami mengumpulkan,
                    menyimpan, menggunakan, dan melindungi informasi pribadi Anda. Kami mengumpulkan informasi yang
                    diperlukan untuk memberikan layanan terbaik dan meningkatkan pengalaman pengguna Anda. Informasi ini
                    mencakup data yang Anda berikan secara langsung seperti nama dan alamat email, serta data yang kami
                    kumpulkan secara otomatis seperti alamat IP dan riwayat penggunaan. Kami memastikan bahwa semua
                    informasi yang kami kumpulkan digunakan hanya untuk tujuan yang sah dan dengan cara yang transparan.
                </p>
                <p>Selain itu, kami akan menjelaskan langkah-langkah keamanan yang kami terapkan untuk melindungi
                    informasi pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah. Kami menggunakan
                    berbagai teknologi dan prosedur keamanan untuk menjaga integritas dan kerahasiaan data Anda. Kami
                    juga berkomitmen untuk mematuhi semua peraturan perundang-undangan yang berlaku terkait perlindungan
                    data pribadi. Dengan memberikan informasi yang lengkap dan transparan ini, kami berharap dapat
                    membangun kepercayaan dan memastikan bahwa Anda memahami dan merasa nyaman dengan cara kami
                    menangani informasi pribadi Anda.</p>
                <h6>1. Data dan Informasi</h6>
                <ol type="a">
                    <p>Kami berkomitmen untuk melindungi privasi dan keamanan informasi pribadi Anda. Kebijakan Privasi
                        ini terkait mengumpulkan, menggunakan, dan melindungi informasi yang Anda berikan saat
                        menggunakan layanan Shortlink.</p>
                </ol>
                <h6>2. Penggunaan Data dan Informasi</h6>
                <ol type="a">
                    <li>Memberikan dan meningkatkan pelayanan kami.</li>
                    <li>Menganalisis penggunaan layanan untuk tujuan pengembangan fitur-fitur baru yang relevan dengan
                        kebutuhan pengguna.</li>
                    <li>Kami tidak akan membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda,
                        kecuali jika diwajibkan oleh hukum atau untuk melindungi hak, properti, atau keamanan kami dan
                        pengguna lain.</li>
                </ol>
                <h6>3. Keamanan Data dan Informasi</h6>
                <ol type="a">
                    <li>Kami mengambil langkah-langkah teknis dan organisasi yang wajar untuk melindungi informasi
                        pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah.</li>
                    <li>Tidak ada metode transmisi data melalui internet atau metode penyimpanan elektronik yang
                        sepenuhnya aman.</li>
                </ol>
                <h6>Hubungi Kami</h6>
                <ol type="a">
                    <p>Jika Anda memiliki pertanyaan atau masalah terkait layanan ini, silahkan hubungi kami
                        di <a href="mailto:info.shortlink@pu.go.id">info.shortlink@pu.go.id</a></p>
                </ol>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="agreeButton">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>