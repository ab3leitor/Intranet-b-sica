// Función para cargar los hilos
        async function loadThreads() {
          try {
            const response = await fetch('php/ObtenerHilosForo.php');
            const threads = await response.json();

            const threadList = document.getElementById('threadList');
            threadList.innerHTML = '';

            threads.forEach(thread => {
              const threadItem = document.createElement('li');
              threadItem.className = 'hilo-item';

              const iconClass = thread.is_pinned ? 'hilo-icon pinned' : 'hilo-icon';
              const icon = thread.is_pinned ? "<i class='bx bx-pin'></i>" : "<i class='bx bx-conversation'></i>";

              const lastReplyDate = thread.last_reply ? new Date(thread.last_reply) : null;
              const formattedLastReply = lastReplyDate ? lastReplyDate.toLocaleString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              }) : 'Sin respuestas';

              threadItem.innerHTML = `
                        <div class="${iconClass}">${icon}</div>
                        <div class="hilo-content">
                            <h3 class="hilo-title">
                                <a href="verHilo.php?id=${thread.id}">${thread.title}</a>
                                ${thread.is_closed ? ' [Cerrado]' : ''}
                            </h3>
                            <div class="hilo-meta">
                                Creado por <a href="#">${thread.creator_name}</a> • 
                                ${new Date(thread.created_at).toLocaleString('es-ES', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                })}
                            </div>
                            ${thread.last_reply_author ? `
                            <div class="hilo-last-reply">
                                Última respuesta: ${thread.last_reply_author} • ${formattedLastReply}
                            </div>
                            ` : ''}
                        </div>
                        <div class="hilo-stats">
                            <span class="hilo-replies">${thread.reply_count} respuestas</span>
                            <span class="hilo-views">${thread.view_count} vistas</span>
                        </div>
                    `;

              threadList.appendChild(threadItem);
            });

            document.getElementById('threadCount').textContent = `${threads.length} hilos`;
          } catch (error) {
            console.error('Error al cargar hilos:', error);
          }
        }

        // Mostrar/ocultar modal
        const modal = document.getElementById('newThreadModal');
        const newThreadBtn = document.getElementById('newThreadBtn');
        const closeModal = document.querySelector('.close-modal');
        const cancelBtn = document.getElementById('cancelThreadBtn');

        newThreadBtn.addEventListener('click', () => {
          modal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
          modal.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
          modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
          if (event.target === modal) {
            modal.style.display = 'none';
          }
        });

        // Enviar nuevo hilo
        document.getElementById('newThreadForm').addEventListener('submit', async (e) => {
          e.preventDefault();

          const title = document.getElementById('threadTitle').value.trim();
          const content = document.getElementById('threadContent').value.trim();
          const creatorId = Number(document.body.dataset.userId) || null;

          if (!title || !content || !creatorId) {
            alert('Por favor completa todos los campos');
            return;
          }

          try {
            const response = await fetch('php/CrearHiloForo.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                title,
                content,
                creator_id: creatorId
              })
            });

            const result = await response.json();

            if (result.success) {
              modal.style.display = 'none';
              document.getElementById('threadTitle').value = '';
              document.getElementById('threadContent').value = '';
              loadThreads();
            } else {
              alert('Error al crear el hilo: ' + (result.message || 'Error desconocido'));
            }
          } catch (error) {
            console.error('Error:', error);
            alert('Error al crear el hilo');
          }
        });

        // Cargar hilos al iniciar
        loadThreads();
