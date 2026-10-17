@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert.alert-success, .alert.alert-danger').forEach(alert => {
        setTimeout(() => {
            window.bootstrap?.Alert?.getOrCreateInstance(alert)?.close();
        }, 4000);
    });

    // Submit guard
    document.querySelectorAll('form[id$="Form"]').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('[id="btnSubmit"], .btn-save-um');
            if (btn && form.checkValidity()) {
                btn.disabled = true;
                const icon = btn.querySelector('i');
                if (icon) { icon.className = 'fas fa-spinner fa-spin'; }
            }
        });
    });
});
</script>
@endpush