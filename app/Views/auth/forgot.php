<?= $this->extend('auth/layouts/index'); ?>

<?= $this->section('content') ?>

<div class="container-forgot">

    <h1 class="auth-title text-center">Lupa Kata Sandi</h1>
    <p class="auth-subtitle mb-5 text-center">Silahkan masukkan email yang telah didaftarkan. Kami akan mengirimkan
        email untuk ubah kata sandi</p>

    <form action="<?= url_to('forgot') ?>" method="post" class="user" id="forgotForm">
        <?= csrf_field() ?>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email">
            <label for="floatingInput">Email</label>
            <div class="invalid-feedback"></div>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Kirim Link</button>
    </form>

    <br>
    <br>
    <br>

    <div class="row">
        <div class="col-md-6 text-center">
            <p class='text-gray-600'>Belum punya akun? <a href="<?= base_url('register') ?>">Buat
                    Akun</a>
            </p>
        </div>
        <div class="col-md-6 text-center">
            <p class='text-gray-600'>Sudah punya akun? <a href="<?= base_url('login') ?>">Masuk</a>
            </p>
        </div>
    </div>

</div>

<div class="modal fade" id="emailSentModal" tabindex="-1" aria-labelledby="emailSentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div>
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h5 class="modal-title mb-3" id="emailSentModalLabel">Email Terkirim</h5>
                <p>Email untuk mengubah kata sandi telah dikirim. Silahkan cek email Anda.</p>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
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

<?= $this->endSection() ?>