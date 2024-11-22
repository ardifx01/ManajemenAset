<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Selamat Datang, <a><?= user()->username; ?></a></h3>
            <p class="text-muted">Dashboard Manajemen Aset Kendaraan</p>
        </div>
        <div>
            <span class="badge bg-primary"><?= date('d F Y') ?></span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-inboxes"></i>
                </div>
                <h4 class="fs-5 fw-bold"><?= $total_kendaraan ?></h4>
                <p class="text-muted mb-0">Total Kendaraan</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h4 class="fs-5 fw-bold"><?= $kendaraan_tersedia ?></h4>
                <p class="text-muted mb-0">Kendaraan Tersedia</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <h4 class="fs-5 fw-bold"><?= count($peminjaman_pending) + count($pengembalian_pending) ?></h4>
                <p class="text-muted mb-0">Menunggu Verifikasi</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h4 class="fs-5 fw-bold"><?= $peminjaman_aktif ?></h4>
                <p class="text-muted mb-0">Peminjaman Aktif</p>
            </div>
        </div>
    </div>

    <div class="quick-actions mb-4">
        <div class="action-card" data-action="tambah">
            <i class="bi bi-plus-circle fs-4 text-primary mb-2"></i>
            <h6>Tambah Kendaraan</h6>
        </div>
        <div class="action-card" data-action="pemeliharaan">
            <i class="bi bi-tools fs-4 text-warning mb-2"></i>
            <h6>Buat Jadwal Pemeliharaan</h6>
        </div>
        <div class="action-card" data-action="verifikasi">
            <i class="bi bi-gear fs-4 text-info mb-2"></i>
            <h6>Verifikasi</h6>
        </div>
        <div class="action-card" data-action="laporan">
            <i class="bi bi-file-earmark-text fs-4 text-success mb-2"></i>
            <h6>Buat Laporan</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12 mb-4">
            <div class="chart-card">
                <canvas id="peminjamanChart"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-12 mb-4">
            <div class="chart-card">
                <canvas id="pengembalianChart"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalVerifikasi" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalVerifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerifikasiLabel">Verifikasi Peminjaman & Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="tab-content" id="verificationTabContent">
                    <ul class="nav nav-tabs" id="verificationTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="peminjaman-tab" data-bs-toggle="tab" href="#peminjaman"
                                role="tab">
                                Peminjaman Pending
                                <?php if (!empty($peminjaman_pending)): ?>
                                    <span class="badge bg-danger"><?= count($peminjaman_pending) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengembalian-tab" data-bs-toggle="tab" href="#pengembalian"
                                role="tab">
                                Pengembalian Pending
                                <?php if (!empty($pengembalian_pending)): ?>
                                    <span class="badge bg-danger"><?= count($pengembalian_pending) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="verificationTabContent">
                        <div class="tab-pane fade show active" id="peminjaman">
                            <?php if (empty($peminjaman_pending)): ?>
                                <div class="alert alert-info mt-3">
                                    Tidak ada peminjaman yang menunggu verifikasi
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Penanggung Jawab</th>
                                                <th>Kendaraan</th>
                                                <th>Status</th>
                                                <th>Dokumen</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($peminjaman_pending as $pinjam): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($pinjam['created_at'])) ?></td>
                                                    <td><?= $pinjam['nama_penanggung_jawab'] ?></td>
                                                    <td><?= $pinjam['merk'] ?></td>
                                                    <td>
                                                        <span class="badge bg-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($pinjam['surat_jalan'])): ?>
                                                            <a href="<?= base_url('/uploads/documents/' . $pinjam['surat_jalan']) ?>"
                                                                target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                                <i class="bi bi-file-earmark-pdf"></i> Surat Jalan
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (!empty($pinjam['surat_pemakaian'])): ?>
                                                            <a href="<?= base_url('/uploads/documents/' . $pinjam['surat_pemakaian']) ?>"
                                                                target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                                <i class="bi bi-file-earmark-pdf"></i> Surat Pemakaian
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (!empty($pinjam['berita_acara_penyerahan'])): ?>
                                                            <a href="<?= base_url('/uploads/documents/' . $pinjam['berita_acara_penyerahan']) ?>"
                                                                target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                                <i class="bi bi-file-earmark-pdf"></i> Berita Acara
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $pinjam['tanggal_pinjam'] ?></td>
                                                    <td><?= $pinjam['tanggal_kembali'] ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success"
                                                            onclick="verifikasiPeminjaman(<?= $pinjam['id'] ?>, 'disetujui')">
                                                            Setujui
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="showTolakModal('peminjaman', <?= $pinjam['id'] ?>)">
                                                            Tolak
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="pengembalian">
                            <?php if (empty($pengembalian_pending)): ?>
                                <div class="alert alert-info mt-3">
                                    Tidak ada pengembalian yang menunggu verifikasi
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Penanggung Jawab</th>
                                                <th>Kendaraan</th>
                                                <th>Status</th>
                                                <th>Dokumen</th>
                                                <th>Tanggal Pinjam</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pengembalian_pending as $kembali): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($kembali['created_at'])) ?></td>
                                                    <td><?= $kembali['nama_penanggung_jawab'] ?></td>
                                                    <td><?= $kembali['merk'] ?></td>
                                                    <td>
                                                        <span class="badge bg-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($kembali['surat_pengembalian'])): ?>
                                                            <a href="<?= base_url('/uploads/documents/' . $kembali['surat_pengembalian']) ?>"
                                                                target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                                <i class="bi bi-file-earmark-pdf"></i> Surat Jalan
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (!empty($kembali['berita_acara_pengembalian'])): ?>
                                                            <a href="<?= base_url('/uploads/documents/' . $kembali['berita_acara_pengembalian']) ?>"
                                                                target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                                <i class="bi bi-file-earmark-pdf"></i> Berita Acara
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $kembali['tanggal_pinjam'] ?></td>
                                                    <td><?= $kembali['tanggal_kembali'] ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success"
                                                            onclick="verifikasiPengembalian(<?= $kembali['id'] ?>, 'disetujui')">
                                                            Setujui
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="showTolakModal('pengembalian', <?= $kembali['id'] ?>)">
                                                            Tolak
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLaporan" tabindex="-1" aria-labelledby="modalLaporanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLaporanLabel">Buat Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLaporan" action="<?= base_url('/admin/laporan/tambah'); ?>" method="post"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-200">
                            <div class="mb-3">
                                <label for="kendaraan_laporan" class="form-label">Kendaraan</label>
                                <select class="form-select" id="kendaraan_laporan" name="kendaraan_id" required>
                                    <option value="">Pilih Kendaraan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                                <select class="form-select" id="jenis_laporan" name="jenis_laporan" required>
                                    <option value="" class="text-muted" disabled selected>Pilih Jenis Laporan</option>
                                    <option value="Laporan Insiden">Laporan Insiden</option>
                                    <option value="Laporan Kerusakan">Laporan Kerusakan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian</label>
                                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="lokasi_kejadian" class="form-label">Lokasi Kejadian</label>
                                <input type="text" class="form-control" id="lokasi_kejadian" name="lokasi_kejadian"
                                    placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="4" placeholder=""
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="tindak_lanjut" class="form-label">Tindak Lanjut</label>
                                <textarea class="form-control" id="tindak_lanjut" name="tindak_lanjut" rows="3"
                                    placeholder=""></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="bukti_foto" class="form-label">Bukti Foto</label>
                                <input type="file" class="form-control" id="bukti_foto" name="bukti_foto"
                                    accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreviewGambar" tabindex="-1" aria-labelledby="modalPreviewGambarLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewGambarLabel">Preview Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 500px;">
            </div>
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

