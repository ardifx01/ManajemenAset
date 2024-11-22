<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Laporan Insiden Kendaraan</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Daftar Insiden</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahInsiden">
                    <i class="bi bi-plus"></i> Tambah Insiden
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filterJenis">
                            <option value="">Semua Jenis Insiden</option>
                            <option value="Kecelakaan">Kecelakaan</option>
                            <option value="Kerusakan">Kerusakan</option>
                            <option value="Pelanggaran">Pelanggaran</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterSeveritas">
                            <option value="">Semua Tingkat</option>
                            <option value="Ringan">Ringan</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Berat">Berat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Proses">Dalam Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="tabelInsiden">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kendaraan</th>
                                <th>Jenis Insiden</th>
                                <th>Lokasi</th>
                                <th>Tingkat</th>
                                <th>Pengguna</th>
                                <th>Status</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahInsiden">
</div>

<?= $this->endSection() ?>