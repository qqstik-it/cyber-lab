@extends('admin.layout')

@section('title', 'Редактировать награду')

@section('admin_content')
<div class="card p-4">
    <div class="fw-bold fs-4 mb-3">Редактирование награды</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.achievements.update', $achievement) }}">
        @csrf
        @method('PUT')
        @include('admin.achievements._form', ['achievement' => $achievement])
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            @if($achievement->topic_id)
                <a href="{{ route('admin.topics.edit', $achievement->topic_id) }}" class="btn btn-outline-dark">Отмена</a>
            @else
                <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline-dark">Отмена</a>
            @endif
        </div>
    </form>
</div>
@endsection
