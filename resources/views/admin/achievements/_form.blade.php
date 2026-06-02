@php
    $achievement = $achievement ?? null;
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-bold">Название</label>
        <input class="form-control" name="title" value="{{ old('title', $achievement?->title) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">Порог (заданий)</label>
        <input class="form-control" type="number" name="threshold" min="1" value="{{ old('threshold', $achievement?->threshold ?? 1) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">Порядок</label>
        <input class="form-control" type="number" name="sort_order" min="0" value="{{ old('sort_order', $achievement?->sort_order ?? 0) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-bold">Описание</label>
        <textarea class="form-control" name="description" rows="2" required>{{ old('description', $achievement?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label fw-bold">Иконка (emoji или URL)</label>
        <input class="form-control" name="icon" value="{{ old('icon', $achievement?->icon) }}" placeholder="🛡️ или https://..." required>
    </div>
</div>
