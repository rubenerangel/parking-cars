const slots = {
  namespaced: true,
  state: {
    slotsParking: [],
    selectedSlotName: null,
    selectedSlotId: null,
    // dataSlots:null,
    selectedSlotType: null
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
      state.selectedSlotType = payLoad.type_vehicle_id
      state.selectedSlotId = payLoad.id
      state.selectedSlotName = payLoad.name
    },
    // DATASLOCKS(state, slotData) {
    //   state.dataSlots = {
    //     [slotData.slotId]: slotData
    //   }
    // }
  },
  actions:{
    async allSlots({commit}) {
      let parkingSlots = await axios.get('/slots')
        .then(resp => {
          return resp.data.data
        })

      commit('ALLSLOTS', parkingSlots)
    },
    selectSlot({commit}, selectS) {
      commit('SELECTEDSLOT', selectS)
    },
    resetSelected({commit}) {
      const reset = {
        id: null,
        name: null,
        type_vehicle_id: null
      }
      commit('SELECTEDSLOT', reset)
    },
    // dataSlocks({commit}, data ) {
    //   console.log(data);
    //   commit('DATASLOCKS', data)
    // }
  }
}

export default slots;
