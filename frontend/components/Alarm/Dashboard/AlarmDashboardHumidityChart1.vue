<template>
  <div style="width: 100%; height: 193px">
    <!-- <ComonPreloader
      icon="face-scan"
      v-if="loading"
      style="max-height: 180px"
      height="150px"
    /> -->
    <div v-if="loading" style="height: 180px; margin: auto; padding-top: 20%">
      Loading....
    </div>
    <VueGauge
      v-if="!loading"
      :options="VueGaugeoptions"
      :refid="name"
      :id="name"
      style="width: 200px; margin-top: 0px"
    />

    <div style="font-size: 12px; margin-top: -50px">
      Updated at : {{ humidity_date_time }}
    </div>
  </div>
</template>

<script>
// import VueGauge from "vue-gauge";
export default {
  props: [
    "name",
    "humidity_latest",
    "height",
    "branch_id",
    "humidity_date_time",
  ],
  // components: { VueGauge },
  data() {
    return {
      filterDeviceId: null,
      devices: [],
      loading: true,
      display_title: "Recent 7 days Attendance",
      date_from: "",
      date_to: "",
      VueGaugeoptions: {
        needleValue: 0,
        centralLabel: "0",
        hasNeedle: true,
        arcDelimiters: [60, 80, 99],
        arcColors: ["#0071bd", "#a9dcf4", "#cc86ec"],
      },

      key: 50,
    };
  },
  watch: {},
  mounted() {
    setTimeout(() => {
      this.VueGaugeoptions = {
        needleValue: this.humidity_latest,
        centralLabel: this.humidity_latest + "%",
        hasNeedle: true,
        arcDelimiters: [60, 80, 99],
        // arcLabels: [60, 80, 99],
        arcColors: ["#0071bd", "#a9dcf4", "#cc86ec"],
        chartWidth: 350,
        needleColor: "#0071bd",
        arcOverEffect: true,

        top: true,

        label: {
          show: true, // Display labels
        },
      };

      this.loading = false;
    }, 1000);
  },
  created() {},

  methods: {},
};
</script>
