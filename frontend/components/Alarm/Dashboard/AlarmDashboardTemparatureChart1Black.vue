<template>
  <div style="width: 100%; height: 190px">
    <!-- <ComonPreloader
      icon="face-scan"

      style="max-height: 180px"
      height="150px"
    /> -->
    <div v-if="loading" style="height: 250px; margin: auto; padding-top: 20%">
      Loading....
    </div>
    <VueGauge
      v-if="!loading"
      :options="VueGaugeoptions"
      :refid="name"
      :id="name"
      style="width: 250px; margin-top: 0px"
    />

    <v-row>
      <v-col class="text-center">
        <div style="font-size: 14px">
          <v-icon color="#FFF">mdi-clock-outline</v-icon> Updated at :
          {{ temperature_date_time }}
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// import VueGauge from "vue-gauge";
export default {
  props: [
    "name",
    "temperature_latest",
    "height",
    "branch_id",
    "temperature_date_time",
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
        // label: {
        //   show: true,
        //   color: "#FFFFFF", // White color for labels
        //   fontSize: "12px",
        //   fontFamily: "Arial, sans-serif",
        // },
        needleValue: 0,
        centralLabel: "0",
        hasNeedle: true,
        arcDelimiters: [23, 50, 99],

        //arcLabels: [23, 50, 99],
        arcColors: ["#008450", "#EFB700", "#B81D13"],
        color: "#FFF",
        centralLabelStyle: {
          color: "#FFFFFF", // White color for central label
          fontSize: "20px",
          fontFamily: "Arial, sans-serif",
        },
        needleColor: "#FFFFFF", // Optional: make needle white too
        label: {
          show: true,
          fontColor: "#FFFFFF", // alternative 1
          textColor: "#FFFFFF", // alternative 2
          style: { color: "#FFFFFF" }, // alternative 3
        },
        arcLabelColor: "#FFFFFF", // some libraries use this
        scaleLabelColor: "#FFFFFF", // or this
      },

      key: 1,
    };
  },
  watch: {},
  mounted() {
    setTimeout(() => {
      this.VueGaugeoptions = {
        needleValue: this.temperature_latest,
        centralLabel: this.temperature_latest + "°C",
        hasNeedle: true,
        arcDelimiters: [23, 50, 99],
        //arcLabels: [23, 50, 99],
        arcColors: ["#008450", "#EFB700", "#B81D13"],
        chartWidth: 350,
        label: {
          show: true, // Display labels
          color: "#FFF",
        },
      };

      this.loading = false;
    }, 1000);
  },
  created() {},

  methods: {},
};
</script>
