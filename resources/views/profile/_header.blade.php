<div class="row mb-5">
    <div class="col-12">
        <div class="card p-4 overflow-hidden position-relative" style="background-image: url('{{ asset('img/bg_black.png') }}'); background-size: cover; height: 180px;">
            <div class="d-flex align-items-center h-100 px-4 position-relative" style="z-index: 2; background: rgba(13, 17, 23, 0.78); border-radius: 10px; width: fit-content;">
                <img src="{{ $user['avatar'] }}" class="rounded-circle border border-white border-4 me-4" width="100" height="100">
                <div>
                    <h3 class="fw-bold mb-1">{{ $user['name'] }}</h3>
                    <span class="badge bg-cyan me-2">{{ $user['role'] }}</span>
                    <p class="text-muted small mb-2">
                        @php
                            $days = $profileStats['daysOnPlatform'];
                            $daysLabel = match (true) {
                                $days % 10 === 1 && $days % 100 !== 11 => $days . ' день',
                                in_array($days % 10, [2, 3, 4], true) && !in_array($days % 100, [12, 13, 14], true) => $days . ' дня',
                                default => $days . ' дней',
                            };
                        @endphp
                        {{ $daysLabel }} с Кибер-Лаб
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>