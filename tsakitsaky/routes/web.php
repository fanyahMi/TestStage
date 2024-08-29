<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AxeController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeleveryController;
use App\Http\Controllers\models\Controllers;
use App\Http\Controllers\PackController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsersController;

//Export et Import
Route::get('/export/csv', [UsersController::class, 'exportToCSV'])->name('export.csv');
Route::get('/export/excel', [UsersController::class, 'exportToExcel'])->name('export.excel');
Route::get('/pdf', [Controllers::class, 'pdf'])->name('pdf');

//ListeFormulaire avec tout les champ
Route::get('/formgeneralize', [Controllers::class, 'formgeneralize'])->name('formgeneralize');
Route::get('/formUpdate/{id}', [Controllers::class, 'getDetail'])->name('formUpdate/{id}');
Route::post('/addform', [Controllers::class, 'addform'])->name('addform');
Route::post('/updateM', [Controllers::class, 'updateM'])->name('updateM');
Route::post('/importerFinal', [Controllers::class, 'importFinal'])->name('importerFinal');

//Login
Route::get('/signIn', [\App\Http\Controllers\UsersController::class, 'signIn'])->name('signIn');
Route::get('/signUp',\App\Http\Controllers\UsersController::class . '@signUp');
Route::post('/register',\App\Http\Controllers\UsersController::class . '@register');
Route::get('/logout',\App\Http\Controllers\UsersController::class . '@logout');
Route::post('/login',\App\Http\Controllers\UsersController::class . '@login');

//Chart
Route::get('/chart-donutData', [ChartController::class, 'donutData']);
Route::get('/chart-secteureData', [ChartController::class, 'secteureData']);
Route::get('/chart-barChartData', [ChartController::class, 'barChartData']);
Route::get('/chart-lineSimpleChartData', [ChartController::class, 'lineSimpleChartData']);
Route::get('/chart-linePlusData', [ChartController::class, 'linePlusData']);

//Admin
Route::get('/', [AdminController::class, 'index'])->middleware('checkUserSession');
Route::post('/ajout_place', [AdminController::class, 'storePlace'])->name('place.store');
Route::get('/get_places', [AdminController::class, 'getPlaces'])->name('places.get');

//Ticket
Route::get('/windTicket', [TicketController::class, 'showWindTicket'])->middleware('checkUserSession');
Route::get('/ticketPayment', [TicketController::class, 'showTicketPayment'])->middleware('checkUserSession');
Route::get('/ticketsold', [TicketController::class, 'showTicketSold'])->middleware('checkUserSession');
Route::post('/createTicket', [TicketController::class, 'createTicket'])->middleware('checkUserSession');
Route::post('/ticketState', [TicketController::class, 'ticketPayment'])->middleware('checkUserSession');
Route::post('/ticketPriceTotal', [TicketController::class, 'ticketPriceTotal'])->middleware('checkUserSession');
Route::post('/checkCustomer', [TicketController::class, 'checkCustomer'])->middleware('checkUserSession');
Route::post('/importVente', [TicketController::class, 'importVente'])->middleware('checkUserSession');


//Pack
Route::get('/listPack', [PackController::class, 'showPacks'])->middleware('checkUserSession');
Route::post('/createPack', [PackController::class, 'createPack'])->middleware('checkUserSession');
Route::get('/detailPack/{pack}', [PackController::class, 'showDetailPack'])->middleware('checkUserSession');
Route::post('/createDetailPack', [PackController::class, 'createDetailPack'])->middleware('checkUserSession');
Route::delete('/deletePackDetail/{id}', [PackController::class, 'deleteDetailPack'])->middleware('checkUserSession')->name('detail-packs.delete');
Route::get('/getDetailPack/{id}', [PackController::class, 'getDetailPack'])->middleware('checkUserSession');
Route::post('/updateDetailPack', [PackController::class, 'updateDetailPack'])->name('updateDetailPack')->middleware('checkUserSession');
Route::get('/pack/{id}', [PackController::class, 'pack'])->middleware('checkUserSession');
Route::delete('/deletePack/{id}', [PackController::class, 'deletePack'])->name('packs.delete')->middleware('checkUserSession');
Route::post('/updatePack', [PackController::class, 'updatePack'])->middleware('checkUserSession');
Route::get('/statistique', [PackController::class, 'showStatePacks'])->middleware('checkUserSession');


//Produit
Route::get('/listProducts', [ProductController::class, 'showProducts'])->middleware('checkUserSession');
Route::post('/createProduit', [ProductController::class, 'createProduct'])->middleware('checkUserSession');


//Axe
Route::get('/axes', [AxeController::class, 'showPlaceAxe'])->middleware('checkUserSession');
Route::get('/axes/{id}', [AxeController::class, 'detailsAxe'])->middleware('checkUserSession');
Route::post('/axes', [AxeController::class, 'createAxe'])->middleware('checkUserSession');
Route::post('/places', [AxeController::class, 'createPlace'])->middleware('checkUserSession');
Route::post('/placesAxe', [AxeController::class, 'createPlaceAxe'])->middleware('checkUserSession');


//Customers
Route::get('/customers', [CustomerController::class, 'showCustomer'])->middleware('checkUserSession');
Route::post('/customers', [CustomerController::class, 'createCustomer'])->middleware('checkUserSession');

//Delevery
Route::get('/delevery', [DeleveryController::class, 'index'])->middleware('checkUserSession');
Route::get('/deleveryDetail', [DeleveryController::class, 'store'])->middleware('checkUserSession');
Route::post('/exportDelevery', [DeleveryController::class, 'exportpdf'])->middleware('checkUserSession');
Route::get('/exportState', [DeleveryController::class, 'exportToExcelStateTickets'])->middleware('checkUserSession');
