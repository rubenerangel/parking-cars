<div class="card mt-3">
  <div class="card-body">
    <div class="row">
      <div class="col text-center">
        <h2>Tipo de vehículos, cantidad que han ingresado</h2>

        <table class="table striped">
          <thead>
            <tr>
              <th>Cantidad</th>
              <th>Tipo de vehículo</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($typeVehicle as $type)
              <tr>
                <td>{{ $type->count_types }}</td>
                <td>{{ $type->name }}</td>
                
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>