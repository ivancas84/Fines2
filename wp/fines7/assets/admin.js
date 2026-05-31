(function () {
    const config = window.fines7Admin || {};
    const minSearchLength = 3;

    const debounce = (callback, wait) => {
        let timer = null;
        return (...args) => {
            window.clearTimeout(timer);
            timer = window.setTimeout(() => callback(...args), wait);
        };
    };

    const clearResults = (results) => {
        results.innerHTML = '';
        results.hidden = true;
    };

    const renderResults = (wrapper, rows) => {
        const input = wrapper.querySelector('.fines7-comision-search');
        const hidden = wrapper.querySelector('.fines7-comision-id');
        const detail = wrapper.querySelector('.fines7-muted');
        const results = wrapper.querySelector('.fines7-comision-results');

        clearResults(results);

        if (!rows.length) {
            const empty = document.createElement('div');
            empty.className = 'fines7-comision-empty';
            empty.textContent = 'Sin resultados';
            results.appendChild(empty);
            results.hidden = false;
            return;
        }

        rows.forEach((row) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'fines7-comision-option';
            button.textContent = row.label;
            button.addEventListener('click', () => {
                input.value = row.pfid || row.id;
                hidden.value = row.id;
                if (detail) {
                    detail.textContent = row.summary || row.label;
                }
                clearResults(results);
            });
            results.appendChild(button);
        });

        results.hidden = false;
    };

    const searchComisiones = async (wrapper, term) => {
        const results = wrapper.querySelector('.fines7-comision-results');
        const params = new URLSearchParams();
        params.append('action', 'fines7_search_comisiones');
        params.append('nonce', config.searchComisionesNonce || '');
        params.append('term', term);

        try {
            const response = await window.fetch(config.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                body: params.toString(),
            });
            const payload = await response.json();
            renderResults(wrapper, payload.success ? payload.data : []);
        } catch (error) {
            clearResults(results);
        }
    };

    document.querySelectorAll('.fines7-comision-autocomplete').forEach((wrapper) => {
        const input = wrapper.querySelector('.fines7-comision-search');
        const hidden = wrapper.querySelector('.fines7-comision-id');
        const results = wrapper.querySelector('.fines7-comision-results');

        if (!input || !hidden || !results || !config.ajaxUrl) {
            return;
        }

        const debouncedSearch = debounce(() => {
            const term = input.value.trim();
            if (term.length < minSearchLength) {
                hidden.value = '';
                clearResults(results);
                return;
            }

            searchComisiones(wrapper, term);
        }, 250);

        input.addEventListener('input', () => {
            hidden.value = '';
            debouncedSearch();
        });

        input.addEventListener('focus', () => {
            if (input.value.trim().length >= minSearchLength) {
                debouncedSearch();
            }
        });
    });

    document.addEventListener('click', (event) => {
        document.querySelectorAll('.fines7-comision-autocomplete').forEach((wrapper) => {
            if (!wrapper.contains(event.target)) {
                const results = wrapper.querySelector('.fines7-comision-results');
                if (results) {
                    clearResults(results);
                }
            }
        });
    });
}());
