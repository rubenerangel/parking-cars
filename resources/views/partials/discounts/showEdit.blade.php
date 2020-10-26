@extends('parking')

  @section('body')
    <div class="row mt-3">
      <div class="col-8 mx-auto">
        <div class="card">
          <div class="card-body">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if ( isset($message) )
                <div class="col alert alert-success  text-center">
                  <span class="text-success">{{ $message }}</span>
                </div>
              @endif
            <form action="/discounts/{{$reduction->id}}" method="POST">
              @csrf
              @method('PUT')
              <h2 class="text-center">Modificar descuento</h2>
              <div class="form-group row">
                <label for="name" class="col-4 col-form-label text-right">Nombre <sup class="text-danger">*</sup>:</label>
                <div class="col-8">
                  <input 
                    type="text" 
                    class="form-control form-control-sm" 
                    id="name" 
                    placeholder="Ingrese su nombre"
                    value="{{$reduction->name}}"
                    name="name"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label for="percentage" class="col-4 col-form-label text-right">(%) descuento <sup class="text-danger">*</sup>:</label>
                <div class="col-8">
                  <input 
                    type="text" 
                    class="form-control form-control-sm" 
                    id="percentage" 
                    placeholder="Ingrese el porentaje (%)"
                    value="{{$reduction->percentage}}"
                    name="percentage"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label for="time" class="col-4 col-form-label text-right">Tiempo a partir de <sup class="text-danger">*</sup>:</label>
                <div class="col-8">
                  <input 
                    type="text" 
                    class="form-control form-control-sm" 
                    id="time" 
                    placeholder="Ingrese el tiempo en minutos"
                    value="{{$reduction->time}}"
                    name="time"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label for="active" class="col-4 col-form-label text-right">Activo:</label>
                <div class="col-8">
                  <div class="form-check">
                    <input 
                      class="form-check-input mt-2" 
                      type="checkbox" 
                      id="active"
                      
                      {{$reduction->active ? 'checked' : '' }}
                      name="active"
                    >
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <input type="submit" value="Modificar" class="btn btn-success btn-sm mx-auto">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @stop
