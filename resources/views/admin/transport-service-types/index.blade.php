@extends('layouts.back')

@section('content')
<h2>Destinos</h2>
<hr>
<table
  class="table table-condensed table-hover table-bordered"
  data-toggle='table'
  data-url='/admin/transport-services/destinations-listing'
  data-height='350'
  data-side-pagination='server'
  data-page-size='50'
  data-pagination='true'
  data-search="true"
>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>País</th>
      <th>Tipos de transporte</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
</table>
<hr>
<h2>Tipos de transporte</h2>
<hr>
<table
  class="table table-condensed table-hover table-bordered"
  data-toggle='table'
  data-url='/admin/transport-services-types/listing'
  data-height='350'
  data-side-pagination='server'
  data-page-size='50'
  data-pagination='true'
  data-search="true"
>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Cobro</th>
      <th>Máx. de personas</th>
      <th>Imagen</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
</table>
@endsection