@extends('admin.layout')

@section('title', 'Общие награды')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <div class="fw-bold fs-4">Общие награды</div>
        <div class="text-muted small">Достижения без привязки к категории (теме)</div>
    </div>
    <a href="{{ route('admin.achievements.create') }}" class="btn btn-cyan">Добавить награду</a>
</div>

<div class="card p-4">
    @if($achievements->isNotEmpty())
        <ul class="list-group list-group-flush border rounded">
            @foreach($achievements as $a)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                    <div class="d-flex align-items-center gap-3">
                        @if($a->icon)
                            <img src="{{ $a->icon }}" alt="" width="40" height="40" class="rounded" style="object-fit: cover;">
                        @endif
                        <div>
                            <div class="fw-medium">#{{ $a->id }} {{ $a->title }}</div>
                            <div class="text-muted small">
                                Порог: {{ $a->threshold }} &bull; {{ Str::limit($a->description, 80) }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.achievements.edit', $a) }}" class="btn btn-sm btn-outline-light">Ред.</a>
                        <form action="{{ route('admin.achievements.destroy', $a) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить награду?')">Удалить</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-muted small">Пока нет общих наград.</div>
    @endif
</div>
@endsection
