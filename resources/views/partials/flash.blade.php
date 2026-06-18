@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#2563eb',
                animation: false
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Peringatan',
                html: `{!! implode('<br>', array_map('addslashes', $errors->all())) !!}`,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                animation: false
            });
        });
    </script>
@endif
