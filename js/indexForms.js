// Validación y manejo de formularios
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const loader = submitBtn.querySelector('.loader');
                const inputs = this.querySelectorAll('input[required]');
                let isValid = true;

                // Validar campos
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = '#ff6b6b';
                        setTimeout(() => input.style.borderColor = '', 2000);
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return;
                }

                // Mostrar loader y deshabilitar botón
                submitBtn.disabled = true;
                loader.style.display = 'block';
            });
        });
