@extends('layouts.back')

@section('content')
@if (\SC::$affiliateId==1)
  <a href="{{url('admin/destinations/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' data-modal-width='800px'><i class="fa fa-plus"></i> Nuevo Destino</a>
@endif
<h2>Destinos</h2>
<hr>
<table
  class="table table-condensed table-hover table-bordered"
  data-toggle='table'
  data-url='/admin/destinations/listing'
  data-height='350'
  data-side-pagination='server'
  data-page-size='50'
  data-pagination='true'
  data-search="true"
>
  <thead>
    <tr>
      <th >Nombre</th>
      <th>País</th>
      <th>Imagen</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
</table>
@endsection