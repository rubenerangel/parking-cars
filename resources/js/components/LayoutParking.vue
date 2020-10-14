<template>
  <section>
    <div class="row m-2">
      <div class="col-3 "></div>
      <div class="col">
        <h1 class="text-center">Registro de vehículos</h1>
      </div>
      <date-time-parking />
    </div>
    <div class="row mb-3">
      <data-customer />
      <parking-occupation />
    </div>
  </section>
</template>

<script>
import DataCustomer from './DataCustomer'
import ParkingOccupation from './ParkingOccupation'
import DateTimeParking from './DateTimeParking'

export default {
  name: 'Layoutparking',
  components: {
    DataCustomer,
    ParkingOccupation,
    DateTimeParking,
  },
}
</script>

<style lang="scss" scoped>

</style>