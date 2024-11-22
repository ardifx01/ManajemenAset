document.addEventListener('DOMContentLoaded', function() {
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
        
        // const modalPeminjaman = document.getElementById('modalPeminjaman');
        // if (modalPeminjaman) {
        //     modalPeminjaman.addEventListener('show.bs.modal', loadKendaraanPinjam);
        // }
    }

    const formPengembalian = document.getElementById('formPengembalian');
    if (formPengembalian) {
        formPengembalian.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const kendaraanId = document.getElementById('kendaraan_id_hidden')?.value || 
                               document.getElementById('kendaraan_id_kembali')?.value;
                               
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
                        text: 'Pengajuan pengembalian berhasil dikirim. Mohon tunggu verifikasi.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.reset();
                            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPengembalian'));
                            modal.hide();
                            window.location.reload();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Gagal menghubungi server. Silakan coba lagi.',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });
            });
        });

        const modalPengembalian = document.getElementById('modalPengembalian');
        if (modalPengembalian) {
            modalPengembalian.addEventListener('show.bs.modal', loadKendaraanKembali);
        }
    }

    const formEditAset = document.getElementById('formEditAset');
    if (formEditAset) {
        formEditAset.addEventListener('submit', handleEditAsetSubmit);
    }
});

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
    fetch(`${window.location.origin}/AsetKendaraan/verifikasiPeminjaman`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'pinjam_id': id,
            'status': status,
            'keterangan': keterangan
        })
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
    fetch(`${window.location.origin}/AsetKendaraan/verifikasiPengembalian`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'kembali_id': id,
            'status': status,
            'keterangan': keterangan
        })
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
    $('#formPengembalian')[0].reset();
    
    $.ajax({
        url: '/AsetKendaraan/getPeminjamanData/' + kendaraanId,
        method: 'GET',
        success: function(response) {
            if (response.error) {
                alert(response.error);
                return;
            }
            
            $('#formPengembalian #nama_penanggung_jawab').val(response.nama_penanggung_jawab);
            $('#formPengembalian #nip_nrp').val(response.nip_nrp);
            $('#formPengembalian #pangkat_golongan').val(response.pangkat_golongan);
            $('#formPengembalian #jabatan').val(response.jabatan);
            $('#formPengembalian #unit_organisasi').val(response.unit_organisasi);
            $('#formPengembalian #kendaraan_id_hidden').val(response.kendaraan_id);
            $('#formPengembalian #pengemudi').val(response.pengemudi);
            $('#formPengembalian #no_hp').val(response.no_hp);
            
            const tanggalPinjam = response.tanggal_pinjam.split('T')[0];
            $('#formPengembalian #tanggal_pinjam').val(tanggalPinjam);
            
            $('#formPengembalian #tanggal_pinjam').prop('readonly', true);
            
            const kendaraanOption = new Option(
                response.merk + ' - ' + response.no_polisi, 
                response.kendaraan_id, 
                true, 
                true
            );
            $('#kendaraan_id_kembali').empty().append(kendaraanOption);
            
            $('#modalPengembalian').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Terjadi kesalahan: ' + error);
        }
    });
}

function loadKendaraanKembali() {
    console.log('Kendaraan loading handled by openPengembalianModal');
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