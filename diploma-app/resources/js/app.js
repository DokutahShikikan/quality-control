import './bootstrap';

const liveContainers = document.querySelectorAll('[data-live-panels]');

for (const container of liveContainers) {
    const refreshUrl = container.dataset.refreshUrl;
    const refreshInterval = Number(container.dataset.refreshInterval || 5000);

    if (!refreshUrl) {
        continue;
    }

    let isRefreshing = false;

    const refreshPanels = async () => {
        if (isRefreshing) {
            return;
        }

        isRefreshing = true;

        try {
            const response = await fetch(refreshUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();

            for (const [key, html] of Object.entries(payload)) {
                const target = container.querySelector(`[data-live-target="${key}"]`);

                if (target && typeof html === 'string') {
                    target.innerHTML = html;
                }
            }
        } catch (error) {
            console.error('Не удалось обновить блоки таблицы', error);
        } finally {
            isRefreshing = false;
        }
    };

    window.setInterval(refreshPanels, refreshInterval);
}

const issueTableRoots = document.querySelectorAll('[data-issues-table-root]');

for (const root of issueTableRoots) {
    const refreshUrl = root.dataset.refreshUrl;
    const feedback = root.querySelector('[data-issues-feedback]');
    let isSubmitting = false;

    if (!refreshUrl) {
        continue;
    }

    const showFeedback = (type, message) => {
        if (!feedback) {
            return;
        }

        feedback.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50/90', 'text-emerald-900', 'border-rose-200', 'bg-rose-50/90', 'text-rose-900');

        if (type === 'success') {
            feedback.classList.add('border', 'border-emerald-200', 'bg-emerald-50/90', 'text-emerald-900');
        } else {
            feedback.classList.add('border', 'border-rose-200', 'bg-rose-50/90', 'text-rose-900');
        }

        feedback.textContent = message;
    };

    const refreshTable = async () => {
        const url = `${refreshUrl}${window.location.search}`;
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Не удалось обновить список ошибок.');
        }

        const parser = new DOMParser();
        const html = await response.text();
        const doc = parser.parseFromString(html, 'text/html');
        const panel = doc.body.firstElementChild;

        if (!panel) {
            throw new Error('Сервер вернул пустой блок ошибок.');
        }

        const currentPanel = root.querySelector('.panel');

        if (currentPanel) {
            currentPanel.replaceWith(panel);
        } else {
            root.append(panel);
        }
    };

    root.addEventListener('submit', async (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.matches('[data-issues-action-form]')) {
            return;
        }

        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        isSubmitting = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: new FormData(form),
            });

            const payload = await response.json();

            if (!response.ok) {
                showFeedback('error', payload.message || 'Не удалось обработать ошибку.');
                return;
            }

            await refreshTable();
            showFeedback('success', payload.message || 'Список ошибок обновлён.');
        } catch (error) {
            console.error('Не удалось обновить ошибки без перезагрузки страницы', error);
            showFeedback('error', 'Не удалось обновить список ошибок без перезагрузки страницы.');
        } finally {
            isSubmitting = false;
        }
    });
}
