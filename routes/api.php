<?php

use Illuminate\Support\Facades\Route;

// loader
Route::prefix('loader')->group(function () {
    Route::get('/software/{id}', 'loader\SoftwareController@index');
    Route::post('/cek_version', 'loader\SoftwareController@cek_version');
    Route::get('/company/{id}', 'loader\CompanyController@index');
    Route::post('/access', 'loader\AccessController@index');
    Route::post('/user', 'loader\UserController@index');
});

// system
Route::middleware(['cek.token'])->prefix('system')->group(function () {
    // software
    Route::get('/software', 'system\SoftwareController@index');
    Route::get('/software_show/{id}', 'system\SoftwareController@show');
    Route::get('/software_combo', 'system\SoftwareController@combo');
    Route::post('/software_add', 'system\SoftwareController@add');

    Route::get('/tab', 'system\TabController@index');
    Route::get('/tab_show/{id}', 'system\TabController@show');
    Route::get('/tab_combo', 'system\TabController@combo');
    Route::get('/tab_combo/{software_id}', 'system\TabController@combo_by_software');
    Route::post('/tab_add', 'system\TabController@add');

    Route::get('/module', 'system\ModuleController@index');
    Route::get('/module_show/{id}', 'system\ModuleController@show');
    Route::get('/module_combo', 'system\ModuleController@combo');
    Route::get('/module_combo/{tab_id}', 'system\ModuleController@combo_by_tab');
    Route::get('/module_combo/{software_id}', 'system\ModuleController@combo_by_software');
    Route::post('/module_add', 'system\ModuleController@add');

    // profile
    Route::get('/company', 'system\CompanyController@index');
    Route::get('/company_show/{id}', 'system\CompanyController@show');
    Route::get('/company_combo', 'system\CompanyController@combo');
    Route::post('/company_add', 'system\CompanyController@add');
    Route::get('/company_logo/{id}', 'system\CompanyController@logo_show');
    Route::post('/company_logo', 'system\CompanyController@logo');

    Route::get('/branch', 'system\BranchController@index');
    Route::get('/branch_show/{id}', 'system\BranchController@show');
    Route::get('/branch_combo', 'system\BranchController@combo');
    Route::post('/branch_add', 'system\BranchController@add');

    // access
    Route::get('/access', 'system\AccessController@index');
    Route::get('/access_show/{id}', 'system\AccessController@show');
    Route::get('/access_combo', 'system\AccessController@combo');
    Route::post('/access_add', 'system\AccessController@add');

    Route::get('/otorisasi_show/{access_id}/{tab_id}', 'system\AccessController@otorisasi_show');
    Route::post('/otorisasi_delete', 'system\AccessController@otorisasi_delete');
    Route::post('/otorisasi_save', 'system\AccessController@otorisasi_save');

    Route::get('/user', 'system\UserController@index');
    Route::get('/user_show/{id}', 'system\UserController@show');
    Route::get('/user_combo', 'system\UserController@combo');
    Route::post('/user_add', 'system\UserController@add');
    Route::post('/user_password', 'system\UserController@password');

    Route::get('/setting', 'system\SettingController@index');
    Route::post('/setting_add', 'system\SettingController@add');
});

