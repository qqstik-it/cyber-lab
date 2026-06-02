@extends('admin.layout')

@section('title', 'Добавить общую награду')

@section('admin_content')
<div class="card p-4">
    <div class="fw-bold fs-4 mb-3">Новая общая награда</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.achievements.store') }}">
        @csrf
        @include('admin.achievements._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline-dark">Отмена</a>
        </div>
    </form>
</div>
@endsection
