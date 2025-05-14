<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-row justify="end" style="display: none1" v-if="devicesList.length > 1">
      <v-col></v-col>
      <v-col style="max-width: 220px; padding: 0px">
        <!-- <span style="float: left; width: 200px"> -->
        <v-select
          style="z-index: 9999"
          @change="ChangeDevice(device_serial_number)"
          v-model="device_serial_number"
          :items="devicesList"
          dense
          small
          outlined
          hide-details
          class="ma-2"
          label="Room"
          item-value="serial_number"
          item-text="name"
        ></v-select>
        <!-- </span> -->
        <!-- <span style="float: left">
          <v-menu
            style="z-index: 9999"
            v-model="from_menu"
            :close-on-content-click="false"
            :nudge-left="50"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                style="
                  width: 230px;
                  float: right;
                  z-index: 9999;
                  height: 5px;
                  padding-top: 8px;
                "
                outlined
                v-model="from_date"
                v-bind="attrs"
                v-on="on"
                dense
                class="custom-text-box shadow-none"
                label="Date Filter"
              ></v-text-field>
            </template>
            <v-date-picker
              no-title
              scrollable
              v-model="from_date"
              @input="from_menu = false"
              @change="getDataFromApi()"
            ></v-date-picker>
          </v-menu>
        </span> -->
      </v-col>
    </v-row>
    <v-row style="margin-top: 0px">
      <v-col cols="6">
        <v-card
          v-if="!displayLiveData"
          height="400px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 10px"
        >
          <AlarmDashboardTemparatureHistoryChart2Black
            :nameChart="'AlarmDashboardTemparatureHistoryChart2Black'"
            :height="'300'"
            :device_serial_number="device_serial_number"
            :key="keyChart2"
            :from_date="from_date"
            :theme="'black'"
            @switchBacktoLiveData="switchBacktoLiveData()"
        /></v-card>
        <v-card
          v-if="displayLiveData"
          class="dashboard-card"
          height="400px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 10px"
          ><AlarmDashboardLiveWeather
            @switchBacktoHistoryData="switchBacktoHistoryData()"
        /></v-card>
      </v-col>
      <v-col cols="6">
        <v-card
          class="dashboard-card"
          height="400px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 10px"
          ><v-row>
            <v-col cols="5">
              <v-row>
                <v-col cols="12" class="text-center"
                  ><span class="pl-5"> Temperature</span></v-col
                >
                <!-- <v-col cols="4" class="pull-right"
                    ><v-icon @click="getDataFromApi(1)" style="float: right"
                      >mdi mdi-reload</v-icon
                    >
                  </v-col>-->
              </v-row>

              <v-row>
                <v-col
                  cols="2"
                  class="align-items-center justify-content-center pt-10"
                >
                  <!-- <img
                    src="../../static/alarm-icons/temperature.png"
                    width="70px"
                /> -->
                </v-col>
                <v-col cols="10" class="pa-0">
                  <ArrowArcChartTemperature
                    :name="'ArrowArcChartTemperature1'"
                    :height="'380'"
                    :temperature_latest="temperature_latest"
                    :temperature_date_time="temperature_date_time"
                    :key="key"
                  />
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="2" class="d-flex justify-center align-center">
              <v-divider inset vertical style="color: #cfcece"></v-divider>
            </v-col>

            <v-col cols="5">
              <v-row>
                <v-col cols="12" class="text-center"
                  ><span class="pl-5"> Humidity</span></v-col
                >
                <!--<v-col cols="4" class="pull-right"
                    ><v-icon @click="getDataFromApi(1)" style="float: right"
                      >mdi mdi-reload</v-icon
                    >
                  </v-col>-->
              </v-row>

              <v-row>
                <v-col
                  cols="2"
                  class="align-items-center justify-content-center pt-10"
                >
                  <!-- <img
                    src="../../static/alarm-icons/humidity.png"
                    width="70px"
                /> -->
                </v-col>
                <v-col cols="10" class="pa-0">
                  <ArrowArcChart2Humidity
                    :name="'ArrowArcChart2Humidity'"
                    :height="'380'"
                    :humidity_latest="humidity_latest"
                    :humidity_date_time="humidity_date_time"
                    :key="key"
                  />
                  <!-- <AlarmDashboardHumidityChart1Black
                    :name="'AlarmDashboardHumidityChart1Black'"
                    :height="'220'"
                    :humidity_latest="humidity_latest"
                    :humidity_date_time="humidity_date_time"
                    :key="key"
                  /> -->
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card></v-col
      >
    </v-row>
    <v-row>
      <v-col cols="6">
        <AlarmDashboardFooterBlack :device="device" :key="key" />
      </v-col>
      <v-col cols="6">
        <v-card
          class="dashboard-card"
          height="380px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 10px"
        >
          <AlarmDashboardTemparatureChart2Black
            :name="'AlarmDashboardTemparatureChart2'"
            :height="'300'"
            :device_serial_number="device_serial_number"
            :from_date="from_date"
          /> </v-card
      ></v-col>
    </v-row>
  </div>
  <!-- https://api.weatherapi.com/v1/current.json?key=6619ca39981a4e4a9c7153233250605&q=Dubai&aqi=no -->

  <NoAccess v-else />
