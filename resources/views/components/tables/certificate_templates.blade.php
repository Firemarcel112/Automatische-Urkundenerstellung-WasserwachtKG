@props([
    'certificate_templates' => collect(),
])
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Urkunden Vorlagen</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>Urkundenname</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificate_templates as $certificate_template)
                        <tr>
                            <td>{{ $certificate_template->name }}</td>
                            <td class="text-end">
                                <a class="btn btn-icon btn-primary" data-bs-placement="top" data-bs-toggle="tooltip" href="{{ route('certificates.create.show', $certificate_template) }}" title="Neue Urkunde erstellen">@includeif('icons.plus')</a>
                                <a class="btn btn-icon btn-warning" data-bs-placement="top" data-bs-toggle="tooltip" href="{{ route('certificates.settings.show', $certificate_template) }}" title="Einstellungen">@includeif('icons.settings')</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Keine Vorlagen gefunden. Klicke auf "Neue Urkunde Hochladen" um eine neue Vorlage zu erstellen.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @if (!empty($certificate_templates) && method_exists($certificate_templates, 'hasPages') && $certificate_templates->hasPages())
                <div class="p-3 mx-auto card-footer border-top-0">
                    <div class="col-12">
                        {{ $certificate_templates->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
