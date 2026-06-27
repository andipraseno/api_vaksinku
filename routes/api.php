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
    Route::get('/company_logo/{id}', 'system\CompanyController@logo_show');
    Route::get('/company_combo', 'system\CompanyController@combo');
    Route::post('/company_add', 'system\CompanyController@add');
    Route::post('/company_logo', 'system\CompanyController@logo');

    Route::get('/branch', 'system\BranchController@index');
    Route::get('/branch_show/{id}', 'system\BranchController@show');
    Route::get('/branch_combo', 'system\BranchController@combo');
    Route::post('/branch_add', 'system\BranchController@add');

    Route::get('/unit', 'system\UnitController@index');
    Route::get('/unit_show/{id}', 'system\UnitController@show');
    Route::get('/unit_combo/{branch_id}', 'system\UnitController@combo');
    Route::post('/unit_add', 'system\UnitController@add');

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
});

// master
Route::middleware(['cek.token'])->prefix('master')->group(function () {
    // lampiran
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

    Route::get('/kategori_mesin', 'master\KategoriMesinController@index');
    Route::get('/kategori_mesin_show/{id}', 'master\KategoriMesinController@show');
    Route::get('/kategori_mesin_combo', 'master\KategoriMesinController@combo');
    Route::post('/kategori_mesin_add', 'master\KategoriMesinController@add');

    Route::get('/group_gudang', 'master\GroupGudangController@index');
    Route::get('/group_gudang_show/{id}', 'master\GroupGudangController@show');
    Route::get('/group_gudang_combo', 'master\GroupGudangController@combo');
    Route::post('/group_gudang_add', 'master\GroupGudangController@add');

    // customer
    Route::get('/customer', 'master\CustomerController@index');
    Route::get('/customer_show/{id}', 'master\CustomerController@show');
    Route::get('/customer_combo', 'master\CustomerController@combo');
    Route::post('/customer_add', 'master\CustomerController@add');

    // produk
    Route::get('/produk', 'master\ProdukController@index');
    Route::get('/produk_show/{id}', 'master\ProdukController@show');
    Route::get('/produk_combo', 'master\ProdukController@combo');
    Route::post('/produk_add', 'master\ProdukController@add');

    // gudang
    Route::get('/gudang', 'master\GudangController@index');
    Route::get('/gudang_show/{id}', 'master\GudangController@show');
    Route::get('/gudang_combo', 'master\GudangController@combo');
    Route::post('/gudang_add', 'master\GudangController@add');

    // mesin
    Route::get('/mesin', 'master\MesinController@index');
    Route::get('/mesin_show/{id}', 'master\MesinController@show');
    Route::get('/mesin_combo', 'master\MesinController@combo');
    Route::post('/mesin_add', 'master\MesinController@add');

    // kode defect
    Route::get('/kode_defect', 'master\KodeDefectController@index');
    Route::get('/kode_defect_show/{id}', 'master\KodeDefectController@show');
    Route::get('/kode_defect_combo', 'master\KodeDefectController@combo');
    Route::post('/kode_defect_add', 'master\KodeDefectController@add');
});

// produksi
Route::middleware(['cek.token'])->prefix('produksi')->group(function () {
    Route::get('/proses', 'produksi\ProsesController@index');
    Route::get('/proses_show/{id}', 'produksi\ProsesController@show');
    Route::get('/proses_combo', 'produksi\ProsesController@combo');
    Route::post('/proses_add', 'produksi\ProsesController@add');

    Route::get('/sub_proses', 'produksi\SubProsesController@index');
    Route::get('/sub_proses_show/{id}', 'produksi\SubProsesController@show');
    Route::get('/sub_proses_combo/{proses_id}', 'produksi\SubProsesController@combo');
    Route::post('/sub_proses_add', 'produksi\SubProsesController@add');

    Route::get('/step_proses', 'produksi\StepProsesController@index');
    Route::get('/step_proses_show/{id}', 'produksi\StepProsesController@show');
    Route::get('/step_proses_combo', 'produksi\StepProsesController@combo');
    Route::post('/step_proses_add', 'produksi\StepProsesController@add');
});
