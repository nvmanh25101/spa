<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TimeController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\AppointmentController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\VnpayController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin'
], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('processLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::group([
        'middleware' => 'checkAdminLogin'
    ], function () {
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/statistic', [HomeController::class, 'statistic'])->name('statistic');
        Route::get('/get_revenue', [HomeController::class, 'get_revenue'])->name('get_revenue');
        Route::group([
            'controller' => \App\Http\Controllers\Admin\OrderController::class,
            'as' => 'orders.',
            'prefix' => 'orders',
        ], function () {
            Route::get('/', 'index')->name('index');
            Route::get('/api', 'api')->name('api');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::patch('/{id}', 'update')->name('update');
        });

        Route::group([
            'middleware' => 'checkExceptTransport'
        ], function () {
            Route::group([
                'middleware' => 'isSuperAdmin'
            ], function () {
                Route::group([
                    'controller' => \App\Http\Controllers\Admin\OrderController::class,
                    'as' => 'orders.',
                    'prefix' => 'orders',
                ], function () {
                    Route::post('/', 'store')->name('store');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::group([
                    'controller' => AdminController::class,
                    'as' => 'employees.',
                    'prefix' => 'employees',
                ], function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/api', 'api')->name('api');
                    Route::get('/resignation', 'resign')->name('resign');
                    Route::get('/resignList', 'resignList')->name('resignList');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{id}/edit', 'edit')->name('edit');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::group([
                    'controller' => VoucherController::class,
                    'as' => 'vouchers.',
                    'prefix' => 'vouchers',
                ], function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/api', 'api')->name('api');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{id}/edit', 'edit')->name('edit');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });
            });

            Route::group([
                'controller' => CategoryController::class,
                'as' => 'categories.',
                'prefix' => 'categories',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::group([
                'controller' => ServiceController::class,
                'as' => 'services.',
                'prefix' => 'services',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::delete('/{id}/prices/{price_id}', 'destroyPrice')->name('destroyPrice');
            });

            Route::group([
                'controller' => ProductController::class,
                'as' => 'products.',
                'prefix' => 'products',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::patch('/{id}/reviews/{reviewId}', 'review')->name('review');
            });

            Route::group([
                'controller' => TimeController::class,
                'as' => 'times.',
                'prefix' => 'times',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::group([
                'controller' => \App\Http\Controllers\Admin\AppointmentController::class,
                'as' => 'appointments.',
                'prefix' => 'appointments',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::patch('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::group([
                'controller' => BlogController::class,
                'as' => 'blogs.',
                'prefix' => 'blogs',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        });

    });
});

Route::group([
    'controller' => \App\Http\Controllers\Customer\AuthController::class,
], function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'processRegister')->name('processRegister');
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'processLogin')->name('processLogin');
    Route::get('/email/verify', function () {
        return view('customer.verify-email');
    })->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware([
            'auth',
            'signed',
        ])->name('verification.verify');
    Route::post('/email/verification-notification',
        'resend')->middleware([
        'auth',
        'throttle:6,1'
    ])->name('verification.send');
    Route::get('/logout', 'logout')->name('logout');
});

Route::group([
    'prefix' => '/',
    'as' => 'customers.',
    'controller' => ShopController::class,
], function () {
    Route::get('/', 'index')->name('home');
    Route::get('/services', 'services')->name('services');
    Route::get('/products', 'products')->name('products');
    Route::get('/product/{id}', 'product')->name('product');
    Route::get('/blogs', 'blogs')->name('blogs');
    Route::get('/blog/{id}', 'blog')->name('blog');
});
//'middleware' => ['auth', 'verified']

Route::group([
    'prefix' => 'reservations',
    'as' => 'reservations.',
    'controller' => AppointmentController::class,
], function () {
    Route::get('/', 'create')->name('booking');
    Route::get('/{id}', 'show')->name('show');
    Route::get('/get-services', 'getServices')->name('getServices');
    Route::get('/get-prices', 'getPrices')->name('getPrices');
    Route::get('/get-times', 'getTimes')->name('getTimes');
    Route::post('/', 'store')->name('store');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::group([
    'prefix' => 'cart',
    'as' => 'cart.',
    'controller' => CartController::class,
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::group([
    'prefix' => 'orders',
    'as' => 'orders.',
    'controller' => OrderController::class,
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::patch('/{id}/checkout', 'update')->name('update');
    Route::post('/', 'store')->name('store');
    Route::delete('/{id}', 'destroy')->name('destroy');
    Route::get('/account/orders/{id}', 'show')->name('show');
});

Route::group([
    'prefix' => 'vnpay',
    'as' => 'vnpay.',
    'controller' => VnpayController::class,
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/return', 'return')->name('return');
});

Route::group([
    'prefix' => 'account',
    'as' => 'account.',
    'controller' => AccountController::class,
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/{id}', 'edit')->name('edit');
    Route::patch('/{id}', 'update')->name('update');
});

Route::post('/{id}/reviews', [ShopController::class, 'review'])->middleware([
    'auth',
    'verified',
])->name('products.review');
