@extends('cliente.layouts')

@section('contenido')
    @livewire('cliente.recepcion-livewire', ['id' => $base64])    
@endsection
