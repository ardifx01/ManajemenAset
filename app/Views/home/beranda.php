<?= $this->extend('home/layouts/index'); ?>

<?= $this->section('content'); ?>

<header class="masthead" id="beranda">
    <div class="container position-relative">
        <div class="logo-container">
            <div class="logo-box">
                <img src="assets2/img/linkicon.png" alt="Link Icon" class="link-icon">
            </div>
            <span class="shortlink-url">s.pu.go.id/<span class="highlight">shortlink</span></span>
            <img src="assets2/img/cursoricon.png" alt="Click" class="hand-icon">
        </div>
        <div class="content-box">
            <h1 class="masthead-heading">
                Selamat Datang di <span class="highlight-sl">Shortlink</span>
            </h1>
            <p class="masthead-subheading">Optimalkan Informasi PUPR: Satu Link, Beragam Akses!</p>
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
                        <i class="bi bi-pen fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Customize Link</h4>
                        <p class="card-text">Sesuaikan tautan pendek Anda agar sesuai dengan kebutuhan Anda.
                            Dengan layanan Custom URL, Anda dapat memilih kata-kata pilihan sendiri untuk membuat tautan
                            yang
                            unik dan mudah diingat.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center gradient-card">
                    <div class="card-body">
                        <i class="bi bi-file-code fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Shortlink</h4>
                        <p class="card-text">Memperpendek tautan Anda, tautan Anda akan terlihat lebih rapi dan menarik.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center gradient-card">
                    <div class="card-body">
                        <i class="bi bi-lock fa-3x mb-3" style="font-size: 4rem; color: #143CAD"></i>
                        <h4 class="card-title">Encryption Link</h4>
                        <p class="card-text">Lindungi privasi Anda dengan fitur enkripsi URL kami. Enkripsikan tautan
                            pendek
                            Anda dengan kata sandi. Jaga keamanan data dan privasi Anda setiap kali membagikan tautan.
                        </p>
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
                        Apa itu Shortlink?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Shortlink adalah URL yang telah dipendekkan dari bentuk aslinya. Biasanya, shortlink
                            digunakan untuk menghemat ruang dan membuat tautan lebih mudah diingat dan dibagikan.
                            Misalnya, tautan panjang seperti "https://puprtes-my.sharepoint.com/:x:/:y/:z/abcde" dapat
                            dipendekkan menjadi "https://pupr.ai/example". Meskipun ukurannya lebih kecil, shortlink
                            tetap mengarahkan pengguna ke halaman yang sama dengan URL aslinya.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Apa Kegunaan Enkripsi URL?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Enkripsi URL penting untuk menjaga keamanan data sensitif saat berbagi tautan online. Dengan
                            mengenkripsi URL, informasi yang dikirimkan melalui tautan dapat dilindungi dari akses yang
                            tidak sah atau perubahan oleh pihak yang tidak diinginkan. Ini membantu melindungi privasi
                            pengguna dan mencegah potensi penipuan atau serangan cyber yang mengancam keamanan data.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apa Kegunaan Shortlink?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Shortlink memiliki beberapa kegunaan utama:</p>
                        <ul>
                            <li>Menghemat Ruang: Shortlink lebih pendek, sehingga lebih cocok digunakan ketika
                                membagikan informasi.</li>
                            <li>Mudah Diingat: URL yang pendek lebih mudah diingat dan diketik oleh pengguna.</li>
                            <li>Estetika: Tautan yang pendek dan rapi lebih terlihat profesional dan lebih menarik,
                                terutama ketika digunakan dalam penyebaran informasi.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Apa itu kustomisasi URL?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Fitur kustomisasi URL memungkinkan pengguna untuk membuat shortlink yang lebih personal dan
                            relevan. Alih-alih menggunakan string karakter acak, Anda bisa mengganti bagian dari
                            shortlink dengan teks yang mudah diingat dan relevan dengan konten. Misalnya, Anda bisa
                            mengganti "https://pupr.ai/xyz123" menjadi "https://pupr.ai/RapatBesar". Fitur ini tidak
                            hanya membuat tautan lebih mudah diingat, tetapi juga dapat meningkatkan kepercayaan dan
                            klik dari pengguna, karena tautan terlihat lebih sah dan relevan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>