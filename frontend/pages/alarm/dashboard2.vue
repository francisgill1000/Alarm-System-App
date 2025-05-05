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
          ><div>111111111</div>
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
          >2222222</v-card
        ></v-col
      >
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
          >2222222</v-card
        ></v-col
      >
    </v-row>
  </div>

  <NoAccess v-else />
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },
  watch: {
    from_date(val) {},
  },
  mounted() {},
  async created() {},

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
  },
};
</script>
