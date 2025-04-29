<?php

use App\Models\Services\UserService;
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="position-absolute top-0 end-0 mt-3 me-3 fade show rounded">
    <div class="card shadow-sm border-0 rounded-3 text-center p-3 bg-light text-dark" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="fw-semibold">
                <i class="bi bi-person-circle text-primary"></i> Benvenuto, <?= htmlspecialchars(model(UserService::class)->get(["userId" => session()->userId, "active" => true])->name) ?>!
            </h5>
            <p class="fs-6 mt-2 text-muted">Siamo felici di rivederti in ShareLinks 🎉</p>
        </div>
    </div>
</div>

