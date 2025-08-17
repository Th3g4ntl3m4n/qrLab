{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    {{-- Bootstrap 5 + Icons (CDN) --}}
    @once
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            /* Tema oscuro para el cuerpo (puedes cambiar a claro si quieres) */
            :root{
                --bs-body-bg: #0f1623;
                --bs-body-color: #e9ecef;
            }
            .card{ background-color:#131b2b; border-color:#22314a; }
            .table { color:#e9ecef; }
            .table thead th{ color:#9db3d1; border-color:#22314a; }
            .table tbody td{ border-color:#22314a; }
            .truncate { max-width:460px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        </style>
    @endonce

    <div class="container py-4">
        {{-- Alerts --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Columna izquierda: Generador + Resumen (+ Uso por usuario si admin) --}}
            <div class="col-lg-5">

                {{-- Generar QR --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-qr-code me-2"></i>Generar QR de una URL
                        </h5>

                        <form class="needs-validation" novalidate method="POST" action="{{ route('qrs.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">URL</label>
                                <input type="url" name="url" class="form-control form-control-lg"
                                       placeholder="https://ejemplo.com" required>
                                <div class="invalid-feedback">Ingresa una URL válida.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-magic"></i> Generar
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Resumen --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="mb-2 text-muted">Resumen</h6>
                        <div class="d-flex gap-3 align-items-center flex-wrap">
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                Total: {{ $qrs->total() }}
                            </span>

                            @if($isAdmin)
                                <span class="badge bg-primary">
                                    QRs (global): {{ $globalCount }}
                                </span>
                            @endif

                            @php
                                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                                $bytes = 0;
                                foreach ($qrs as $qr) {
                                    foreach ([$qr->file_png, $qr->file_svg] as $p) {
                                        if ($p && $disk->exists($p)) $bytes += $disk->size($p);
                                    }
                                }
                                $human = $bytes > 0 ? number_format($bytes/1024, 1).' KB' : '0 KB';
                            @endphp
                            <span class="badge bg-secondary">Espacio (página actual): {{ $human }}</span>
                        </div>
                    </div>
                </div>

                {{-- Uso por usuario (solo admin) --}}
                @if($isAdmin)
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <h6 class="mb-2 text-muted">
                                <i class="bi bi-people me-2"></i>Uso por usuario
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th class="text-end">QRs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($perUser as $row)
                                            <tr>
                                                <td>{{ $row->user->name ?? '—' }}</td>
                                                <td class="text-muted">{{ $row->user->email ?? '' }}</td>
                                                <td class="text-end">
                                                    <span class="badge bg-primary">{{ $row->total }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-muted">Sin datos.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div> {{-- /col-lg-5 --}}

            {{-- Columna derecha: Historial --}}
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">
                            @if($isAdmin)
                                <i class="bi bi-collection me-2"></i>Todos los QR generados
                            @else
                                <i class="bi bi-clock-history me-2"></i>Mis QR generados
                            @endif
                        </h5>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        @if($isAdmin)
                                            <th style="width: 220px;">Usuario</th>
                                        @endif
                                        <th>URL</th>
                                        <th class="text-nowrap">Fecha</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($qrs as $qr)
                                        <tr>
                                            @if($isAdmin)
                                                <td>
                                                    {{ $qr->user->name ?? '—' }}
                                                    <div class="text-muted small">{{ $qr->user->email ?? '' }}</div>
                                                </td>
                                            @endif

                                            <td class="truncate">
                                                <a href="{{ $qr->url }}" target="_blank" class="link-light text-decoration-none">
                                                    {{ $qr->url }}
                                                </a>
                                            </td>
                                            <td class="text-muted">
                                                {{ $qr->created_at->diffForHumans() }}
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-2">
                                                    @if ($qr->file_png)
                                                        <a href="{{ route('qrs.download', ['qr' => $qr->id, 'format' => 'png']) }}"
                                                           class="btn btn-sm btn-outline-primary">PNG</a>
                                                    @endif
                                                    <a href="{{ route('qrs.download', ['qr' => $qr->id, 'format' => 'svg']) }}"
                                                       class="btn btn-sm btn-outline-secondary">SVG</a>

                                                    @unless($isAdmin)
                                                        <form method="POST" action="{{ route('qrs.destroy', $qr) }}"
                                                              onsubmit="return confirm('¿Eliminar este QR?')" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                                        </form>
                                                    @endunless
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $isAdmin ? 4 : 3 }}" class="text-muted">Aún no hay QRs generados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-end">
                            {{ $qrs->onEachSide(1)->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div> {{-- /col-lg-7 --}}
        </div> {{-- /row --}}
    </div> {{-- /container --}}

    @once
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Validación Bootstrap
            (() => {
                'use strict';
                const forms = document.querySelectorAll('.needs-validation');
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', e => {
                        if (!form.checkValidity()) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        </script>
    @endonce
</x-app-layout>
