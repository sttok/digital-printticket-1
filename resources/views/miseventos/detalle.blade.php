@extends('layouts.new.app')

@section('contenido')
    @livewire('misentradas.nuevo.detalle-livewire', ['event_id' => $id])
@endsection

