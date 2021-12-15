<form action="{{url('admin/transport-services/update',['id'=>$dest->id])}}" method="post" class="validate-form ajax-submit" data-reload-tables='true'>
  {{ csrf_field() }}
  <p class="text-danger"><strong>*Los costos en 0 no se mostraran</strong></p>
  <table class="table">
    <thead>
      <tr>
        <th>Transporte</th>
        <th>Precio Oneway</th>
        <th>Precio Roundtrip</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transportServiceTypes as $tst)
        @php
          list($oneway,$roundtrip) = $dest->getPrices($tst);
        @endphp
        <tr>
          <td>
            <p>{{$tst->name}}</p>
            <input type="hidden" name="transportServiceTypes[]" value="{{$tst->id}}">
          </td>
          <td>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">$</span>
              <input type="text" name="onewayPrices[]" class="form-control required number" value="{{$oneway}}">
              <span class="input-group-addon">USD</span>
            </div>
          </td>
          <td>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">$</span>
              <input type="text" name="roundtripPrices[]" class="form-control required number" value="{{$roundtrip}}">
              <span class="input-group-addon">USD</span>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <br>
  <button type="submit" class="btn btn-primary btn-block">Guardar</button>
</form>