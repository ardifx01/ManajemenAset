document.addEventListener('DOMContentLoaded', function() {
    const formSetuju = document.getElementById('formSetuju');
    if (formSetuju) {
        formSetuju.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Mohon Tunggu',
                text: 'Sedang memproses verifikasi...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            let formData = new FormData(this);
            formData.append('status', 'disetujui');
            
            fetch(`${BASE_URL}/AsetKendaraan/verifikasiPeminjaman`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        $('#modalSetuju').modal('hide');
                        location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan saat verifikasi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat verifikasi',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        });
    }
    
    const formTambahAset = document.getElementById('formTambahAset');
    if (formTambahAset) {
        formTambahAset.addEventListener('submit', handleTambahAsetSubmit);
    }
    initializeHistoryTables();

    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('detailContent').innerHTML = '';
        });
    }

    const formPeminjaman = document.getElementById('formPeminjaman');
    if (formPeminjaman) {
        formPeminjaman.addEventListener('submit', handlePeminjamanSubmit);
    }

    const formPengembalian = document.getElementById('formPengembalian');
    if (formPengembalian) {
        formPengembalian.removeEventListener('submit', handlePengembalianSubmit);
        formPengembalian.addEventListener('submit', handlePengembalianSubmit);
    }
    
    const modalPengembalian = document.getElementById('modalPengembalian');
    if (modalPengembalian) {
    }

    const formEditAset = document.getElementById('formEditAset');
    if (formEditAset) {
        formEditAset.addEventListener('submit', handleEditAsetSubmit);
    }
});

function handlePengembalianSubmit(e) {
    e.preventDefault();
    
    const kendaraanId = document.getElementById('kendaraan_id_hidden')?.value;
    if (!kendaraanId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Data kendaraan tidak valid',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    const requiredFields = [
        'nama_penanggung_jawab', 
        'nip_nrp',
        'pangkat_golongan',
        'jabatan',
        'unit_organisasi',
        'tanggal_kembali',
        'surat_pengembalian',
        'berita_acara_pengembalian'
    ];

    for (const field of requiredFields) {
        const input = e.target.querySelector(`[name="${field}"]`);
        if (!input?.value) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Field ${field.replace(/_/g, ' ')} harus diisi`,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
    }

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses data...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData(e.target);
    
    console.log('Submitting form data:', {
        kendaraanId: kendaraanId,
        formValues: Object.fromEntries(formData)
    });

    fetch(e.target.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            if (data.error.includes('terdeteksi tidak aman')) {
                showFileUnsafeModal(data.error);
            } else {
                throw new Error(data.error);
            }
        }
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pengajuan pengembalian berhasil dikirim',
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then(() => {
                e.target.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalPengembalian'));
                if (modal) modal.hide();
                window.location.reload();
            });
        } else {
            throw new Error('Terjadi kesalahan saat memproses data');
        }
    })
    .catch(error => {
        console.error('Error in form submission:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mengirim data',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}

function showSetujuModal(pinjamId) {
    $('#pinjamId').val(pinjamId);
    $('#modalSetuju').modal('show');
}

function openEditModal(id) {
    if (!id) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID aset tidak valid',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang mengambil data...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const url = `${window.location.origin}/AsetKendaraan/getAsetById/${id}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const aset = data.data;
                
                const fields = [
                    'id', 'kategori_id', 'no_sk_psp', 'kode_barang',
                    'merk', 'tahun_pembuatan', 'kapasitas', 'no_polisi',
                    'no_bpkb', 'no_stnk', 'no_rangka', 'kondisi'
                ];
                
                for (const field of fields) {
                    if (typeof aset[field] === 'undefined') {
                        throw new Error(`Data ${field} tidak ditemukan`);
                    }
                }
                
                fields.forEach(field => {
                    const element = document.getElementById(`edit_${field}`);
                    if (element) {
                        element.value = aset[field] || '';
                    }
                });

                const currentImagePreview = document.getElementById('current_image_preview');
                if (currentImagePreview && aset.gambar_mobil) {
                    currentImagePreview.src = `${window.location.origin}/uploads/images/${aset.gambar_mobil}`;
                    currentImagePreview.style.display = 'block';
                    document.getElementById('edit_gambar_mobil').value = '';
                }
                
                Swal.close();
                const modal = new bootstrap.Modal(document.getElementById('modalEditAset'));
                modal.show();
            } else {
                throw new Error(data.error || 'Gagal mengambil data aset');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message || 'Gagal mengambil data aset',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
        });
}

