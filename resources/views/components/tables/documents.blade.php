@use('App\Models\Document')
@props([
    'documents' => collect(),
])

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Fertige Urkunden</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>Dateiname</th>
                        <th>Erstellungsdatum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>{{ $document->file_name }}</td>
                            <td>{{ $document->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-icon btn-primary" data-bs-placement="top" data-bs-toggle="tooltip" href="{{ route('documents.show', ['document' => $document->document_id, 'hash' => $document->hash]) }}" target="_blank" title="Urkunde ansehen">@includeif('icons.eye')</a>
                                <a class="btn btn-icon btn-warning" data-bs-placement="top" data-bs-toggle="tooltip" href="{{ route('documents.download', ['document' => $document->document_id, 'hash' => $document->hash]) }}" title="Urkunde Herunterladen">@includeif('icons.file-download')</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Keine Urkunden gefunden, erstelle neue Urkunden auf der Startseite</td>
                        </tr>
                    @endif
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
            @endif
        </div>
    </div>
</div>
