<template>
  <div v-if="can('devices_permissions_access') && can('devices_view')">
    <div class="text-center ma-2">
      <v-snackbar
        v-model="snackbar"
        top="top"
        :color="snackbarColor"
        elevation="24"
      >
        {{ snackbarResponse }}
      </v-snackbar>
    </div>

    <v-icon
      title="Reload Device Data from SDK"
      color="black"
      class="pull-right"
      style="float: right"
      @click="getDataFromApi()"
      dark
      >mdi-sync</v-icon
    ><br />

    <v-row v-if="deviceSettings.config == null">
      <v-col style="color: red">{{ message }}</v-col>
    </v-row>

    <v-row v-else>
      <v-col cols="12">
        <v-tabs v-model="tab">
          <v-tab> Device Info </v-tab>
          <v-tab> Netwrok Settings </v-tab>
          <v-tab>Alarm Settings</v-tab>
          <v-tab>Phone Numbers</v-tab>

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <v-card color="basil">
                <v-card-text>
                  <v-row>
                    <!-- <v-col cols="12"> <h3>Network Settings</h3></v-col> -->

                    <v-col cols="6">
                      <v-row>
                        <v-col cols="12">
                          <v-select
                            disabled
                            v-model="deviceSettings.config.wifi_or_ethernet"
                            outlined
                            dense
                            small
                            :hide-details="true"
                            label="Connection Type (Wifi/Ethernet)"
                            :items="[
                              { text: 'Wifi', value: '1' },
                              { text: 'Ethernet', value: '0' },
                            ]"
                            item-value="value"
                            item-text="text"
                          ></v-select>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.wifi_ssid"
                            outlined
                            dense
                            small
                            :hide-details="true"
                            label="Wifi SSID Name"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.wifi_password"
                            outlined
                            dense
                            small
                            :hide-details="true"
                            label="Wifi Password"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.wifi_ip"
                            outlined
                            dense
                            small
                            :hide-details="true"
                            label="Wifi IP Address"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-col>

                    <v-col cols="6">
                      <v-row>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.eth_ip"
                            outlined
                            dense
                            small
                            number
                            :hide-details="true"
                            label="Eithernet IP Address"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.eth_gateway"
                            outlined
                            dense
                            small
                            number
                            :hide-details="true"
                            label="Ethernet Gateway"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.eth_subnet"
                            outlined
                            dense
                            small
                            number
                            :hide-details="true"
                            label="EtherNet Subnet"
                          ></v-text-field>
                        </v-col>
                        <v-col>
                          <v-text-field
                            disabled
                            v-model="deviceSettings.config.device_serial_number"
                            outlined
                            dense
                            small
                            number
                            :hide-details="true"
                            label="Device Serial Number"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card color="basil">
                <v-card-text>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="deviceSettings.config.server_ip"
                        outlined
                        dense
                        small
                        number
                        :hide-details="true"
                        label="Server/Socket IP"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="deviceSettings.config.server_port"
                        outlined
                        dense
                        small
                        number
                        :hide-details="true"
                        label="Server/Socket Port"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12">
                      <v-text-field
                        v-model="deviceSettings.config.server_url"
                        outlined
                        dense
                        small
                        number
                        :hide-details="true"
                        label="Server event URL"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <v-tab-item>
              <v-row>
                <v-col cols="12">
                  <v-card elevation="2" outlined style="height: 280px">
                    <v-card-text>
                      <v-row>
                        <v-col cols="6">
                          <v-row>
                            <v-col cols="12">
                              <v-select
                                v-model="deviceSettings.config.heartbeat"
                                outlined
                                dense
                                small
                                :hide-details="true"
                                label="Device Heartbeat"
                                :items="heartBeatData"
                                item-value="value"
                                item-text="label"
                              ></v-select>
                            </v-col>

                            <v-col cols="12">
                              <v-checkbox
                                :hide-details="true"
                                v-model="deviceSettings.config.siren_checkbox"
                                :label="`Siren Sound `"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="12">
                              <v-select
                                :disabled="
                                  !deviceSettings.config.siren_checkbox
                                "
                                v-model="deviceSettings.config.max_siren_play"
                                outlined
                                dense
                                small
                                :hide-details="true"
                                label="Siren Play Duration"
                                :items="heartBeatData"
                                item-value="value"
                                item-text="label"
                              ></v-select> </v-col
                            ><v-col cols="12">
                              <v-select
                                :disabled="
                                  !deviceSettings.config.siren_checkbox
                                "
                                v-model="deviceSettings.config.max_siren_pause"
                                outlined
                                dense
                                small
                                :hide-details="true"
                                label="Siren Pause Duration"
                                :items="heartBeatData"
                                item-value="value"
                                item-text="label"
                              ></v-select>
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-col>

                <v-col cols="12">
                  <v-row>
                    <v-col cols="12">
                      <v-card elevation="2" outlined style="height: 100px">
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="powerAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.power_checkbox"
                            label="Power Alarm"
                          ></v-checkbox>

                          <v-icon color="red"
                            >mdi-lightning-bolt-outline</v-icon
                          >

                          <v-spacer> </v-spacer>
                        </v-card-title>
                        <v-card-text>
                          <v-row>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.power_checkbox
                                "
                                :hide-details="true"
                                v-model="deviceSettings.config.power_alert_sms"
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.power_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="deviceSettings.config.power_alert_call"
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.power_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.power_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                    <v-col cols="12">
                      <v-card elevation="2" outlined style="height: 100px">
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="waterAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.water_checkbox"
                            label="Water Alarm"
                          ></v-checkbox>

                          <v-icon color="blue">mdi-water</v-icon>

                          <v-spacer> </v-spacer>
                        </v-card-title>
                        <v-card-text>
                          <v-row>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.water_checkbox
                                "
                                :hide-details="true"
                                v-model="deviceSettings.config.water_alert_sms"
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.water_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="deviceSettings.config.water_alert_call"
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.water_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.water_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                    <v-col cols="12">
                      <v-card elevation="2" outlined style="height: 100px">
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="fireAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.fire_checkbox"
                            label="Fire Alarm"
                          ></v-checkbox>

                          <v-icon style="margin-left: 10px" color="red"
                            >mdi-fire</v-icon
                          >

                          <v-spacer> </v-spacer>
                        </v-card-title>
                        <v-card-text>
                          <v-row>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.fire_checkbox"
                                :hide-details="true"
                                v-model="deviceSettings.config.fire_alert_sms"
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.fire_checkbox"
                                cols="4"
                                :hide-details="true"
                                v-model="deviceSettings.config.fire_alert_call"
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.fire_checkbox"
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.fire_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>

                    <v-col cols="12">
                      <v-card elevation="2" style="height: 200px" outlined>
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="tempAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.temp_checkbox"
                            label="Temperature Alarm"
                          ></v-checkbox>

                          <v-icon style="margin-left: 10px" color="red"
                            >mdi-thermometer-plus</v-icon
                          >
                        </v-card-title>

                        <v-card-text>
                          <br />
                          <v-row>
                            <v-col cols="6">
                              <v-text-field
                                :disabled="!deviceSettings.config.temp_checkbox"
                                v-model="deviceSettings.config.max_temperature"
                                outlined
                                dense
                                small
                                type="number"
                                :hide-details="true"
                                label="Max Temperature Threshold"
                                step="0.01"
                              ></v-text-field> </v-col
                            ><v-col cols="6">
                              <v-text-field
                                :disabled="!deviceSettings.config.temp_checkbox"
                                v-model="deviceSettings.config.min_temperature"
                                outlined
                                dense
                                small
                                type="number"
                                :hide-details="true"
                                step="0.01"
                                label="Min Temperature Threshold"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.temp_checkbox"
                                :hide-details="true"
                                v-model="deviceSettings.config.temp_alert_sms"
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.temp_checkbox"
                                cols="4"
                                :hide-details="true"
                                v-model="deviceSettings.config.temp_alert_call"
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="!deviceSettings.config.temp_checkbox"
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.temp_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                    <v-col cols="12">
                      <v-card elevation="2" style="height: 200px" outlined>
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="humidityAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.humidity_checkbox"
                            label="Humidity Alarm"
                          ></v-checkbox>

                          <v-icon style="margin-left: 10px" color="blue"
                            >mdi-thermometer-water</v-icon
                          >
                        </v-card-title>

                        <v-card-text>
                          <br />
                          <v-row>
                            <v-col cols="6">
                              <v-text-field
                                :disabled="
                                  !deviceSettings.config.humidity_checkbox
                                "
                                v-model="deviceSettings.config.max_humidity"
                                outlined
                                dense
                                small
                                type="number"
                                :hide-details="true"
                                label="Max Humidity Threshold"
                                step="0.01"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="6"></v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.humidity_checkbox
                                "
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.humidity_alert_sms
                                "
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.humidity_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.humidity_alert_call
                                "
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.humidity_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.humidity_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                    <v-col cols="12">
                      <v-card elevation="2" style="height: 200px" outlined>
                        <v-card-title
                          dense
                          class="popup_background"
                          style="padding: 2px"
                        >
                          <v-checkbox
                            color="primary"
                            style="margin-top: -3px"
                            @click="doorAlerts()"
                            :hide-details="true"
                            v-model="deviceSettings.config.doorcontact_checkbox"
                            label="Door Alarm"
                          ></v-checkbox>

                          <v-icon style="margin-left: 10px" color="red"
                            >mdi-door-sliding-open</v-icon
                          >
                        </v-card-title>

                        <v-card-text>
                          <br />
                          <v-row>
                            <v-col cols="6">
                              <v-select
                                :disabled="
                                  !deviceSettings.config.doorcontact_checkbox
                                "
                                v-model="deviceSettings.config.max_doorcontact"
                                outlined
                                dense
                                small
                                :hide-details="true"
                                label="Max Waiting Door Alarm"
                                :items="heartBeatData"
                                item-value="value"
                                item-text="label"
                              ></v-select>
                            </v-col>
                            <v-col cols="6"></v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.doorcontact_checkbox
                                "
                                :hide-details="true"
                                v-model="deviceSettings.config.door_alert_sms"
                                :label="`SMS Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.doorcontact_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="deviceSettings.config.door_alert_call"
                                :label="`Call Alert`"
                              ></v-checkbox>
                            </v-col>
                            <v-col cols="4">
                              <v-checkbox
                                :disabled="
                                  !deviceSettings.config.doorcontact_checkbox
                                "
                                cols="4"
                                :hide-details="true"
                                v-model="
                                  deviceSettings.config.door_alert_whatsapp
                                "
                                :label="`Whatsapp Alert`"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-tab-item>
            <v-tab-item>
              <v-card elevation="2" outlined>
                <v-card-text>
                  <v-row>
                    <v-col cols="3">
                      <v-label>Phone Number 1</v-label>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        style="width: 300px"
                        v-model="deviceSettings.config.phone_number1"
                        outlined
                        dense
                        small
                        :hide-details="true"
                        label="Phone Number1"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="3">
                      <v-label>Phone Number 2</v-label>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        style="width: 300px"
                        v-model="deviceSettings.config.phone_number2"
                        outlined
                        dense
                        small
                        :hide-details="true"
                        label="Phone Number2"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="3">
                      <v-label>Phone Number 3</v-label>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        style="width: 300px"
                        v-model="deviceSettings.config.phone_number3"
                        outlined
                        dense
                        small
                        :hide-details="true"
                        label="Phone Number3"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="3">
                      <v-label>Phone Number 4</v-label>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        style="width: 300px"
                        v-model="deviceSettings.config.phone_number4"
                        outlined
                        dense
                        small
                        :hide-details="true"
                        label="Phone Number4"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="3">
                      <v-label>Phone Number 5</v-label>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        style="width: 300px"
                        v-model="deviceSettings.config.phone_number5"
                        outlined
                        dense
                        small
                        :hide-details="true"
                        label="Phone Number5"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-tabs>
      </v-col>
    </v-row>

    <v-card-actions class="mt-5" v-if="!viewMode">
      <v-btn @click="newItemDialog = false" dark filled color="red"
        >Cancel</v-btn
      >
      <v-spacer></v-spacer>
      <span
        v-if="errorValidateMessage != ''"
        style="color: red; padding-right: 50px"
        >Error: {{ errorValidateMessage }}</span
      >
      <v-btn @click="save()" dark filled color="primary">Save</v-btn>
    </v-card-actions>
  </div>

  <NoAccess v-else />
