document.addEventListener('DOMContentLoaded', function () {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

document.addEventListener('DOMContentLoaded', function () {

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

});
