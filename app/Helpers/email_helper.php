<?php

use CodeIgniter\Email\Email;

function sendEmail($to, $subject, $message, $attachment = null)
{
    $email = \Config\Services::email();

    $email->setTo($to);
    $email->setFrom('mumbuzaki16@gmail.com', 'Sistem Manajemen Aset');
    $email->setSubject($subject);
    $email->setMessage($message);

    if ($attachment && file_exists(ROOTPATH . 'public/uploads/documents/' . $attachment)) {
        $email->attach(ROOTPATH . 'public/uploads/documents/' . $attachment);
    }

    try {
        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Email error: ' . $email->printDebugger(['headers']));
            return false;
        }
    } catch (\Exception $e) {
        log_message('error', 'Email error: ' . $e->getMessage());
        return false;
    }
}

function sendPeminjamanNotification($data, $type = 'new')
{
    $adminEmail = 'mumbuzaki16@gmail.com';
    $userEmail = $data['user_email'];

    if ($type === 'new') {
        $subject = 'Pengajuan Peminjaman Kendaraan Baru';
        $message = "
            <p>Peminjaman baru telah diajukan dengan detail berikut:</p>
            <ul>
                <li>Penanggung Jawab: {$data['nama_penanggung_jawab']}</li>
                <li>NIP/NRP: {$data['nip_nrp']}</li>
                <li>Jabatan: {$data['jabatan']}</li>
                <li>Unit Organisasi: {$data['unit_organisasi']}</li>
                <li>Tanggal Pinjam: {$data['tanggal_pinjam']}</li>
                <li>Tanggal Kembali: {$data['tanggal_kembali']}</li>
            </ul>
            <br>
            <p>Silakan masuk untuk melakukan verifikasi.</p>
        ";
        sendEmail($adminEmail, $subject, $message);
    } elseif ($type === 'verified') {
        $status = $data['status'];
        $subject = "Peminjaman Kendaraan " . ucfirst($status);

        if ($status === 'disetujui') {
            $message = "
                <p>Halo,</p>
                <p>Pengajuan peminjaman kendaraan Anda telah disetujui dengan detail sebagai berikut:</p>
                <ul>
                    <li>Penanggung Jawab: {$data['nama_penanggung_jawab']}</li>
                    <li>NIP/NRP: {$data['nip_nrp']}</li>
                    <li>Tanggal Pinjam: {$data['tanggal_pinjam']}</li>
                    <li>Tanggal Kembali: {$data['tanggal_kembali']}</li>
                </ul>
                <br>
                <p><strong>Catatan Penting:</strong></p>
                <ul>
                    <li>Surat Jalan Admin telah dilampirkan dalam email ini</li>
                    <li>Harap membawa Surat Jalan Admin yang terlampir saat pengambilan kendaraan</li>
                    <li>Pastikan untuk memeriksa kondisi kendaraan bersama petugas sebelum pengambilan</li>
                </ul>
                <p>Jika ada pertanyaan, silakan hubungi admin kami.</p>
                <br>
                <p>Terima kasih atas kepercayaan Anda.</p>
            ";

            $attachmentPath = ROOTPATH . 'public/uploads/documents/' . $data['surat_jalan_admin'];
            if (file_exists($attachmentPath)) {
                sendEmail($userEmail, $subject, $message, $data['surat_jalan_admin']);
            } else {
                log_message('error', 'Surat jalan tidak ditemukan: ' . $attachmentPath);
                sendEmail($userEmail, $subject, $message);
            }
        } else {
            $message = "
                <h2>Peminjaman Kendaraan Ditolak</h2>
                <p>Pengajuan peminjaman kendaraan Anda ditolak dengan detail sebagai berikut:</p>
                <ul>
                    <li>Penanggung Jawab: {$data['nama_penanggung_jawab']}</li>
                    <li>NIP/NRP: {$data['nip_nrp']}</li>
                    <li>Tanggal Pinjam: {$data['tanggal_pinjam']}</li>
                    <li>Tanggal Kembali: {$data['tanggal_kembali']}</li>
                </ul>
                <br>
                <p><strong>Alasan Penolakan:</strong></p>
                <p>{$data['keterangan']}</p>
                <br>
                <p>Jika ada pertanyaan, silakan hubungi admin kami.</p>
            ";
            sendEmail($userEmail, $subject, $message);
        }
    }
}

function sendPengembalianNotification($data, $type = 'new')
{
    $adminEmail = 'mumbuzaki16@gmail.com';
    $userEmail = $data['user_email'];

    if ($type === 'new') {
        $subject = 'Pengajuan Pengembalian Kendaraan';
        $message = "
            <h2>Pengajuan Pengembalian Kendaraan</h2>
            <p>Pengembalian kendaraan telah diajukan dengan detail berikut:</p>
            <ul>
                <li>Penanggung Jawab: {$data['nama_penanggung_jawab']}</li>
                <li>NIP/NRP: {$data['nip_nrp']}</li>
                <li>Tanggal Pinjam: {$data['tanggal_pinjam']}</li>
                <li>Tanggal Kembali: {$data['tanggal_kembali']}</li>
            </ul>
            <p>Silakan login ke sistem untuk memverifikasi pengembalian ini.</p>
        ";
        sendEmail($adminEmail, $subject, $message);
    } elseif ($type === 'verified') {
        $status = $data['status'];
        $subject = "Pengembalian Kendaraan " . ucfirst($status);

        if ($status === 'disetujui') {
            $message = "
                <h2>Pengembalian Kendaraan Disetujui</h2>
                <p>Pengembalian kendaraan Anda telah disetujui.</p>
                <p>Terima kasih telah menggunakan layanan kami.</p>
            ";
        } else {
            $message = "
                <h2>Pengembalian Kendaraan Ditolak</h2>
                <p>Pengembalian kendaraan Anda ditolak dengan alasan:</p>
                <p>{$data['keterangan']}</p>
            ";
        }
        sendEmail($userEmail, $subject, $message);
    }
}