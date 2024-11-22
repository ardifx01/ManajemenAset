<?php
helper('auth');
$uri = service('uri')->getSegments();
$uri1 = $uri[1] ?? 'index';
?>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <div class="navbar-brand-wrapper">
            <div class="app-brand">
                <h3 class="app-title">
                    <a href="<?= base_url('beranda'); ?>" class="app-name">Manajemen Aset</a>
                </h3>
            </div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarMain">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($uri1 == 'index') ? 'active' : '' ?>" href="<?= base_url('homepage') ?>">
                        <i class="bi bi-grid-fill"></i> Home
                    </a>
                </li>

                <?php if (in_groups('user') && !in_groups('admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($uri1 == 'index') ? 'active' : '' ?>"
                            href="<?= base_url('user/riwayat') ?>">
                            <i class="bi bi-sliders"></i> Riwayat
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (in_groups('admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($uri1 == 'dashboard') ? 'active' : '' ?>"
                            href="<?= base_url('admin/dashboard') ?>">
                            <i class="bi bi-sliders"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-card-list"></i> Riwayat
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/riwayat') ?>">
                                    <i class="bi bi-arrow-left-right me-2"></i>Peminjaman & Pengembalian
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/laporan/pemeliharaan-rutin') ?>">
                                    <i class="bi bi-tools me-2"></i>Pemeliharaan
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-collection-fill"></i> Aset
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
                                    <i class="bi bi-plus-circle"></i> Tambah Aset
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-file-earmark-text"></i> Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <h6 class="dropdown-header">Pemeliharaan & Perawatan</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/laporan/pemeliharaan-rutin') ?>">
                                    <i class="bi bi-calendar-check me-2"></i>Jadwal Pemeliharaan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/laporan/kerusakan') ?>">
                                    <i class="bi bi-tools me-2"></i>Laporan Kerusakan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/laporan/riwayat-pemeliharaan') ?>">
                                    <i class="bi bi-clock-history me-2"></i>Riwayat Pemeliharaan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Pengamanan & Penertiban</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/laporan/insiden') ?>">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Laporan Insiden
                                </a>
                            </li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($uri1 == 'index') ? 'active' : '' ?>" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>

                <span>|</span>

                <a class="nav-link <?= ($uri1 == 'index') ? 'active' : '' ?>"
                    style="color:blue"><?= user()->fullname; ?></a>

            </ul>
        </div>

    </div>
</nav>

<div class="modal fade" id="modalPengembalian" tabindex="-1" aria-labelledby="modalPengembalianLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPengembalianLabel">Form Pengembalian Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPengembalian" action="<?= base_url('/AsetKendaraan/kembali'); ?>" method="post"
                class="kembali" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nama_penanggung_jawab">Nama Penanggung Jawab</label>
                                <input type="text" class="form-control" id="nama_penanggung_jawab"
                                    name="nama_penanggung_jawab" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="nip_nrp">NIP / NRP</label>
                                <input type="text" class="form-control" id="nip_nrp" name="nip_nrp" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="pangkat_golongan">Pangkat / Golongan</label>
                                <input type="text" class="form-control" id="pangkat_golongan" name="pangkat_golongan"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="unit_organisasi">Unit Organisasi</label>
                                <input type="text" class="form-control" id="unit_organisasi" name="unit_organisasi"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="surat_pengembalian">Surat Pengembalian (PDF)</label>
                                <input type="file" class="form-control" id="surat_pengembalian"
                                    name="surat_pengembalian" accept="application/pdf" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kendaraan_id">Kendaraan</label>
                                <select class="form-control" id="kendaraan_id_kembali" name="kendaraan_id" required>
                                    <option value="" disabled selected>Kendaraan</option>
                                </select>
                                <input type="hidden" id="kendaraan_id_hidden" name="kendaraan_id">
                            </div>

                            <div class="form-group mb-3">
                                <label for="pengemudi">Nama Pengemudi</label>
                                <input type="text" class="form-control" id="pengemudi" name="pengemudi" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam"
                                    readonly required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tanggal_kembali">Tanggal Kembali</label>
                                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="berita_acara_pengembalian">Berita Acara Pengembalian (PDF)</label>
                                <input type="file" class="form-control" id="berita_acara_pengembalian"
                                    name="berita_acara_pengembalian" accept="application/pdf" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalPeminjaman" tabindex="-1" aria-labelledby="modalPeminjamanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPeminjamanLabel">Form Peminjaman Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPeminjaman" action="<?= base_url('/AsetKendaraan/pinjam'); ?>" method="post" class="pinjam"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nama_penanggung_jawab">Nama Penanggung Jawab</label>
                                <input type="text" class="form-control" id="nama_penanggung_jawab"
                                    name="nama_penanggung_jawab" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="nip_nrp">NIP / NRP</label>
                                <input type="text" class="form-control" id="nip_nrp" name="nip_nrp" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="pangkat_golongan">Pangkat / Golongan</label>
                                <input type="text" class="form-control" id="pangkat_golongan" name="pangkat_golongan"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="unit_organisasi">Unit Organisasi</label>
                                <input type="text" class="form-control" id="unit_organisasi" name="unit_organisasi"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="surat_jalan">Surat Jalan (PDF)</label>
                                <input type="file" class="form-control" id="surat_jalan" name="surat_jalan"
                                    accept="application/pdf" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kendaraan_id">Pilih Kendaraan</label>
                                <select class="form-control" id="kendaraan_id_pinjam" name="kendaraan_id" required>
                                    <option value="" disabled selected>Pilih Kendaraan</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="pengemudi">Nama Pengemudi</label>
                                <input type="text" class="form-control -lg" id="pengemudi" name="pengemudi" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tanggal_kembali">Tanggal Kembali</label>
                                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="surat_pemakaian">Surat Pemakaian (PDF)</label>
                                <input type="file" class="form-control" id="surat_pemakaian" name="surat_pemakaian"
                                    accept="application/pdf" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="berita_acara_penyerahan">Berita Acara Penyerahan (PDF)</label>
                                <input type="file" class="form-control" id="berita_acara_penyerahan"
                                    name="berita_acara_penyerahan" accept="application/pdf" required>
                            </div>
                        </div>

                        <div class="form-group mb-3 mt-auto order-last">
                            <label for="urusan_kedinasan">Urusan Kedinasan</label>
                            <textarea class="form-control" id="urusan_kedinasan" name="urusan_kedinasan" rows="3"
                                required></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahAsetLabel">Tambah Aset Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formTambahAset" action="<?= base_url('/AsetKendaraan/tambah'); ?>" method="post" class="assets"
                enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_id">Kategori</label>
                                <select class="form-control" id="kategori_id" name="kategori_id" required>
                                    <option value="" class="text-muted" disabled selected> Pilih Kategori Aset</option>
                                    <option class="fw-bold text-dark" value="Plat Merah">Plat Merah</option>
                                    <option class="fw-bold text-dark" value="Plat Hitam">Plat Hitam</option>
                                    <option class="fw-bold text-dark" value="Bus">Bus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="no_sk_psp">No SK PSP</label>
                                <input type="text" class="form-control" id="no_sk_psp" name="no_sk_psp" required>
                            </div>
                            <div class="form-group">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                            </div>
                            <div class="form-group">
                                <label for="merk">Merk</label>
                                <input type="text" class="form-control" id="merk" name="merk" required>
                            </div>
                            <div class="form-group">
                                <label for="tahun_pembuatan">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan">
                            </div>
                            <div class="form-group">
                                <label for="tahun_pembuatan">Kapasitas</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor_polisi">Nomor Polisi</label>
                                <input type="text" class="form-control" id="no_polisi" name="no_polisi">
                            </div>
                            <div class="form-group">
                                <label for="no_bpkb">No BPKB</label>
                                <input type="number" class="form-control" id="no_bpkb" name="no_bpkb">
                            </div>
                            <div class="form-group">
                                <label for="no_stnk">No STNK</label>
                                <input type="number" class="form-control" id="no_stnk" name="no_stnk">
                            </div>
                            <div class="form-group">
                                <label for="no_rangka">No Rangka</label>
                                <input type="number" class="form-control" id="no_rangka" name="no_rangka">
                            </div>
                            <div class="form-group">
                                <label for="kondisi">Kondisi</label>
                                <select class="form-control" id="kondisi" name="kondisi">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gambar_mobil">Gambar Mobil (JPG/PNG)</label>
                                <input type="file" class="form-control" id="gambar_mobil" name="gambar_mobil"
                                    accept="image/jpeg,image/png" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="fileUnsafeModal" tabindex="-1" aria-labelledby="fileUnsafeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="fileUnsafeModalLabel">Peringatan Keamanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-shield-x text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center" id="fileUnsafeMessage">File yang Anda upload terdeteksi tidak aman</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>