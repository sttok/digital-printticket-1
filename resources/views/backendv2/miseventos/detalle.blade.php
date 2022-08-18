@extends('layouts.backendv2.app')

@section('contenido')
    @livewire('misentradas.detalle-livewire', ['id' => $id])
@endsection