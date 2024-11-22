<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Beranda');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Beranda::index');

$routes->get('register', 'Auth::register');
$routes->post('auth/check-username', 'Auth::checkUsername');
$routes->post('auth/check-email', 'Auth::checkEmail');
$routes->post('auth/check-email-forgot', 'Auth::checkEmailForgot');
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'login'], function ($routes) {
    $routes->get('homepage', 'User\Homepage::index');
    $routes->get('user/riwayat', 'User\Riwayat::index');
    $routes->get('user/riwayat/detail/(:segment)/(:num)', 'User\Riwayat::getDetail/$1/$2');

    $routes->get('AsetKendaraan/getKendaraan', 'AsetKendaraan::getKendaraan');
    $routes->get('AsetKendaraan/getKendaraanDipinjam', 'AsetKendaraan::getKendaraanDipinjam');
    $routes->post('AsetKendaraan/pinjam', 'AsetKendaraan::pinjam');
    $routes->post('AsetKendaraan/kembali', 'AsetKendaraan::kembali');
});

$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('riwayat', 'Admin\Riwayat::index');
    $routes->post('AsetKendaraan/tambah', 'AsetKendaraan::tambah');
    $routes->delete('AsetKendaraan/delete/(:num)', 'AsetKendaraan::delete/$1');
    $routes->post('AsetKendaraan/verifikasiPeminjaman', 'AsetKendaraan::verifikasiPeminjaman');
    $routes->post('AsetKendaraan/verifikasiPengembalian', 'AsetKendaraan::verifikasiPengembalian');
    $routes->get('AsetKendaraan/getAsetById/(:num)', 'AsetKendaraan::getAsetById/$1');
    $routes->post('AsetKendaraan/edit/(:num)', 'AsetKendaraan::edit/$1');
    $routes->post('laporan/tambah', 'Admin\Laporan::tambah');
    $routes->get('laporan/get-laporan', 'Admin\Laporan::getLaporan');
    $routes->get('laporan/get-laporan/(:num)', 'Admin\Laporan::getLaporan/$1');
    $routes->post('laporan/update/(:num)', 'Admin\Laporan::update/$1');
    $routes->delete('laporan/delete/(:num)', 'Admin\Laporan::delete/$1');
    $routes->get('laporan/statistik', 'Admin\Laporan::getStatistik');

    $routes->get('pemeliharaan-rutin', 'Admin\PemeliharaanRutin::index');
    $routes->get('pemeliharaan-rutin/get-pemeliharaan', 'Admin\PemeliharaanRutin::getPemeliharaan');
    $routes->get('pemeliharaan-rutin/get-kendaraan', 'Admin\PemeliharaanRutin::getKendaraan');
    $routes->post('pemeliharaan-rutin/tambah-jadwal', 'Admin\PemeliharaanRutin::tambahJadwal');
    $routes->delete('pemeliharaan-rutin/delete/(:num)', 'Admin\PemeliharaanRutin::delete/$1');
    $routes->get('pemeliharaan-rutin/get-pemeliharaan/(:num)', 'Admin\PemeliharaanRutin::getJadwalById/$1');
    $routes->post('pemeliharaan-rutin/update/(:num)', 'Admin\PemeliharaanRutin::update/$1');
    $routes->get('pemeliharaan-rutin/export-excel', 'Admin\PemeliharaanRutin::exportExcel');
    $routes->get('pemeliharaan-rutin/export-pdf', 'Admin\PemeliharaanRutin::exportPDF');

    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/pemeliharaan-rutin', 'Laporan::pemeliharaanRutin');
    $routes->get('laporan/kerusakan', 'Laporan::kerusakan');
    $routes->get('laporan/riwayat-pemeliharaan', 'Laporan::riwayatPemeliharaan');
    $routes->get('laporan/kepatuhan', 'Laporan::kepatuhan');
    $routes->get('laporan/insiden', 'Laporan::insiden');
    $routes->get('laporan/penertiban', 'Laporan::penertiban');
    $routes->get('laporan/statistik-aset', 'Laporan::statistikAset');
    $routes->get('laporan/analisis', 'Laporan::analisis');
});

$routes->get('uploads/images/(:any)', function ($filename) {
    $path = ROOTPATH . 'public/uploads/images/' . $filename;
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});

$routes->get('uploads/documents/(:any)', function ($filename) {
    $path = ROOTPATH . 'public/uploads/documents/' . $filename;
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});