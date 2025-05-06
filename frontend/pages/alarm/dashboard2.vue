<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-row>
      <v-col cols="6">
        <v-card
          height="400px"
          elevation="24"
          loading="false"
          outlined
          class="dashboard-card"
          style="border-radius: 20px"
        >
          <div>Hello , Customer Name</div>
          <br />
          <div style="font-weight: 400; color: lightblue">
            Welcome To XtremeGuard. AI Alarm sensor Monitoring System with
            Instant Alert messagesing and calling controller.
          </div>
          <div class="text-center" style="margin-top: 50px; height: 400px">
            <v-row>
              <v-col cols="3">
                <img
                  src="../../static/dashboardicons/cloud1.png"
                  width="100px"
                />
              </v-col>
              <v-col cols="3">
                <img
                  src="../../static/dashboardicons/humidity.png"
                  width="100px"
                />
              </v-col>
              <v-col cols="3" color="#FFF">
                <img
                  src="../../static/dashboardicons/temperature.png"
                  width="100px"
                />
              </v-col>
            </v-row>
          </div>
        </v-card>
      </v-col>
      <v-col cols="6">
        <v-card
          class="dashboard-card"
          height="400px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px"
          ><v-row>
            <v-col cols="6">
              <v-row>
                <v-col cols="12" class="text-center"
                  ><span class="pl-5" style="font-size: 16px">
                    Temperature</span
                  ></v-col
                >
                <!-- <v-col cols="4" class="pull-right"
                    ><v-icon @click="getDataFromApi(1)" style="float: right"
                      >mdi mdi-reload</v-icon
                    >
                  </v-col> -->
              </v-row>

              <v-row>
                <v-col
                  cols="2"
                  class="align-items-center justify-content-center pt-10"
                  ><img
                    src="../../static/alarm-icons/temperature.png"
                    width="70px"
                /></v-col>
                <v-col cols="10" class="pa-0">
                  <AlarmDashboardTemparatureChart1Black
                    :name="'AlarmDashboardTemparatureChart1Black1'"
                    :height="'220'"
                    :temperature_latest="temperature_latest"
                    :temperature_date_time="temperature_date_time"
                    :key="key"
                  />
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="6">
              <v-row>
                <v-col cols="12" class="text-center"
                  ><span class="pl-5" style="font-size: 16px">
                    Humidity</span
                  ></v-col
                >
                <!-- <v-col cols="4" class="pull-right"
                    ><v-icon @click="getDataFromApi(1)" style="float: right"
                      >mdi mdi-reload</v-icon
                    >
                  </v-col> -->
              </v-row>

              <v-row>
                <v-col
                  cols="2"
                  class="align-items-center justify-content-center pt-10"
                  ><img
                    src="../../static/alarm-icons/humidity.png"
                    width="70px"
                /></v-col>
                <v-col cols="10" class="pa-0">
                  <AlarmDashboardHumidityChart1Black
                    :name="'AlarmDashboardHumidityChart1Black'"
                    :height="'220'"
                    :humidity_latest="humidity_latest"
                    :humidity_date_time="humidity_date_time"
                    :key="key"
                  />
                </v-col>
              </v-row>
            </v-col>
          </v-row> </v-card
      ></v-col>
    </v-row>
    <v-row>
      <v-col cols="6">
        <v-row>
          <v-col cols="3">
            <v-card
              class="dashboard-card-small-pink"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card> </v-col
          ><v-col cols="3">
            <v-card
              class="dashboard-card-small"
              height="180px"
              elevation="24"
              loading="false"
              outlined
              style="border-radius: 20px"
              ><div>33333333</div>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="6">
        <v-card
          class="dashboard-card"
          height="400px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px"
        >
          <AlarmDashboardTemparatureChart2Black
            :name="'AlarmDashboardTemparatureChart2'"
            :height="'310'"
            :device_serial_number="'24000001'"
            :from_date="'2025-05-06'"
            :theme="'black'"
          /> </v-card
      ></v-col>
    </v-row>
  </div>
  <!-- https://api.weatherapi.com/v1/current.json?key=6619ca39981a4e4a9c7153233250605&q=Dubai&aqi=no -->

  <NoAccess v-else />
</template>

<script>
import AlarmDashboardHumidityChart1Black from "../../components/Alarm/Dashboard/AlarmDashboardHumidityChart1Black.vue";
import AlarmDashboardTemparatureChart1Black from "../../components/Alarm/Dashboard/AlarmDashboardTemparatureChart1Black.vue";
import AlarmDashboardTemparatureChart2Black from "../../components/Alarm/Dashboard/AlarmDashboardTemparatureChart2Black.vue";

export default {
  components: {
    AlarmDashboardTemparatureChart2Black,
    AlarmDashboardTemparatureChart1Black,
    AlarmDashboardHumidityChart1Black,
  },
  data() {
    return {
      temperature: "",
      weatherCondition: "",
      humidity: "",
      key: 1,
      temperature_latest: 25,
      temperature_date_time: "2025-05-06 10:25:10",
      humidity_latest: 50,
      humidity_date_time: "2025-05-06 10:25:10",

      series: [
        {
          name: "Series 1",
          data: [44, 55, 41, 64, 22],
        },
        {
          name: "Series 2",
          data: [53, 32, 33, 52, 13],
        },
      ],
      chartOptions: {
        chart: {
          type: "bar",
          height: 380,
          background: "#000000", // This sets the chart background to black
          foreColor: "#ffffff", // This sets the default text color to white for better visibility
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "55%",
            endingShape: "rounded",
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"],
        },
        xaxis: {
          categories: ["Feb", "Mar", "Apr", "May", "Jun"],
          labels: {
            style: {
              colors: "#ffffff", // White text for x-axis labels
            },
          },
        },
        yaxis: {
          labels: {
            style: {
              colors: "#ffffff", // White text for y-axis labels
            },
          },
        },
        fill: {
          opacity: 1,
        },
        tooltip: {
          theme: "dark", // This makes the tooltip dark to match the theme
        },
        grid: {
          borderColor: "#333333", // Darker grid lines for better visibility on black
        },
        legend: {
          labels: {
            colors: "#ffffff", // White text for legend
          },
        },
      },
    };
  },
  watch: {
    from_date(val) {},
  },
  mounted() {},
  async created() {
    this.getWeatherReport();
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    getWeatherReport() {
      this.$axios
        .get("https://api.weatherapi.com/v1/current.json", {
          params: {
            key: "6619ca39981a4e4a9c7153233250605", // Your API key
            q: "Dubai",
            aqi: "no",
          },
        })
        .then((response) => {
          const weatherData = response.data;
          console.log(
            "Current Temperature:",
            weatherData.current.temp_c + "°C"
          );
          console.log("Condition:", weatherData.current.condition.text);
          console.log("Humidity:", weatherData.current.humidity + "%");

          // You can use this data in your component
          this.temperature = weatherData.current.temp_c;
          this.weatherCondition = weatherData.current.condition.text;
          this.humidity = weatherData.current.humidity;
        })
        .catch((error) => {
          console.error("Error fetching weather data:", error);
          // Handle errors (e.g., show error message to user)
        });
    },
  },
};
</script>
<style scoped>
@import "@../../assets/blacktheme.css";
</style>
