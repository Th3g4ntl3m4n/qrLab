<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Models\GeneratedQr;

Route::view('/', 'welcome'); // portada por defecto

// Dashboard
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    // Es admin solo si el rol es "administrator".
    // Si tienes el helper $user->isAdmin(), puedes usarlo aquí en su lugar.
    $isAdmin = strcasecmp((string) $user->role, 'administrator') === 0;

    // Inicializamos para que la vista nunca falle
    $globalCount = null;
    $perUser     = collect();

    if ($isAdmin) {
        // Métricas globales
        $globalCount = GeneratedQr::count();

        $perUser = GeneratedQr::select('user_id', DB::raw('COUNT(*) as total'))
            ->groupBy('user_id')
            ->with('user:id,name,email')
            ->orderByDesc('total')
            ->get();

        // Lista global de QRs
        $qrs = GeneratedQr::with('user:id,name,email')
            ->latest()
            ->paginate(15);
    } else {
        // Solo QRs del usuario autenticado
        $qrs = GeneratedQr::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    return view('dashboard', [
        'qrs'         => $qrs,
        'isAdmin'     => $isAdmin,
        'globalCount' => $globalCount,
        'perUser'     => $perUser,
    ]);
})->name('dashboard');

// Rutas autenticadas
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // QRs
    Route::post('/qrs', [QrController::class, 'store'])->name('qrs.store');

    // Descarga restringida a png|svg
    Route::get('/qrs/{qr}/download/{format}', [QrController::class, 'download'])
        ->whereIn('format', ['png', 'svg'])
        ->name('qrs.download');

    Route::delete('/qrs/{qr}', [QrController::class, 'destroy'])->name('qrs.destroy');
});

require __DIR__ . '/auth.php';
