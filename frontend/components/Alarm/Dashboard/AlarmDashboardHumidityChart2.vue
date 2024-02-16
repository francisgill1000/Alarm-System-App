<template>
  <div style="width: 100%">
    <div :id="name" style="width: 100%"></div>
  </div>
</template>

<script>
// import VueApexCharts from 'vue-apexcharts'
export default {
  props: ["name", "height", "branch_id", "device_serial_number"],
  data() {
    return {
      series: [
        {
          name: "Humidity",
          type: "column",
          data: [],
        },
        {
          name: "Line",
          type: "line",
          data: [],
        },
      ],
      chartOptions: {
        chart: {
          height: 350,
          type: "line",
          toolbar: {
            show: false,
          },
        },
        stroke: {
          width: [0, 4],
          curve: "smooth",
        },
        title: {
          text: "Humidity Hourly Chart",
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1],
        },
        labels: [],
        xaxis: {
          type: "string",
        },
        yaxis: [
          {
            title: {
              text: "Humidity",
            },
          },
          {
            opposite: true,
            title: {
              text: "Line",
            },
          },
        ],
      },
    };
  },
  watch: {
    branch_id() {
      try {
        this.$store.commit("AlarmDashboard/alarm_temparature_hourly", null);
        this.getDataFromApi();
      } catch (e) {}
    },
  },

  created() {},
  mounted() {
    this.chartOptions.chart.height = this.height;
    this.chartOptions.series = this.series;

    this.getDataFromApi();
  },

  methods: {
    viewLogs() {
      this.$router.push("/attendance_report");
    },
    getDataFromApi() {
      // const data = await this.$store.dispatch("dashboard/every_hour_count");
      this.$axios
        .get("alarm_dashboard_get_humidity_hourly_data", {
          params: {
            company_id: this.$auth.user.company_id,
            branch_id: this.branch_id > 0 ? this.branch_id : null,
            device_serial_number: this.device_serial_number,
          },
        })
        .then(({ data }) => {
          this.data = data;
          this.loading = false;
          this.$store.commit("AlarmDashboard/alarm_humidity_hourly", data);

          this.temperature_hourly_data = data.houry_data;
          this.key = this.key + 1;

          this.renderChart(this.temperature_hourly_data);
        });
    },
    renderChart(data) {
      let counter = 0;

      data.forEach((item) => {
        this.chartOptions.series[0]["data"][counter] = item.count; //parseInt(item.count);

        this.chartOptions.series[1]["data"][counter] = item.count;

        this.chartOptions.labels[counter] = item.hour;
        counter++;
      });
      try {
        new ApexCharts(
          document.querySelector("#" + this.name),
          this.chartOptions
        ).render();
      } catch (error) {}
    },
  },
};
</script>

<style>
.apexcharts-canvas {
  width: 100%;
}
</style>
