@extends('layouts.backendv2.app')

@section('css')
    <link href="{{ asset('backendv2/plugins/apexcharts/apexcharts.css') }}" rel="stylesheet">
@endsection

@section('contenido')
    @livewire('misentradas.detalle-livewire', ['id' => $id])
@endsection

@section('js')
    <script src="{{ asset('backendv2/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endsection