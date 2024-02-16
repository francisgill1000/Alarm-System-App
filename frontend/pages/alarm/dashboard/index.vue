<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col cols="12" class="pt-5">
        <v-row style="width: 100%">
          <v-col lg="3" md="3" sm="12" xs="12">
            <v-row style="width: 100%; height: 250px">
              <v-card class="py-2" style="width: 100%">
                <v-row>
                  <v-col cols="8"><h3 class="pl-5">Temperature</h3></v-col>
                  <v-col cols="4" class="pull-right"
                    ><v-icon @click="getDataFromApi()" style="float: right"
                      >mdi mdi-reload</v-icon
                    >
                  </v-col>
                </v-row>
                <v-col
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <AlarmDashboardTemparatureChart1
                    :branch_id="branch_id"
                    :name="'AlarmDashboardTemparatureChart1'"
                    :height="'200'"
                    :temperature_latest="temperature_latest"
                    :temperature_date_time="temperature_date_time"
                    :key="key"
                  />
                </v-col>
              </v-card>
            </v-row>
          </v-col>
          <v-col lg="2" md="2" sm="12" xs="12">
            <v-row style="width: 100%; height: 260px">
              <v-card class="py-2" style="width: 100%">
                <div
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <div style="text-align: center; font-size: 20px">Min</div>
                  <div class="bold text-h4 green--text">
                    {{ temperature_min }}
                  </div>
                  <span style="font-size: 10px">
                    At : {{ temperature_min_date_time }}
                  </span>
                </div>

                <div
                  class="pt-5"
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <div style="text-align: center; color: red; font-size: 20px">
                    Max
                  </div>
                  <div class="bold text-h4 red--text">
                    {{ temperature_max }}
                  </div>
                  <span style="font-size: 10px">
                    At : {{ temperature_max_date_time }}</span
                  >
                </div>
                <div style="font-size: 10px; text-align: center">
                  Last Fire Alarm: {{ fire_alarm_start_datetime }}
                </div>
              </v-card>
            </v-row>
          </v-col>
          <v-col lg="7" md="7" sm="12" xs="12">
            <v-row style="width: 100%; height: 250px">
              <v-card class="py-2" style="width: 100%">
                <v-col
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <AlarmDashboardTemparatureChart2
                    :branch_id="branch_id"
                    :name="'AlarmDashboardTemparatureChart2'"
                    :height="'200'"
                    :temperature_hourly_data="temperature_hourly_data"
                  />
                </v-col>
              </v-card> </v-row
          ></v-col>
        </v-row>
      </v-col>
      <!-- <v-col cols="12" class="pt-10">
        <v-row style="width: 100%">
          <v-col lg="3" md="3" sm="12" xs="12">
            <v-row style="width: 100%; height: 250px">
              <v-card class="py-2" style="width: 100%">
                <h3 class="pl-5">Humidity</h3>
                <v-col
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <AlarmDashboardTemparatureChart1
                    :branch_id="branch_id"
                    :name="'Humidity1'"
                    :height="'200'"
                  />
                </v-col>
              </v-card>
            </v-row>
          </v-col>
          <v-col lg="2" md="2" sm="12" xs="12">
            <v-row style="width: 100%; height: 250px">
              <v-card class="py-2" style="width: 100%">
                <v-col
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <div style="text-align: center; font-size: 30px">Min</div>
                  <div class="bold text-h3 green--text">10c</div>
                </v-col>

                <v-col
                  class="pt-5"
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <div style="text-align: center; color: red; font-size: 30px">
                    Max
                  </div>
                  <div class="bold text-h3 red--text">10c</div>
                </v-col>
              </v-card>
            </v-row>
          </v-col>
          <v-col lg="7" md="7" sm="12" xs="12">
            <v-row style="width: 100%; height: 250px">
              <v-card class="py-2" style="width: 100%">
                <v-col
                  lg="12"
                  md="12"
                  style="text-align: center; padding-top: 0px"
                >
                  <AlarmDashboardTemparatureChart2
                    :branch_id="branch_id"
                    :name="'Humidity2'"
                    :height="'200'"
                  />
                </v-col>
              </v-card> </v-row
          ></v-col>
        </v-row>
      </v-col>
      <v-col clas="12" class="pt-10">
        <AlarmDashboardFooter />
      </v-col> -->
    </v-row>
  </div>

  <NoAccess v-else />
</template>

<script>
import AlarmDashboardTemparatureChart1 from "../../../components/Alarm/Dashboard/AlarmDashboardTemparatureChart1.vue";
import AlarmDashboardTemparatureChart2 from "../../../components/Alarm/Dashboard/AlarmDashboardTemparatureChart2.vue";
// import AlarmDashboardFooter from "../../../components/Alarm/Dashboard/AlarmDashboardFooter.vue";

// import DashboardlastMultiStatistics from "../../components/dashboard2/DashboardlastMultiStatistics.vue";
export default {
  components: {
    AlarmDashboardTemparatureChart1,
    AlarmDashboardTemparatureChart2,
    // AlarmDashboardFooter,
  },
  data() {
    return {
      key: 1,
      branchList: [],
      selectedBranchName: "All Branches",
      seelctedBranchId: "",
      branch_id: "",
      overlay: false,
      temperature_latest: 0,
      temperature_date_time: 0,
      temperature_min: 0,
      temperature_max: 0,
      temperature_min_date_time: 0,
      temperature_max_date_time: 0,
      temperature_hourly_data: {},
      fire_alarm_start_datetime: null,
    };
  },
  // watch: {
  //   branch_id(branch_id) {
  //     return branch_id > 0 ? branch_id : null;
  //   },
  // },
  mounted() {
    // if (this.$auth.user.user_type == "employee") {
    //   this.$router.push(`/dashboard/employee`);
    // }

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
    this.loading = true;
    this.getDataFromApi();

    setInterval(() => {
      this.loading = true;
      getDataFromApi();
    }, 1000 * 60);
  },
  async created() {
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
      await this.$store.dispatch("fetchDropDowns", {
        key: "employeeList",
        endpoint: "employee-list",
        refresh: true,
      });
      this.branchList = await this.$store.dispatch("fetchDropDowns", {
        key: "branchList",
        endpoint: "branch-list",
        refresh: true,
      });
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  },
  watch: {
    overlay(val) {
      val &&
        setTimeout(() => {
          this.overlay = false;
        }, 3000);
    },
  },
  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    openalert(data) {
      alert("");
    },
    filterBranch(branch) {
      this.$emit("openalert", "");

      // if (branch) {
      //   this.selectedBranchName = branch.branch_name;
      //   this.seelctedBranchId = branch.id;
      //   this.branch_id = branch.id;
      // } else {
      //   this.selectedBranchName = "All Branches";
      //   this.seelctedBranchId = "";
      //   this.branch_id = "";
      // }
    },
    getDataFromApi() {
      if (this.$store.state.alarm_temparature_latest) {
        this.data = this.$store.state.alarm_temparature_latest;
      } else {
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
            if (this.fire_alarm_start_datetime)
              this.fire_alarm_start_datetime = this.$dateFormat.format4(
                data.fire_alarm_start_datetime
              );
            this.key = this.key + 1;
          });
      }
    },
  },
};
</script>
