<template>
  <v-dialog persistent v-model="dialog" width="900">
    <template v-slot:activator="{ on, attrs }">
      <span style="cursor: pointer" v-bind="attrs" v-on="on">
        <v-btn dense small class="primary" text title="Add Company">
          Create Contractor
          <v-icon right dark>mdi-plus-circle-outline</v-icon>
        </v-btn>
      </span>
    </template>
    <v-card>
      <v-toolbar dense flat>
        <v-card-title>Create Contractor</v-card-title>
        <v-spacer></v-spacer>
        <v-icon color="primary" @click="dialog = false">mdi-close</v-icon>
      </v-toolbar>
      <v-container fluid>
        <v-row>
          <v-col cols="3">
            <v-row>
              <!-- <v-col cols="12">
                <div class="text-center">
                  <SnippetsUploadAttachment
                    :defaultImage="setImagePreview"
                    @uploaded="handleAttachment"
                  />

                  <span v-if="errors && errors.logo" class="text-danger mt-2">{{
                    errors.logo[0]
                  }}</span>
                </div>
              </v-col> -->
              <v-col cols="12">
                <div class="text-center">
                  <Camera
                    ref="CameraComponent"
                    @imageSrc="
                      (e) => {
                        payload.logo = e;
                      }
                    "
                  />
                  <span v-if="errors && errors.logo" class="error--text mt-2">{{
                    errors.logo[0]
                  }}</span>
                </div>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="payload.visitor_company_name"
                  dense
                  outlined
                  :hide-details="!errors.visitor_company_name"
                  :error-messages="
                    errors && errors.visitor_company_name
                      ? errors.visitor_company_name[0]
                      : ''
                  "
                  label="Company Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="payload.id_number"
                  dense
                  outlined
                  :hide-details="!errors.id_number"
                  :error-messages="
                    errors && errors.id_number ? errors.id_number[0] : ''
                  "
                  label="Emirates ID"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="payload.rfid"
                  dense
                  outlined
                  :hide-details="!errors.rfid"
                  :error-messages="errors && errors.rfid ? errors.rfid[0] : ''"
                  label="RFID"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="9">
            <v-row class="">
              <v-col cols="6">
                <v-text-field
                  v-model="payload.first_name"
                  dense
                  outlined
                  :hide-details="!errors.first_name"
                  :error-messages="
                    errors && errors.first_name ? errors.first_name[0] : ''
                  "
                  label="First Name"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="payload.last_name"
                  dense
                  outlined
                  :hide-details="!errors.last_name"
                  :error-messages="
                    errors && errors.last_name ? errors.last_name[0] : ''
                  "
                  label="Last Name"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  type="number"
                  v-model="payload.phone_number"
                  dense
                  outlined
                  :hide-details="!errors.phone_number"
                  :error-messages="
                    errors && errors.phone_number ? errors.phone_number[0] : ''
                  "
                  label="Phone Number"
                ></v-text-field>
              </v-col>

              <v-col cols="6">
                <v-text-field
                  label="Email"
                  :readonly="disabled"
                  v-model="payload.email"
                  dense
                  class="text-center"
                  outlined
                  :hide-details="!errors.email"
                  :error-messages="
                    errors && errors.email ? errors.email[0] : ''
                  "
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-select
                  v-model="payload.purpose_id"
                  :items="purposes"
                  dense
                  outlined
                  item-text="name"
                  item-value="id"
                  :hide-details="!errors.purpose_id"
                  label="Purpose"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-radio-group
                  class="ma-0 mt-2 px-2 pa-0"
                  v-model="payload.gender"
                  row
                  :hide-details="!errors.gender"
                  :error-messages="
                    errors && errors.gender ? errors.gender[0] : ''
                  "
                >
                  <v-radio label="Male" value="Male"></v-radio>
                  <v-radio label="Female" value="Female"></v-radio>
                  <v-radio label="Other" value="Other"></v-radio>
                </v-radio-group>
              </v-col>

              <v-col cols="6">
                <v-menu
                  v-model="menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="payload.visit_from"
                      label="Visit From"
                      append-icon="mdi-calendar"
                      outlined
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :hide-details="!errors.visit_from"
                      :error-messages="
                        errors && errors.visit_from ? errors.visit_from[0] : ''
                      "
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="payload.visit_from"
                    @input="menu = false"
                    no-title
                    scrollable
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menu2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="payload.visit_to"
                      label="Visit To"
                      append-icon="mdi-calendar"
                      outlined
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :hide-details="!errors.visit_to"
                      :error-messages="
                        errors && errors.visit_to ? errors.visit_to[0] : ''
                      "
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="payload.visit_to"
                    @input="menu2 = false"
                    no-title
                    scrollable
                  ></v-date-picker>
                </v-menu>
              </v-col>

              <v-col cols="12" sm="6" md="4" lg="6">
                <SnippetsTimePickerV1
                  label="Entry Time"
                  :default_value="payload.time_in"
                  @getTime="
                    (e) => {
                      payload.time_in = e;
                    }
                  "
                />
              </v-col>
              <v-col cols="12" sm="6" md="4" lg="6">
                <SnippetsTimePickerV1
                  label="Exit Time"
                  :default_value="payload.time_out"
                  @getTime="
                    (e) => {
                      payload.time_out = e;
                    }
                  "
                />
              </v-col>

              <v-col cols="12"> <b>Contractor Company Details</b></v-col>
              <v-col cols="6">
                <v-select
                  @change="setContractorCompany(contractor.company_id)"
                  v-model="contractor.company_id"
                  :items="[
                    { id: ``, branch_name: `Select Contractor Company` },
                    ...contractor_companies,
                  ]"
                  dense
                  outlined
                  item-text="branch_name"
                  item-value="id"
                  :hide-details="!errors.company_id"
                  :error-messages="
                    errors && errors.company_id ? errors.company_id[0] : ''
                  "
                  label="Contractor Company"
                ></v-select>
              </v-col>

              <v-col cols="6">
                <v-text-field
                  v-model="contractor.manager_name"
                  dense
                  outlined
                  :hide-details="!errors.manager_name"
                  :error-messages="
                    errors && errors.manager_name ? errors.manager_name[0] : ''
                  "
                  label="Manager Name"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="contractor.phone_number"
                  dense
                  outlined
                  :hide-details="!errors.phone_number"
                  :error-messages="
                    errors && errors.phone_number ? errors.phone_number[0] : ''
                  "
                  label="Phone Number"
                ></v-text-field>
              </v-col>

              <v-col cols="6">
                <v-text-field
                  v-model="contractor.email"
                  dense
                  outlined
                  :hide-details="!errors.email"
                  :error-messages="
                    errors && errors.email ? errors.email[0] : ''
                  "
                  label="Email"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="text-right my-1">
                <v-btn @click="dialog = false">Close</v-btn>
                <v-btn class="primary" @click="submit">Submit</v-btn>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
