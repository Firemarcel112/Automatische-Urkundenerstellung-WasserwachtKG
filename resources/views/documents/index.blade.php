@use('App\Models\Document')
@props([
    'documents' => collect(),
])
@php
    /** @var Document[] $documents **/
@endphp
@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Dokumente</h2>
                </div>
                <div class="col-auto mx-auto d-print-none">
                    <div class="btn-list">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                @include('components.tables.documents')
            </div>
        </div>
    </div>
@endsection
