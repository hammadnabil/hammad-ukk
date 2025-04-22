    <?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\kasirController;
    use App\Http\Controllers\ManagerController;
    use App\Http\Controllers\WaiterController;
    use Illuminate\Support\Facades\Route;


    Route::get('/', function () {
        return view('welcome');
    });


    route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard')->middleware('auth');

    route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    route::post('/login', [AuthController::class, 'login']);
    route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/logs', [AdminController::class, 'log'])->name('admin.logs.index');
    });

    Route::middleware(['auth', 'manager'])->group(function () {
        Route::get('/manager/menu', [ManagerController::class, 'indexMenu'])->name('manager.menu.index');
        Route::get('/manager/menu/create', [ManagerController::class, 'createMenu'])->name('manager.menu.create');
        Route::post('menu', [ManagerController::class, 'storeMenu'])->name('manager.menu.store');
        Route::get('menu/{menu}/edit', [ManagerController::class, 'editMenu'])->name('manager.menu.edit');
        Route::put('menu/{menu}', [ManagerController::class, 'updateMenu'])->name('manager.menu.update');
        Route::delete('manager/menu/{menu}', [ManagerController::class, 'destroyMenu'])->name('manager.menu.destroy');
        Route::get('manager/history',[ManagerController::class, 'history'])->name('manager.history');
        Route::get('/manager/logs', [ManagerController::class, 'log'])->name('manager.logs');
        Route::get('/manager/report', [ManagerController::class, 'report']) ->name('manager.report');
        Route::get('/manager/report/export-excel', [ManagerController::class, 'exportExcel'])->name('manager.report.export_excel');
    });

    Route::middleware(['auth', 'waiter'])->group(function() {
        Route::get('waiter/order/index', [WaiterController::class, 'indexOrder'])->name('waiter.orders.index');
        Route::get('waiter/order/create', [WaiterController::class, 'createOrder'])->name('waiter.orders.create');
        Route::post('waiter/order/store', [WaiterController::class, 'storeOrder'])->name('waiter.orders.store');
        Route::get('/waiter/orders/{id}/edit',[WaiterController::class, 'editOrder'])->name('waiter.orders.edit');
        Route::put('/waiter/orders/{id}', [WaiterController::class, 'updateOrder'])->name('waiter.orders.update');
        Route::get('/menus/search', [ManagerController::class, 'search'])->name('menus.search');
    });

    Route::middleware(['auth','kasir'])->group(function(){
        Route::get('kasir/index', [kasirController::class,'index'])->name('kasir.index');
        Route::get('/payment/{id}', [kasirController::class,'show'])->name('kasir.payment');
        Route::post('kasir/payment/{id}', [kasirController::class, 'processPayment'])->name('kasir.processPayment');
        Route::get('kasir/history',[kasirController::class, 'history'])->name('kasir.history');
    });