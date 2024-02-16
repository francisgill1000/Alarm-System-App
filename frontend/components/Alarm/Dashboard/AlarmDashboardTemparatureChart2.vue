<template>
  <div style="width: 100%">
    <div :id="name" style="width: 100%"></div>
  </div>
</template>

<script>
// import VueApexCharts from 'vue-apexcharts'
export default {
  props: ["name", "height", "branch_id", "temperature_hourly_data"],
  data() {
    return {
      series: [
        {
          name: "Temparature",
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
          text: "Temparature Hourly Chart",
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
              text: "Temparature",
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
        this.$store.commit("dashboard/every_hour_count", null);
        this.getDataFromApi();
      } catch (e) {}
    },
  },

  created() {
    this.getDataFromApi();
  },
  mounted() {
    this.chartOptions.chart.height = this.height;
    this.chartOptions.series = this.series;
    // new ApexCharts(
    //   document.querySelector("#" + this.name),
    //   this.chartOptions
    // ).render();

    setInterval(() => {
      getDataFromApi();
    }, 1000 * 60);
  },

  methods: {
    viewLogs() {
      this.$router.push("/attendance_report");
    },
    getDataFromApi() {
      // const data = await this.$store.dispatch("dashboard/every_hour_count");
      this.$axios
        .get("alarm_dashboard_get_temparature_latest", {
          params: {
            company_id: this.$auth.user.company_id,
            branch_id: this.branch_id > 0 ? this.branch_id : null,
            device_serial_number: "105",
          },
        })
        .then(({ data }) => {
          this.data = data;
          this.loading = false;
          this.$store.commit("AlarmDashboard/alarm_temparature_latest", data);

          this.temperature_latest = data.temperature_latest;
          this.temperature_date_time = this.$dateFormat.format4(
            data.temperature_date_time
          );

          this.temperature_min = data.temperature_min;
          this.temperature_max = data.temperature_max;
          this.temperature_min_date_time = this.$dateFormat.format6(
            data.temperature_min_date_time
          );
          this.temperature_max_date_time = this.$dateFormat.format6(
            data.temperature_max_date_time
          );
          this.temperature_hourly_data = data.houry_data;
          this.key = this.key + 1;

          console.log(
            "this.temperature_hourly_data11111",
            this.temperature_hourly_data
          );

          this.renderChart(this.temperature_hourly_data);
        });
    },
    renderChart(data) {
      let counter = 0;
      console.log(" data", data);
      data.forEach((item) => {
        console.log(" (item.count)", item);
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
