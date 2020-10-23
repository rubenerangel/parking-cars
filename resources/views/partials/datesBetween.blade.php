  @section('body')
    <div class="card mt-4">
      <div class="card-body">
        <form action="/reports" method="POST" id="reports">
          @csrf
          <div class="row">
            <div class="col mr-1">
              <div class="form-group row mb-0 mt-2">
                <label for="staticEmail" class="col-4 text-right col-form-label pb-0">Desde:</label>
                <div class="col">
                  <input 
                    type="date" 
                    class="form-control form-control-sm" 
                    id="from" 
                    name="from"
                    value="<?php echo date('Y-m-d')?>"
                  >
                </div>
              </div>
            </div>
            <div class="col ml-1">
              <div class="form-group row mb-0 mt-2">
                  <label for="staticEmail" class="col-2 text-left col-form-label pb-0">Hasta:</label>
                  <div class="col-8 px-0">
                    <input 
                      type="date" 
                      class="form-control form-control-sm" 
                      id="until" 
                      name="until"
                      value="<?php echo date('Y-m-d')?>"
                    >
                  </div>
                </div>
              </div>
          </div>
          <div class="row m-1 mt-4">
            <div class="col-12 text-center">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="moreUsed" value="more_used">
                <label class="form-check-label" for="moreUsed">Más usado</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="inOut" value="in_out">
                <label class="form-check-label" for="inOut">Entrada y Salida</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="typeVehicle" value="type_vehicle">
                <label class="form-check-label" for="typeVehicle">Vehículos por tipo</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="amount" value="amount" checked>
                <label class="form-check-label" for="amount">Monto por día</label>
              </div>
            </div>
          </div>
          <div class="row m-1 mt-4">
            <div class="col-12 text-center">
              <button class="btn btn-sm btn-success mx-auto">Ver</button>
            </div>
          </div>
        </form> 
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (isset($moreUsed))
      @include('partials.moreUsed')
    @endif

    @if (isset($inOut))
      @include('partials.inOut')
    @endif

    
    @if (isset($typeVehicle))
      @include('partials.typeVehicle')
    @endif

    @if (isset($amount))
      @include('partials.amount')
    @endif
  @stop
