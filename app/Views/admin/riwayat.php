<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <h3>Riwayat Peminjaman & Pengembalian</h3>
    </div>

    <div class="page-content">
        <ul class="nav nav-tabs" id="historyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="peminjaman-history-tab" data-bs-toggle="tab" href="#peminjaman-history">
                    Riwayat Peminjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pengembalian-history-tab" data-bs-toggle="tab" href="#pengembalian-history">
                    Riwayat Pengembalian
                </a>
            </li>
        </ul>

        <div class="tab-content" id="historyTabContent">
            <div class="tab-pane fade show active" id="peminjaman-history">
                <?php if (empty($peminjaman_history)): ?>
                    <div class="alert alert-info mt-3">
                        Tidak ada riwayat peminjaman
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table" id="tabelRiwayatPeminjaman">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Kendaraan</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Urusan Kedinasan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjaman_history as $pinjam): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($pinjam['created_at'])) ?></td>
                                        <td><?= $pinjam['nama_penanggung_jawab'] ?></td>
                                        <td><?= $pinjam['merk'] ?></td>
                                        <td>
                                            <?php if ($pinjam['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Menunggu</span>
                                            <?php elseif ($pinjam['status'] === 'disetujui'): ?>
                                                <span class="badge bg-success">Disetujui</span>
                                            <?php elseif ($pinjam['status'] === 'ditolak'): ?>
                                                <span class="badge bg-danger">Ditolak</span>
                                            <?php endif; ?>
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
                                        <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pinjam'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($pinjam['tanggal_kembali'])) ?></td>
                                        <td><?= $pinjam['urusan_kedinasan'] ?></td>
                                        <td><?= $pinjam['keterangan'] ?? '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="pengembalian-history">
                <?php if (empty($pengembalian_history)): ?>
                    <div class="alert alert-info mt-3">
                        Tidak ada riwayat pengembalian
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table" id="tabelRiwayatPengembalian">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Kendaraan</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengembalian_history as $kembali): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($kembali['created_at'])) ?></td>
                                        <td><?= $kembali['nama_penanggung_jawab'] ?></td>
                                        <td><?= $kembali['merk'] ?></td>
                                        <td>
                                            <?php if ($kembali['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Menunggu</span>
                                            <?php elseif ($kembali['status'] === 'disetujui'): ?>
                                                <span class="badge bg-success">Disetujui</span>
                                            <?php elseif ($kembali['status'] === 'ditolak'): ?>
                                                <span class="badge bg-danger">Ditolak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($kembali['surat_pengembalian'])): ?>
                                                <a href="<?= base_url('/uploads/documents/' . $kembali['surat_pengembalian']) ?>"
                                                    target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="bi bi-file-earmark-pdf"></i> Surat Pengembalian
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($kembali['berita_acara_pengembalian'])): ?>
                                                <a href="<?= base_url('/uploads/documents/' . $kembali['berita_acara_pengembalian']) ?>"
                                                    target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="bi bi-file-earmark-pdf"></i> Berita Acara
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($kembali['tanggal_pinjam'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($kembali['tanggal_kembali'])) ?></td>
                                        <td><?= $kembali['keterangan'] ?? '-' ?></td>
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

<?= $this->endSection() ?>