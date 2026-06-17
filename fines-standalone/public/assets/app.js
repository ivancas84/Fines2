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

    const renderResults = (wrapper, rows, config) => {
        const input = wrapper.querySelector(config.input);
        const hidden = wrapper.querySelector(config.hidden);
        const summary = wrapper.querySelector(config.summary);
        const results = wrapper.querySelector(config.results);
        clearResults(results);

        if (!rows.length) {
            const empty = document.createElement('div');
            empty.className = config.emptyClass;
            empty.textContent = 'Sin resultados';
            results.appendChild(empty);
            results.hidden = false;
            return;
        }

        rows.forEach((row) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = config.optionClass;
            button.textContent = row.label;
            button.addEventListener('click', () => {
                input.value = config.inputValue(row);
                hidden.value = row.id;
                summary.textContent = row.label;
                clearResults(results);
            });
            results.appendChild(button);
        });

        results.hidden = false;
    };

    const setupPicker = (selector, config) => {
        document.querySelectorAll(selector).forEach((wrapper) => {
            const input = wrapper.querySelector(config.input);
            const hidden = wrapper.querySelector(config.hidden);
            const results = wrapper.querySelector(config.results);
            if (!input || !hidden || !results) {
                return;
            }

            const search = debounce(async () => {
                const q = input.value.trim();
                if (q.length < 3) {
                    clearResults(results);
                    return;
                }

                try {
                    const baseUrl = document.body.dataset.baseUrl || '/';
                    const response = await fetch(config.url(baseUrl.replace(/\/$/, ''), q, wrapper), {
                        credentials: 'same-origin',
                    });
                    renderResults(wrapper, await response.json(), config);
                } catch (error) {
                    clearResults(results);
                }
            }, 250);

            input.addEventListener('input', () => {
                hidden.value = '';
                search();
            });
            input.addEventListener('focus', search);
        });
    };

    setupPicker('.comision-picker', {
        input: '.comision-search',
        hidden: '.comision-id',
        summary: '.comision-summary',
        results: '.comision-results',
        optionClass: 'comision-option',
        emptyClass: 'comision-empty',
        inputValue: (row) => row.pfid || row.id,
        url: (baseUrl, q) => `${baseUrl}/comisiones/buscar?q=${encodeURIComponent(q)}`,
    });

    document.querySelectorAll('.curso-picker').forEach((wrapper) => {
        const hidden = wrapper.querySelector('.curso-id');
        const summary = wrapper.querySelector('.curso-summary');
        const button = wrapper.querySelector('.curso-associate');
        if (!hidden || !summary || !button) {
            return;
        }

        button.addEventListener('click', async () => {
            button.disabled = true;
            try {
                const baseUrl = (document.body.dataset.baseUrl || '/').replace(/\/$/, '');
                const response = await fetch(`${baseUrl}/cursos/asociar?alumno=${encodeURIComponent(wrapper.dataset.alumno || '')}&disposicion=${encodeURIComponent(wrapper.dataset.disposicion || '')}`, {
                    credentials: 'same-origin',
                });
                const row = await response.json();

                if (!response.ok) {
                    hidden.value = '';
                    summary.textContent = row.message || 'No se encontro un curso';
                    summary.classList.remove('text-secondary');
                    summary.classList.add('text-danger');
                    return;
                }

                hidden.value = row.id;
                summary.textContent = row.label;
                summary.classList.remove('text-danger');
                summary.classList.add('text-secondary');
            } catch (error) {
                hidden.value = '';
                summary.textContent = 'No se pudo asociar el curso';
                summary.classList.remove('text-secondary');
                summary.classList.add('text-danger');
            } finally {
                button.disabled = false;
            }
        });
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
