@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            @include('components.modals.upload_certificates')
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Dashboard</h2>
                </div>
                <div class="col-auto mx-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary" data-bs-target="#upload-certificate" data-bs-toggle="modal"data-bs-toggle="modal" type="button">Neue Urkunde Hochladen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                @include('components.tables.certificate_templates')
            </div>
        </div>
    </div>
@endsection
