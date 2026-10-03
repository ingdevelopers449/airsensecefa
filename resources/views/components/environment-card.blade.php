{{-- ================================================================
   Componente: Tarjeta de Ambiente Asignado (environment-card)
   Uso: <x-environment-card :ambiente="$nombre" :estado="$estado" />

   Props:
   - ambiente (string): Nombre del ambiente/aula asignada
   - estado  (string): verde | amarillo | rojo | sin_datos
   ================================================================ --}}

@props([
    'ambiente' => 'Sin asignar',
    'estado'   => 'sin_datos',
])

@php
    $config = match($estado) {
        'verde' => [
            'badge'  => 'success',
            'label'  => 'Óptimo',
            'icon'   => 'bi-check-circle-fill',
            'accent' => '#39A900',
        ],
        'amarillo' => [
            'badge'  => 'warning',
            'label'  => 'En alerta',
            'icon'   => 'bi-exclamation-triangle-fill',
            'accent' => '#f9a825',
        ],
        'rojo' => [
            'badge'  => 'danger',
            'label'  => 'Crítico',
            'icon'   => 'bi-exclamation-octagon-fill',
            'accent' => '#e63946',
        ],
        default => [
            'badge'  => 'secondary',
            'label'  => 'Sin datos',
            'icon'   => 'bi-hourglass-split',
            'accent' => '#6c757d',
        ],
    };
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4 environment-card-component" {{ $attributes }}>

    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-building me-2" style="color: var(--sena-green, #39A900);"></i>
            Ambiente Asignado
        </h5>

        <span class="badge text-bg-{{ $config['badge'] }} rounded-pill px-3 py-2">
            <i class="{{ $config['icon'] }} me-1"></i>
            {{ $config['label'] }}
        </span>
    </div>

    <div class="card-body px-4 pb-4">
        <div class="d-flex align-items-center gap-3">
            {{-- Indicador visual de estado --}}
            <div class="environment-status-indicator"
                 style="border-color: {{ $config['accent'] }};">
                <div class="environment-status-dot"
                     style="background-color: {{ $config['accent'] }};
                            box-shadow: 0 0 12px {{ $config['accent'] }}40;">
                </div>
            </div>

            <div>
                <h3 class="fw-bold mb-0">{{ $ambiente }}</h3>
                <small class="text-secondary">Aula asignada al instructor</small>
            </div>
        </div>
    </div>

</div>
