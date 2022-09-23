<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\MiEnvio\RateController as MiEnvioRateController;
use App\Http\Controllers\PickingController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\PushController;

use App\Http\Controllers\Datatables\PickingController as PickingControllerDT;
use App\Http\Controllers\Datatables\LogPurchaseController as LogPurchaseControllerDT;
use App\Http\Controllers\LogPurchaseController;
use App\Http\Controllers\TempImageUploadController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PosRechargeController;
use App\Http\Controllers\PosServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [WelcomeController::class, 'index'])->name('/');

Route::get('/mix', [WelcomeController::class, 'mix'])->name('/a');
Route::get('test', function () {
    event(new App\Events\PaymentReport('TEST report'));
    return "Event has been sent!";
});


Route::prefix('auth')->group(function () {

    //Route::middleware(['isLogged'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/create/user', [AuthController::class, 'create'])->name('auth.create');
    //});
    Route::get('/createRoles', [AuthController::class, 'createRoles'])->name('saf');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
Route::middleware(['auth', 'role:userF|cliente_corporativo'])->group(function () {
    Route::post('/push', [PushController::class, 'store']);
    Route::get('/pusha', [PushController::class, 'push'])->name('push');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/send', [HomeController::class, 'send'])->name('home.send');

    Route::get('/cotizar', [QuoteController::class, 'index'])->name('quote.index');
    Route::post('/pagar_wallet', [PayController::class, 'create'])->name('pay.create');
    Route::get('/shipments', [ShipmentController::class, 'show'])->name('shipment.show');
    Route::get('/envio/borrar/{index}', [ShipmentController::class, 'delete'])->name('shipment.delete');
    Route::post('/checkout/process', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::middleware(['checkPreCart'])->group(function () {
        Route::post('/envio/detalles', [ShipmentController::class, 'details'])->name('shipment.details');
        Route::get('/envio/details', [ShipmentController::class, 'detailsView'])->name('shipment.detailsView');
    });
    Route::middleware(['checkCart'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    });
    Route::post('/domicilio/guardar/origen', [AddressController::class, 'createOrigin'])->name('address.create.origin');
    Route::post('/domicilio/guardar/destino', [AddressController::class, 'createDestination'])->name('address.create.destination');

    Route::get('/domicilio/ver', [AddressController::class, 'show'])->name('address.show');
    Route::post('/cupon/usar', [CuponController::class, 'create'])->name('cupon.create');
    Route::post('/tarifas/crear', [RateController::class, 'create'])->name('rates.create');
    Route::get('/tarifas', [RateController::class, 'show'])->name('rates.show');
    Route::post('/label/create', [LabelController::class, 'create'])->name('label.create');
    Route::post('/label/delete', [LabelController::class, 'delete'])->name('label.delete');
    Route::get('/payment_report/index', [PaymentReportController::class, 'index'])->name('payment_report.index');
    Route::get('/payment_report/list', [PaymentReportController::class, 'list'])->name('payment_report.list');
    Route::get('/payment_report/create', [PaymentReportController::class, 'create'])->name('payment_report.create');
    Route::post('/payment_report/store', [PaymentReportController::class, 'store'])->name('payment_report.store');
    Route::post('/payment_report/destroy', [PaymentReportController::class, 'destroy'])->name('payment_report.destroy');

    Route::get('/notifications', [NotificationController::class, 'get'])->name('notifications.get');
    Route::post('/rastreo', [TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/per', [HomeController::class, 'per'])->name('per');
    Route::prefix('picking')->group(function () {
        Route::get('/', [PickingController::class, 'index'])->name('/picking');
    });
    Route::resource('wallet', WalletController::class, [
        'names' => [
            'index' => '/wallet',
            'store' => 'faq.new',

        ]
    ]);

    Route::get('/pickup', [PickingController::class, 'index'])->name('picking.index');
    Route::get('/pickup/crear', [PickingController::class, 'create'])->name('picking.create');
    Route::get('/pickup/detalles/{id}', [PickingController::class, 'show'])->name('picking.show');
    Route::put('/pickup/detalles/{id}', [PickingController::class, 'update'])->name('picking.update');

    Route::middleware(['checkBalance'])->group(function () {
        Route::post('/pickup/guardar', [PickingController::class, 'store'])->name('picking.store');
    });
    Route::get('/datatables/pickup', [PickingControllerDT::class, 'index'])->name('picking.datatables.index');
    Route::get('/datatables/logs', LogPurchaseControllerDT::class)->name('logs.datatables.index');

    Route::get('/recibo', [InvoiceController::class, 'pdf'])->name('invoice.pdf');

    Route::prefix('shipping')->group(function () {
        Route::get('/', [ShippingController::class, 'index'])->name('shipping');
    });
    Route::prefix('pos')->group(function () {
        Route::get('/index', [PosController::class, 'index'])->name('pos.index');
        Route::get('/ticket', [PosController::class, 'ticket'])->name('pos.ticket');

        Route::prefix('services')->group(function () {
            Route::get('/index', [PosServiceController::class, 'index'])->name('pos.service.index');
            Route::get('/subcategories/{category}', [PosServiceController::class, 'subcategory'])->name('pos.service.subcategory');
            Route::get('/product/{category}', [PosServiceController::class, 'product'])->name('pos.service.product');
            Route::post('/item', [PosServiceController::class, 'item'])->name('pos.service.item');
            Route::post('/buy', [PosServiceController::class, 'buy'])->name('pos.service.buy');

        });
        Route::prefix('recharges')->group(function () {
            Route::get('/index', [PosRechargeController::class, 'index'])->name('pos.recharge');
            Route::get('/subcategories/{category}', [PosRechargeController::class, 'subcategory'])->name('pos.recharge.subcategory');
            Route::get('/product/{category}', [PosRechargeController::class, 'product'])->name('pos.recharge.product');
            Route::post('/item', [PosRechargeController::class, 'item'])->name('pos.recharge.item');
            Route::post('/buy', [PosRechargeController::class, 'buy'])->name('pos.recharge.buy');
        });

    });
    Route::get('/movimientos', LogPurchaseController::class)->name('logs.index');
    Route::get('/movimientos/sobrepeso', [LogPurchaseController::class, 'overweight'])->name('logs.sobrepeso');
    Route::get('/movimientos/cancelados',[LogPurchaseController::class, 'cancelados'])->name('logs.cancelados');
    Route::get('/movimientos/manuales',[LogPurchaseController::class, 'manuales'])->name('logs.manuales');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});


Route::get('image-upload', [TempImageUploadController::class, 'image_upload'])->name('image.upload');
Route::post('image-upload', [TempImageUploadController::class, 'upload_post_image'])->name('upload.post.image');
