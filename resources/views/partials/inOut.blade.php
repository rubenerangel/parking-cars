<div class="card mt-3">
  <div class="card-body">
    <div class="row">
      <div class="col text-center">
        <h2>Entrada y salida de cada vehículo</h2>

        <table class="table striped">
          <thead>
            <tr>
              <th>Placa / Serial</th>
              <th>Entrada</th>
              <th>Salida</th>
              <th>Tiempo total</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($inOut as $in_out)
              <tr>
                <td>{{ $in_out->plate }}</td>
                <td>{{ $in_out->in_time }}</td>
                <td>{{ $in_out->out_time }}</td>
                <td>{{ $in_out->total_time }}</td>
                <td>{{ $in_out->earned_amount }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>