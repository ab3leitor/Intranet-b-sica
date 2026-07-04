const btn = document.querySelector("#btn");
    const sidebar = document.querySelector(".sidebar");
    const searchBtn = document.querySelector(".bx-search");

    btn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    searchBtn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    const userSearch = document.getElementById('userSearch');
    const clearUserSearch = document.getElementById('clearUserSearch');
    const userRows = Array.from(document.querySelectorAll('.user-row'));
    const emptySearchState = document.getElementById('emptySearchState');

    const filterUsers = () => {
      const term = userSearch.value.trim().toLowerCase();
      let visibleCount = 0;

      userRows.forEach((row) => {
        const matches = row.dataset.search.includes(term);
        row.classList.toggle('is-hidden', !matches);
        if (matches) visibleCount++;
      });

      emptySearchState.classList.toggle('is-hidden', visibleCount !== 0 || term === '');
      clearUserSearch.classList.toggle('is-visible', term !== '');
    };

    userSearch.addEventListener('input', filterUsers);
    clearUserSearch.addEventListener('click', () => {
      userSearch.value = '';
      filterUsers();
      userSearch.focus();
    });

    const editUserModal = document.getElementById('editUserModal');
    const editUserId = document.getElementById('editUserId');
    const editUserName = document.getElementById('editUserName');
    const editUserUsername = document.getElementById('editUserUsername');
    const editUserEmail = document.getElementById('editUserEmail');

    const openEditModal = (button) => {
      editUserId.value = button.dataset.id || '';
      editUserName.value = button.dataset.nombre || '';
      editUserUsername.value = button.dataset.usuario || '';
      editUserEmail.value = button.dataset.correo || '';
      editUserModal.classList.add('is-open');
      editUserModal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
      editUserName.focus();
    };

    const closeEditModal = () => {
      editUserModal.classList.remove('is-open');
      editUserModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
    };

    document.querySelectorAll('.js-open-edit-modal').forEach((button) => {
      button.addEventListener('click', () => openEditModal(button));
    });

    document.querySelectorAll('[data-close-edit-modal]').forEach((button) => {
      button.addEventListener('click', closeEditModal);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && editUserModal.classList.contains('is-open')) {
        closeEditModal();
      }
    });

    document.querySelectorAll('.delete-user-form').forEach((form) => {
      form.addEventListener('submit', (event) => {
        const row = form.closest('.user-row');
        const name = row?.querySelector('h2')?.textContent?.trim() || 'este usuario';
        const confirmed = confirm(`Eliminar a ${name}? Esta acción no se puede deshacer.`);
        if (!confirmed) event.preventDefault();
      });
    });
