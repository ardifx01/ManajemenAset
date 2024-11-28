<?= $this->extend('auth/layouts/index') ?>

<?= $this->section('content') ?>

<title>Login</title>
<div class="asset-grid"></div>
<div class="login-form">

    <h1 class="auth-title text-center">Masuk</h1>
    <p class="auth-subtitle mb-5">Silahkan masukkan email atau username dan kata sandi Anda</p>

    <?= view('Myth\Auth\Views\_message_block') ?>

    <form action="<?= route_to('login') ?>" method="post" class="users">
        <?= csrf_field() ?>

        <?php if ($config->validFields === ['email']): ?>
            <div class="form-floating mb-3">
                <input type="email" class="form-control <?php if (session('errors.login')): ?>is-invalid<?php endif ?>"
                    name="login" placeholder="Email atau Username">
                <label for="floatingInput">Email atau Username</label>
                <div class="invalid-feedback">
                    <?= session('errors.login') ?>
                </div>
            </div>
        <?php else: ?>
            <div class="form-floating mb-3">
                <input type="text" class="form-control <?php if (session('errors.login')): ?>is-invalid<?php endif ?>"
                    name="login" placeholder="Email atau Username">
                <label for="floatingInput">Email atau Username</label>
                <div class="invalid-feedback">
                    <?= session('errors.login') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-floating mb-3">
            <input class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                type="password" name="password" placeholder="Kata Sandi">
            <label for="floatingInput">Kata Sandi</label>
            <div class="invalid-feedback">
                <?= session('errors.password') ?>
            </div>
        </div>

        <?php if ($config->allowRemembering): ?>
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked <?php endif ?>>
                <label class="form-check-label">
                    <?= lang('Auth.rememberMe') ?>
                </label>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">
            Masuk
        </button>

        <?php if ($config->activeResetter): ?>
            <div class="text-center mt-5 text-lg">
                <a class="katasandi" href="<?= route_to('forgot') ?>">
                    Lupa Kata Sandi?</a>
            </div>
        <?php endif; ?>
    </form>

    <!-- <?php if ($config->allowRegistration): ?>
        <div class="text-center mt-5 text-lg">
            <p class="text-gray-600">Belum punya akun? <a class="register" href="<?= base_url('register') ?>">Buat Akun</a>
            </p>
        </div>
    <?php endif; ?> -->
</div>

<?= $this->endSection() ?>