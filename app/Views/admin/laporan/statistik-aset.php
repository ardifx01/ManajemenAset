<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Statistik Aset Kendaraan</h3>
    </div>

    <section class="section">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Kondisi Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartKondisi"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Tren Penggunaan Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartPenggunaan"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Detail Statistik per Kendaraan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kendaraan</th>
                                <th>Total Peminjaman</th>
                                <th>Durasi Rata-rata</th>
                                <th>Frekuensi Pemeliharaan</th>
                                <th>Total Biaya Pemeliharaan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>