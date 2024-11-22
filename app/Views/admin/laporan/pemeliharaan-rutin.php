<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Jadwal Pemeliharaan Rutin</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Daftar Jadwal Pemeliharaan</h5>
                <div class="btn-group">
                    <button class="btn btn-success me-2" onclick="exportToExcel()">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-danger" onclick="exportToPDF()">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="kendaraan_id" name="kendaraan_id">
                            <option value="">Semua Kendaraan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="jenis_pemeliharaan" name="jenis_pemeliharaan">
                            <option value="">Semua Jenis</option>
                            <option value="Service Rutin">Service Rutin</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Tune Up">Tune Up</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="tabelPemeliharaan" name="tabelPemeliharaan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kendaraan</th>
                                <th>Jenis Pemeliharaan</th>
                                <th>Tanggal Terjadwal</th>
                                <th>Status</th>
                                <th>Bengkel</th>
                                <th>Biaya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
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

<div class="modal fade" id="modalEditJadwal" tabindex="-1" aria-labelledby="modalEditJadwalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJadwalLabel">Edit Jadwal Pemeliharaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditJadwal">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kendaraan</label>
                        <input type="text" class="form-control" id="edit_kendaraan" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Pemeliharaan</label>
                        <select class="form-select" id="edit_jenis_pemeliharaan" name="jenis_pemeliharaan" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Service Rutin">Service Rutin</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Tune Up">Tune Up</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Terjadwal</label>
                        <input type="date" class="form-control" id="edit_tanggal_terjadwal" name="tanggal_terjadwal"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bengkel</label>
                        <input type="text" class="form-control" id="edit_bengkel" name="bengkel">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya</label>
                        <input type="number" class="form-control" id="edit_biaya" name="biaya" min="0" step="1000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
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
        exportPDF: BASE_URL + '/admin/pemeliharaan-rutin/export-pdf'
    };
</script>

<?= $this->endSection() ?>