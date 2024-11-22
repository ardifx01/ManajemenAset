document.addEventListener('DOMContentLoaded', function() {
    initializePeminjamanChart();
    initializeStatusChart();
    initializePengembalianChart();
    
    setInterval(refreshChartData, 300000);

    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.dataset.action;
            
            switch(action) {
                case 'verifikasi':
                    const modalVerifikasi = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
                    modalVerifikasi.show();
                    break;
                case 'tambah':
                    const modalTambahAset = new bootstrap.Modal(document.getElementById('modalTambahAset'));
                    modalTambahAset.show();
                    break;
                case 'pemeliharaan':
                    const modalTambahJadwal = new bootstrap.Modal(document.getElementById('modalTambahJadwal'));
                    modalTambahJadwal.show();
                    break;
                    case 'laporan':
                        const modalLaporan = new bootstrap.Modal(document.getElementById('modalLaporan'));
                        modalLaporan.show();
                        
                        fetch('/admin/pemeliharaan-rutin/get-kendaraan')
                            .then(response => response.json())
                            .then(data => {
                                const select = document.getElementById('kendaraan_laporan');
                                select.innerHTML = '<option value="" class="text-muted" disabled selected>Pilih Kendaraan</option>';
                                data.forEach(kendaraan => {
                                    select.innerHTML += `<option value="${kendaraan.id}">${kendaraan.merk} - ${kendaraan.no_polisi}</option>`;
                                });
                            })
                            .catch(error => console.error('Error:', error));
                        break;
            }
        });
    });

    const formTambahAset = document.getElementById('formTambahAset');
    if (formTambahAset) {
        formTambahAset.addEventListener('submit', handleTambahAsetSubmit);
    }

    const triggerTabList = [].slice.call(document.querySelectorAll('#verificationTabs a'));
    triggerTabList.forEach(function(triggerEl) {
        new bootstrap.Tab(triggerEl);
    });

    const buktiInput = document.getElementById('bukti_foto');
    if (buktiInput) {
        buktiInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Ukuran file terlalu besar. Maksimal 2MB'
                    });
                    e.target.value = '';
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Format file tidak didukung. Gunakan JPG atau PNG'
                    });
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    const previewModal = new bootstrap.Modal(document.getElementById('modalPreviewGambar'));
                    previewModal.show();
                }
                reader.readAsDataURL(file);
            }
        });
    }

    const formLaporan = document.getElementById('formLaporan');
    if (formLaporan) {
        formLaporan.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
            
            const formData = new FormData(this);
            
            if (typeof csrfToken !== 'undefined') {
                formData.append(csrfToken.name, csrfToken.hash);
            }

            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(this.action, {
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
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        formLaporan.reset();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalLaporan'));
                        modal.hide();
                        
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan sistem'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan';
            });
        });
    }

    const btnSubmitLaporan = document.querySelector('#formLaporan button[type="submit"]');
    if (btnSubmitLaporan) {
        btnSubmitLaporan.addEventListener('click', function() {
            const form = document.getElementById('formLaporan');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
        });
    }
});

function formatDate(date) {
    if (!date) return '-';
    const d = new Date(date);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function initializePeminjamanChart() {
    const ctx = document.getElementById('peminjamanChart');
    if (!ctx) return;

    const peminjamanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Peminjaman',
                data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80],
                borderColor: '#435ebe',
                backgroundColor: 'rgba(67, 94, 190, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Peminjaman Bulanan',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    fetchPeminjamanData(peminjamanChart);
}

function initializeStatusChart() {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    const statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam', 'Maintenance'],
            datasets: [{
                data: [12, 8, 4],
                backgroundColor: [
                    '#198754',
                    '#435ebe',
                    '#ffc107' 
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Status Kendaraan',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            },
            cutout: '65%'
        }
    });
    fetchStatusData(statusChart);
}

function initializePengembalianChart() {
    const ctx = document.getElementById('pengembalianChart');
    if (!ctx) return;

    const pengembalianChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Pengembalian',
                data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80],
                borderColor: '#20c997',
                backgroundColor: 'rgba(32, 201, 151, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Pengembalian Bulanan',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    fetchPengembalianData(pengembalianChart);
}

async function fetchPeminjamanData(chart) {
    try {
        const response = await fetch('/admin/dashboard/getStatistikAPI');
        const data = await response.json();
        
        if (data.data) {
            chart.data.datasets[0].data = data.data;
            chart.update();
        }
    } catch (error) {
        console.error('Error fetching peminjaman data:', error);
    }
}

async function fetchStatusData(chart) {
    try {
        const response = await fetch('/admin/dashboard/getStatusKendaraanAPI');
        const data = await response.json();
        
        if (data.data) {
            const statusData = [
                data.data.find(item => item.status_pinjam === 'Tersedia')?.total || 0,
                data.data.find(item => item.status_pinjam === 'Dipinjam')?.total || 0,
                data.data.find(item => item.kondisi !== 'Baik')?.total || 0
            ];
            
            chart.data.datasets[0].data = statusData;
            chart.update();
        }
    } catch (error) {
        console.error('Error fetching status data:', error);
    }
}

async function fetchPengembalianData(chart) {
    try {
        const response = await fetch('/admin/dashboard/getPengembalianAPI');
        const data = await response.json();
        
        if (data.data) {
            chart.data.datasets[0].data = data.data;
            chart.update();
        }
    } catch (error) {
        console.error('Error fetching pengembalian data:', error);
    }
}

function refreshChartData() {
    const charts = Chart.instances;
    charts.forEach(chart => {
        switch(chart.canvas.id) {
            case 'peminjamanChart':
                fetchPeminjamanData(chart);
                break;
            case 'statusChart':
                fetchStatusData(chart);
                break;
            case 'pengembalianChart':
                fetchPengembalianData(chart);
                break;
        }
    });
}

window.addEventListener('resize', function() {
    Chart.instances.forEach(chart => {
        chart.resize();
    });
});