function handleEditAsetSubmit(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_id').value;
    
    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses perubahan data...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData(this);

    fetch(`${window.location.origin}/AsetKendaraan/edit/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditAset'));
                    modal.hide();
                    window.location.reload();
                }
            });
        } else {
            throw new Error(data.error || 'Terjadi kesalahan saat memperbarui data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat memperbarui data',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}

function previewEditImage(input) {
    const preview = document.getElementById('edit_image_preview');
    const currentPreview = document.getElementById('current_image_preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (currentPreview) {
                currentPreview.style.display = 'none';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function showDetail(type, id) {
    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang mengambil data...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`${window.location.origin}/user/riwayat/detail/${type}/${id}`)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                document.getElementById('detailContent').innerHTML = data.html;
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal mengambil detail data',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengambil data',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
        });
}

function initializeHistoryTables() {
    const tabelRiwayatPeminjaman = document.getElementById('tabelRiwayatPeminjaman');
    const tabelRiwayatPengembalian = document.getElementById('tabelRiwayatPengembalian');

    if (tabelRiwayatPeminjaman) {
        $(tabelRiwayatPeminjaman).DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    }

    if (tabelRiwayatPengembalian) {
        $(tabelRiwayatPengembalian).DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    }
}

function showTolakModal(type, id) {
    document.getElementById('formTolak').reset();
    document.getElementById('tolakId').value = id;
    document.getElementById('tolakTipe').value = type;
    
    const modalTolak = new bootstrap.Modal(document.getElementById('modalTolak'));
    modalTolak.show();
    
    document.getElementById('modalTolak').addEventListener('shown.bs.modal', function () {
        document.getElementById('alasanPenolakan').focus();
    });
}

function submitPenolakan() {
    const id = document.getElementById('tolakId').value;
    const type = document.getElementById('tolakTipe').value;
    const alasan = document.getElementById('alasanPenolakan').value;
    
    if (!alasan.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Alasan penolakan harus diisi!',
            confirmButtonColor: '#dc3545'
        });
        return;
    }

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses penolakan...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    if (type === 'peminjaman') {
        verifikasiPeminjaman(id, 'ditolak', alasan);
    } else {
        verifikasiPengembalian(id, 'ditolak', alasan);
    }

    bootstrap.Modal.getInstance(document.getElementById('modalTolak')).hide();
}

function verifikasiPeminjaman(id, status, keterangan = '') {
    const formData = new FormData();
    formData.append('pinjam_id', id);
    formData.append('status', status);
    formData.append('keterangan', keterangan);

    if (status === 'disetujui') {
        const suratJalanInput = document.querySelector('#surat_jalan_admin');
        if (suratJalanInput && suratJalanInput.files[0]) {
            formData.append('surat_jalan_admin', suratJalanInput.files[0]);
        }
    }

    fetch(`${window.location.origin}/AsetKendaraan/verifikasiPeminjaman`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Verifikasi berhasil dilakukan',
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.error || 'Terjadi kesalahan saat verifikasi');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat verifikasi',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}

function verifikasiPengembalian(id, status, keterangan = '') {
    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses verifikasi pengembalian...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const headers = {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
    };

    const formData = new URLSearchParams({
        'kembali_id': id,
        'status': status,
        'keterangan': keterangan
    });

    const timeout = 30000;
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    fetch(`${window.location.origin}/AsetKendaraan/verifikasiPengembalian`, {
        method: 'POST',
        headers: headers,
        body: formData,
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Verifikasi pengembalian berhasil dilakukan',
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.error || 'Terjadi kesalahan saat verifikasi');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message === 'The user aborted a request.' 
                ? 'Waktu permintaan habis. Silakan coba lagi.' 
                : (error.message || 'Terjadi kesalahan saat verifikasi'),
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}

function deleteAset(id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${window.location.origin}/AsetKendaraan/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan saat menghapus data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat menghapus data',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

function openPeminjamanModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalPeminjaman'));
    
    document.getElementById('modalPeminjaman').addEventListener('shown.bs.modal', function () {
        const kendaraanSelect = document.getElementById('kendaraan_id_pinjam');
        
        fetch('/AsetKendaraan/getKendaraan')
            .then(response => response.json())
            .then(data => {
                kendaraanSelect.innerHTML = '<option value="" disabled>Pilih Kendaraan</option>';
                
                data.forEach(kendaraan => {
                    if (kendaraan.status_pinjam === 'Tersedia' || kendaraan.id === id) {
                        const option = document.createElement('option');
                        option.value = kendaraan.id;
                        option.textContent = `${kendaraan.merk} - ${kendaraan.no_polisi}`;
                        kendaraanSelect.appendChild(option);
                    }
                });
                
                if (id) {
                    kendaraanSelect.value = id;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal memuat data kendaraan',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
    }, { once: true });
    
    modal.show();
}

function loadKendaraanPinjam() {
    console.log('Kendaraan loading handled by openPeminjamanModal');
}

function openPengembalianModal(kendaraanId) {
    const form = document.getElementById('formPengembalian');
    if (form) form.reset();
    
    console.log('Loading data for kendaraan:', kendaraanId);
    
    fetch(`/AsetKendaraan/getPeminjamanData/${kendaraanId}`)
        .then(response => response.json())
        .then(response => {
            if (response.error) {
                throw new Error(response.error);
            }
            
            document.getElementById('kendaraan_id_hidden').value = kendaraanId;
            
            const fields = [
                'nama_penanggung_jawab', 'nip_nrp', 'pangkat_golongan',
                'jabatan', 'unit_organisasi', 'pengemudi', 'no_hp'
            ];
            
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (input && response[field]) {
                    input.value = response[field];
                }
            });
            
            if (response.tanggal_pinjam) {
                const tanggalPinjam = response.tanggal_pinjam.split('T')[0];
                document.getElementById('tanggal_pinjam').value = tanggalPinjam;
            }
            
            const kendaraanSelect = document.getElementById('kendaraan_id_kembali');
            if (kendaraanSelect) {
                kendaraanSelect.innerHTML = '';
                const option = new Option(
                    `${response.merk} - ${response.no_polisi}`,
                    kendaraanId,
                    true,
                    true
                );
                kendaraanSelect.appendChild(option);
            }
            
            const modal = new bootstrap.Modal(document.getElementById('modalPengembalian'));
            modal.show();
            
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Gagal memuat data kendaraan',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
        });
}

function loadKendaraanData() {
    const kendaraanSelect = document.getElementById('kendaraan_id');
    
    kendaraanSelect.disabled = true;
    
    fetch('/AsetKendaraan/getKendaraan')
        .then(response => response.json())
        .then(data => {
            kendaraanSelect.innerHTML = '<option value="" disabled selected>Pilih Kendaraan</option>';
            
            data.forEach(kendaraan => {
                const option = document.createElement('option');
                option.value = kendaraan.id;
                option.textContent = `${kendaraan.merk} - ${kendaraan.no_polisi}`;
                kendaraanSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal memuat data kendaraan',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
        })
        .finally(() => {
            kendaraanSelect.disabled = false;
        });
}

function showFileUnsafeModal(message) {
    Swal.fire({
        icon: 'error',
        title: 'File Tidak Aman',
        text: message,
        showConfirmButton: true,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#dc3545',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const fileInputs = form.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => input.value = '');
            });
        }
    });
}

function handleTambahAsetSubmit(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses data...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.error,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc3545'
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahAset'));
                    modal.hide();
                    window.location.reload();
                }
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: 'Gagal menghubungi server. Silakan coba lagi.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}

function handlePeminjamanSubmit(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memproses data dan memeriksa keamanan file...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            if (data.error.includes('terdeteksi tidak aman')) {
                showFileUnsafeModal(data.error);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.error,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            }
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pengajuan peminjaman berhasil dikirim. Mohon tunggu verifikasi.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalPeminjaman'));
                    modal.hide();
                    window.location.reload();
                }
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: 'Gagal menghubungi server. Silakan coba lagi.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc3545'
        });
    });
}