<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Laporan Kerusakan Kendaraan</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Daftar Laporan Kerusakan</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKerusakan">
                    <i class="bi bi-plus"></i> Tambah Laporan
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filterKendaraan">
                            <option value="">Semua Kendaraan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterTingkat">
                            <option value="">Semua Tingkat</option>
                            <option value="Ringan">Ringan</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Berat">Berat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="tabelKerusakan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kendaraan</th>
                                <th>Jenis Kerusakan</th>
                                <th>Tingkat</th>
                                <th>Tanggal Lapor</th>
                                <th>Status</th>
                                <th>Estimasi Biaya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahKerusakan">
</div>

<?= $this->endSection() ?>