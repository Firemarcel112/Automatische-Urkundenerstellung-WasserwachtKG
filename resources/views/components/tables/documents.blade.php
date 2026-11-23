@use('App\Models\Document')
@props([
    'documents' => collect(),
])

<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Fertige Urkunden</h3>

            <button class="btn btn-sm btn-warning d-none"
                id="bulk-download-btn"
                data-download-url="{{ route('documents.bulk-download') }}"
                type="button">
                @includeif('icons.file-download')
                <span class="ms-1">Ausgewählte herunterladen (<span id="selected-count">0</span>)</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <input class="form-check-input m-0 align-middle"
                                id="select-all-documents"
                                type="checkbox"
                                aria-label="Alle Urkunden auswählen">
                        </th>
                        <th>Dateiname</th>
                        <th>Erstellungsdatum</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>
                                <input class="form-check-input m-0 align-middle document-checkbox"
                                    type="checkbox"
                                    value="{{ $document->document_id }}"
                                    aria-label="Urkunde {{ $document->file_name }} auswählen">
                            </td>
                            <td>{{ $document->file_name }}</td>
                            <td>{{ $document->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-icon btn-primary"
                                    data-bs-placement="top"
                                    data-bs-toggle="tooltip"
                                    href="{{ route('documents.show', ['document' => $document->document_id, 'hash' => $document->hash]) }}"
                                    title="Urkunde ansehen"
                                    target="_blank">@includeif('icons.eye')</a>

                                <a class="btn btn-icon btn-warning"
                                    data-bs-placement="top"
                                    data-bs-toggle="tooltip"
                                    href="{{ route('documents.download', ['document' => $document->document_id, 'hash' => $document->hash]) }}"
                                    title="Urkunde Herunterladen">@includeif('icons.file-download')</a>

                                <span data-bs-placement="top"
                                    data-bs-toggle="tooltip"
                                    title="Urkunde Löschen"><a class="btn btn-icon btn-danger"
                                        data-bs-target="#delete-file-{{ $document->document_id }}"
                                        data-bs-toggle="modal">@includeif('icons.trash')</a>
                                </span>

                                @include('components.modals.delete_document', [
                                    'id' => 'delete-file-' . $document->document_id,
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center"
                                colspan="4">Keine Urkunden gefunden, erstelle neue Urkunden auf der Startseite</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            @if (!empty($documents) && method_exists($documents, 'hasPages') && $documents->hasPages())
                <div class="p-3 mx-auto card-footer border-top-0">
                    <div class="col-12">
                        {{ $documents->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @elseif(!empty($documents->count()))
                <div class="p-3 mx-auto card-footer border-top-0">
                    <div class="col-12">
                        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                            <div class="small text-muted">
                                Zeige
                                <span class="fw-semibold">{{ $documents->count() }}</span>
                                Ergebnisse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="text-end">
                <button class="btn btn-sm btn-warning d-none"
                    id="bulk-download-btn-footer"
                    data-download-url="{{ route('documents.bulk-download') }}"
                    type="button">
                    @includeif('icons.file-download')
                    <span class="ms-1">Ausgewählte herunterladen (<span id="selected-count-footer">0</span>)</span>
                </button>
            </div>
        </div>
    </div>
</div>

@pushonce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select_all = document.getElementById('select-all-documents');
            const checkboxes = () => Array.from(document.querySelectorAll('.document-checkbox'));
            const bulk_download_btn = document.getElementById('bulk-download-btn');
            const bulk_download_btn_footer = document.getElementById('bulk-download-btn-footer');
            const count_selected_elements = document.getElementById('selected-count');
            const count_selected_elements_footer = document.getElementById('selected-count-footer');

            function updateBulkUi() {
                const checked = checkboxes().filter(cb => cb.checked);
                count_selected_elements.textContent = checked.length;
                count_selected_elements_footer.textContent = checked.length;
                bulk_download_btn.classList.toggle('d-none', checked.length === 0);
                bulk_download_btn_footer.classList.toggle('d-none', checked.length === 0);

                if (select_all) {
                    const all = checkboxes();
                    select_all.checked = all.length > 0 && checked.length === all.length;
                    select_all.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }

            select_all?.addEventListener('change', function() {
                checkboxes().forEach(cb => {
                    cb.checked = select_all.checked;
                });
                updateBulkUi();
            });

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('document-checkbox')) {
                    updateBulkUi();
                }
            });

            bulk_download_btn?.addEventListener('click', function() {
                bulkDownload();
            });

            bulk_download_btn_footer?.addEventListener('click', function() {
                bulkDownload();
            });

            function bulkDownload() {
                const ids = checkboxes().filter(cb => cb.checked).map(cb => cb.value);
                if (ids.length === 0) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = bulk_download_btn.dataset.downloadUrl;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                form.appendChild(csrf);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'document_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
                form.remove();
            }
        });
    </script>
@endpushonce