// master
Route::middleware(['cek.token'])->prefix('master')->group(function () {
    // lampiran
    Route::get('/provinsi_combo', 'master\ProvinsiController@provinsi');
    Route::get('/kota_combo/{provinsi_id}', 'master\ProvinsiController@kota');
    Route::get('/kecamatan_combo/{kota_id}', 'master\ProvinsiController@kecamatan');
    Route::get('/kelurahan_combo/{kecamatan_id}', 'master\ProvinsiController@kelurahan');
    Route::get('/kelurahan_selected/{id}', 'master\ProvinsiController@kelurahan_selected');

    Route::get('/satuan', 'master\SatuanController@index');
    Route::get('/satuan_show/{id}', 'master\SatuanController@show');
    Route::get('/satuan_combo', 'master\SatuanController@combo');
    Route::post('/satuan_add', 'master\SatuanController@add');

    Route::get('/kategori_produk', 'master\KategoriProdukController@index');
    Route::get('/kategori_produk_show/{id}', 'master\KategoriProdukController@show');
    Route::get('/kategori_produk_combo', 'master\KategoriProdukController@combo');
    Route::post('/kategori_produk_add', 'master\KategoriProdukController@add');

    Route::get('/jenis_produk', 'master\JenisProdukController@index');
    Route::get('/jenis_produk_show/{id}', 'master\JenisProdukController@show');
    Route::get('/jenis_produk_combo/{kategori_id}', 'master\JenisProdukController@combo');
    Route::post('/jenis_produk_add', 'master\JenisProdukController@add');

    Route::get('/group_customer', 'master\GroupCustomerController@index');
    Route::get('/group_customer_show/{id}', 'master\GroupCustomerController@show');
    Route::get('/group_customer_combo', 'master\GroupCustomerController@combo');
    Route::post('/group_customer_add', 'master\GroupCustomerController@add');

    Route::get('/jenis_customer', 'master\JenisCustomerController@index');
    Route::get('/jenis_customer_show/{id}', 'master\JenisCustomerController@show');
    Route::get('/jenis_customer_combo', 'master\JenisCustomerController@combo');
    Route::post('/jenis_customer_add', 'master\JenisCustomerController@add');

    Route::get('/orientasi_customer', 'master\OrientasiCustomerController@index');
    Route::get('/orientasi_customer_show/{id}', 'master\OrientasiCustomerController@show');
    Route::get('/orientasi_customer_combo', 'master\OrientasiCustomerController@combo');
    Route::post('/orientasi_customer_add', 'master\OrientasiCustomerController@add');

    Route::get('/kategori_klinik', 'master\KategoriKlinikController@index');
    Route::get('/kategori_klinik_show/{id}', 'master\KategoriKlinikController@show');
    Route::get('/kategori_klinik_combo', 'master\KategoriKlinikController@combo');
    Route::post('/kategori_klinik_add', 'master\KategoriKlinikController@add');

    Route::get('/group_salesman', 'master\GroupSalesmanController@index');
    Route::get('/group_salesman_show/{id}', 'master\GroupSalesmanController@show');
    Route::get('/group_salesman_combo', 'master\GroupSalesmanController@combo');
    Route::post('/group_salesman_add', 'master\GroupSalesmanController@add');

    Route::get('/level_salesman', 'master\LevelSalesmanController@index');
    Route::get('/level_salesman_show/{id}', 'master\LevelSalesmanController@show');
    Route::get('/level_salesman_combo', 'master\LevelSalesmanController@combo');
    Route::post('/level_salesman_add', 'master\LevelSalesmanController@add');

    // customer
    Route::get('/customer', 'master\CustomerController@index');
    Route::get('/customer_show/{id}', 'master\CustomerController@show');
    Route::get('/customer_combo', 'master\CustomerController@combo');
    Route::post('/customer_add', 'master\CustomerController@add');
    Route::get('/customer_gambar_nktp_show/{id}', 'master\CustomerController@gambar_nktp_show');
    Route::post('/customer_gambar_nktp_add', 'master\CustomerController@gambar_nktp_add');
    Route::get('/customer_gambar_npwp_show/{id}', 'master\CustomerController@gambar_npwp_show');
    Route::post('/customer_gambar_npwp_add', 'master\CustomerController@gambar_npwp_add');

    // produk
    Route::get('/produk', 'master\ProdukController@index');
    Route::get('/produk_show/{id}', 'master\ProdukController@show');
    Route::get('/produk_combo', 'master\ProdukController@combo');
    Route::post('/produk_add', 'master\ProdukController@add');
    Route::get('/produk_gambar_show/{id}', 'master\ProdukController@gambar_show');
    Route::post('/produk_gambar_add', 'master\ProdukController@gambar_add');

    // salesman
    Route::get('/salesman', 'master\SalesmanController@index');
    Route::get('/salesman_show/{id}', 'master\SalesmanController@show');
    Route::get('/salesman_combo', 'master\SalesmanController@combo');
    Route::post('/salesman_add', 'master\SalesmanController@add');
    Route::get('/salesman_gambar_nktp_show/{id}', 'master\SalesmanController@gambar_nktp_show');
    Route::post('/salesman_gambar_nktp_add', 'master\SalesmanController@gambar_nktp_add');
    Route::get('/salesman_gambar_npwp_show/{id}', 'master\SalesmanController@gambar_npwp_show');
    Route::post('/salesman_gambar_npwp_add', 'master\SalesmanController@gambar_npwp_add');
    Route::get('/salesman_gambar_foto_show/{id}', 'master\SalesmanController@gambar_foto_show');
    Route::post('/salesman_gambar_foto_add', 'master\SalesmanController@gambar_foto_add');

    // klinik
    Route::get('/klinik', 'master\KlinikController@index');
    Route::get('/klinik_show/{id}', 'master\KlinikController@show');
    Route::get('/klinik_combo', 'master\KlinikController@combo');
    Route::post('/klinik_add', 'master\KlinikController@add');
    Route::get('/klinik_gambar_nktp_show/{id}', 'master\KlinikController@gambar_nktp_show');
    Route::post('/klinik_gambar_nktp_add', 'master\KlinikController@gambar_nktp_add');
    Route::get('/klinik_gambar_npwp_show/{id}', 'master\KlinikController@gambar_npwp_show');
    Route::post('/klinik_gambar_npwp_add', 'master\KlinikController@gambar_npwp_add');
    Route::get('/klinik_gambar_foto_show/{id}', 'master\KlinikController@gambar_foto_show');
    Route::post('/klinik_gambar_foto_add', 'master\KlinikController@gambar_foto_add');
});

// website
Route::middleware(['cek.token'])->prefix('website')->group(function () {
    Route::get('/language', 'website\LanguageController@index');
    Route::get('/language_show/{id}', 'website\LanguageController@show');
    Route::get('/language_combo', 'website\LanguageController@combo');
    Route::post('/language_add', 'website\LanguageController@add');

    Route::get('/agreement/{language_id}', 'website\AgreementController@index');
    Route::post('/agreement_add', 'website\AgreementController@add');
});
