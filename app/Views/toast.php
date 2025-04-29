<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    #liveToast {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background-color: #dc3545 !important; /* Rosso acceso */
    }

    #liveToast:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .toast-body {
        color: #fff !important;
    }

    .progress-bar {
        background-color: #dc3545 !important; /* Rosso acceso */
    }
</style>
    <?php if (session()->has('toastMessage')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-white fw-bold">
                        <?= session()->getFlashdata('toastMessage') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Chiudi"></button>
                </div>
                <div class="progress" style="height: 5px; background-color: rgba(255, 255, 255, 0.3);">
                    <div class="progress-bar bg-white" id="toastProgressBar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toastEl = document.getElementById("liveToast");
            const progressBar = document.getElementById("toastProgressBar");
            const baseDuration = 3000; // Base toast duration in milliseconds
            let remainingTime = baseDuration;
            let startTime = Date.now();
            let interval;
            let isHovering = false;

            const updateProgressBar = () => {
                const elapsedTime = Date.now() - startTime;
                const progress = Math.max(0, 100 - (elapsedTime / remainingTime) * 100);
                progressBar.style.width = progress + "%";

                if (progress <= 0) {
                    clearInterval(interval);
                    progressBar.style.width = "0%"; // Ensure the progress bar is fully empty
                    const toast = bootstrap.Toast.getInstance(toastEl);
                    if (toast) {
                        toast.hide(); // Hide the toast when progress reaches 0
                    }
                }
            };

            const startToast = () => {
                startTime = Date.now();
                interval = setInterval(updateProgressBar, 50);
            };

            const pauseToast = () => {
                clearInterval(interval);
                remainingTime -= Date.now() - startTime;
            };

            const resumeToast = () => {
                startTime = Date.now();
                interval = setInterval(updateProgressBar, 50);
            };

            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { autohide: false });
                toast.show();
                startToast();

                // Adjust speed on hover
                toastEl.addEventListener("mouseenter", () => {
                    isHovering = true;
                    pauseToast();
                });

                toastEl.addEventListener("mouseleave", () => {
                    isHovering = false;
                    resumeToast();
                });
            }
        });
    </script>