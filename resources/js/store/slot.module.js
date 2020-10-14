const slots = {
  namespaced: true,
  state: {
    slotsParking: [],
    selectedSlotName: null,
    selectedSlotId: null,
  },
  getters: {
    carsSlotsBack: state => {
      return state.slotsParking.filter(slot => slot.position % 2 !== 0 && slot.type_vehicle_id === 3)
    },
    carsSlotsFront: state => {
      return state.slotsParking.filter(slot => slot.position % 2 === 0 && slot.type_vehicle_id === 3)
    },
    bicycleSlots: state => {
      return state.slotsParking.filter(slot => slot.type_vehicle_id === 1)
    },
    MotorcycleSlotsBack: state => {
      return state.slotsParking.filter(slot => slot.position % 2 !== 0 && slot.type_vehicle_id === 2)
    },
    MotorcycleSlotsFront: state => {
      return state.slotsParking.filter(slot => slot.position % 2 === 0 && slot.type_vehicle_id === 2)
    },
    slotsCarsNotBusy: state => {
      return state.slotsParking.filter(slot => slot.type_vehicle_id === 3 && slot.availability_status === 0)
    }

  },
  mutations: {
    ALLSLOTS(state, payLoad) {
      state.slotsParking = payLoad
    },
    SELECTEDSLOT(state, payLoad) {
      // state.selectedSlot.id = payLoad.id
      state.selectedSlotId = payLoad.id
      state.selectedSlotName = payLoad.name
    }
  },
  actions:{
    async allSlots({commit}) {
      let parkingSolts = await axios.get('/slots')
        .then(resp => {
          return resp.data.data
        })

      commit('ALLSLOTS', parkingSolts)
    },
    selectSlot({commit}, selectS) {
      commit('SELECTEDSLOT', selectS)
    },
    resetSelected({commit}) {
      const reset = {
        id: null,
        name: null
      }
      commit('SELECTEDSLOT', reset)
    }
  }
}

export default slots;
