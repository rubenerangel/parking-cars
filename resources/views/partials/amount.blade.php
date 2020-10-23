<div class="card mt-3">
  <div class="card-body">
    <div class="row">
      <div class="col text-center">
        <h2>Monto entre fechas</h2>

        <table class="table striped">
          <thead>
            <tr>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($amount as $earned)
              <tr>
                <td>{{ $earned->mount_total }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>