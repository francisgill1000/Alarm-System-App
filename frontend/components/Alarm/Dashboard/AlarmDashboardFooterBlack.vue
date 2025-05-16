<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-row>
      <v-col cols="3">
        <v-card
          :class="
            device.fire_alarm_status == 0
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
              v-if="device.fire_alarm_status"
              src="../../../static/blue-dashboard-icons/fire_alarm.png"
              style="width: 80px"
            /><img
              v-else
              src="../../../static/blue-dashboard-icons/fire.png"
              style="width: 80px"
            />
            <div>Smoke/Fire</div>

            <div :style="getPriorityColor(device.fire_alarm_status)">
              <div style="font-size: 12px">
                {{
                  device.fire_alarm_start_datetime == null
                    ? "---"
                    : $dateFormat.format4(device.fire_alarm_start_datetime)
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
              v-else
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
              v-else
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
              v-else
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
          :class="
            relayStatus && relayStatus.relay0
              ? 'dashboard-card-small-pink'
              : 'dashboard-card-small'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          style="border-radius: 20px; text-align: center"
          @click="relayCommand('relay0', relayStatus.relay0)"
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
          :class="
            relayStatus && relayStatus.relay1
              ? 'dashboard-card-small-pink'
              : 'dashboard-card-small'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          @click="relayCommand('relay1', relayStatus.relay1)"
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
          :class="
            relayStatus && relayStatus.relay2
              ? 'dashboard-card-small-pink'
              : 'dashboard-card-small'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          @click="relayCommand('relay2', relayStatus.relay2)"
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
          :class="
            relayStatus && relayStatus.relay3
              ? 'dashboard-card-small-pink'
              : 'dashboard-card-small'
          "
          height="180px"
          elevation="24"
          loading="false"
          outlined
          @click="relayCommand('relay3', relayStatus.relay3)"
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
      relayStatusRelay0: false,
      relayStatusRelay0: false,
      relayStatusRelay0: false,
      relayStatusRelay0: false,

      relayStatus: {},
      deviceSettings: null,
      branchList: [],
      selectedBranchName: "All Branches",
      seelctedBranchId: "",
      branch_id: "",
      overlay: false,
      intervalObj: null,
      loading: false,
    };
  },
  beforeDestroy() {
    // 1. Clear interval (with null check)
    if (this.intervalObj) {
      clearInterval(this.intervalObj);
      this.intervalObj = null; // Prevent memory leaks
    }
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

    this.intervalObj = setInterval(() => {
      this.getDeviceSettings();
    }, 1000 * 10);
  },
  async created() {
    this.getDeviceSettings();
  },
  watch: {
    relayStatus: {
      deep: true,
      handler(val) {
        console.log("relayStatus updated:", val);
      },
    },
    // overlay(val) {
    //   val &&
    //     setTimeout(() => {
    //       this.overlay = false;
    //     }, 3000);
    // },
  },
  methods: {
    getDeviceSettings() {
      if (this.loading) return;
      this.message = "loading....";

      let options = {
        params: {
          company_id: this.$auth.user.company.id,
          serial_number: this.device.serial_number,
        },
      };

      this.$axios
        .get(`get_device_settings_from_socket_arduino`, options)
        .then(({ data }) => {
          if (!data.error) {
            if (data.config) {
              this.$set(this.relayStatus, "relay0", data.config.relay0);
              this.$set(this.relayStatus, "relay1", data.config.relay1);
              this.$set(this.relayStatus, "relay2", data.config.relay2);
              this.$set(this.relayStatus, "relay3", data.config.relay3);

              // this.relayStatus.relay0 = data.config.relay0;
              // this.relayStatus.relay1 = data.config.relay1;
              // this.relayStatus.relay2 = data.config.relay2;
              // this.relayStatus.relay3 = data.config.relay3;
            }
          } else this.message = data.error;

          this.loading = false;
        });
    },
    relayCommand(cmd, status) {
      console.log(cmd, status);

      this.$set(this.relayStatus, cmd, !status);

      this.message = "loading....";

      let options = {
        params: {
          company_id: this.$auth.user.company.id,
          serial_number: this.device.serial_number,
          cmd: cmd,
          action: !status,
        },
      };

      // this.deviceSettings.deviceSettings;

      this.$axios
        .post(`command_call_device_to_arduino`, options.params)
        .then(({ data }) => {
          // if (cmd == "relay0") this.relayStatus.relay0 = status;
          // if (cmd == "relay1") this.relayStatus.relay1 = status;
          // if (cmd == "relay2") this.relayStatus.relay2 = status;
          // if (cmd == "relay3") this.relayStatus.relay3 = status;
          // if (!data.error) this.deviceSettings = data;
          // else this.message = data.error;
        });
    },
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
