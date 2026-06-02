@php
    $global = $achievementGroups['global'] ?? ['earned' => 0, 'total' => 0, 'items' => []];
    $topicGroups = $achievementGroups['topics'] ?? [];
@endphp

<div class="achievement-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Общие</h6>
        <span class="text-muted small">{{ $global['earned'] }} / {{ $global['total'] }}</span>
    </div>
    @if(!empty($global['items']))
        <ul class="list-group list-group-flush border rounded">
            @foreach($global['items'] as $item)
                @include('profile._achievement_card', ['item' => $item])
            @endforeach
        </ul>
    @else
        <p class="text-muted small mb-0">Общие награды пока не настроены.</p>
    @endif
</div>

@foreach($topicGroups as $group)
    <div class="achievement-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">{{ $group['topic']->title }}</h6>
            <span class="text-muted small">{{ $group['earned'] }} / {{ $group['total'] }}</span>
        </div>
        @if(!empty($group['items']))
            <ul class="list-group list-group-flush border rounded">
                @foreach($group['items'] as $item)
                    @include('profile._achievement_card', ['item' => $item])
                @endforeach
            </ul>
        @endif
    </div>
@endforeach
