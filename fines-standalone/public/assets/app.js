(function () {
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
        const input = wrapper.querySelector('.comision-search');
        const hidden = wrapper.querySelector('.comision-id');
        const summary = wrapper.querySelector('.comision-summary');
        const results = wrapper.querySelector('.comision-results');
        clearResults(results);

        if (!rows.length) {
            const empty = document.createElement('div');
            empty.className = 'comision-empty';
            empty.textContent = 'Sin resultados';
            results.appendChild(empty);
            results.hidden = false;
            return;
        }

        rows.forEach((row) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'comision-option';
            button.textContent = row.label;
            button.addEventListener('click', () => {
                input.value = row.pfid || row.id;
                hidden.value = row.id;
                summary.textContent = row.label;
                clearResults(results);
            });
            results.appendChild(button);
        });

        results.hidden = false;
    };

    document.querySelectorAll('.comision-picker').forEach((wrapper) => {
        const input = wrapper.querySelector('.comision-search');
        const hidden = wrapper.querySelector('.comision-id');
        const results = wrapper.querySelector('.comision-results');
        if (!input || !hidden || !results) {
            return;
        }

        const search = debounce(async () => {
            const q = input.value.trim();
            hidden.value = '';
            if (q.length < 3) {
                clearResults(results);
                return;
            }

            try {
                const baseUrl = document.body.dataset.baseUrl || '/';
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/comisiones/buscar?q=${encodeURIComponent(q)}`, {
                    credentials: 'same-origin',
                });
                renderResults(wrapper, await response.json());
            } catch (error) {
                clearResults(results);
            }
        }, 250);

        input.addEventListener('input', search);
        input.addEventListener('focus', search);
    });

    document.addEventListener('click', (event) => {
        document.querySelectorAll('.comision-picker').forEach((wrapper) => {
            if (!wrapper.contains(event.target)) {
                const results = wrapper.querySelector('.comision-results');
                if (results) {
                    clearResults(results);
                }
            }
        });
    });
}());
