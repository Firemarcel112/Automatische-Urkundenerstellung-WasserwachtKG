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
                    <h2 class="page-title">Urkunden erstellen</h2>
                    <p class="page-subtitle">{{ $certificate->name }}</p>
                </div>
                <div class="col-auto mx-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary" data-bs-target="#delete-certificate" data-bs-toggle="modal" type="button">CSV Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <form action="{{ route('certificates.create.store', $certificate) }}" method="POST">
                @csrf
                <div class="row mb-2">
                    <div class="card shadow-sm">
                        <div class="alert alert-info mt-2 mb-0" role="alert">
                            <div class="alert-icon">@includeif('icons.info')</div>
                            <span>
                                Jeder neuer Block ist eine eigene Urkunde
                            </span>
                        </div>
                        <div class="card-header py-3">
                            <h2 class="mb-0">Neue Urkunden anlegen</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3"></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="certificate-fields">
                                    <tr>
                                        <td class="ps-3">
                                            <div class="row">
                                                @foreach ($certificate->fields as $name => $type)
                                                    <div class="form-floating mb-2 col-md-4 col-6">
                                                        @switch($type)
                                                            @case('Button')
                                                            @case('button')
                                                                <select class="form-select" id="{{ $name }}-0" name="certificate[0][{{ $name }}]">
                                                                    <option value="true">Ja</option>
                                                                    <option selected value="false">Nein</option>
                                                                </select>
                                                            @break

                                                            @default
                                                                <input class="form-control" id="{{ $name }}-0" name="certificate[0][{{ $name }}]" placeholder="{{ $name }}">
                                                        @endswitch
                                                        <label for="{{ $name }}-0">{{ $name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-icon btn-outline-danger" data-bs-toggle="tooltip" onclick="const row = this.closest('tr'); const tbody = row?.parentElement; if (tbody && tbody.children.length > 1) row.remove();" title="Feld löschen" type="button">
                                                @include('icons.trash')
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-outline-primary" data-icon-trash="{{ view('icons.trash')->render() }}" id="add-new-row" type="button">
                                Neuen Block Anlegen
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

    <script></script>
@endsection
