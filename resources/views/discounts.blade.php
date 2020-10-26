@extends('parking')

  @section('body')
    <div class="row m-3">
      <div class="col">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>% Descuento</th>
              <th>Tiempo a partir de</th>
              <th>Activo</th>
              <th>&nbsp;</th>
            </th>
          </thead>
          <tbody>
            @foreach( $reductions as $reduction )
            <tr>
              <td>{{ $reduction->id }}</td>
              <td>{{ $reduction->name }}</td>
              <td>{{ $reduction->percentage }}</td>
              <td>{{ $reduction->time }}</td>
              <td>{{ $reduction->active === 1 ? 'Sí' : 'No' }}</td>
              <td class="d-flex">
                <span class="mr-3"><a href="/discounts/{{ $reduction->id }}" class="btn btn-sm btn-warning">Modificar</a></span>
                <span>
                  <form action="/discounts/{{ $reduction->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Eliminar" class="btn btn-sm btn-danger">
                  </form>
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @stop