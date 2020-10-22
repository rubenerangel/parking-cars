<template>
  <div class="col-4">
    <form id="parking_form">
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Cliente</h4>
                <div class="form-group row mb-0">
                  <label for="staticEmail" class="col-4 text-right col-form-label pb-0">Documento:<sup class="text-danger">*</sup></label>
                  <div class="col">
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      id="documentId" 
                      name="documentId"
                      v-model="documentId"
                    >
                  </div>
                </div>

                <div class="row" v-if="error && error.document">
                  <small class="col offset-4 text-danger">{{ error.document }}</small>
                </div>
                
                <div class="form-group row mb-0 mt-2">
                  <label for="staticEmail" class="col-4 text-right col-form-label pb-0">Nombre:<sup class="text-danger">*</sup></label>
                  <div class="col">
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      id="name" 
                      name="name"
                      v-model="name"
                    >
                  </div>
                </div>

                <div class="row" v-if="error && error.name">
                    <small  class="col offset-4 text-danger">{{ error.name }}</small>
                </div>
              
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col text-left">
                    <h4 class="card-title font-weight-bold">Vehículo</h4>
                  </div>
                  
                  <div class="col text-right d-flex justify-content-between">
                    <span class="h5">Puesto:</span> <span class="h5" v-if="selectedSlotName"> {{selectedSlotName}}</span>
                  </div>
                </div>
                <div class="form-group row pr-3 mb-0">
                  <label for="typeVehicle" class="col-5 pb-0 pr-4 text-right">Tipo vehículo:<sup class="text-danger">*</sup></label>
                  <select 
                    class="form-control form-control-sm col" 
                    id="typeVehicle"
                    :value="$store.state.slots.selectedSlotType"
                    @input="$store.commit('slots/INPUTSELECTTYPESLOT', Number($event.target.value))"
                    name="type_vehicle_id"
                    @change="selectTypeVehicleChange()"
                  >
                    <option value="">--Seleccione--</option>
                    <option 
                      v-for="(vehicle, index) in vehicles" 
                      :key="index" 
                      :value="vehicle.id"
                    >{{ vehicle.name }}</option>
                  </select>
                </div>

                <div class="row" v-if="error && error.typeVehicle">
                  <small  class="col offset-4 text-danger">{{ error.typeVehicle }}</small>
                </div>

                <div class="form-group row mb-0 mt-2">
                  <label for="plate" class="col-4 text-right col-form-label px-0 pb-0">Placa/Serial:<sup class="text-danger">*</sup></label>
                  <div class="col">
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      id="plate" 
                      name="plate"
                      v-model="plate"
                    >
                  </div>
                </div>

                <div class="row" v-if="error && error.plate">
                  <small  class="col offset-4 text-danger">{{ error.plate }}</small>
                </div>
                <!--div class="form-group row">
                  <label for="serial" class="col-4 text-right col-form-label">Serial:<sup class="text-danger">*</sup></label>
                  <div class="col">
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      id="serial" 
                      name="serial"
                      v-model="serial"
                    >
                  </div>
                </div-->
                <div class="form-group row mb-0 mt-2">
                  <label for="model" class="col-4 text-right col-form-label pb-0 px-0">Modelo:<sup class="text-danger">*</sup></label>
                  <div class="col">
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      id="model" 
                      name="model"
                      v-model="model"
                      >
                  </div>
                </div>

                <div class="row" v-if="error && error.model">
                  <small  class="col offset-4 text-danger">{{ error.model }}</small>
                </div>

                <div class="row mt-3">
                  <button class="btn btn-success btn-sm mx-auto" @click="asignSlot($event)">Asignar</button>
                </div>
              
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import { mapState, mapGetters, mapActions } from 'vuex'

import TypeVehicle from './TypeVehicle'
export default {
  name: 'DataCustomer',
  data() {
    return {
      vehicles: [],
      documentId: null,
      plate: null,
      model: null,
      name: null,
      error: {
        document: null,
        name: null,
        typeVehicle: null,
        plate: null,
        model: null
      }
    }
  },
  mounted () {
    this.allTypeVehicles();
  },
  methods: {
    ...mapActions('slots', [
      'allSlots',
      'selectSlot',
      'resetSelected',
      'inputSelectTypeSlot',
    ]),
    
    allTypeVehicles() {
      axios.get('/vehicles')
        .then(resp => {
          this.vehicles = resp.data.data
        })
    },
    validateForm() {
      this.error = {}

      if (!this.documentId) {
        this.error.document = 'El documento es Requerido!'
      }
      if (!this.name) {
        this.error.name = 'El Nombre del Cliente es requerido!'
      }
      if (!this.selectedSlotType) {
        this.error.typeVehicle = 'El Tipo de Vehiculo es requerido!'
      }
      if (!this.plate) {
        this.error.plate = 'La placa es requerida!'
      }
      if (!this.model) {
        this.error.model = 'El modelo es requerido!'
      }

      if (Object.keys(this.error).length > 0) {
        return false
      } else {
        return true
      }
    },
    async asignSlot(e) {
      e.preventDefault();
      if (!this.validateForm()) return false

      let parkingForm = document.getElementById('parking_form')
      let formData = new FormData(parkingForm)

      formData.append('rate_id', 1)
      if (!this.selectedSlotId) {
        formData.append('slot_id', this.slotsNotBusy[0].id)
      } else {
        formData.append('slot_id', this.selectedSlotId)
      }
      formData.append('in_time', this.inTime())

      await axios.post('/parking', formData)
        .then(resp => {
          if (resp.data.status) {
            this.resetData()
            this.resetSelected()
            this.allSlots();

            Swal.fire(
              'Slot Asignado!',
              'Genial',
              'success'
            )
          }
        })
        .catch(error => {
          console.log(error)
        })
    },
    inTime() {
      let CurrentDateUnixTimestamp = moment().unix()
      return moment.unix(CurrentDateUnixTimestamp).format("YYYY-MM-DD HH:mm")
    },
    resetData() {
      this.documentId = null
      this.plate = null
      this.model = null
      this.name = null
      this.error = {}
    },
    selectTypeVehicleChange() {
      if (this.selectedSlotType) {
        let randomSlot = this.typeSelectec[Math.floor(Math.random() * this.typeSelectec.length)];
        this.selectSlot(randomSlot)

        EventBus.$emit('selSlot', randomSlot)
      }
    }
  },
  computed: {
    ...mapState({
      selectedSlotName: state => state.slots.selectedSlotName,
      selectedSlotId: state => state.slots.selectedSlotId,
      selectedSlotType: state => state.slots.selectedSlotType,
      typeSelectec() {
        return this.slotWayChange(this.selectedSlotType)
      }
    }),
    ...mapGetters('slots', {
      slotsNotBusy :'slotsNotBusy',
      slotWayChange: 'slotWayChange'
    })
  }, 
}
</script>

<style lang="scss" scoped>

</style>