import "cropperjs/dist/cropper.css";
import VueCropper from "vue-cropperjs";
let date = new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
  .toISOString()
  .substring(0, 10);

let hours = String(new Date().getHours()).padStart(2, "0");
let minutes = String(new Date().getMinutes()).padStart(2, "0");
let dynamicTime = hours + ":" + minutes;
export default {
  components: {
    VueCropper,
  },

  data: () => ({
    disabled: false,
    step: 1,
    contractor: {
      company_id:0
    },
    payload: {
      company_id: 0,
      visit_from: date,
      visit_to: date,
      time_in: dynamicTime,
      time_out: dynamicTime,

      withOutHost: true,
      host_flat_number: null,
      host_company_name: null,
      host_name: null,
      host_phone_number: null,
      host_email: null,
      note: null,

      timezone_id: 1,
      purpose_id: 1,
      first_name: null,
      last_name: null,
      gender: "Male",
      phone_number: null,
      email: null,
      visitor_company_name: null,

      status_id: 1,
      date,
      updated_by: 1,
      status_phone_number: null,
      company_name: null,
      reason: null,
      host_company_id: 0,
    },
    imagePreview: "/no-profile-image.jpg",
    setImagePreview: null,
    imageMemberPreview: "/no-profile-image.jpg",

    tab: null,

    totalRowsCount: 0,
    filters: {},
    isFilter: false,
    sortBy: "id",
    sortDesc: false,
    snack: false,
    snackColor: "",
    snackText: "",
    loadinglinear: true,
    displayErrormsg: false,
    image: "",
    mime_type: "",
    cropedImage: "",
    cropper: "",
    autoCrop: false,
    dialogCropping: false,
    tabMenu: [],
    tab: "0",
    employeeId: 0,
    employeeObject: {},
    attrs: [],
    dialog: false,
    editDialog: false,
    viewDialog: false,
    selectedFile: "",
    dialog: false,
    m: false,
    right: true,
    rightDrawer: false,
    drawer: true,
    tab: null,
    selectedItem: 1,
    on: "",
    files: "",
    search: "",
    loading: false,
    //total: 0,
    next_page_url: "",
    prev_page_url: "",
    current_page: 1,
    per_page: 1000,
    ListName: "",
    color: "background",
    response: "",
    snackbar: false,
    btnLoader: false,
    max_employee: 0,
    employee: {
      title: "Mr",
      display_name: "",
      employee_id: "",
      system_user_id: "",
    },
    upload: {
      name: "",
    },

    pagination: {
      current: 1,
      total: 0,
      per_page: 10,
    },
    options: {},
    Model: "Tanent",
    endpoint: "tanent",
    search: "",
    snackbar: false,
    ids: [],
    loading: false,
    //total: 0,
    titleItems: ["Mr", "Mrs", "Miss", "Ms", "Dr"],
    editedIndex: -1,
    editedItem: { name: "" },
    defaultItem: { name: "" },
    response: "",
    data: [],
    errors: [],
    designations: [],
    roles: [],
    employees: [],
    department_filter_id: "",
    dialogVisible: false,
    formAction: "Create",

    date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
      .toISOString()
      .substr(0, 10),
    menu: false,
    menu2: false,
    menu3: false,

    floors: [],
    rooms: [],
    purposes: [],
    contractor_companies: [],
  }),

  async created() {
    this.loading = false;
    await this.getPurposes();
    await this.getContractorCompanies();
  },

  methods: {
    setContractorCompany(id) {
     console.log(id);
      this.contractor = this.contractor_companies.find((e) => e.id == id);
    },
    async getContractorCompanies() {
      this.$axios
        .get(`branch`, {
          params: {
            company_id: this.$auth.user.company_id,
          },
        })
        .then(({ data }) => {
          this.contractor_companies = data.data;
        })
        .catch((e) => console.log(e));
    },
    async getPurposes() {
      this.$axios
        .get(`purpose_list`, {
          params: {
            company_id: this.$auth.user.company_id,
            type: "contractor",
          },
        })
        .then(({ data }) => {
          this.purposes = data;
        })
        .catch((e) => console.log(e));
    },
    handleAttachment(e) {
      this.payload.profile_picture = e;
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    submit() {
      this.$axios
        .post(`visitor-self-register`, this.payload)
        .then(({ data }) => {
          this.handleSuccessResponse("Contractor inserted successfully");
        })
        .catch(({ response }) => {
          this.handleErrorResponse(response);
        });

      // }
    },
    handleSuccessResponse(message) {
      this.errors = [];
      this.dialog = false;
      this.$emit("success", message);
    },
    handleErrorResponse(response) {
      if (!response) {
        this.snackbar = true;
        this.response = "An unexpected error occurred.";
        return;
      }
      let { status, data, statusText } = response;

      if (status && status == 422) {
        this.errors = data.errors;
        return;
      }

      this.snackbar = true;
      this.response = statusText;
    },
  },
};
</script>
