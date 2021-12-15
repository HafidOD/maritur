<a href="{{url('admin/transport-services/create',['dest'=>$dest->id])}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' ><i class="fa fa-plus"></i></a>
<table
  class="table table-condensed table-hover table-bordered"
  data-toggle='table'
  data-url='{{url('admin/transport-services/listing',['dest'=>$dest->id])}}'
  data-height='350'
  data-side-pagination='server'
  data-page-size='50'
  data-pagination='true'
  data-search="false"
>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Precio Oneway</th>
      <th>Precio Roundtrip</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
</table>