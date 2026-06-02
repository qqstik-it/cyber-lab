<div class="profile-stat-grid mb-4">
    <div class="profile-stat-card">
        <div class="profile-stat-label">Место в рейтинге</div>
        @if($profileStats['rank'])
            <div class="profile-stat-value">#{{ $profileStats['rank'] }}</div>
            <p class="profile-stat-hint">среди участников с принятыми заданиями</p>
        @else
            <div class="profile-stat-value">—</div>
            <p class="profile-stat-hint">появится после решений</p>
        @endif
    </div>
    <div class="profile-stat-card">
        <div class="profile-stat-label">Заданий выполнено</div>
        <div class="profile-stat-value">{{ $profileStats['acceptedCount'] }} <span class="fs-6 fw-normal text-muted">из {{ $profileStats['totalTasks'] }}</span></div>
        <p class="profile-stat-hint">только со статусом «Принято»</p>
    </div>
    <div class="profile-stat-card">
        <div class="profile-stat-label">Последняя активность</div>
        @if($profileStats['lastActivityAt'])
            <div class="profile-stat-value fs-5">{{ $profileStats['lastActivityAt']->format('d.m.Y') }}</div>
            <p class="profile-stat-hint">{{ $profileStats['lastActivityAt']->format('H:i') }}</p>
        @else
            <div class="profile-stat-value">—</div>
            <p class="profile-stat-hint">нет данных</p>
        @endif
    </div>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-4">Прогресс на платформе</h5>
    <div class="profile-topic-grid">
        @foreach($profileStats['topicProgress'] as $topic)
            <div class="profile-topic-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-bold">{{ $topic['title'] }}</div>
                    <span class="text-muted small">{{ $topic['accepted'] }} / {{ $topic['total'] }}</span>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: {{ $topic['percent'] }}%;" aria-valuenow="{{ $topic['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="profile-stat-hint mb-2">{{ $topic['caption'] }}</p>
                <a href="{{ route('topic.show', ['id' => $topic['topic_id']]) }}" class="btn btn-sm btn-outline-light">К теме</a>
            </div>
        @endforeach
    </div>
</div>
