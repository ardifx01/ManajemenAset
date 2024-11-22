(function() {
    if (typeof $ === 'undefined') throw new Error('jQuery is required');
    if (typeof moment === 'undefined') throw new Error('Moment.js is required');
    if (typeof Swal === 'undefined') throw new Error('SweetAlert2 is required');
    if (typeof ROUTES === 'undefined') throw new Error('ROUTES configuration is required');

    const formatCurrency = (amount) => {
        if (!amount) return '-';
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
    };

    const formatDate = (date) => {
        if (!date) return '-';
        return moment(date).format('DD/MM/YYYY');
    };

    const initializeDataTable = () => {
        return $('#tabelPemeliharaan').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            ajax: {
                url: ROUTES.getPemeliharaan,
                method: 'GET',
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('Error loading data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat data. Silakan refresh halaman.'
                    });
                }
            },
            columns: [
                { 
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { 
                    data: null,
                    render: (data, type, row) => 
                        type === 'display' ? 
                            `${row.merk} - ${row.no_polisi}` : 
                            `${row.merk} ${row.no_polisi}`
                },
                { 
                    data: 'jenis_pemeliharaan',
                    render: data => data || '-'
                },
                { 
                    data: 'tanggal_terjadwal',
                    render: formatDate
                },
                { 
                    data: 'status',
                    render: data => {
                        const badgeClass = {
                            'Pending': 'bg-warning',
                            'Selesai': 'bg-success'
                        }[data] || 'bg-secondary';
                        
                        return `<span class="badge ${badgeClass}">${data || '-'}</span>`;
                    }
                },
                { 
                    data: 'bengkel',
                    render: data => data || '-'
                },
                { 
                    data: 'biaya',
                    render: formatCurrency
                },
                { 
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: data => `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary" onclick="editJadwal(${data})" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteJadwal(${data})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>`
                }
            ],
            order: [[3, 'desc']],
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });
    };

    const loadKendaraanOptions = (targetElement) => {
        $.ajax({
            url: ROUTES.getKendaraan,
            type: 'GET',
            dataType: 'json',
            success: (response) => {
                const selectElement = $(targetElement);
                selectElement.empty();

                const defaultOption = targetElement === '#kendaraan_id' ? 
                    'Semua Kendaraan' : 'Pilih Kendaraan';
                selectElement.append(`<option value="">${defaultOption}</option>`);

                if (Array.isArray(response)) {
                    response.forEach(kendaraan => {
                        selectElement.append(
                            `<option value="${kendaraan.id}">${kendaraan.merk} - ${kendaraan.no_polisi}</option>`
                        );
                    });
                }
            },
            error: (xhr, status, error) => {
                console.error('Error loading kendaraan:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data kendaraan'
                });
            }
        });
    };

    window.exportToExcel = () => {
        const params = {
            kendaraan_id: $('#kendaraan_id').val(),
            jenis: $('#jenis_pemeliharaan').val(),
            status: $('#status').val()
        };

        const searchParams = new URLSearchParams(params).toString();
        const url = `${ROUTES.exportExcel}${searchParams ? '?' + searchParams : ''}`;

        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang menyiapkan file Excel',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                window.location.href = url;
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        });
    };

    window.exportToPDF = () => {
        const params = {
            kendaraan_id: $('#kendaraan_id').val(),
            jenis: $('#jenis_pemeliharaan').val(),
            status: $('#status').val()
        };

        const searchParams = new URLSearchParams(params).toString();
        const url = `${ROUTES.exportPDF}${searchParams ? '?' + searchParams : ''}`;

        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang menyiapkan file PDF',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                window.location.href = url;
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        });
    };

    $(document).ready(function() {
        const table = initializeDataTable();
        
        loadKendaraanOptions('#kendaraan_id');

        Object.assign(ROUTES, {
            exportExcel: BASE_URL + '/admin/pemeliharaan-rutin/export-excel',
            exportPDF: BASE_URL + '/admin/pemeliharaan-rutin/export-pdf'
        });

        $('#modalTambahJadwal').on('show.bs.modal', () => {
            loadKendaraanOptions('#kendaraan');
        });

        $('#formTambahJadwal').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: ROUTES.tambahJadwal,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: (response) => {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            $('#modalTambahJadwal').modal('hide');
                            $('#formTambahJadwal')[0].reset();
                            table.ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        });

        $('#kendaraan_id, #jenis_pemeliharaan, #status').on('change', function() {
            const params = {
                kendaraan_id: $('#kendaraan_id').val(),
                jenis: $('#jenis_pemeliharaan').val(),
                status: $('#status').val()
            };
            table.ajax.url(`${ROUTES.getPemeliharaan}?${$.param(params)}`).load();
        });
    });

    window.editJadwal = (id) => {
        console.log('Edit jadwal:', id);
    };

    window.deleteJadwal = (id) => {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${ROUTES.deleteJadwal}/${id}`,
                    type: 'DELETE',
                    success: (response) => {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#tabelPemeliharaan').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Gagal menghapus data'
                            });
                        }
                    },
                    error: () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    };
})();

let table;

window.editJadwal = function(id) {
    Swal.fire({
        title: 'Memuat Data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `${ROUTES.getPemeliharaan}/${id}`,
        type: 'GET',
        success: function(response) {
            Swal.close();
            
            $('#edit_id').val(response.id);
            $('#edit_kendaraan').val(`${response.merk} - ${response.no_polisi}`);
            $('#edit_jenis_pemeliharaan').val(response.jenis_pemeliharaan);
            $('#edit_tanggal_terjadwal').val(response.tanggal_terjadwal);
            $('#edit_status').val(response.status);
            $('#edit_bengkel').val(response.bengkel);
            $('#edit_biaya').val(response.biaya);
            $('#edit_keterangan').val(response.keterangan);

            $('#modalEditJadwal').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengambil data jadwal'
            });
        }
    });
};

$('#formEditJadwal').on('submit', function(e) {
    e.preventDefault();
    
    const id = $('#edit_id').val();
    
    Swal.fire({
        title: 'Memproses...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = $(this).serialize();

    $.ajax({
        url: `${ROUTES.updateJadwal}/${id}`,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $('#modalEditJadwal').modal('hide');
                    if (table) table.ajax.reload(null, false);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: response.message || 'Gagal mengupdate data'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Update error:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengupdate data'
            });
        }
    });
});