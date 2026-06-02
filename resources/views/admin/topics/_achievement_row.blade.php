<div class="achievement-row border rounded p-3 mb-3" data-achievement-row>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-bold small">Название</label>
            <input class="form-control form-control-sm" name="achievements[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold small">Порог</label>
            <input class="form-control form-control-sm" type="number" min="1" name="achievements[{{ $index }}][threshold]" value="{{ $row['threshold'] ?? 1 }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold small">Порядок</label>
            <input class="form-control form-control-sm" type="number" min="0" name="achievements[{{ $index }}][sort_order]" value="{{ $row['sort_order'] ?? $index }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold small">Иконка (URL)</label>
            <input class="form-control form-control-sm" name="achievements[{{ $index }}][icon]" value="{{ $row['icon'] ?? '' }}">
        </div>
        <div class="col-12">
            <label class="form-label fw-bold small">Описание</label>
            <input class="form-control form-control-sm" name="achievements[{{ $index }}][description]" value="{{ $row['description'] ?? '' }}">
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger mt-2" data-remove-achievement-row>Убрать</button>
</div>
