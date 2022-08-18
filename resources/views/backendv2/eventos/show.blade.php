@extends('layouts.backendv2.app')

@section('contenido')
    @livewire('entradas-evento-livewire', ['id' => $id])
@endsection