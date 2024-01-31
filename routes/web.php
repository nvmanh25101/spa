<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\VnpayController;
use App\Models\Destination;
use App\Models\Reservation;
use App\Models\Tour;
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
        Route::group([
            'controller' => TourController::class,
            'as' => 'tours.',
            'prefix' => 'tours',
        ], function () {
            Route::get('/', 'index')->name('index');
            Route::get('/api', 'api')->name('api');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::get('/{id}/schedule', 'edit_schedule')->name('edit_schedule');
            Route::put('/{id}/schedule', 'update_schedule')->name('update_schedule');
            Route::get('/schedule', 'create_schedule')->name('create_schedule');
            Route::post('/schedule', 'store_schedule')->name('store_schedule');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::patch('/{id}/reviews/{reviewId}', 'review')->name('review');
        });

        Route::group([
            'middleware' => 'checkAdminRole'
        ], function () {
            Route::group([
                'middleware' => 'isSuperAdmin'
            ], function () {
                Route::group([
                    'controller' => AdminController::class,
                    'as' => 'employees.',
                    'prefix' => 'employees',
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
                'controller' => DestinationController::class,
                'as' => 'destinations.',
                'prefix' => 'destinations',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::post('/', 'store')->name('store');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });

            Route::group([
                'controller' => \App\Http\Controllers\Admin\ReservationController::class,
                'as' => 'reservations.',
                'prefix' => 'reservations',
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/api', 'api')->name('api');
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
    Route::get('/blogs', 'blogs')->name('blogs');
    Route::get('/blog/{id}', 'blog')->name('blog');
    Route::get('/{id}', 'tour')->name('tour');
});
//'middleware' => ['auth', 'verified']

Route::group([
    'prefix' => 'reservations',
    'as' => 'reservations.',
    'controller' => ReservationController::class,
], function () {
    Route::get('/{id}/booking', 'create')->name('booking');
    Route::get('/', 'index')->name('index');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/{id}', 'update')->name('update');
    Route::post('/', 'store')->name('store');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::group([
    'prefix' => 'favorite',
    'as' => 'favorite.',
    'controller' => FavoriteController::class,
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{tourId}', 'store')->name('store');
    Route::delete('/{id}', 'destroy')->name('destroy');
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
])->name('tours.review');
