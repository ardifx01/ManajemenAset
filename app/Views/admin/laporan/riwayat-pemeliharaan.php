<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Riwayat Pemeliharaan Kendaraan</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-select" id="filterKendaraan">
                                    <option value="">Semua Kendaraan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="month" class="form-control" id="filterBulan">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterJenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="Service Rutin">Service Rutin</option>
                                    <option value="Perbaikan">Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success me-2">
                            <i class="bi bi-file-excel"></i> Export Excel
                        </button>
                        <button class="btn btn-danger">
                            <i class="bi bi-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="tabelRiwayat">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kendaraan</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Biaya</th>
                                <th>Bengkel</th>
                                <th>Status</th>
                                <th>Dokumen</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>