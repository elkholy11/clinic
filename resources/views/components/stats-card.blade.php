@props([
    'title',
    'value',
    'icon' => 'fas fa-chart-line',
    'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'color' => 'white',
    'link' => null,
    'linkText' => null
])

<div class="card stats-card" style="background: {{ $gradient }}; color: {{ $color }};">
    <div class="card-body">
        <h5 class="card-title">
            <i class="{{ $icon }} me-2"></i>{{ $title }}
        </h5>
        <h1 class="display-4">{{ $value }}</h1>
        @if($link && $linkText)
            <a href="{{ $link }}" class="text-{{ $color === 'white' ? 'white' : 'dark' }} text-decoration-none small">
                <i class="fas fa-arrow-right me-1"></i>{{ $linkText }}
            </a>
        @endif
    </div>
</div>

