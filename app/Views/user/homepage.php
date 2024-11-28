<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-container">
    <div class="page-heading">
        <div class="page-title">
            <div class="row mb-4">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Aset Kendaraan</h3>
                </div>
                <!-- <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Aset Kendaraan</li>
                        </ol>
                    </nav>
                </div> -->
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card-grid">
            <?php foreach ($aset as $item): ?>
                <div class="card h-300">
                    <div class="position-relative" style="height: 13rem;">
                        <?php if (!empty($item['gambar_mobil']) && file_exists(ROOTPATH . 'public/uploads/images/' . $item['gambar_mobil'])): ?>
                            <img src="<?= base_url('/uploads/images/' . $item['gambar_mobil']) ?>"
                                class="w-100 h-100 object-fit-cover" alt="<?= $item['merk'] ?>"
                                style="border-top-left-radius: .7rem; border-top-right-radius: .7rem;">
                        <?php else: ?>
                            <img src="<?= base_url('/assets/images/faces/1.jpg') ?>" class="w-100 h-100 object-fit-cover"
                                alt="<?= $item['merk'] ?>"
                                style="border-top-left-radius: .7rem; border-top-right-radius: .7rem;">
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <?= $item['merk'] ?>
                        </h5>
                        <div class="mb-3">
                            <p class="mb-1"><small class="text-muted">No. Polisi:</small>
                                <?= $item['no_polisi'] ?>
                            </p>
                            <p class="mb-1"><small class="text-muted">Tahun:</small>
                                <?= $item['tahun_pembuatan'] ?>
                            </p>
                            <p class="mb-1"><small class="text-muted">Kode Barang:</small>
                                <?= $item['kode_barang'] ?>
                            </p>
                            <p class="mb-1"><small class="text-muted">Tipe Kendaraan:</small>
                                <?= $item['kategori_id'] ?>
                            </p>
                            <p class="mb-1"><small class="text-muted">Kapasitas:</small>
                                <?= $item['kapasitas'] ?>
                                Orang
                            </p>
                            <?php if (!empty($item['tanggal_kembali'])): ?>
                                <p class="mb-1">
                                    <small class="text-muted">Kembali:
                                        <?= date('d/m/Y', strtotime($item['tanggal_kembali'])) ?>
                                    </small>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <span class="badge <?= $item['kondisi'] === 'Baik' ? 'bg-success' :
                                ($item['kondisi'] === 'Rusak Ringan' ? 'bg-warning' : 'bg-danger') ?>">
                                <?= $item['kondisi'] ?>
                            </span>

                            <span class="badge <?= $item['status_pinjam'] === 'Tersedia' ? 'bg-success' :
                                ($item['status_pinjam'] === 'Pending' ? 'bg-warning' : 'bg-info') ?>">
                                <?= $item['status_pinjam'] ?>
                            </span>

                            <?php if (!empty($item['keterangan'])): ?>
                                <span class="badge bg-danger" data-bs-toggle="tooltip" title="<?= $item['keterangan'] ?>">
                                    <i class="bi bi-info-circle"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <div class="d-grid">
                            <?php if ($item['status_pinjam'] === 'Tersedia' || $item['status_pinjam'] === 'Dalam Verifikasi'): ?>
                                <div class="d-flex flex-column gap-2">
                                    <?php if ($item['status_pinjam'] === 'Dalam Verifikasi'): ?>
                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill shadow-sm" disabled>
                                            <i class="bi bi-clock"></i> Menunggu Verifikasi
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm hover-effect"
                                            onclick="openPeminjamanModal('<?= $item['id'] ?>')">
                                            <i class="bi bi-plus-circle"></i> Pinjam
                                        </button>
                                    <?php endif; ?>

                                    <?php if (in_groups('admin')): ?>
                                        <div class="d-flex flex-column gap-2">
                                            <button type="button" class="btn btn-warning btn-sm rounded-pill shadow-sm hover-effect"
                                                onclick="openEditModal('<?= $item['id'] ?>')">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm rounded-pill shadow-sm hover-effect"
                                                onclick="deleteAset('<?= $item['id'] ?>')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-info btn-sm rounded-pill shadow-sm hover-effect"
                                        onclick="openPengembalianModal('<?= $item['id'] ?>')">
                                        <i class="bi bi-box-arrow-in-down"></i> Kembalikan
                                    </button>

                                    <?php if (in_groups('admin')): ?>
                                        <div class="mt-3">
                                            <h6 class="mb-2">Dokumen Peminjaman:</h6>
                                            <?php if (!empty($item['surat_permohonan']) && file_exists(ROOTPATH . 'public/uploads/documents/' . $item['surat_permohonan'])): ?>
                                                <a href="<?= base_url('/uploads/documents/' . $item['surat_permohonan']) ?>"
                                                    target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="bi bi-file-earmark-pdf"></i> Surat Permohonan
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($item['surat_jalan_admin']) && file_exists(ROOTPATH . 'public/uploads/documents/' . $item['surat_jalan_admin'])): ?>
                                                <a href="<?= base_url('/uploads/documents/' . $item['surat_jalan_admin']) ?>"
                                                    target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="bi bi-file-earmark-pdf"></i> Surat Jalan
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-danger btn-sm mt-2"
                                                onclick="deleteAset('<?= $item['id'] ?>')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<div class="modal fade" id="modalEditAset" tabindex="-1" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditAsetLabel">Edit Aset Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditAset" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_kategori_id">Kategori</label>
                                <select class="form-control" id="edit_kategori_id" name="kategori_id" required>
                                    <option value="" class="text-muted" disabled selected>Pilih Kategori Aset</option>
                                    <option class="fw-bold text-dark" value="Plat Merah">Plat Merah</option>
                                    <option class="fw-bold text-dark" value="Plat Hitam">Plat Hitam</option>
                                    <option class="fw-bold text-dark" value="Bus">Bus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_no_sk_psp">No SK PSP</label>
                                <input type="text" class="form-control" id="edit_no_sk_psp" name="no_sk_psp" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_kode_barang">Kode Barang</label>
                                <input type="text" class="form-control" id="edit_kode_barang" name="kode_barang"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="edit_merk">Merk</label>
                                <input type="text" class="form-control" id="edit_merk" name="merk" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_tahun_pembuatan">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="edit_tahun_pembuatan"
                                    name="tahun_pembuatan">
                            </div>
                            <div class="form-group">
                                <label for="edit_kapasitas">Kapasitas</label>
                                <input type="number" class="form-control" id="edit_kapasitas" name="kapasitas">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_no_polisi">Nomor Polisi</label>
                                <input type="text" class="form-control" id="edit_no_polisi" name="no_polisi">
                            </div>
                            <div class="form-group">
                                <label for="edit_no_bpkb">No BPKB</label>
                                <input type="number" class="form-control" id="edit_no_bpkb" name="no_bpkb">
                            </div>
                            <div class="form-group">
                                <label for="edit_no_stnk">No STNK</label>
                                <input type="number" class="form-control" id="edit_no_stnk" name="no_stnk">
                            </div>
                            <div class="form-group">
                                <label for="edit_no_rangka">No Rangka</label>
                                <input type="number" class="form-control" id="edit_no_rangka" name="no_rangka">
                            </div>
                            <div class="form-group">
                                <label for="edit_kondisi">Kondisi</label>
                                <select class="form-control" id="edit_kondisi" name="kondisi">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_gambar_mobil">Gambar Mobil (JPG/PNG)</label>
                                <input type="file" class="form-control" id="edit_gambar_mobil" name="gambar_mobil"
                                    accept="image/jpeg,image/png">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>