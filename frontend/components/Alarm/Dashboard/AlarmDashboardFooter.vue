<template>
  <div
    style="width: 100%"
    v-if="can('dashboard_access') && can('dashboard_view')"
  >
    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col lg="3" md="3" sm="12" xs="12">
        <v-row style="width: 100%; height: 250px">
          <v-card class="py-2" style="width: 100%">
            <v-col lg="12" md="12" style="text-align: center; padding-top: 0px">
              <h3 style="text-align: left">Smoke Alarm</h3>

              <v-row style="font-size: 24px">
                <v-col cols="4">
                  <img
                    class="pa-10"
                    src="../../../static/icons/fire_logo.jpg"
                    style="width: 160px"
                  />
                </v-col>
                <v-col cols="8">
                  <div>Status</div>
                  <div class="green--text">Normal</div>
                  <br />
                  <div>Last Alarm</div>
                  <div class="green--text">Feb 23 2024 at 10:00PM</div>
                </v-col>
              </v-row>
            </v-col>
          </v-card>
        </v-row>
      </v-col>
      <v-col lg="3" md="3" sm="12" xs="12">
        <v-row style="width: 100%; height: 250px">
          <v-card class="py-2" style="width: 100%">
            <v-col lg="12" md="12" style="text-align: center; padding-top: 0px">
              <h3 style="text-align: left">Water Leakage Alarm</h3>

              <v-row style="font-size: 24px">
                <v-col cols="4">
                  <img
                    class="pa-10"
                    src="../../../static/icons/water_logo.jpg"
                    style="width: 160px"
                  />
                </v-col>
                <v-col cols="8">
                  <div>Status</div>
                  <div class="green--text">Normal</div>
                  <br />
                  <div>Last Alarm</div>
                  <div class="green--text">Feb 23 2024 at 10:00PM</div>
                </v-col>
              </v-row>
            </v-col>
          </v-card>
        </v-row>
      </v-col>
      <v-col lg="3" md="3" sm="12" xs="12">
        <v-row style="width: 100%; height: 250px">
          <v-card class="py-2" style="width: 100%">
            <v-col lg="12" md="12" style="text-align: center; padding-top: 0px">
              <h3 style="text-align: left">A/C Power Failure Alarm</h3>

              <v-row style="font-size: 24px">
                <v-col cols="4">
                  <img
                    class="pa-10"
                    src="../../../static/icons/power_logo.jpg"
                    style="width: 160px"
                  />
                </v-col>
                <v-col cols="8">
                  <div>Status</div>
                  <div class="green--text">Normal</div>
                  <br />
                  <div>Last Alarm</div>
                  <div class="green--text">Feb 23 2024 at 10:00PM</div>
                </v-col>
              </v-row>
            </v-col>
          </v-card>
        </v-row>
      </v-col>
      <v-col lg="3" md="3" sm="12" xs="12">
        <v-row style="width: 100%; height: 250px">
          <v-card class="py-2" style="width: 100%">
            <v-col lg="12" md="12" style="text-align: center; padding-top: 0px">
              <h3 style="text-align: left">Door Open Status</h3>

              <v-row style="font-size: 24px">
                <v-col cols="4">
                  <img
                    class="pa-10"
                    src="../../../static/icons/door_logo.jpg"
                    style="width: 160px"
                  />
                </v-col>
                <v-col cols="8">
                  <div>Status</div>
                  <div class="green--text">Normal</div>
                  <br />
                  <div>Last Open</div>
                  <div class="green--text">Feb 23 2024 at 10:00PM</div>
                </v-col>
              </v-row>
            </v-col>
          </v-card>
        </v-row>
      </v-col>
    </v-row>
  </div>

  <NoAccess v-else />
</template>

<script>
export default {
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
  },
};
</script>
