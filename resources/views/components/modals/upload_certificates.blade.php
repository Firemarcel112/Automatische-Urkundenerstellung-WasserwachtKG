<div aria-hidden="true" aria-labelledby="upload-certificate" class="modal fade" id="upload-certificate" tabindex="-1">
    <form action="{{ route('certificates.upload.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="upload-certificate">Urkunde Hochladen</h1>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="input-icon mb-3">
                        <input class="form-control" data-bs-content="Der Name wird verwendet um die Urkunde im nachgang zu identifizieren" data-bs-title="Info" data-bs-toggle="popover" name="name" placeholder="Name" type="text" value="">
                        <span class="input-icon-addon">
                            <svg class="icon" fill="none" height="24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h24v24H0z" fill="none" stroke="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <input accept=".pdf" class="form-control" name="certificate" required type="file" />
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" type="button">Schließen</a>
                    <button class="btn btn-primary" type="submit">Hochladen</button>
                </div>
            </div>
        </div>
    </form>
</div>
