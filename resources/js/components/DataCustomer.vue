<template>
  <div class="col-4">
    <form id="parking_form">
      <div class="row mt-2">
        <div class="col">
          <div class="card">
            <div class="card-body">
              
                <h4 class="card-title font-weight-bold">Cliente</h4>
                <div class="form-group row">
                  <label for="staticEmail" class="col-4 text-right col-form-label">Documento:<sup class="text-danger">*</sup></label>
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
                <div class="form-group row">
                  <label for="staticEmail" class="col-4 text-right col-form-label">Nombre:<sup class="text-danger">*</sup></label>
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
                <div class="form-group row pr-3">
                  <label for="typeVehicle" class="col-5">Tipo vehículo:<sup class="text-danger">*</sup></label>
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
                <div class="form-group row">
                  <label for="plate" class="col-4 text-right col-form-label px-0">Placa/Serial:<sup class="text-danger">*</sup></label>
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
                <div class="form-group row">
                  <label for="model" class="col-4 text-right col-form-label">Modelo:<sup class="text-danger">*</sup></label>
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
                <div class="row">
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
      name: null
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
    async asignSlot(e) {
      e.preventDefault();

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
            this.allSlots();
            this.resetSelected()
            this.resetData()
            
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
      this.typeVehicles = ''
      this.documentId = null
      this.plate = null
      this.model = null
      this.name = null
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
      wayBoard: state => state.slots.wayBoard,
      waySelectInput: state => state.slots.waySelectInput,
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