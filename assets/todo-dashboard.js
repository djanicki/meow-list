function reloadPage(reloadUrl) {
    const refreshUrl = new URL(reloadUrl || window.location.pathname, window.location.origin);
    refreshUrl.searchParams.set('_t', String(Date.now()));
    window.location.href = refreshUrl.toString();
}

async function handleCreate(form, input, addButton, reloadUrl) {
    const text = input.value.trim();

    if (text === '') {
        return;
    }

    addButton.disabled = true;

    try {
        const response = await fetch(form.dataset.createUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text }),
        });

        if (!response.ok) {
            console.error('Failed to add todo');
            addButton.disabled = false;
            return;
        }

        reloadPage(reloadUrl);
    } catch (error) {
        console.error(error);
        addButton.disabled = false;
    }
}

async function handleStatusChange(checkbox) {
    const item = checkbox.closest('.todo-item');
    const statusUrl = checkbox.dataset.statusUrl;

    if (!item || !statusUrl) {
        return;
    }

    const isDone = checkbox.checked;
    item.classList.toggle('is-done', isDone);
    item.classList.add('loading');

    try {
        const response = await fetch(statusUrl, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ isDone }),
        });

        if (!response.ok) {
            checkbox.checked = !isDone;
            item.classList.toggle('is-done', !isDone);
            console.error('Failed to update status');
        }
    } catch (error) {
        console.error(error);
        checkbox.checked = !isDone;
        item.classList.toggle('is-done', !isDone);
    } finally {
        item.classList.remove('loading');
    }
}

async function handleDelete(button, list, reloadUrl) {
    const item = button.closest('.todo-item');
    const deleteUrl = button.dataset.deleteUrl;

    if (!item || !deleteUrl) {
        return;
    }

    if (!window.confirm('Are you sure you want to delete this task?')) {
        return;
    }

    item.classList.add('loading');

    try {
        const response = await fetch(deleteUrl, { method: 'DELETE' });

        if (!response.ok) {
            console.error('Failed to delete');
            item.classList.remove('loading');
            return;
        }

        item.remove();

        if (list.querySelectorAll('.todo-item').length === 0) {
            reloadPage(reloadUrl);
        }
    } catch (error) {
        console.error(error);
        item.classList.remove('loading');
    }
}

function initializeTodoDashboard() {
    const dashboard = document.querySelector('[data-todo-dashboard]');

    if (!dashboard || dashboard.dataset.todoDashboardInitialized === 'true') {
        return;
    }

    const form = dashboard.querySelector('#todo-form');
    const input = dashboard.querySelector('#todo-input');
    const list = dashboard.querySelector('#todo-list');
    const addButton = dashboard.querySelector('#todo-add-btn');

    if (!form || !input || !list || !addButton) {
        return;
    }

    dashboard.dataset.todoDashboardInitialized = 'true';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        await handleCreate(form, input, addButton, dashboard.dataset.reloadUrl);
    });

    list.addEventListener('change', async (event) => {
        const checkbox = event.target.closest('.todo-checkbox');

        if (!checkbox) {
            return;
        }

        await handleStatusChange(checkbox);
    });

    list.addEventListener('click', async (event) => {
        const button = event.target.closest('.todo-delete-btn');

        if (!button) {
            return;
        }

        await handleDelete(button, list, dashboard.dataset.reloadUrl);
    });
}

document.addEventListener('turbo:load', initializeTodoDashboard);
