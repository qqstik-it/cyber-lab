@php
    $summary = $activity['summary'];
@endphp

<div class="mb-4">
    <div class="profile-hero-label mb-1">Профиль</div>
    <h5 class="fw-bold mb-1">Активность</h5>
    <p class="text-muted small mb-0">Последние принятые задания, категории и темп прохождения.</p>
</div>

<div class="profile-activity-stat-grid mb-4">
    <div class="profile-stat-card">
        <div class="profile-stat-label">Всего решений</div>
        <div class="profile-stat-value">{{ $summary['total'] }}</div>
        <p class="profile-stat-hint">в истории профиля</p>
    </div>
    <div class="profile-stat-card">
        <div class="profile-stat-label">За 30 дней</div>
        <div class="profile-stat-value">{{ $summary['last_30_days'] }}</div>
        <p class="profile-stat-hint">недавний прогресс</p>
    </div>
    <div class="profile-stat-card">
        <div class="profile-stat-label">Категорий</div>
        <div class="profile-stat-value">{{ $summary['categories_with_solutions'] }}</div>
        <p class="profile-stat-hint">с принятыми заданиями</p>
    </div>
    <div class="profile-stat-card">
        <div class="profile-stat-label">Последнее решение</div>
        @if($summary['last_solution_at'])
            <div class="profile-stat-value fs-5">{{ $summary['last_solution_at']->format('d.m.Y') }}</div>
            <p class="profile-stat-hint">{{ $summary['last_solution_at']->format('H:i') }}</p>
        @else
            <div class="profile-stat-value">—</div>
            <p class="profile-stat-hint">нет данных</p>
        @endif
    </div>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">История решений</h5>
        <span class="text-muted small">{{ $activity['history']->count() ? $activity['history']->count() . ' записей' : 'история пуста' }}</span>
    </div>

    @if($activity['history']->isNotEmpty())
        <ul class="list-group list-group-flush border rounded">
            @foreach($activity['history'] as $submission)
                @php
                    $solvedAt = $submission->reviewed_at ?? $submission->updated_at;
                @endphp
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 bg-transparent text-reset border-secondary-subtle">
                    <div>
                        <div class="fw-medium">{{ $submission->task?->title }}</div>
                        <div class="text-muted small">
                            {{ $submission->task?->level?->topic?->title }} / {{ $submission->task?->level?->title }}
                        </div>
                        @if($solvedAt)
                            <div class="text-muted small">{{ $solvedAt->format('d.m.Y H:i') }}</div>
                        @endif
                    </div>
                    <a href="{{ route('level.show', ['id' => $submission->task?->level_id, 'task_id' => $submission->task_id]) }}" class="btn btn-sm btn-outline-light flex-shrink-0">К заданию</a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-center text-muted py-5 rounded border border-secondary-subtle">
            Активность пока не найдена
        </div>
    @endif
</div>