<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahJadwalLabel">Tambah Jadwal Pemeliharaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahJadwal" action="<?= base_url('/PemeliharaanRutin/tambahJadwal'); ?>" method="post"
                name="pemeliharaan_rutin">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kendaraan" class="form-label">Kendaraan</label>
                        <select class="form-select" id="kendaraan" name="kendaraan_id" required>
                            <option value="">Pilih Kendaraan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pemeliharaan" class="form-label">Jenis Pemeliharaan</label>
                        <select class="form-select" id="jenis_pemeliharaan" name="jenis_pemeliharaan" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Service Rutin">Service Rutin</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Tune Up">Tune Up</option>
                            <!-- <option value="Ganti Spareparts">Ganti Spareparts</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_terjadwal" class="form-label">Tanggal Terjadwal</label>
                        <input type="date" class="form-control" id="tanggal_terjadwal" name="tanggal_terjadwal"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="bengkel" class="form-label">Bengkel</label>
                        <input type="text" class="form-control" id="bengkel" name="bengkel">
                    </div>
                    <div class="mb-3">
                        <label for="biaya" class="form-label">Estimasi Biaya</label>
                        <input type="number" class="form-control" id="biaya" name="biaya" min="0" step="1000">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTolakLabel">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTolak">
                    <input type="hidden" id="tolakId" name="id">
                    <input type="hidden" id="tolakTipe" name="tipe">
                    <div class="mb-3">
                        <label for="alasanPenolakan" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="alasanPenolakan" name="alasan" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="submitPenolakan()">Kirim</button>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= base_url() ?>';
    const ROUTES = {
        getKendaraan: BASE_URL + '/admin/pemeliharaan-rutin/get-kendaraan',
        getPemeliharaan: BASE_URL + '/admin/pemeliharaan-rutin/get-pemeliharaan',
        tambahJadwal: BASE_URL + '/admin/pemeliharaan-rutin/tambah-jadwal',
        deleteJadwal: BASE_URL + '/admin/pemeliharaan-rutin/delete',
        updateJadwal: BASE_URL + '/admin/pemeliharaan-rutin/update',
        exportExcel: BASE_URL + '/admin/pemeliharaan-rutin/export-excel',
        exportPDF: BASE_URL + '/admin/pemeliharaan-rutin/export-pdf',
        tambahLaporan: BASE_URL + '/admin/laporan/tambah',
        getLaporan: BASE_URL + '/admin/laporan/get-laporan',
        updateLaporan: BASE_URL + '/admin/laporan/update',
        deleteLaporan: BASE_URL + '/admin/laporan/delete'
    };
</script>

<?= $this->endSection() ?>