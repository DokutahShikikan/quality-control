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
