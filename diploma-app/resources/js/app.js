import './bootstrap';

const stickyHeadEntries = [];

const syncFloatingHead = (entry) => {
    const { wrap, table, thead, floating, floatingViewport, floatingTable } = entry;

    if (!document.body.contains(wrap) || !thead) {
        floating.remove();
        entry.disposed = true;
        return;
    }

    const wrapRect = wrap.getBoundingClientRect();
    const tableRect = table.getBoundingClientRect();
    const headRect = thead.getBoundingClientRect();
    const shouldShow = wrapRect.top <= 0 && wrapRect.bottom > headRect.height;

    if (!shouldShow) {
        floating.classList.remove('is-visible');
        return;
    }

    const sourceHeaders = [...thead.querySelectorAll('th')];
    const cloneHeaders = [...floatingTable.querySelectorAll('th')];

    sourceHeaders.forEach((header, index) => {
        if (!cloneHeaders[index]) {
            return;
        }

        cloneHeaders[index].style.width = `${header.getBoundingClientRect().width}px`;
        cloneHeaders[index].style.minWidth = `${header.getBoundingClientRect().width}px`;
        cloneHeaders[index].style.maxWidth = `${header.getBoundingClientRect().width}px`;
    });

    floating.style.left = `${wrapRect.left}px`;
    floating.style.width = `${wrap.clientWidth}px`;
    floatingViewport.style.width = `${wrap.clientWidth}px`;
    floatingTable.style.width = `${tableRect.width}px`;
    floatingTable.style.transform = `translateX(-${wrap.scrollLeft}px)`;
    floating.classList.add('is-visible');
};

const updateFloatingHeads = () => {
    for (let index = stickyHeadEntries.length - 1; index >= 0; index -= 1) {
        const entry = stickyHeadEntries[index];
        syncFloatingHead(entry);

        if (entry.disposed) {
            stickyHeadEntries.splice(index, 1);
        }
    }
};

const initFloatingHeads = (scope = document) => {
    const wraps = scope.querySelectorAll('.data-table-wrap.is-sticky-head');

    for (const wrap of wraps) {
        if (wrap.dataset.floatingHeadReady === 'true') {
            continue;
        }

        const table = wrap.querySelector('table');
        const thead = table?.querySelector('thead');

        if (!table || !thead) {
            continue;
        }

        const floating = document.createElement('div');
        floating.className = 'floating-table-head';

        const floatingViewport = document.createElement('div');
        floatingViewport.className = 'floating-table-head__viewport';

        const floatingTable = document.createElement('table');
        floatingTable.className = 'floating-table-head__table';
        floatingTable.append(thead.cloneNode(true));

        floatingViewport.append(floatingTable);
        floating.append(floatingViewport);
        document.body.append(floating);

        const entry = { wrap, table, thead, floating, floatingViewport, floatingTable, disposed: false };

        wrap.addEventListener('scroll', () => syncFloatingHead(entry), { passive: true });
        stickyHeadEntries.push(entry);
        wrap.dataset.floatingHeadReady = 'true';
        syncFloatingHead(entry);
    }
};

initFloatingHeads();
window.addEventListener('scroll', updateFloatingHeads, { passive: true });
window.addEventListener('resize', updateFloatingHeads);

const cookieBanner = document.querySelector('[data-cookie-banner]');
const cookieAcceptButton = document.querySelector('[data-cookie-accept]');
const cookieConsentKey = 'data-quality-cookie-consent';

if (cookieBanner && cookieAcceptButton) {
    const isAccepted = window.localStorage.getItem(cookieConsentKey) === 'accepted';

    if (!isAccepted) {
        cookieBanner.classList.remove('hidden');
    }

    cookieAcceptButton.addEventListener('click', () => {
        window.localStorage.setItem(cookieConsentKey, 'accepted');
        cookieBanner.classList.add('hidden');
    });
}

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
    const loading = root.querySelector('[data-issues-loading]');
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

    const extractMessageFromHtml = (html) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const alert = doc.querySelector('.alert span');

        if (alert?.textContent?.trim()) {
            return alert.textContent.trim();
        }

        return '';
    };

    const refreshTable = async () => {
        const url = `${refreshUrl}${window.location.search}`;
        const response = await fetch(url, {
            credentials: 'same-origin',
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

        initFloatingHeads(root);
        updateFloatingHeads();
    };

    const setLoading = (state) => {
        if (loading) {
            loading.classList.toggle('hidden', !state);
        }

        const buttons = root.querySelectorAll('[data-issues-action-form] button');

        for (const button of buttons) {
            if (!(button instanceof HTMLButtonElement)) {
                continue;
            }

            button.disabled = state || button.disabled;

            if (!state && button.dataset.wasDisabled !== 'true') {
                button.disabled = false;
            }
        }
    };

    const submitIssueForm = async (form) => {
        if (isSubmitting) {
            return;
        }

        isSubmitting = true;
        const buttons = root.querySelectorAll('[data-issues-action-form] button');

        for (const button of buttons) {
            if (!(button instanceof HTMLButtonElement)) {
                continue;
            }

            button.dataset.wasDisabled = button.disabled ? 'true' : 'false';
        }

        setLoading(true);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: new FormData(form),
            });

            const contentType = response.headers.get('content-type') || '';
            let payload = null;

            if (contentType.includes('application/json')) {
                payload = await response.json();
            } else {
                const text = await response.text();
                payload = { message: extractMessageFromHtml(text) || 'Список ошибок обновлён.' };
            }

            if (!response.ok) {
                showFeedback('error', payload?.message || 'Не удалось обработать ошибку.');
                return;
            }

            await refreshTable();
            showFeedback('success', payload?.message || 'Список ошибок обновлён.');
        } catch (error) {
            console.error('Не удалось обновить ошибки без перезагрузки страницы', error);
            showFeedback('error', 'Не удалось обновить список ошибок без перезагрузки страницы.');
        } finally {
            setLoading(false);
            isSubmitting = false;
        }
    };

    root.addEventListener('submit', async (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.matches('[data-issues-action-form]')) {
            return;
        }

        event.preventDefault();
        await submitIssueForm(form);
    });
}
