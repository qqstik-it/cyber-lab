@extends('layouts.app')

@section('title', 'Личный кабинет')

@push('page_styles')
@php
    $profileCss = resource_path('css/views/profile.css');
    $achievementsCss = resource_path('css/views/profile-achievements.css');
@endphp
@if (file_exists($profileCss))
    <style>{!! file_get_contents($profileCss) !!}</style>
@endif
@if (file_exists($achievementsCss))
    <style>{!! file_get_contents($achievementsCss) !!}</style>
@endif
@endpush

@section('content')
@include('profile._header')

<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4 profile-tabs" role="tablist">
            <button type="button" class="list-group-item list-group-item-action active border-0" data-profile-tab="progress" style="background-color: #d8f05e; color: #111;">Прогресс</button>
            <button type="button" class="list-group-item list-group-item-action border-0" data-profile-tab="achievements">Награды</button>
            <button type="button" class="list-group-item list-group-item-action border-0" data-profile-tab="activity">Активность</button>
        </div>
    </div>
    <div class="col-md-9">
        <div class="profile-tab-pane active" id="profile-tab-progress">
            @include('profile._progress_tab')
        </div>

        <div class="profile-tab-pane" id="profile-tab-achievements">
            <div class="card p-4">
                <h5 class="fw-bold mb-2">Награды</h5>
                <p class="text-muted small mb-4">Считаются только задания со статусом «Принято» (автопроверка или решение эксперта).</p>
                @include('profile._achievements')
            </div>
        </div>

        <div class="profile-tab-pane" id="profile-tab-activity">
            @include('profile._activity_tab')
        </div>
    </div>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('[data-profile-tab]');
    var panes = {
        progress: document.getElementById('profile-tab-progress'),
        achievements: document.getElementById('profile-tab-achievements'),
        activity: document.getElementById('profile-tab-activity'),
    };
    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.disabled) return;
            var key = btn.getAttribute('data-profile-tab');
            tabs.forEach(function (b) {
                b.classList.remove('active');
                b.style.backgroundColor = '';
                b.style.color = '';
            });
            btn.classList.add('active');
            btn.style.backgroundColor = '#d8f05e';
            btn.style.color = '#111';
            Object.keys(panes).forEach(function (k) {
                if (panes[k]) panes[k].classList.toggle('active', k === key);
            });
        });
    });
})();
</script>
@endsection