</template>

<script>
import AlarmDashboardFooterBlack from "../../components/Alarm/Dashboard/AlarmDashboardFooterBlack.vue";
import AlarmDashboardHumidityChart1Black from "../../components/Alarm/Dashboard/AlarmDashboardHumidityChart1Black.vue";
import AlarmDashboardLiveWeather from "../../components/Alarm/Dashboard/AlarmDashboardLiveWeather.vue";
import ArrowArcChartTemperature from "../../components/Alarm/Dashboard/ArrowArcChartTemperature.vue";
import AlarmDashboardTemparatureChart2Black from "../../components/Alarm/Dashboard/AlarmDashboardTemparatureChart2Black.vue";
import AlarmDashboardTemparatureHistoryChart2Black from "../../components/Alarm/Dashboard/AlarmDashboardTemparatureHistoryChart2Black.vue";
import ArrowArcChart2 from "../../components/Alarm/Dashboard/ArrowArcChart2Sample.vue";
import ArrowArcChart2Humidity from "../../components/Alarm/Dashboard/ArrowArcChart2Humidity.vue";

export default {
  // layout: "black",
  components: {
    AlarmDashboardTemparatureChart2Black,
    ArrowArcChartTemperature,
    AlarmDashboardHumidityChart1Black,
    AlarmDashboardLiveWeather,
    AlarmDashboardTemparatureHistoryChart2Black,
    ArrowArcChart2,
    ArrowArcChart2Humidity,
  },
  data() {
    return {
      displayLiveData: false,
      temperature: "",
      weatherCondition: "",
      humidity: "",
      key: 1,
      temperature_latest: 25,
      temperature_date_time: "2025-05-06 10:25:10",
      humidity_latest: 50,
      humidity_date_time: "2025-05-06 10:25:10",
      device: null,
      from_date: "",
      from_menu: false,
      selectedDeviceIndex: 0,
      audioSrc: null,
      topMenu: 0,
      key: 1,
      keyChart2: 1,
      branchList: [],
      selectedBranchName: "All Branches",
      seelctedBranchId: "",
      branch_id: "",
      overlay: false,
      temperature_latest: 0,
      temperature_date_time: "---",
      temperature_min: 0,
      temperature_max: 0,
      temperature_min_date_time: 0,
      temperature_max_date_time: 0,
      temperature_hourly_data: {},
      fire_alarm_start_datetime: "---",
      device_serial_number: "",

      humidity_latest: 0,
      humidity_date_time: "---",
      humidity_min: 0,
      humidity_max: 0,
      humidity_min_date_time: 0,
      humidity_max_date_time: 0,
      device: {
        fire_alarm_status: 0,
        fire_alarm_start_datetime: 0,
        water_alarm_status: 0,
        water_alarm_start_datetime: 0,
        power_alarm_status: 0,
        power_alarm_start_datetime: 0,
        door_open_status: 0,
        door_open_start_datetime: 0,
        smoke_alarm_status: 0,
        smoke_alarm_start_datetime: 0,
      },
      devicesList: [],

      intervalObj: null,
      viewportHeight: 0,
    };
  },
  beforeDestroy() {
    console.log("Cleaning up resources...");

    // 1. Clear interval (with null check)
    if (this.intervalObj) {
      clearInterval(this.intervalObj);
      this.intervalObj = null; // Prevent memory leaks
    }
  },
  watch: {
    from_date(val) {},
  },
  mounted() {
    // if (window) {
    //   this.viewportHeight = window.innerHeight;
    //   window.addEventListener("resize", this.handleResize);
    // }
    // if (this.$auth.user.user_type == "employee") {
    //   this.$router.push(`/dashboard/employee`);
    // }

    if (this.$auth.user.branch_id == 0 && this.$auth.user.is_master == false) {
      alert("You do not have permission to access  ");
      //this.$router.push("/login");
      this.$axios.get(`/logout`).then(({ res }) => {
        this.$auth.logout();
        this.$router.push(`/login`);
      });

      this.$router.push(`/login`);
      return "";
    }
    this.loading = true;
    setTimeout(() => {}, 1000);
    ///this.getDataFromApi(1);

    this.intervalObj = setInterval(() => {
      {
        this.getDataFromApi(1);
        //this.key++;
      }
    }, 1000 * 15);
  },
  async created() {
    if (window) {
      const viewportHeight = window.innerHeight;
      console.log("Visible content height:", viewportHeight, "px");

      const contentHeight = document.documentElement.clientHeight;
      console.log("Content height (excludes scrollbar):", contentHeight, "px");
    }
    const today = new Date();

    this.from_date = today.toISOString().slice(0, 10);
    if (this.$auth.user.branch_id == 0 && this.$auth.user.is_master == false) {
      alert("You do not have permission to access this branch");
      //this.$router.push("/login");
      this.$axios.get(`/logout`).then(({ res }) => {
        this.$auth.logout();
        this.$router.push(`/login`);
      });

      this.$router.push(`/login`);
      return "";
    }

    try {
      await this.$store.dispatch("fetchDropDowns", {
        key: "deviceList",
        endpoint: "device-list",
        refresh: true,
      });
      this.devicesList = this.$store.state.deviceList;

      this.devicesList = this.devicesList.filter(
        (item) => item.serial_number != null
      );
      if (this.$store.state.deviceList && this.$store.state.deviceList[0]) {
        this.device_serial_number = this.$store.state.deviceList[0].device_id;
        //this.getDataFromApi();
      }

      // await this.$store.dispatch("fetchDropDowns", {
      //   key: "employeeList",
      //   endpoint: "employee-list",
      //   refresh: true,
      // });
      this.branchList = await this.$store.dispatch("fetchDropDowns", {
        key: "branchList",
        endpoint: "branch-list",
        refresh: true,
      });
    } catch (error) {
      console.error("Error fetching data:", error);
    }
    this.getDataFromApi(1);
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    switchBacktoLiveData() {
      this.displayLiveData = true;
    },
    switchBacktoHistoryData() {
      this.displayLiveData = false;
    },

    handleResize() {
      this.viewportHeight = window.innerHeight;
    },
    ChangeDevice(serial_number) {
      try {
        this.device_serial_number = serial_number;
        this.key++;
        this.keyChart2++;

        this.getDataFromApi(1);
        //console.log(this.device_serial_number, " this.device_serial_number");
      } catch (e) {}
    },
    getDataFromApi(reset = 0) {
      // if (reset == 1) {
      //   this.keyChart2++;
      // }
      try {
        if (
          this.device_serial_number == "" ||
          this.device_serial_number == null
        )
          return false;
        if (this.$store.state.alarm_temparature_latest && reset == 0) {
          this.data = this.$store.state.alarm_temparature_latest;
        } else {
          this.$axios
            .get("alarm_dashboard_get_temparature_latest", {
              params: {
                company_id: this.$auth.user.company_id,

                device_serial_number: this.device_serial_number,
              },
            })
            .then(({ data }) => {
              this.data = data;
              this.device = data.device;
              this.loading = false;
              this.$store.commit(
                "AlarmDashboard/alarm_temparature_latest",
                data
              );

              this.temperature_latest = data.temperature_latest;
              this.temperature_date_time = this.$dateFormat.format4(
                data.temperature_date_time
              );

              this.temperature_min = data.temperature_min + "&deg;C";
              this.temperature_max = data.temperature_max + "&deg;C";
              this.temperature_min_date_time = this.$dateFormat.format6(
                data.temperature_min_date_time
              );
              this.temperature_max_date_time = this.$dateFormat.format6(
                data.temperature_max_date_time
              );
              // this.temperature_hourly_data = data.houry_data;
              if (data.fire_alarm_start_datetime)
                this.fire_alarm_start_datetime = this.$dateFormat.format4(
                  data.fire_alarm_start_datetime
                );

              //humidity
              this.humidity_latest = data.humidity_latest;
              this.humidity_date_time = this.$dateFormat.format4(
                data.humidity_date_time
              );

              this.humidity_min = data.humidity_min + "%";
              this.humidity_max = data.humidity_max + "%";
              this.humidity_min_date_time = this.$dateFormat.format6(
                data.humidity_min_date_time
              );
              this.humidity_max_date_time = this.$dateFormat.format6(
                data.humidity_max_date_time
              );

              this.key = this.key + 1;
            });
        }
      } catch (e) {}
    },
  },
};
</script>
