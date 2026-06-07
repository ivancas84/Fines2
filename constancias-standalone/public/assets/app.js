(function () {
    const setupDoubleSubmitProtection = () => {
        document.querySelectorAll('form.js-prevent-double-submit').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (form.dataset.submitted === '1') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitted = '1';
                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
                    if (button.dataset.submittingText) {
                        button.dataset.originalText = button.textContent || button.value || '';
                        if (button.tagName === 'INPUT') {
                            button.value = button.dataset.submittingText;
                        } else {
                            button.textContent = button.dataset.submittingText;
                        }
                    }
                    button.disabled = true;
                });
            });
        });
    };

    const setupRichTableEditors = () => {
        if (!window.ClassicEditor) {
            return;
        }

        const editors = [];
        document.querySelectorAll('.js-rich-table-editor').forEach((textarea) => {
            window.ClassicEditor.create(textarea, {
                toolbar: ['bold', 'italic', 'insertTable', 'undo', 'redo'],
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
                    defaultHeadings: { rows: 1, columns: 0 },
                },
            }).then((editor) => {
                editors.push({ editor, textarea });
            }).catch(() => {
                textarea.classList.remove('js-rich-table-editor');
            });
        });

        document.querySelectorAll('form.js-rich-editor-form').forEach((form) => {
            form.addEventListener('submit', () => {
                editors.forEach(({ editor, textarea }) => {
                    if (form.contains(textarea)) {
                        textarea.value = editor.getData();
                    }
                });
            });
        });
    };

    setupDoubleSubmitProtection();
    setupRichTableEditors();
}());
