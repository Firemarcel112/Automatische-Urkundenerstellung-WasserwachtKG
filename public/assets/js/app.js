document.addEventListener('DOMContentLoaded', function () {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

document.addEventListener('DOMContentLoaded', function () {
    const theme_toggle = document.getElementById('theme-toggle');
    const storage_key = 'theme';
    const html = document.documentElement;

    const stored_theme = localStorage.getItem(storage_key);
    if (stored_theme === 'light' || stored_theme === 'dark') {
        html.setAttribute('data-bs-theme', stored_theme);
    }

    function updateThemeIcons() {
        const darkmode_enabled = html.getAttribute('data-bs-theme') === 'dark';

        if (!theme_toggle) return;

        const light_icon = theme_toggle.querySelector('.light-icon');
        const dark_icon = theme_toggle.querySelector('.dark-icon');

        light_icon.classList.remove('d-none');
        dark_icon.classList.remove('d-none');

        if (light_icon) light_icon.style.display = darkmode_enabled ? 'none' : 'block';
        if (dark_icon) dark_icon.style.display = darkmode_enabled ? 'block' : 'none';
    }

    updateThemeIcons();

    theme_toggle?.addEventListener('click', function () {
        const switch_theme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-bs-theme', switch_theme);
        localStorage.setItem(storage_key, switch_theme);
        updateThemeIcons();
    });
});

document.addEventListener('DOMContentLoaded', function () {

    if (document.getElementById('add-certificate-field')) {
        document.getElementById('add-certificate-field').addEventListener('click', function () {
            const index = `new_${Date.now()}`;
            const fields_container = document.getElementById('certificate-fields');
            const field_types = JSON.parse(this.dataset.fieldTypes || '{}');
            const fieldTypeOptions = Object.entries(field_types)
                .map(([value, label]) => `<option value="${value}">${value}</option>`)
                .join('');

            const row = document.createElement('tr');

            row.innerHTML = `
                <td class="ps-3">
                    <input class="form-control form-control-sm" name="fields[${index}][name]" placeholder="Feldname" required type="text">
                </td>
                <td>
                    <select class="form-select form-select-sm" name="fields[${index}][type]" required>
                        <option disabled selected value="">Feldtyp Auswählen</option>
                        ${fieldTypeOptions}
                    </select>
                </td>
                <td>
                    <button class="btn btn-icon btn-outline-danger" onclick="this.closest('tr').remove()" title="Feld löschen" type="button">
                        ${this.dataset.iconTrash}
                    </button>
                </td>
            `;

            fields_container.appendChild(row);
        });
    }

});
