<div aria-hidden="true" aria-labelledby="delete-certificate" class="modal fade" id="delete-certificate" tabindex="-1">
    <form action="{{ route('certificates.settings.delete', $certificate) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-dialog">
            <div class="modal-content">
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    @includeif('icons.alert-triangle', ['classes' => 'mb-2 text-danger icon-lg'])
                    <h3>Bist du dir sicher?</h3>
                    <div class="text-secondary">Möchtest du diese Urkunde wirklich löschen? Deine Aktion kann nicht rückgängig gemacht werden.</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-3 w-100" data-bs-dismiss="modal" href="#">abbrechen</a>
                            </div>
                            <div class="col">
                                <button class="btn btn-danger btn-4 w-100" type="submit">Löschen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
