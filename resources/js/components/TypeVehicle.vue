<template>
  <div>
    <!--  -->
  </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
  name: 'TypeVehicle',
  data() {
    return {
      typeVehicles: '',
      vehicles: [],
    }
  },
  mounted () {
    this.allTypeVehicles();
  },
  methods: {
    allTypeVehicles() {
      axios.get('/vehicles')
        .then(resp => {
          this.vehicles = resp.data.data
        })
    },
    asignSlot(e) {
      e.preventDefault();
      console.log('clikc');
      if (!this.typeVehicles) {
        alert('Seleccionar el Tipo de vehículo')
      }
    }
  },
  
}
</script>

<style lang="scss" scoped>

</style>