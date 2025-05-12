<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-row>
      <v-col cols="3">
        <v-card
          :class="
            device.smoke_alarm_status == 0
              ? 'dashboard-card-small'
              : 'dashboard-card-small-pink'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              v-if="device.smoke_alarm_status"
              src="../../../static/blue-dashboard-icons/fire_alarm.png"
              style="width: 80px"
            /><img
              src="../../../static/blue-dashboard-icons/fire.png"
              style="width: 80px"
            />
            <div>Smoke/Fire</div>

            <div :style="getPriorityColor(device.smoke_alarm_status)">
              <div style="font-size: 12px">
                {{
                  device.smoke_alarm_start_datetime == null
                    ? "---"
                    : $dateFormat.format4(device.smoke_alarm_start_datetime)
                }}
              </div>
            </div>
          </div>
        </v-card> </v-col
      ><v-col cols="3">
        <v-card
          :class="
            device.water_alarm_status == 0
              ? 'dashboard-card-small'
              : 'dashboard-card-small-pink'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <!-- <img
              src="../../../static/alarm-icons/water-leakage.png"
              style="width: 80px"
            /> -->
            <img
              v-if="device.water_alarm_status"
              src="../../../static/blue-dashboard-icons/water_alarm.png"
              style="width: 80px"
            /><img
              src="../../../static/blue-dashboard-icons/water.png"
              style="width: 80px"
            />
            <div>Water Leakage</div>
            <div style="font-size: 12px">
              <div :style="getPriorityColor(device.water_alarm_status)">
                {{
                  device.water_alarm_start_datetime == null
                    ? "---"
                    : $dateFormat.format4(device.water_alarm_start_datetime)
                }}
              </div>
            </div>
          </div>
        </v-card> </v-col
      ><v-col cols="3">
        <v-card
          :class="
            device.power_alarm_status == 0
              ? 'dashboard-card-small'
              : 'dashboard-card-small-pink'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              v-if="device.power_alarm_status"
              src="../../../static/blue-dashboard-icons/power_alarm.png"
              style="width: 80px"
            /><img
              src="../../../static/blue-dashboard-icons/power.png"
              style="width: 80px"
            />
            <div>A/C Power</div>
            <div style="font-size: 12px">
              <div :style="getPriorityColor(device.power_alarm_status)">
                {{
                  device.power_alarm_start_datetime == null
                    ? "---"
                    : $dateFormat.format4(device.power_alarm_start_datetime)
                }}
              </div>
            </div>
          </div>
        </v-card> </v-col
      ><v-col cols="3">
        <v-card
          :class="
            device.door_open_status == 0
              ? 'dashboard-card-small'
              : 'dashboard-card-small-pink'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              v-if="device.door_open_status"
              src="../../../static/blue-dashboard-icons/door_alarm.png"
              style="width: 80px"
            /><img
              src="../../../static/blue-dashboard-icons/door.png"
              style="width: 80px"
            />
            <div>Door Open</div>
            <div style="font-size: 12px">
              <div :style="getPriorityColor(device.door_open_status)">
                {{
                  device.door_open_start_datetime == null
                    ? "---"
                    : $dateFormat.format4(device.door_open_start_datetime)
                }}
              </div>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="3">
        <v-card
          class="dashboard-card-small"
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              src="../../../static/blue-dashboard-icons/ac.png"
              style="width: 80px"
            />
            <div>A/C Off</div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="3">
        <v-card
          class="dashboard-card-small"
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              src="../../../static/blue-dashboard-icons/fan.png"
              style="width: 80px"
            />
            <div>Fan Off</div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="3">
        <v-card
          class="dashboard-card-small"
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              src="../../../static/blue-dashboard-icons/siren.png"
              style="width: 80px"
            />
            <div>Siren Off</div>
          </div>
        </v-card> </v-col
      ><v-col cols="3">
        <v-card
          class="dashboard-card-small"
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
        >
          <div style="margin: auto">
            <img
              src="../../../static/blue-dashboard-icons/lamp.png"
              style="width: 80px"
            />
            <div>Lamp Off</div>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </div>

  <NoAccess v-else />
</template>

<script>
export default {
  props: ["device"],
  data() {
    return {
      branchList: [],
      selectedBranchName: "All Branches",
      seelctedBranchId: "",
      branch_id: "",
      overlay: false,
    };
  },
  // watch: {
  //   branch_id(branch_id) {
  //     return branch_id > 0 ? branch_id : null;
  //   },
  // },
  mounted() {
    // // if (this.$auth.user.user_type == "employee") {
    // //   this.$router.push(`/dashboard/employee`);
    // // }
    // if (this.$auth.user.branch_id == 0 && this.$auth.user.is_master == false) {
    //   alert("You do not have permission to access this branch");
    //   //this.$router.push("/login");
    //   this.$axios.get(`/logout`).then(({ res }) => {
    //     this.$auth.logout();
    //     this.$router.push(`/login`);
    //   });
    //   this.$router.push(`/login`);
    //   return "";
    // }
  },
  async created() {
    // if (this.$auth.user.branch_id == 0 && this.$auth.user.is_master == false) {
    //   alert("You do not have permission to access this branch");
    //   //this.$router.push("/login");
    //   this.$axios.get(`/logout`).then(({ res }) => {
    //     this.$auth.logout();
    //     this.$router.push(`/login`);
    //   });
    //   this.$router.push(`/login`);
    //   return "";
    // }
    // try {
    //   await this.$store.dispatch("fetchDropDowns", {
    //     key: "deviceList",
    //     endpoint: "device-list",
    //     refresh: true,
    //   });
    //   await this.$store.dispatch("fetchDropDowns", {
    //     key: "employeeList",
    //     endpoint: "employee-list",
    //     refresh: true,
    //   });
    //   this.branchList = await this.$store.dispatch("fetchDropDowns", {
    //     key: "branchList",
    //     endpoint: "branch-list",
    //     refresh: true,
    //   });
    // } catch (error) {
    //   console.error("Error fetching data:", error);
    // }
  },
  watch: {
    // overlay(val) {
    //   val &&
    //     setTimeout(() => {
    //       this.overlay = false;
    //     }, 3000);
    // },
  },
  methods: {
    getPriorityColor(status) {
      return "";
      if (status == 1) {
        return "color:red;font-weight:500";
      } else {
        return " font-weight:500";
      }
    },

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
  },
};
</script>
