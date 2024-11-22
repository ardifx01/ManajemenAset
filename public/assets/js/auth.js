document.addEventListener("DOMContentLoaded", function () {
    const unitOrganisasiSelect = document.querySelector('[name="unit_organisasi"]');
    const unitKerjaSelect = document.querySelector('[name="unit_kerja"]');
    const unitKerjaOptions = {
        "Setjen": ["Biro Perencanaan Anggaran dan Kerja Sama Luar Negeri", "Biro Kepegawaian, Organisasi, dan Tata Laksana",
            "Biro Keuangan", "Biro Umum", "Biro Hukum", "Biro Pengelolaan Barang Milik Negara", "Biro Komunikasi Publik",
            "Pusat Analisis Pelaksanaan Kebijakan", "Pusat Data dan Teknologi Informasi", "Pusat Fasilitasi Infrastruktur Daerah"],

        "Itjen": ["Sekretariat Inspektorat Jenderal", "Inspektorat I", "Inspektorat II", "Inspektorat III", "Inspektorat IV",
            "Inspektorat V", "Inspektorat VI"],

        "Ditjen Sumber Daya Air": ["Sekretariat Direktorat Jenderal", "Direktorat Sistem dan Strategi Pengelolaan Sumber Daya Air",
            "Direktorat Sungai dan Pantai", "Direktorat Irigasi dan Rawa", "Direktorat Bendungan dan Danau", "Direktorat Air Tanah dan Air Baku",
            "Direktorat Bina Operasi dan Pemeliharaan", "Direktorat Bina Teknik Sumber Daya Air", "Direktorat Kepatuhan Intern", "Pusat Pengendalian Lumpur Sidoarjo",
            "Balai Besar Wilayah Sungai Sumatera VIII Palembang", "Balai Besar Wilayah Sungai Mesuji Sekampung", "Balai Besar Wilayah Sungai Cidanau, Ciujung, Cidurian",
            "Balai Besar Wilayah Sungai Ciliwung Cisadane", "Balai Besar Wilayah Sungai Citarum", "Balai Besar Wilayah Sungai Cimanuk Cisanggarung", "Balai Besar Wilayah Sungai Pemali Juana",
            "Balai Besar Wilayah Sungai Serayu Opak", "Balai Besar Wilayah Sungai Bengawan Solo", "Balai Besar Wilayah Sungai Brantas", "Balai Besar Wilayah Sungai Pompengan Jeneberang",
            "Balai Besar Wilayah Sungai Citanduy", "Balai Wilayah Sungai Sumatra I Banda Aceh", "Balai Wilayah Sungai Sumatra II Medan", "Balai Wilayah Sungai Sumatra III Pekanbaru",
            "Balai Wilayah Sungai Sumatra IV Batam", "Balai Wilayah Sungai Bangka Belitung", "Balai Wilayah Sungai Sumatra V Padang", "Balai Wilayah Sungai Sumatra VI Jambi",
            "Balai Wilayah Sungai Sumatra VII Bengkulu", "Balai Wilayah Sungai Bali Penida", "Balai Wilayah Sungai Nusa Tenggara I Mataram", "Balai Wilayah Sungai Nusa Tenggara II Kupang",
            "Balai Wilayah Sungai Kalimantan I Pontianak", "Balai Wilayah Sungai Kalimantan II Palangkaraya", "Balai Wilayah Sungai Kalimantan III Banjarmasin",
            "Balai Wilayah Sungai Kalimantan IV Samarinda", "Balai Wilayah Sungai Kalimantan V Tanjung Selor", "Balai Wilayah Sungai Sulawesi I Manado", "Balai Wilayah Sungai Sulawesi II Gorontalo",
            "Balai Wilayah Sungai Sulawesi III Palu", "Balai Wilayah Sungai Sulawesi IV Kendari", "Balai Wilayah Sungai Maluku", "Balai Wilayah Sungai Maluku Utara", "Balai Wilayah Sungai Papua",
            "Balai Wilayah Sungai Papua Barat", "Balai Wilayah Sungai Papua Marauke", "Balai Teknik Bendungan", "Balai Teknik Pantai", "Balai Teknik Sungai", "Balai Teknik Rawa", "Balai Teknik Irigasi",
            "Balai Teknik Sabo", "Balai Teknik Hidrolika dan Geoteknik Keairan", "Balai Air Tanah", "Balai Hidrologi Dan Lingkungan Keairan"],

        "Ditjen Bina Marga": ["Sekretariat Direktorat Jenderal", "Direktorat Sistem dan Strategi Pengelenggaraan Jalan dan Jembatan",
            "Subdirektorat Keterpaduan Sistem Jaringan Jalan dan Jembatan", "Direktorat Pembangunan Jalan", "Direktorat Pembangunan Jembatan", "Direktorat Preservasi Jalan dan Jembatan Wilayah I",
            "Direktorat Preservasi Jalan dan Jembatan Wilayah II", "Direktorat Jalan Bebas Hambatan", "Direktorat Bina Teknik Jalan dan Jembatan", "Direktorat Kepatuhan Intern",
            "Balai Besar Pelaksanaan Jalan Nasional Sumatera Utara", "Balai Besar Pelaksanaan Jalan Nasional Sumatera Selatan", "Balai Besar Pelaksanaan Jalan Nasional DKI Jakarta - Jawa Barat",
            "Balai Besar Pelaksanaan Jalan Nasional Jawa Tengah - DI Yogyakarta", "Balai Besar Pelaksanaan Jalan Nasional Jawa Timur - Bali", "Balai Besar Pelaksanaan Jalan Nasional Sulawesi Selatan", "Balai Besar Pelaksanaan Jalan Nasional Kalimantan Timur",
            "Balai Pelaksanaan Jalan Nasional Aceh", "Balai Pelaksanaan Jalan Nasional Riau", "Balai Pelaksanaan Jalan Nasional Kepulauan Riau", "Balai Pelaksanaan Jalan Nasional Sumatera Barat",
            "Balai Pelaksanaan Jalan Nasional Jambi", "Balai Pelaksanaan Jalan Nasional Bengkulu", "Balai Pelaksanaan Jalan Nasional Bangka Belitung", "Balai Pelaksanaan Jalan Nasional Lampung",
            "Balai Pelaksanaan Jalan Nasional Banten", "Balai Pelaksanaan Jalan Nasional Nusa Tenggara Barat", "Balai Pelaksanaan Jalan Nasional Nusa Tenggara Timur", "Balai Pelaksanaan Jalan Nasional Kalimantan Barat",
            "Balai Pelaksanaan Jalan Nasional Kalimantan Selatan", "Balai Pelaksanaan Jalan Nasional Kalimantan Utara", "Balai Pelaksanaan Jalan Nasional Kalimantan Tengah", "Balai Pelaksanaan Jalan Nasional Sulawesi Utara",
            "Balai Pelaksanaan Jalan Nasional Gorontalo", "Balai Pelaksanaan Jalan Nasional Sulawesi Tengah", "Balai Pelaksanaan Jalan Nasional Sulawesi Tenggara",
            "Balai Pelaksanaan Jalan Nasional Sulawesi Barat", "Balai Pelaksanaan Jalan Nasional Maluku", "Balai Pelaksanaan Jalan Nasional Maluku Utara", "Balai Pelaksanaan Jalan Nasional Jayapura",
            "Balai Pelaksanaan Jalan Nasional Merauke", "Balai Pelaksanaan Jalan Nasional Papua Barat", "Balai Pelaksanaan Jalan Nasional Wamena", "Balai Bahan Jalan", "Balai Jembatan",
            "Balai Geoteknik dan Terowongan, dan Struktur", "Balai Perkerasan dan Lingkungan Jalan"],

        "Ditjen Cipta Karya": ["Sekretariat Direktorat Jenderal", "Direktorat Sistem dan Strategi Pengelenggaraan Infrastruktur Permukiman",
            "Direktorat Bina Penataan Bangunan", "Direktorat Air Minum", "Direktorat Pembangunan Jembatan", "Direktorat Pengembangan Kawasan Permukiman", "Direktorat Sanitasi", "Direktorat Prasarana Strategis",
            "Direktorat Bina Teknik Permukiman dan Perumahan", "Direktorat Kepatuhan Intern", "Balai Prasarana Permukiman Wilayah Aceh", "Balai Prasarana Permukiman Wilayah Sumatera Utara", "Balai Prasarana Permukiman Wilayah Riau",
            "Balai Prasarana Permukiman Wilayah Kepulauan Riau", "Balai Prasarana Permukiman Wilayah Sumatera Barat", "Balai Prasarana Permukiman Wilayah Sumatera Selatan", "Balai Prasarana Permukiman Wilayah Lampung",
            "Balai Prasarana Permukiman Wilayah Banten", "Balai Prasarana Permukiman Wilayah Jakarta Metropolitan", "Balai Prasarana Permukiman Wilayah Jawa Barat", "Balai Prasarana Permukiman Wilayah Jawa Tengah",
            "Balai Prasarana Permukiman Wilayah D.I. Yogyakarta", "Balai Prasarana Permukiman Wilayah Jawa Timur", "Balai Prasarana Permukiman Wilayah Bali", "Balai Prasarana Permukiman Wilayah Nusa Tenggara Barat",
            "Balai Prasarana Permukiman Wilayah Nusa Tenggara Timur", "Balai Prasarana Permukiman Wilayah Kalimantan Barat", "Balai Prasarana Permukiman Wilayah Kalimantan Selatan", "Balai Prasarana Permukiman Wilayah Kalimantan Tengah",
            "Balai Prasarana Permukiman Wilayah Kalimantan Timur", "Balai Prasarana Permukiman Wilayah Kalimantan Utara", "Balai Prasarana Permukiman Wilayah Sulawesi Utara", "Balai Prasarana Permukiman Wilayah Sulawesi Tenggara",
            "Balai Prasarana Permukiman Wilayah Sulawesi Tengah", "Balai Prasarana Permukiman Wilayah Sulawesi Selatan", "Balai Prasarana Permukiman Wilayah Papua", "Balai Prasarana Permukiman Wilayah Papua Barat",
            "Balai Prasarana Permukiman Wilayah Bengkulu", "Balai Prasarana Permukiman Wilayah Bangka Belitung", "Balai Prasarana Permukiman Wilayah Jambi", "Balai Prasarana Permukiman Wilayah Gorontalo",
            "Balai Prasarana Permukiman Wilayah Sulawesi Barat", "Balai Prasarana Permukiman Wilayah Maluku", "Balai Prasarana Permukiman Wilayah Maluku Utara", "Balai Teknologi Air Minum", "Balai Teknologi Sanitasi", "Balai Sains Bangunan",
            "Balai Bahan dan Struktur Bangunan Gedung", "Balai Kawasan Permukiman dan Perumahan"],

        "Ditjen Perumahan": ["Sekretariat Direktorat Jenderal", "Direktorat Sistem dan Strategi Pengelenggaraan Perumahan", "Direktorat Rumah Umum dan Komersial",
            "Direktorat Rumah Swadaya", "Direktorat Rumah Susun", "Direktorat Rumah Khusus", "Direktorat Kepatuhan Intern", "Balai Pelaksana Penyediaan Perumahan Sumatera II", "Balai Pelaksana Penyediaan Perumahan Sumatera III",
            "Balai Pelaksana Penyediaan Perumahan Sumatera IV", "Balai Pelaksana Penyediaan Perumahan Sumatera V", "Balai Pelaksana Penyediaan Perumahan Jawa I", "Balai Pelaksana Penyediaan Perumahan Jawa II", "Balai Pelaksana Penyediaan Perumahan Jawa III",
            "Balai Pelaksana Penyediaan Perumahan Jawa IV", "Balai Pelaksana Penyediaan Perumahan Kalimantan I", "Balai Pelaksana Penyediaan Perumahan Kalimantan II", "Balai Pelaksana Penyediaan Perumahan Sulawesi I",
            "Balai Pelaksana Penyediaan Perumahan Sulawesi I", "Balai Pelaksana Penyediaan Perumahan Sulawesi II", "Balai Pelaksana Penyediaan Perumahan Sulawesi III", "Balai Pelaksana Penyediaan Perumahan Maluku", "Balai Pelaksana Penyediaan Perumahan Papua I",
            "Balai Pelaksana Penyediaan Perumahan Sumatera I", "Balai Pelaksana Penyediaan Perumahan Nusa Tenggara I", "Balai Pelaksana Penyediaan Perumahan Nusa Tenggara II", "Balai Pelaksana Penyediaan Perumahan Papua II"],

        "Ditjen Bina Konstruksi": ["Sekretariat Direktorat Jenderal", "Direktorat Pengembangan Jasa Konstruksi", "Direktorat Kelembagaan dan Sumber Daya Konstruksi", "Direktorat Kompentensi dan Produktivitas Konstruksi", "Direktorat Pengadaan Jasa Konstruksi",
            "Direktorat Keberlanjutan Konstruksi", "Balai Jasa Konstruksi Wilayah I Aceh", "Balai Jasa Konstruksi Wilayah II Palembang", "Balai Jasa Konstruksi Wilayah III Jakarta", "Balai Jasa Konstruksi Wilayah IV Surabaya", "Balai Jasa Konstruksi Wilayah V Banjarmasin",
            "Balai Jasa Konstruksi Wilayah VI Makassar", "Balai Jasa Konstruksi Wilayah VII Jayapura", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Aceh", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sumatera Utara", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sumatera Barat",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sumatera Selatan", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Jambi", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Lampung", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Banten",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah DKI Jakarta", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Jawa Barat", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah D.I. Yogyakarta", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Jawa Tengah",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Jawa Timur", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Bali", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Nusa Tenggara Timur", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Nusa Tenggara Barat",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kalimantan Barat", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kalimantan Selatan", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kalimantan Tengah", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kalimantan Timur",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kalimantan Utara", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Utara", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Tenggara", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Tengah",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Selatan", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Papua", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Papua Barat", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Riau",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Kepulauan Papua", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Bengkulu", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Bangka Belitung", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Gorontalo",
            "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Barat", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Sulawesi Barat", "Balai Pelaksana Pemilihan Jasa Konstruksi Wilayah Maluku Utara"],

        "Ditjen Pembiayaan Infrastruktur Pekerjaan Umum dan Perumahan": ["Sekretariat Direktorat Jenderal", "Direktorat Pengembangan Sistem dan Strategi Penyelenggaraan Pembiayaan", "Direktorat Pelaksanaan Pembiayaan Infrastruktur Sumber Daya Air", "Direktorat Pelaksanaan Pembiayaan Infrastruktur Jalan dan Jembatan",
            "Direktorat Pelaksanaan Pembiayaan Infrastruktur Permukiman", "Direktorat Pelaksanaan Pembiayaan Perumahan"],

        "BPIW": ["Sekretariat Badan", "Pusat Pengembangan Infrastruktur Wilayah Nasional", "Pusat Pengembangan Infrastruktur Pekerjaan Umum dan Perumahan Rakyat Wilayah I", "Pusat Pengembangan Infrastruktur Pekerjaan Umum dan Perumahan Rakyat Wilayah II",
            "Pusat Pengembangan Infrastruktur Pekerjaan Umum dan Perumahan Rakyat Wilayah III"],

        "BPSDM": ["Sekretariat Badan", "Pusat Pengembangan Talenta", "Pusat Pengembangan Kompetensi Sumber Daya Air dan Permukaan", "Pusat Pengembangan Kompetensi Jalan, Perumahan, dan Pengembangan Infrastruktur Wilayah",
            "Pusat Pengembangan Kompetensi Manajemen", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah I Medan", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah II Palembang", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah III Jakarta",
            "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah IV Bandung", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah V Yogyakarta", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah VI Surabaya",
            "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah VII Banjarmasin", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah VIII Makassar", "Balai Pengembangan Kompetensi Pekerjaan Umum dan Perumahan Rakyat Wilayah IX Jayapura", "Balai Penilaian Kompetensi"],

        "BPJT": ["Sekretariat BPJT", "Bagian Hukum", "Bidang Teknik", "Bagian Investasi", "Bidang Operasi dan Pemeliharaan", "Bidang Pendanaan", "Kelompok Jabatan Fungsional"]
    };

    function updateUnitKerjaOptions() {
        const selectedOrganisasi = unitOrganisasiSelect.value;
        unitKerjaSelect.innerHTML = "";
        unitKerjaOptions[selectedOrganisasi].forEach(function (option) {
            const optionElement = document.createElement("option");
            optionElement.value = option;
            optionElement.textContent = option;
            unitKerjaSelect.appendChild(optionElement);
        });
    }

    unitOrganisasiSelect.addEventListener("change", updateUnitKerjaOptions);

    updateUnitKerjaOptions();
});

// BUAT TERMS & CONDITIONS
document.addEventListener('DOMContentLoaded', function () {
    var termsModal = new bootstrap.Modal(document.getElementById('termsModal'), {
        backdrop: 'static',
        keyboard: false
    });
    var agreeButton = document.getElementById('agreeButton');
    var registerForm = document.querySelector('form.users');

    function disableForm() {
        Array.from(registerForm.elements).forEach(element => {
            element.disabled = true;
        });
    }

    function enableForm() {
        Array.from(registerForm.elements).forEach(element => {
            element.disabled = false;
        });
    }

    disableForm();
    termsModal.show();

    agreeButton.addEventListener('click', function () {
        termsModal.hide();
        enableForm();
    });

    registerForm.addEventListener('submit', function (event) {
        if (agreeButton.disabled) {
            event.preventDefault();
            alert('Anda harus menyetujui Syarat & Ketentuan dan Kebijakan Privasi terlebih dahulu.');
            termsModal.show();
        }
    });
});

// BUAT FORGOT PASSWORD
class ForgotPasswordValidator {
    constructor(form) {
        this.form = form;
        this.formInput = {
            email: {
                element: form.querySelector('[name="email"]'),
                rules: {
                    required: 'Email harus diisi.',
                    pattern: {
                        value: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: 'Email harus menggunakan domain @pu.go.id',
                    },
                    domain: {
                        value: /@pu\.go\.id$/,
                        message: 'Email harus menggunakan domain @pu.go.id'
                    },
                    async: true
                }
            }
        };

        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.emailSentModal = new bootstrap.Modal(document.getElementById('emailSentModal'));
        
        this.debouncedEmailCheck = this.debounce(this.checkEmailExists.bind(this), 500);
        
        this.setupValidation();
    }

    setupValidation() {
        const emailField = this.formInput.email;
        
        emailField.element.addEventListener('input', async () => {
            if (this.validateBasicEmail()) {
                await this.debouncedEmailCheck();
            }
        });

        emailField.element.addEventListener('blur', async () => {
            if (this.validateBasicEmail()) {
                await this.checkEmailExists();
            }
        });

        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (this.validateBasicEmail() && await this.checkEmailExists()) {
                await this.submitForm();
            }
        });
    }

    validateBasicEmail() {
        const field = this.formInput.email;
        const element = field.element;
        const value = element.value.trim();

        this.resetFieldValidation(element);

        if (!value) {
            this.showError(element, field.rules.required);
            return false;
        }

        if (!field.rules.pattern.value.test(value)) {
            this.showError(element, field.rules.pattern.message);
            return false;
        }

        if (!field.rules.domain.value.test(value)) {
            this.showError(element, field.rules.domain.message);
            return false;
        }

        return true;
    }

    async checkEmailExists() {
        const field = this.formInput.email;
        const element = field.element;
        const value = element.value.trim();

        if (!value || !this.validateBasicEmail()) {
            return false;
        }
    
        try {
            const formData = new FormData();
            formData.append('email', value);
            
            const csrfToken = document.querySelector('input[name="csrf_test_name"]')?.value;
            if (csrfToken) {
                formData.append('csrf_test_name', csrfToken);
            }
    
            const response = await fetch('/auth/check-email-forgot', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
    
            const data = await response.json();
    
            if (!data.success) {
                this.showError(element, data.message);
                return false;
            }
    
            if (!data.exists) {
                this.showError(element, data.message);
                return false;
            }
    
            element.classList.add('is-valid');
            return true;
        } catch (error) {
            console.error('Validation error:', error);
            return false;
        }
    }

    debounce(func, wait) {
        let timeout;
        return async (...args) => {
            clearTimeout(timeout);
            return new Promise((resolve) => {
                timeout = setTimeout(async () => {
                    resolve(await func(...args));
                }, wait);
            });
        };
    }

    resetFieldValidation(element) {
        element.classList.remove('is-invalid', 'is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = '';
    }

    showError(element, message) {
        element.classList.add('is-invalid');
        element.classList.remove('is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = message;
    }

    async submitForm() {
        try {
            this.loadingOverlay.classList.remove('d-none');
            
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            this.loadingOverlay.classList.add('d-none');

            if (data.success) {
                this.form.reset();
                this.resetFieldValidation(this.formInput.email.element);
                this.emailSentModal.show();
            } else {
                this.showError(this.formInput.email.element, data.message);
            }
        } catch (error) {
            this.loadingOverlay.classList.add('d-none');
            console.error('Error:', error);
            this.showError(this.formInput.email.element, 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const forgotForm = document.getElementById('forgotForm');
    if (forgotForm) {
        new ForgotPasswordValidator(forgotForm);
    }
});

// BUAT RESET PASS
class ResetPasswordValidator {
    constructor(form) {
        this.form = form;
        this.formInputs = {
            token: {
                element: form.querySelector('[name="token"]'),
                rules: {
                    required: 'Token harus diisi.'
                }
            },
            email: {
                element: form.querySelector('[name="email"]'),
                rules: {
                    required: 'Email harus diisi.',
                    pattern: {
                        value: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: 'Format email tidak valid.'
                    },
                    domain: {
                        value: /@pu\.go\.id$/,
                        message: 'Email harus menggunakan domain @pu.go.id'
                    }
                }
            },
            password: {
                element: form.querySelector('[name="password"]'),
                rules: {
                    required: 'Kata Sandi harus diisi.',
                    pattern: {
                        value: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,}$/,
                        message: 'Kata Sandi harus mengandung minimal 6 karakter, karakter khusus dan angka.'
                    }
                }
            },
            pass_confirm: {
                element: form.querySelector('[name="pass_confirm"]'),
                rules: {
                    required: 'Konfirmasi Kata Sandi harus diisi.',
                    matches: {
                        field: 'password',
                        message: 'Konfirmasi Kata Sandi tidak cocok dengan Kata Sandi.'
                    }
                }
            }
        };

        this.resetSuccessModal = new bootstrap.Modal(document.getElementById('resetSuccessModal'));
        this.resetFailedModal = new bootstrap.Modal(document.getElementById('resetFailedModal'));
        
        this.setupValidation();
    }

    setupValidation() {
        Object.keys(this.formInputs).forEach(inputName => {
            const field = this.formInputs[inputName];
            const element = field.element;

            if (!element) return;

            element.addEventListener('input', () => {
                this.validateField(inputName, element.value);
            });

            element.addEventListener('blur', () => {
                this.validateField(inputName, element.value);
            });
        });

        const passwordElement = this.formInputs.password.element;
        if (passwordElement) {
            passwordElement.addEventListener('input', () => {
                const confirmElement = this.formInputs.pass_confirm.element;
                if (confirmElement && confirmElement.value) {
                    this.validateField('pass_confirm', confirmElement.value);
                }
            });
        }

        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (await this.validateAllFields()) {
                await this.submitForm();
            }
        });
    }

    validateField(inputName, value) {
        const field = this.formInputs[inputName];
        const element = field.element;
        const rules = field.rules;

        this.resetFieldValidation(element);

        if (rules.required && !value.trim()) {
            this.showError(element, rules.required);
            return false;
        }

        if (rules.pattern && !rules.pattern.value.test(value)) {
            this.showError(element, rules.pattern.message);
            return false;
        }

        if (rules.domain && !rules.domain.value.test(value)) {
            this.showError(element, rules.domain.message);
            return false;
        }

        if (rules.matches) {
            const originalValue = this.formInputs[rules.matches.field].element.value;
            if (value !== originalValue) {
                this.showError(element, rules.matches.message);
                return false;
            }
        }

        element.classList.add('is-valid');
        return true;
    }

    async validateAllFields() {
        let isValid = true;
        
        for (const inputName of Object.keys(this.formInputs)) {
            const field = this.formInputs[inputName];
            const isFieldValid = this.validateField(inputName, field.element.value);
            isValid = isValid && isFieldValid;
        }

        return isValid;
    }

    resetFieldValidation(element) {
        element.classList.remove('is-invalid', 'is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = '';
    }

    showError(element, message) {
        element.classList.add('is-invalid');
        element.classList.remove('is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = message;
    }

    async submitForm() {
        try {
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.form.reset();
                this.resetSuccessModal.show();
                
                document.querySelector('#resetSuccessModal .btn-primary').addEventListener('click', () => {
                    window.location.href = '/login';
                });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const element = this.formInputs[field]?.element;
                        if (element) {
                            this.showError(element, data.errors[field]);
                        }
                    });
                } else {
                    document.getElementById('resetFailedMessage').textContent = data.message;
                    this.resetFailedModal.show();
                }
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('resetFailedMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            this.resetFailedModal.show();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const resetForm = document.querySelector('form.users');
    if (resetForm) {
        new ResetPasswordValidator(resetForm);
    }
});

// BUAT REGISTER
class FormValidator {
    constructor(form) {
        this.form = form;
        this.validationState = new Map();
        this.formInputs = {
            fullname: {
                element: form.querySelector('[name="fullname"]'),
                rules: {
                    required: 'Fullname harus diisi.',
                    minLength: {
                        value: 4,
                        message: 'Fullname harus memiliki minimal 4 karakter.'
                    }
                }
            },
            username: {
                element: form.querySelector('[name="username"]'),
                rules: {
                    required: 'Username harus diisi.',
                    pattern: {
                        value: /^[a-zA-Z0-9\s]+$/,
                        message: 'Username hanya boleh berisi huruf, angka, dan spasi.'
                    },
                    minLength: {
                        value: 4,
                        message: 'Username harus memiliki minimal 4 karakter.'
                    },
                    maxLength: {
                        value: 30,
                        message: 'Username tidak boleh lebih dari 30 karakter.'
                    },
                    asyncValidation: true
                }
            },
            email: {
                element: form.querySelector('[name="email"]'),
                rules: {
                    required: 'Email harus diisi.',
                    pattern: {
                        value: /@pu\.go\.id$/,
                        message: 'Email harus menggunakan email kedinasan @pu.go.id.'
                    },
                    asyncValidation: true
                }
            },
            unit_organisasi: {
                element: form.querySelector('[name="unit_organisasi"]'),
                rules: {
                    required: 'Unit Organisasi harus dipilih.'
                }
            },
            unit_kerja: {
                element: form.querySelector('[name="unit_kerja"]'),
                rules: {
                    required: 'Unit Kerja harus dipilih.'
                }
            },
            password: {
                element: form.querySelector('[name="password"]'),
                rules: {
                    required: 'Kata Sandi harus diisi.',
                    pattern: {
                        value: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/,
                        message: 'Kata Sandi harus mengandung huruf besar, huruf kecil, angka, dan karakter.'
                    }
                }
            },
            pass_confirm: {
                element: form.querySelector('[name="pass_confirm"]'),
                rules: {
                    required: 'Konfirmasi Kata Sandi harus diisi.',
                    matches: {
                        field: 'password',
                        message: 'Konfirmasi Kata Sandi tidak cocok dengan Kata Sandi.'
                    }
                }
            }
        };

        this.registerSuccessModal = new bootstrap.Modal(document.getElementById('registerSuccessModal'));
        this.registerErrorModal = new bootstrap.Modal(document.getElementById('registerErrorModal'));
        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.setupValidation();
    }

    setupValidation() {
        Object.keys(this.formInputs).forEach(inputName => {
            const field = this.formInputs[inputName];
            const element = field.element;

            if (!element) return;

            element.addEventListener('input', async (e) => {
                if (field.rules.asyncValidation) {
                    await this.debounceValidate(inputName, e.target);
                } else {
                    this.validateBasicField(inputName, e.target.value);
                }
            });

            element.addEventListener('blur', async () => {
                if (field.rules.asyncValidation) {
                    const isValid = await this.validateField(inputName, element);
                    this.validationState.set(inputName, isValid);
                } else {
                    const isValid = this.validateBasicField(inputName, element.value);
                    this.validationState.set(inputName, isValid);
                }
            });
        });

        const passwordElement = this.formInputs.password.element;
        if (passwordElement) {
            passwordElement.addEventListener('input', () => {
                const confirmElement = this.formInputs.pass_confirm.element;
                if (confirmElement && confirmElement.value) {
                    this.validateBasicField('pass_confirm', confirmElement.value);
                }
            });
        }

        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (await this.validateAllFields()) {
                await this.submitForm();
            }
        });
    }

    async submitForm() {
        try {
            this.loadingOverlay.classList.remove('d-none');
            
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            this.loadingOverlay.classList.add('d-none');

            if (data.success) {
                document.getElementById('registerSuccessMessage').textContent = data.message;
                this.registerSuccessModal.show();
                
                document.querySelector('#registerSuccessModal .btn-primary').addEventListener('click', () => {
                    window.location.href = '/login';
                });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = this.form.querySelector(`[name="${field}"]`);
                        if (input) {
                            this.showError(input, data.errors[field]);
                        }
                    });
                } else {
                    document.getElementById('registerErrorMessage').innerHTML = data.message;
                    this.registerErrorModal.show();
                }
            }
        } catch (error) {
            this.loadingOverlay.classList.add('d-none');
            console.error('Error:', error);
            document.getElementById('registerErrorMessage').innerHTML = 'Terjadi kesalahan. Silakan coba lagi.';
            this.registerErrorModal.show();
        }
    }

    debounceValidate = debounce(async (inputName, element) => {
        await this.validateField(inputName, element);
    }, 500);

    validateBasicField(inputName, value) {
        const field = this.formInputs[inputName];
        const rules = field.rules;
        const element = field.element;

        this.resetFieldValidation(element);

        if (rules.required && !value.trim()) {
            this.showError(element, rules.required);
            return false;
        }

        if (rules.pattern && !rules.pattern.value.test(value)) {
            this.showError(element, rules.pattern.message);
            return false;
        }

        if (rules.minLength && value.length < rules.minLength.value) {
            this.showError(element, rules.minLength.message);
            return false;
        }

        if (rules.maxLength && value.length > rules.maxLength.value) {
            this.showError(element, rules.maxLength.message);
            return false;
        }

        if (rules.matches) {
            const originalValue = this.formInputs[rules.matches.field].element.value;
            if (value !== originalValue) {
                this.showError(element, rules.matches.message);
                return false;
            }
        }

        element.classList.add('is-valid');
        return true;
    }

    async validateField(inputName, element) {
        const value = element.value.trim();
        
        if (!this.validateBasicField(inputName, value)) {
            return false;
        }

        try {
            const formData = new FormData();
            formData.append(inputName, value);
            
            const csrfToken = document.querySelector('input[name="csrf_test_name"]')?.value;
            if (csrfToken) {
                formData.append('csrf_test_name', csrfToken);
            }

            const response = await fetch(`/auth/check-${inputName}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (!data.success || !data.available) {
                this.showError(element, data.message);
                return false;
            }

            element.classList.add('is-valid');
            return true;
        } catch (error) {
            console.error('Validation error:', error);
            return false;
        }
    }

    resetFieldValidation(element) {
        element.classList.remove('is-invalid', 'is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = '';
    }

    showError(element, message) {
        element.classList.add('is-invalid');
        element.classList.remove('is-valid');
        const feedback = element.nextElementSibling?.nextElementSibling;
        if (feedback) feedback.innerHTML = message;
    }

    async validateAllFields() {
        let isValid = true;

        for (const [inputName, field] of Object.entries(this.formInputs)) {
            if (field.rules.asyncValidation) {
                const fieldValid = await this.validateField(inputName, field.element);
                isValid = isValid && fieldValid;
            } else {
                const fieldValid = this.validateBasicField(inputName, field.element.value);
                isValid = isValid && fieldValid;
            }
        }

        return isValid;
    }
}

const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

document.addEventListener('DOMContentLoaded', () => {
    const registrationForm = document.getElementById('registerForm');
    if (registrationForm) {
        new FormValidator(registrationForm);
    }
});