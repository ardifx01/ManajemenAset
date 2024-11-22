<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Tindakan Penertiban</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Log Tindakan Penertiban</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTindakan">
                    <i class="bi bi-plus"></i> Tambah Tindakan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelPenertiban">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Kendaraan</th>
                                <th>Pengguna</th>
                                <th>Tindakan</th>
                                <th>Sanksi</th>
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

<div class="modal fade" id="modalTambahTindakan">
</div>

<?= $this->endSection() ?>