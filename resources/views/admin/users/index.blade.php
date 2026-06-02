@extends('admin.layout')

@section('title', 'Пользователи (админка)')

@section('admin_content')
@php
    $panelUser = auth()->user();
    $canDelete = $panelUser?->canDeleteUsers();
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="fw-bold fs-4">Пользователи</div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-cyan">Добавить пользователя</a>
</div>

<div class="card p-4">
    @if($users->isNotEmpty())
        <ul class="list-group list-group-flush border rounded">
            @foreach($users as $u)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                    <div>
                        <div class="fw-medium">#{{ $u->id }} {{ $u->name }}</div>
                        <div class="text-muted small">
                            {{ $u->email }} &bull; {{ $u->roleLabel() }}
                        </div>
                    </div>
                    @if($canDelete && $u->id !== $panelUser?->id)
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить пользователя {{ $u->name }}?')">Удалить</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-muted small">Пока нет пользователей.</div>
    @endif
</div>
@endsection
