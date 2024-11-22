<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-4">
                <div class="col-12">
                    <h3>Laporan Manajemen Aset Kendaraan</h3>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pemeliharaan & Perawatan</h5>
                        <div class="list-group mt-3">
                            <a href="<?= base_url('laporan/pemeliharaan-rutin') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-check me-2"></i>Jadwal Pemeliharaan Rutin
                            </a>
                            <a href="<?= base_url('laporan/kerusakan') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-tools me-2"></i>Laporan Kerusakan/Perbaikan
                            </a>
                            <a href="<?= base_url('laporan/riwayat-pemeliharaan') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Pemeliharaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pengamanan & Penertiban</h5>
                        <div class="list-group mt-3">
                            <a href="<?= base_url('laporan/kepatuhan') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-shield-check me-2"></i>Pemantauan Kepatuhan
                            </a>
                            <a href="<?= base_url('laporan/insiden') ?>" class="list-group-item list-group-item-action">
                                <i class="bi bi-exclamation-triangle me-2"></i>Laporan Insiden
                            </a>
                            <a href="<?= base_url('laporan/penertiban') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-list-check me-2"></i>Tindakan Penertiban
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Monitoring & Analisis</h5>
                        <div class="list-group mt-3">
                            <a href="<?= base_url('laporan/statistik-aset') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-graph-up me-2"></i>Statistik Aset
                            </a>
                            <a href="<?= base_url('laporan/penggunaan') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-pie-chart me-2"></i>Laporan Penggunaan
                            </a>
                            <a href="<?= base_url('laporan/analisis') ?>"
                                class="list-group-item list-group-item-action">
                                <i class="bi bi-file-earmark-bar-graph me-2"></i>Analisis & Rekomendasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>