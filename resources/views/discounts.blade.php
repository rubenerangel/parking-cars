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
            </th>
          </thead>
          <tbody>
            @foreach( $reductions as $reduction )
            <tr>
              <td>{{ $reduction->id }}</td>
              <td>{{ $reduction->name }}</td>
              <td>{{ $reduction->percentage }}</td>
              <td>{{ $reduction->time }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @stop