@extends('cliente.layouts')

@section('contenido')
    @livewire('cliente.detalle-livewire', ['token' => $token])    
@endsection
