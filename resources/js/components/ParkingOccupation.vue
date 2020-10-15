<template>
  <div class="col d-flex justify-content-center align-items-center">
    <div class="row mt-2">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-1 col-in">
              ENTRADA
            </div>
            <div class="col">
              <div class="row">
                <div 
                  class="col text-center cars-slots" 
                  v-for="(slot, index) in carsSlotsBack" 
                  :id="`slot_${slot.id}`"
                  :key="index"
                  :class="slot.availability_status ? 'occupied' : selected_class ? 'selected' : 'not-busy'"
                  @click="slotSelect(slot)"
                >{{ slot.name }}</div>
              </div>
              <div class="row">
                <div 
                  class="col text-center cars-slots" 
                  v-for="(slot, index) in carsSlotsFront" 
                  :id="`slot_${slot.id}`"
                  :key="index"
                  :class="slot.availability_status ? 'occupied' : selected_class ? 'selected' : 'not-busy'"
                  @click="slotSelect(slot)"
                >{{ slot.name }}</div>
              </div>
              <div class="row">
                <div class="col">
                  -->
                </div>
              </div>
              <div class="row">
                <div 
                class="col text-center cars-slots" 
                v-for="(slot, index) in bicycleSlots" :key="index"
                :class="slot.availability_status ? 'occupied' : 'not-busy'"
                >{{ slot.name }}</div>
              </div>
              <div class="row">
                <div class="col">
                  -->
                </div>
              </div>

              <div class="row">
                <div 
                  class="col text-center cars-slots"
                  v-for="(slot, index) in MotorcycleSlotsFront" :key="index"
                  :class="slot.availability_status ? 'occupied' : 'not-busy'"
                >{{slot.name}}</div>
              </div>

              <div class="row">
                <div 
                  class="col text-center cars-slots"
                  v-for="(slot, index) in MotorcycleSlotsBack" :key="index"
                  :class="slot.availability_status ? 'occupied' : 'not-busy'"
                >{{slot.name}}</div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import {mapState, mapGetters, mapActions} from 'vuex'

export default {
  name: 'ParkingOccupation',
  data() {
    return {
      slot_was_occupied: null,
      selected_class: false,
      slotParking: null
    }
  },
  mounted () {
    this.allSlots()
  },
  methods: {
    ...mapActions('slots', [
      'allSlots',
      'selectSlot'
    ]),
    async slotSelect(slot) {
      // Validate element exists whit class no-busy
      let slotPreSelectClass = document.querySelector(`#slot_${slot.id}.not-busy`)

      if (slotPreSelectClass) {

        if (!this.selectedSlotName && slotPreSelectClass) { 
          //mark first
          this.markSlot(slot)

          this.selectSlot(slot)
          
          return 0
        }
        
        //Unamark last
        if (this.selectedSlotName && slotPreSelectClass)  {
          // previus slot unmark
          this.unMarkPrevius()
          // mark new selected
          this.markSlot(slot)

          this.selectSlot(slot)

          return 0
        }
      } else {
        let slotOccupied = document.querySelector(`#slot_${slot.id}.occupied`)
        this.slotParking = slot.id

        await axios.post('/empty-slot', {'id': slot.id, 'out_time': this.outTime()})
          .then(resp => {
            if (resp.data.status === 1) {
              let slotReleased = document.querySelector(`#slot_${this.slotParking}.occupied`)

              this.releasedSlot(this.slotParking)
              // TODO Sweetalert Slot Liberado

              /* Update Store Slots */
              this.allSlots();
            }
          })
          .catch(error => {
            console.log(error);
          })
      }
    },
    markSlot(slot) {
      let slotPreSelect = document.querySelector(`#slot_${slot.id}`)
      slotPreSelect.classList.remove('not-busy')
      slotPreSelect.classList.add('selected')
    },
    unMarkPrevius() {
      let slotPreSelect = document.querySelector(`#slot_${this.selectedSlotId}`)
      slotPreSelect.classList.remove('selected')
      slotPreSelect.classList.add('not-busy')
    },
    releasedSlot() {
      let slotReles = document.querySelector(`#slot_${this.slotParking}`)
      slotReles.classList.remove('occupied')
      slotReles.classList.add('not-busy')
    },
    outTime() {
      let CurrentDateUnixTimestamp = moment().unix()
      return moment.unix(CurrentDateUnixTimestamp).format("YYYY-MM-DD HH:mm")
    }
  },
  computed: {
    ...mapState({
      theSlosts: state => state.slots.slotsParking,
      selectedSlotName: state => state.slots.selectedSlotName,
      selectedSlotId: state => state.slots.selectedSlotId,
    }),
    ...mapGetters('slots', [
      'carsSlotsBack',
      'carsSlotsFront',
      'bicycleSlots',
      'MotorcycleSlotsBack',
      'MotorcycleSlotsFront',
    ])
  }
}
</script>

<style lang="scss" scoped>
  .not-busy {
    background: green;
    color: #fff;
    -webkit-transition: background 1s; 
    transition: background 1s;
    font-weight: 600;
  }

  .occupied {
    background: red;
    color: #fff;
    -webkit-transition: background 1s; 
    transition: background 1s;
    font-weight: 600;
  }

  .col-in {
    font-size: 1rem;
    letter-spacing: 1rem;
    text-align: center;
    padding: 0rem 3rem;
    font-weight: 600;
  }

  .cars-slots:hover {
    background: #3cc16e;
    cursor: pointer;
    font-weight: 600;
  }

  .selected {
    background: rgba(0,0,255, 0.2);
    color: #fff;
    font-weight: 600;
  }

</style>