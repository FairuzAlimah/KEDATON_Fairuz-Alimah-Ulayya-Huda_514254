{{-- SUCCESS TOAST --}}
@if (session()->has('success'))
    <div class="toast-container position-fixed end-0 p-3 custom-toast-offset">
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="LiveToastSuccess">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const toastSuccess = new bootstrap.Toast(document.getElementById('LiveToastSuccess'), {
            autohide: true,
            delay: 3000
        });
        toastSuccess.show();
    </script>
@endif

{{-- ERROR TOAST --}}
@if (session()->has('error'))
    <div class="toast-container position-fixed end-0 p-3 custom-toast-offset">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="LiveToastError1">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const toastError1 = new bootstrap.Toast(document.getElementById('LiveToastError1'), {
            autohide: true,
            delay: 3000
        });
        toastError1.show();
    </script>
@endif

{{-- VALIDATION ERRORS --}}
@if ($errors->any())
    <div class="toast-container position-fixed end-0 p-3 custom-toast-offset">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
            aria-atomic="true" id="LiveToastError2">
            <div class="d-flex">
                <div class="toast-body">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const toastError2 = new bootstrap.Toast(document.getElementById('LiveToastError2'), {
            autohide: true,
            delay: 3000
        });
        toastError2.show();
    </script>
@endif

<style>
    .custom-toast-offset {
        bottom: 60px; /* Naikin toast biar nggak nabrak */
        z-index: 1080;  /* Lebih tinggi dari elemen biasa */
    }
</style>
