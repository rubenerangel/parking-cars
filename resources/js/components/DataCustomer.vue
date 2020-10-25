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
                      maxlength="7"
                      @blur="validatePlate()"
                      ref="plate"
                    >
                  </div>
                </div>

                <div class="row" v-if="isInSlot && isInSlot!== 1">
                  <small class="text-danger col offset-4">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                    </svg>
                    Ya esta ubicado en el Puesto <strong>{{ isInSlot }}</strong></small>
                </div>

                <div class="row" v-if="isInSlot === 1">
                  <small class="text-success col offset-4">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    Correcto
                  </small>
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
                  <button 
                    class="btn btn-success btn-sm mx-auto" 
                    @click="asignSlot($event)"
                    ref="btnAssign"
                    :disabled="toggleDisable"
                  >Asignar</button>
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
      },
      isInSlot: null,
      validPlateStatus: null,
      disabledBtn: false
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
    async validatePlate() {
      this.isInSlot = null
      await axios.post('/plate-validate', {plate: this.plate})
        .then(resp => {
          if (resp.data.status === 0) {
            this.isInSlot = resp.data.slot
            this.validPlateStatus = resp.data.status // 0
            this.disabledBtn = true

            this.$refs.plate.focus()
            this.$refs.btnAssign.disabled

            return false
          } else if (resp.data.status === 1) {
            this.isInSlot = 1

            this.validPlateStatus = resp.data.status // 1
            this.disabledBtn = false

            return false
          }
        }) 
    },
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

      formData.append('rate_id', this.selectedSlotRateId)
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
      selectedSlotRateId: state => state.slots.selectedSlotRateId,
      typeSelectec() {
        return this.slotWayChange(this.selectedSlotType)
      }
    }),
    ...mapGetters('slots', {
      slotsNotBusy :'slotsNotBusy',
      slotWayChange: 'slotWayChange'
    }),
    toggleDisable() {
      return this.disabledBtn
    }
  }, 
}
</script>

<style lang="scss" scoped>

</style>