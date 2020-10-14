<template>
  <div class="col-3 d-flex align-items-center justify-content-between">
    <h5 class="text-right">{{ currentDate }}</h5> &nbsp; <h4>{{ currentTime }}</h4>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'DateTimeParking',
  data() {
    return {
      currentDate: moment().format('DD-MM-YYYY'),
      currentTime: null
    }
  },
  created () {
    this.currentTime = moment().format('LTS');
    setInterval(() => this.updateCurrentTime(), 1 * 1000)
  },
  methods: {
    updateCurrentTime() {
      this.currentTime = moment().format('LTS')
    }
  },
}
</script>

<style lang="scss" scoped>

</style>