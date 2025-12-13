import re, pathlib
path = pathlib.Path(r"app/Views/auth/login-mahasiswa.php")
data = path.read_text()
new_block = """document.getElementById('togglePassword')?.addEventListener('change', function () {
    const field = document.getElementById('passwordInput');
    if (field) field.type = this.checked ? 'text' : 'password';
});

// Ambil lokasi (best effort) untuk dicatat ke aktivitas login
(function () {
    const latInput = document.getElementById('loginLat');
    const lngInput = document.getElementById('loginLng');
    const form = document.querySelector('form[action$=\"/login\"]');
    const geoError = document.getElementById('geoError');
    let submitting = false;

    const setCoords = (lat, lng) => {
        if (latInput) latInput.value = lat ?? '';
        if (lngInput) lngInput.value = lng ?? '';
    };

    const showGeoError = (msg) => {
        if (!geoError) return;
        geoError.textContent = msg;
        geoError.classList.remove('hidden');
    };

    const requestGeo = (onSuccess, onFail) => {
        if (!navigator.geolocation) {
            onFail?.('Browser tidak mendukung lokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                setCoords(pos.coords.latitude.toFixed(6), pos.coords.longitude.toFixed(6));
                geoError?.classList.add('hidden');
                onSuccess?.();
            },
            () => onFail?.('Izinkan akses lokasi terlebih dahulu.'),
            { enableHighAccuracy: true, timeout: 6000, maximumAge: 60000 }
        );
    };

    requestGeo();
    form?.addEventListener('submit', (event) => {
        if (submitting) return;
        if (!(latInput?.value && lngInput?.value)) {
            event.preventDefault();
            requestGeo(() => {
                submitting = true;
                form.submit();
            }, (msg) => showGeoError(msg));
        }
    });
})();
</script>"""
pattern = r"document.getElementById\('togglePassword'\)\?\.addEventListener\('change',[\s\S]*?</script>"
new_data, count = re.subn(pattern, new_block, data)
print('replaced', count)
path.write_text(new_data)