</template>
<script>
export default {
  props: ["addNew", "editedItem"],
  data: () => ({
    //datatable varables
    tab: 0,
    errorValidateMessage: "",
    message: "",
    page: 1,
    perPage: 0,
    currentPage: 1,
    cumulativeIndex: 1,
    totalTableRowsCount: 0,
    options: {},
    filters: {},
    isFilter: false,
    data: [],
    loading: false,
    deviceSettings: { config: null },
    headers_table: [
      {
        text: "#",
        value: "sno",
        align: "left",
        sortable: false,
        filterable: false,
      },
      {
        text: "Serial Number",
        value: "serial_number",
        align: "left",
        sortable: true,
        filterable: true,
      },
      {
        text: "Device Name",
        value: "name",
        key: "name",
        align: "left",
        sortable: true,
        filterable: true,
        filterSpecial: true,
      },
      {
        text: "Roo No",
        value: "room.room_no",
        align: "left",
        sortable: true,
        key: "room_id",
        filterable: true,
        filterSpecial: true,
      },

      {
        text: "Status",
        value: "latest_status",
        align: "left",
        sortable: true,
        key: "room_id",
        filterable: true,
        filterSpecial: true,
      },

      {
        text: "Status Time",
        value: "latest_status_time",
        align: "left",
        sortable: true,
        key: "room_id",
        filterable: true,
        filterSpecial: true,
      },

      { text: "Options", value: "options", align: "left", sortable: false },
    ],
    roomList: [],

    endpoint: "devices",

    newItemDialog: false,

    //add edit item details

    editedItemIndex: -1,
    roomTypesForSelectOptions: [],
    errors: {},
    snackbar: false,
    snackbarColor: "black",
    snackbarResponse: "",
    viewMode: false,
    floors: [
      1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
    ],
    heartBeatData: [],
    timeOptionsData: [],
  }),

  created() {
    this.generateTimeOptions();
    this.generateTimeOptionsHeartBeat();

    this.getDataFromApi();
  },
  methods: {
    can(per) {
      let u = this.$auth.user;
      return (
        (u && u.permissions.some((e) => e == per || per == "/")) || u.is_master
      );
    },

    powerAlerts() {
      if (!this.deviceSettings.config.power_checkbox) {
        this.deviceSettings.config.power_alert_sms =
          this.deviceSettings.config.power_checkbox;
        this.deviceSettings.config.power_alert_call =
          this.deviceSettings.config.power_checkbox;
        this.deviceSettings.config.power_alert_whatsapp =
          this.deviceSettings.config.power_checkbox;
      }
    },

    waterAlerts() {
      if (!this.deviceSettings.config.water_checkbox) {
        this.deviceSettings.config.water_alert_sms =
          this.deviceSettings.config.water_checkbox;
        this.deviceSettings.config.water_alert_call =
          this.deviceSettings.config.water_checkbox;
        this.deviceSettings.config.water_alert_whatsapp =
          this.deviceSettings.config.water_checkbox;
      }
    },
    fireAlerts() {
      if (!this.deviceSettings.config.fire_checkbox) {
        this.deviceSettings.config.fire_alert_sms =
          this.deviceSettings.config.fire_checkbox;
        this.deviceSettings.config.fire_alert_call =
          this.deviceSettings.config.fire_checkbox;
        this.deviceSettings.config.fire_alert_whatsapp =
          this.deviceSettings.config.fire_checkbox;
      }
    },
    tempAlerts() {
      if (!this.deviceSettings.config.temp_checkbox) {
        this.deviceSettings.config.temp_alert_sms =
          this.deviceSettings.config.temp_checkbox;
        this.deviceSettings.config.temp_alert_call =
          this.deviceSettings.config.temp_checkbox;
        this.deviceSettings.config.temp_alert_whatsapp =
          this.deviceSettings.config.temp_checkbox;
      }
    },
    humidityAlerts() {
      if (!this.deviceSettings.config.humidity_checkbox) {
        this.deviceSettings.config.humidity_alert_sms =
          this.deviceSettings.config.humidity_checkbox;
        this.deviceSettings.config.humidity_alert_call =
          this.deviceSettings.config.humidity_checkbox;
        this.deviceSettings.config.humidity_alert_whatsapp =
          this.deviceSettings.config.humidity_checkbox;
      }
    },
    doorAlerts() {
      if (!this.deviceSettings.config.doorcontact_checkbox) {
        this.deviceSettings.config.door_alert_sms =
          this.deviceSettings.config.doorcontact_checkbox;
        this.deviceSettings.config.door_alert_call =
          this.deviceSettings.config.doorcontact_checkbox;
        this.deviceSettings.config.door_alert_whatsapp =
          this.deviceSettings.config.doorcontact_checkbox;
      }
    },
    generateTimeOptionsHeartBeat() {
      const options = [];
      const increments = [5, 10, 15, 30]; // Time increments in seconds and minutes

      // Generate options for seconds (5s to 55s)
      for (let sec = 1; sec <= 10; sec += 1) {
        options.push({
          id: "heartbeat",
          value: sec + "",
          label: `${sec} seconds`,
        });
      }

      this.heartBeatData = options;
    },
    generateTimeOptions() {
      const options = [];
      const increments = [5, 10, 15, 30]; // Time increments in seconds and minutes

      // Generate options for seconds (5s to 55s)
      for (let sec = 5; sec < 60; sec += 5) {
        options.push({
          id: "heartbeat",
          value: sec + "",
          label: `${sec} seconds`,
        });
      }

      // Generate options for minutes (1 min to 60 min)
      for (let min = 1; min <= 60; min += 1) {
        if (min == 0)
          options.push({
            id: "heartbeat",
            value: (min + 1) * 60 + "",
            label: `${min + 1} minute${min + 1 > 1 ? "s" : ""}`,
          });
        else
          options.push({
            id: "heartbeat",
            value: min * 60 + "",
            label: `${min} minute${min > 1 ? "s" : ""}`,
          });
      }

      this.timeOptionsData = options;
    },
    getDataFromApi() {
      this.message = "loading....";

      this.deviceSettings = { config: null };

      let options = {
        params: {
          company_id: this.$auth.user.company.id,
          serial_number: this.editedItem.serial_number,
        },
      };

      this.$axios
        .get(`get_device_settings_from_socket_arduino`, options)
        .then(({ data }) => {
          if (!data.error) this.deviceSettings = data;
          else this.message = data.error;
        });
    },
    isValidNumber(value) {
      const pattern = /^$|^\d{10,13}$/;
      return pattern.test(value);
    },
    save() {
      this.errorValidateMessage = "";

      if (this.deviceSettings.config.temp_checkbox) {
        let maxTemp = parseFloat(this.deviceSettings.config.max_temperature);
        if (isNaN(maxTemp)) {
          this.errorValidateMessage = "Temperature Max value is invalid";
        }

        let minTemp = parseFloat(this.deviceSettings.config.min_temperature);
        if (isNaN(minTemp) && this.errorValidateMessage == "") {
          this.errorValidateMessage = "Temperature Min value is invalid";
        }
      } else if (this.deviceSettings.config.humidity_checkbox) {
        let maxHumidity = parseFloat(this.deviceSettings.config.max_humidity);
        if (isNaN(maxHumidity)) {
          this.errorValidateMessage = "Humidity Max value is invalid";
        }
      } else if (this.deviceSettings.config.doorcontact_checkbox) {
        let maxDoorContact = parseFloat(
          this.deviceSettings.config.max_doorcontact
        );
        if (isNaN(maxDoorContact)) {
          this.errorValidateMessage = "Door Contact waiting value is invalid";
        }
      } else if (this.deviceSettings.config.siren_checkbox) {
        let maxSirenPlay = parseFloat(
          this.deviceSettings.config.max_siren_play
        );
        if (isNaN(maxSirenPlay) && this.errorValidateMessage == "") {
          this.errorValidateMessage = "Siren Play time value is invalid";
        }

        let maxSirenPause = parseFloat(
          this.deviceSettings.config.max_siren_pause
        );
        if (isNaN(maxSirenPause) && this.errorValidateMessage == "") {
          this.errorValidateMessage = "Siren Pause time value is invalid";
        }
      }
      if (
        this.deviceSettings.config.phone_number1 &&
        !this.isValidNumber(this.deviceSettings.config.phone_number1)
      ) {
        this.errorValidateMessage = "Phone Number 1 is invalid";
      } else if (
        this.deviceSettings.config.phone_number2 &&
        !this.isValidNumber(this.deviceSettings.config.phone_number2)
      ) {
        this.errorValidateMessage = "Phone Number 2 is invalid";
      } else if (
        this.deviceSettings.config.phone_number3 &&
        !this.isValidNumber(this.deviceSettings.config.phone_number3)
      ) {
        this.errorValidateMessage = "Phone Number 3 is invalid";
      } else if (
        this.deviceSettings.config.phone_number4 &&
        !this.isValidNumber(this.deviceSettings.config.phone_number4)
      ) {
        this.errorValidateMessage = "Phone Number 4 is invalid";
      } else if (
        this.deviceSettings.config.phone_number5 &&
        !this.isValidNumber(this.deviceSettings.config.phone_number5)
      ) {
        this.errorValidateMessage = "Phone Number 5 is invalid";
      }

      if (this.errorValidateMessage != "") return false;

      let options = {
        params: {
          company_id: this.$auth.user.company.id,
          serial_number: this.editedItem.serial_number,
          config: this.deviceSettings.config,
        },
      };

      this.$axios
        .post(`update_device_settings_from_socket_arduino`, options.params)
        .then(({ data }) => {
          this.snackbar = true;
          this.snackbarResponse = "Device Settings Updated successfully";

          setTimeout(() => {
            this.$emit("emitCloseEvent");
          }, 1000 * 2);
        });
    },
  },
};
</script>
