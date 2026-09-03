@use('App\Models\Certificate')
@props([
    'certificate' => collect(),
])
@php
    /** @var Certificate $certificate **/
@endphp
@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            @include('components.modals.delete_certificate')
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Einstellungen</h2>
                    <p class="page-subtitle">{{ $certificate->name }}</p>
                </div>
                <div class="col-auto mx-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-danger" data-bs-target="#delete-certificate" data-bs-toggle="modal" type="button">Urkunde Löschen</button>
                        <a class="btn btn-primary" href="{{ route('certificates.settings.showDocument', $certificate) }}" target="_blank" type="button">Urkunde ansehen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <form action="{{ route('certificates.settings.update', $certificate) }}" method="POST">
                @csrf
                <div class="form-floating mb-2">
                    <input class="form-control" id="name" name="name" placeholder="Name" value="{{ $certificate->name }}">
                    <label for="name">Name</label>
                </div>

                <div class="text-end mb-2">
                    <button class="btn btn-success" type="submit">Änderungen Speichern</button>
                </div>

                <div class="row mb-2">
                    <div class="card shadow-sm">
                        <div class="alert alert-danger mt-2 mb-0" role="alert">
                            <div class="alert-icon">@includeif('icons.info')</div>
                            <span>
                                <b>ACHTUNG</b>: Ändere hier nur etwas, wenn die Felder nicht korrekt erkannt worden sind!
                                <br>
                                Änderungen hier wirken sich nicht auf dass Template aus, solltest du dein Template ändern, lade dies erneut hoch sonst werden Feldänderungen im Template nicht erkannt!
                            </span>
                        </div>
                        <div class="card-header py-3">
                            <h2 class="mb-0">Felder-Konfiguration</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" data-bs-toggle="tooltip" style="width: 60%;" title="Name des Feldes im PDF">Feldname</th>
                                        <th data-bs-content="@foreach ($field_types as $field_type => $field_description){{ $field_type }}: {{ $field_description }}<br> @endforeach" data-bs-html="true" data-bs-placement="top" data-bs-title="Typdefinition Felder" data-bs-toggle="popover" data-bs-trigger="hover" style="width: 40%;">FeldTyp</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="certificate-fields">
                                    @foreach ($certificate->fields as $name => $type)
                                        <tr>
                                            <td class="ps-3">
                                                <input class="form-control form-control-sm" name="fields[{{ $name }}][name]" type="text" value="{{ $name }}">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm" name="fields[{{ $name }}][type]">
                                                    @foreach ($field_types as $field_type => $field_description)
                                                        <option {{ strtolower($type) == strtolower($field_type) ? 'selected' : '' }} value="{{ strtolower($field_type) }}">{{ $field_type }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-icon btn-outline-danger" data-bs-toggle="tooltip" onclick="this.closest('tr').remove()" title="Feld Löschen" title="Feld löschen" type="button">
                                                    @include('icons.trash')
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-outline-primary" data-field-types='@json($field_types)' data-icon-trash="{{ view('icons.trash')->render() }}" id="add-certificate-field" type="button">
                                Feld hinzufügen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-success" type="submit">Änderungen Speichern</button>
                </div>
            </form>
            <div class="row row-deck row-cards">
            </div>
        </div>
    </div>
@endsection
