document.addEventListener('DOMContentLoaded', () => {
    const defaultAvatar = 'images/mewtwo-inspired-avatar.png';
    const fotoInput = document.getElementById('foto-input');
    const fotoPreview = document.getElementById('foto-preview');
    const uploadPhotoBtn = document.getElementById('btn-subir-foto');
    const deletePhotoBtn = document.querySelector('.btn-eliminar');
    const savePasswordBtn = document.querySelector('.btn-guardar');
    const logoutBtn = document.querySelector('.btn-cerrar-sesion');

    const notify = (message, type = 'success') => {
        const existing = document.querySelector('.config-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `config-toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('is-hiding');
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        }, 2600);
    };

    if (uploadPhotoBtn && fotoInput) {
        uploadPhotoBtn.addEventListener('click', () => {
            fotoInput.click();
        });
    }

    if (fotoInput && fotoPreview) {
        fotoInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                fotoInput.value = '';
                notify('Selecciona una imagen válida.', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = (readerEvent) => {
                fotoPreview.src = readerEvent.target.result;
                notify('Vista previa actualizada.');
            };
            reader.readAsDataURL(file);
        });
    }

    if (deletePhotoBtn && fotoPreview) {
        deletePhotoBtn.addEventListener('click', () => {
            fotoPreview.src = defaultAvatar;
            if (fotoInput) fotoInput.value = '';
            notify('Foto eliminada. Se restauró el avatar por defecto.');
        });
    }

    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', () => {
            notify('Configuración guardada correctamente.');
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            window.location.href = 'php/cerrar_sesion.php';
        });
    }
});
