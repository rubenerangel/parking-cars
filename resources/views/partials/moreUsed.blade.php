<div class="card mt-3">
  <div class="card-body">
    <div class="row">
      <div class="col text-center">
        <h2>Puesto más ocupado</h2>

        <table class="table striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($moreUsed as $slot)
              <tr>
                <td>{{ $slot->slot_id }}</td>
                <td>{{ $slot->name }}</td>
                <td>{{ $slot->more_used }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>