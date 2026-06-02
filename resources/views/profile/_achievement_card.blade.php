<li class="list-group-item d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 bg-transparent text-reset border-secondary-subtle {{ $item['earned'] ? 'achievement-item-earned' : '' }}">
  <div class="d-flex align-items-start gap-3 flex-grow-1">
    <div class="fs-3 lh-1" style="width: 40px; text-align: center;" aria-hidden="true">
      @if(!empty($item['icon']) && !str_starts_with($item['icon'], 'http'))
        {{ $item['icon'] }}
      @elseif(!empty($item['icon']))
        <img src="{{ $item['icon'] }}" alt="" width="40" height="40" class="rounded" style="object-fit: cover;">
      @else
        🏅
      @endif
    </div>
    <div>
      <div class="fw-medium">{{ $item['title'] }}</div>
      <div class="text-muted small">{{ $item['description'] }}</div>
      <div class="text-muted small mt-1">
        Нужно принятых заданий: <strong>{{ $item['threshold'] }}</strong>
        @if(!empty($item['in_category']))
          <span class="text-muted">(в этой теме)</span>
        @endif
      </div>
    </div>
  </div>
  <div class="d-flex align-items-center gap-2 flex-shrink-0">
    @if($item['earned'])
      <span class="badge bg-success">Получено</span>
    @else
      <span class="badge bg-secondary">В процессе</span>
    @endif
    <a href="{{ $item['tasks_url'] }}" class="btn btn-sm {{ $item['earned'] ? 'btn-outline-light' : 'btn-cyan' }}">
      {{ !empty($item['in_category']) ? 'К теме' : 'К заданиям' }}
    </a>
  </div>
</li>
