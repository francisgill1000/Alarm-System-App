<template>
  <div style="width: 100%; height: 200px">
    <ComonPreloader icon="face-scan" v-if="loading" />
    <VueGauge
      v-if="!loading"
      :options="options"
      :refid="name"
      :id="name"
      style="height: 180px"
    />
    <!-- <VueGauge
      :key="key"
      :refid="'type-unique-id2'"
      :options="options"
    ></VueGauge> -->
    <!-- <div :id="name" style="width: 100%" :key="display_title"></div> -->
    <span style="font-size: 14px"
      >Updated at : {{ temperature_date_time }}</span
    >
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
      options: {
        needleValue: 5,
        centralLabel: "5",
        hasNeedle: true,
        arcDelimiters: [50, 75, 99],
        arcColors: ["#008450", "#EFB700", "#B81D13"],
        chartWidth: 400,
      },

      // series: [44, 55, 41, 17, 15],
      // chartOptions1: {
      //   title: {
      //     text: "Browser<br>shares<br>January<br>2022",
      //     align: "center",
      //     verticalAlign: "middle",
      //     y: 60,
      //   },
      //   series: [44, 55, 41, 17, 15],
      //   chart: {
      //     type: "donut",
      //   },
      //   plotOptions: {
      //     pie: {
      //       startAngle: -90,
      //       endAngle: 90,
      //       offsetY: 10,
      //       donut: {
      //         labels: {
      //           show: true,

      //           total: {
      //             showAlways: true,
      //             fontSize: "30px",
      //             fontFamily: "Helvetica, Arial, sans-serif",

      //             offsetY: 16,
      //             show: true,
      //             label: "Test",
      //             color: "red",
      //             formatter: function (w) {
      //               return "";
      //             },
      //           },
      //         },
      //       },
      //     },
      //   },
      //   grid: {
      //     padding: {
      //       bottom: -80,
      //     },
      //   },
      //   responsive: [
      //     {
      //       breakpoint: 480,
      //       options: {
      //         chart: {
      //           width: 200,
      //         },
      //         legend: {
      //           position: "bottom",
      //         },
      //       },
      //     },
      //   ],
      // },
      key: 50,
    };
  },
  watch: {
    // temperature_latest: {
    //   handler: "updateGauge",
    //   deep: true,
    // },
    // async display_title() {
    //   await this.getDataFromApi();
    // },
    // async branch_id(val) {
    //   this.$store.commit("CommDashboard/setDashboardData", null);
    //   //this.$store.commit("setDashboardData", null);
    //   await this.getDataFromApi();
    // },
  },
  mounted() {
    //this.getDataFromApi();
    setTimeout(() => {
      ///this.key = 50;
      //this.updateGauge();
      //// let el = this.$refs["type-unique-id2"].$el;
      // All not working...
      // this.GaugeChart.gaugeChart(el, 300, this.options).updateNeedle(this.theVal)
      // this.$refs[this.gaugeid].updateNeedle(this.theVal);
      // this.$refs[this.gaugeid].GaugeChart.gaugeChart(el, 300, this.options).updateNeedle(this.theVal)
      // this.GaugeChart.gaugeChart(el, 300, this.options).updateNeedle(this.theVal)
      // GaugeChart.GaugeChart(el, 300, this.options).updateNeedle(this.theVal)
      // this.$refs[this.gaugeid].gaugeChart(el, 300, this.options).updateNeedle(this.theVal)
      //this.gaugeChart(el, 300, this.options).updateNeedle(50);
    }, 5000);
    // this.chartOptions1.chart.height = 310;
    // this.chartOptions1.series = this.series;
    // new ApexCharts(
    //   document.querySelector("#" + this.name),
    //   this.chartOptions
    // ).render();

    // new VueGauge(
    //   document.querySelector("#" + this.name),
    //   this.optioins
    // ).render();
  },
  async created() {
    setTimeout(() => {
      this.options = {
        needleValue: this.temperature_latest,
        centralLabel: this.temperature_latest + "",
        hasNeedle: true,
        arcDelimiters: [50, 75, 99],
        arcColors: ["#008450", "#EFB700", "#B81D13"],
      };

      this.loading = false;
    }, 5000);

    // // Get today's date
    // let today = new Date();
    // // Subtract 7 days from today
    // let sevenDaysAgo = new Date(today);
    // sevenDaysAgo.setDate(today.getDate() - 0);
    // // Format the dates (optional)
    // this.date_to = today.toISOString().split("T")[0];
    // this.date_from = sevenDaysAgo.toISOString().split("T")[0];
    // // this.display_title =
    // //   "Attendance : " + this.date_from + " to " + this.date_to;
    // await this.getDataFromApi();
    // this.getDeviceList();
  },

  methods: {
    // updateGauge() {
    //   this.options = {
    //     needleValue: 20,
    //     centralLabel: "20",
    //     hasNeedle: true,
    //     arcDelimiters: [50, 75, 99],
    //     arcColors: ["#008450", "#EFB700", "#B81D13"],
    //   };
    // },
    //   getDeviceList() {
    //     let options = {
    //       params: {
    //         company_id: this.$auth.user.company_id,
    //       },
    //     };
    //     this.$axios.get(`/device_list`, options).then(({ data }) => {
    //       this.devices = data;
    //     });
    //   },
    //   filterDevice() {
    //     this.$store.commit("CommDashboard/setDashboardData", null);
    //     this.$store.commit("CommDashboard/every_hour_count", null);
    //     this.getDataFromApi();
    //   },
    //   filterAttr(data) {
    //     this.date_from = data.from;
    //     this.date_to = data.to;
    //     this.filterType = "Monthly"; // data.type;
    //     if (this.date_from != this.date_to)
    //       this.display_title =
    //         "Access  : " + this.date_from + " to " + this.date_to;
    //     else this.display_title = "Access  : " + this.date_from;
    //     this.$store.commit("CommDashboard/setDashboardData", null);
    //     this.$store.commit("CommDashboard/every_hour_count", null);
    //     this.getDataFromApi();
    //   },
    // getDataFromApi() {
    //   //this.loading = true;
    //   if (this.$store.state.alarm_temparature_latest) {
    //     this.data = this.$store.state.alarm_temparature_latest;
    //   } else {
    //     this.$axios
    //       .get("alarm_dashboard_get_temparature_latest", {
    //         params: {
    //           company_id: this.$auth.user.company_id,
    //           branch_id: this.branch_id > 0 ? this.branch_id : null,
    //           device_serial_number: "",
    //         },
    //       })
    //       .then(({ data }) => {
    //         this.data = data;
    //         this.$store.commit("AlarmDashboard/alarm_temparature_latest", data);
    //       });
    //   }
    // },
    //   renderChart1(data) {
    //     let counter = 0;
    //     data.forEach((item) => {
    //       // this.chartOptions1.series[0]["data"][counter] = 10; //parseInt(        item.TenantCount        );
    //       // this.chartOptions1.series[1]["data"][counter] = 10; //parseInt(         item.VisitorCount        );
    //       // this.chartOptions1.series[2]["data"][counter] = 10; // parseInt(         item.EmployeeCount        );
    //       // this.chartOptions1.series[3]["data"][counter] = 10; //parseInt(         item.DeniedCount        );
    //       // //this.chartOptions1.xaxis.categories[counter] = item.hour;
    //       counter++;
    //     });
    //     try {
    //       new ApexCharts(
    //         document.querySelector("#" + this.name),
    //         this.chartOptions1
    //       ).render();
    //       this.loading = false;
    //     } catch (error) {}
    //   },
    //   renderChart2(data) {
    //     try {
    //       this.chartOptions2.chart.height = this.height;
    //       this.chartOptions2.series = this.series;
    //       let counter = 0;
    //       this.chartOptions2.series = [
    //         {
    //           name: "Tenants",
    //           data: [],
    //         },
    //         {
    //           name: "Visitors",
    //           data: [],
    //         },
    //         {
    //           name: "Employees",
    //           data: [],
    //         },
    //         {
    //           name: "Denied",
    //           data: [],
    //         },
    //       ];
    //       this.chartOptions2.xaxis = {
    //         categories: [],
    //       };
    //       data.forEach((item) => {
    //         this.chartOptions2.series[0]["data"][counter] = parseInt(
    //           item.TenantCount
    //         );
    //         this.chartOptions2.series[1]["data"][counter] = parseInt(
    //           item.VisitorCount
    //         );
    //         this.chartOptions2.series[2]["data"][counter] = parseInt(
    //           item.EmployeeCount
    //         );
    //         this.chartOptions2.series[3]["data"][counter] = parseInt(
    //           item.DeniedCount
    //         );
    //         this.chartOptions2.xaxis.categories[counter] =
    //           this.$dateFormat.format2(item.date);
    //         counter++;
    //       });
    //       this.loading = false;
    //       new ApexCharts(
    //         document.querySelector("#" + this.name),
    //         this.chartOptions2
    //       ).render();
    //     } catch (error) {}
    //   },
  },
};
</script>
