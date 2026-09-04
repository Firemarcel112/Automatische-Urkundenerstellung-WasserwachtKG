<div aria-hidden="true" aria-labelledby="import-xlsx" class="modal fade" id="import-xlsx" tabindex="-1">
    <form action="{{ route('certificates.create.import.xlsx', $certificate) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="upload-certificate">Excel Importieren</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="input-icon mb-3">
                    </div>
                    <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" name="import_file" required type="file" />
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" type="button">Schließen</a>
                    <button class="btn btn-primary" type="submit">Importieren</button>
                </div>
            </div>
        </div>
    </form>
</div>
