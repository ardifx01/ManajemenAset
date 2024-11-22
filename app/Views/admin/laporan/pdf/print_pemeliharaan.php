<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .date {
            font-size: 12px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                size: landscape;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            padding: 10px 20px;
            background: blue;
            color: white;
            border: none;
            border-radius: 5rem;
            cursor: pointer;
            z-index: 1000;
        }

        .save-pdf-button {
            position: fixed;
            top: 20px;
            padding: 10px 20px;
            background: blueviolet;
            color: white;
            border: none;
            border-radius: 5rem;
            cursor: pointer;
            z-index: 1000;
        }

        .print-button:hover,
        .save-pdf-button:hover {
            background: #0056b3;
        }

        .save-pdf-button {
            right: 10rem;
        }

        .print-button {
            right: 2rem;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body>
    <button onclick="saveAsPDF()" class="save-pdf-button no-print">Simpan PDF</button>
    <button onclick="window.print()" class="print-button no-print">Print PDF</button>

    <div class="header">
        <div class="title"><?= $title ?></div>
        <div class="date">Tanggal: <?= $date ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kendaraan</th>
                <th>Jenis Pemeliharaan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bengkel</th>
                <th>Biaya</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $row['merk'] . ' - ' . $row['no_polisi'] ?></td>
                    <td><?= $row['jenis_pemeliharaan'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_terjadwal'])) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['bengkel'] ?></td>
                    <td class="text-right">Rp <?= number_format($row['biaya'], 0, ',', '.') ?></td>
                    <td><?= $row['keterangan'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function saveAsPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: "landscape",
                unit: "pt",
                format: "a4"
            });

            const printButton = document.querySelector('.print-button');
            const saveButton = document.querySelector('.save-pdf-button');
            printButton.style.display = 'none';
            saveButton.style.display = 'none';

            const content = document.body;

            html2canvas(content).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps = doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                doc.save('Laporan Pemeliharaan.pdf');

                printButton.style.display = 'block';
                saveButton.style.display = 'block';
            });
        }
    </script>
</body>

</html>