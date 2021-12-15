@extends('layouts.back')

@section('content')
@php
    $settings = SettingsComponent::get();
@endphp
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Configuraciones {{Auth::user()->affiliate->name}}
            </h1>
        </div>
    </div>
    @include('templates.tabs',['tabs'=>[
            'Generales'=> 'admin.settings.generals',
            'Formas de pago'=> 'admin.settings.payment-methods',
    ]])
@endsection
