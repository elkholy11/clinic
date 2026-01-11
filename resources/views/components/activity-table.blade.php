@props([
    'title',
    'icon' => 'fas fa-list',
    'viewAllRoute' => null,
    'viewAllText' => null,
    'headers' => [],
    'items' => [],
    'emptyMessage' => 'No records found'
])

<div class="card activity-card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="{{ $icon }} me-2"></i>{{ $title }}
        </h5>
        @if($viewAllRoute && $viewAllText)
            <a href="{{ $viewAllRoute }}" class="btn btn-light btn-sm">{{ $viewAllText }}</a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        {{ $slot }}
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) }}" class="text-center text-muted">{{ $emptyMessage }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

