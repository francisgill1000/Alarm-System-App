<template>
  <div v-if="can('branch_access')">
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" small top="top" :color="color">
        {{ response }}
      </v-snackbar>
      <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
        {{ snackText }}

        <template v-slot:action="{ attrs }">
          <v-btn v-bind="attrs" text @click="snack = false"> Close </v-btn>
        </template>
      </v-snackbar>
    </div>
    <div v-if="!loading">
      <v-dialog persistent v-model="branchDialog" width="900">
        <v-card>
          <v-card-title dark class="popup_background">
            {{ formTitle }} {{ Model }}
            <v-spacer></v-spacer>
            <v-icon @click="branchDialog = false" outlined dark>
              mdi mdi-close-circle
            </v-icon>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="3">
                  <div class="text-center mt-7">
                    <v-img
                      style="
                        width: 150px;
                        height: 150px;
                        border-radius: 50%;
                        margin: 0 auto;
                      "
                      :src="previewImage || '/no-business_profile.png'"
                    ></v-img>
                    <v-btn
                      class="mt-2"
                      style="width: 100%"
                      small
                      @click="onpick_attachment"
                      >{{ !upload.name ? "Upload" : "Change" }}
                      <v-icon right dark>mdi-cloud-upload</v-icon>
                    </v-btn>

                    <input
                      required
                      type="file"
                      @change="attachment"
                      style="display: none"
                      accept="image/*"
                      ref="attachment_input"
                    />

                    <span
                      v-if="errors && errors.logo"
                      class="text-danger mt-2"
                      >{{ errors.logo[0] }}</span
                    >
                  </div>
                </v-col>
                <v-col md="9" sm="12" cols="12" dense>
                  <v-row>
                    <v-col md="6" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.branch_name"
                        label="Contractor Company Name"
                        :hide-details="!errors.branch_name"
                        :error-messages="
                          errors && errors.branch_name
                            ? errors.branch_name[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="6" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.manager_name"
                        label="Contractor Manager Name"
                        :hide-details="!errors.manager_name"
                        :error-messages="
                          errors && errors.manager_name
                            ? errors.manager_name[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="6" cols="12" sm="12" dense>
                      <v-autocomplete
                        v-model="branch.contractor_type"
                        :items="[`Short Type`, `Long Type`]"
                        dense
                        outlined
                        label="Contractor Type"
                        :hide-details="!errors.contractor_type"
                        :error-messages="
                          errors && errors.contractor_type
                            ? errors.contractor_type[0]
                            : ''
                        "
                      >
                      </v-autocomplete>
                    </v-col>

                    <v-col md="6" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.email"
                        label="Email"
                        :hide-details="!errors.email"
                        :error-messages="
                          errors && errors.email ? errors.email[0] : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="6" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.phone_number"
                        label="Phone Number"
                        :hide-details="!errors.phone_number"
                        :error-messages="
                          errors && errors.phone_number
                            ? errors.phone_number[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="6" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.mobile_number"
                        label="Mobile Number"
                        :hide-details="!errors.mobile_number"
                        :error-messages="
                          errors && errors.mobile_number
                            ? errors.mobile_number[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="4" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.licence_number"
                        label="Licence Number"
                        :hide-details="!errors.licence_number"
                        :error-messages="
                          errors && errors.licence_number
                            ? errors.licence_number[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>
                    <v-col md="4" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.licence_issue_by_department"
                        label="Licence Issued By"
                        :hide-details="!errors.licence_issue_by_department"
                        :error-messages="
                          errors && errors.licence_issue_by_department
                            ? errors.licence_issue_by_department[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>
                    <v-col md="4" sm="12" cols="12">
                      <div>
                        <v-menu
                          v-model="joiningDateMenuOpen"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          max-width="290px"
                          min-width="auto"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              :disabled="disabled"
                              v-model="branch.licence_expiry"
                              persistent-hint
                              append-icon="mdi-calendar"
                              readonly
                              outlined
                              dense
                              v-bind="attrs"
                              v-on="on"
                              label="Licence Expiry Date"
                              :hide-details="
                                !errors.licence_expiry
                              "
                              :error-messages="
                                errors && errors.licence_expiry
                                  ? errors.licence_expiry[0]
                                  : ''
                              "
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            style="min-height: 320px"
                            v-model="branch.licence_expiry"
                            no-title
                            @input="joiningDateMenuOpen = false"
                          ></v-date-picker>
                        </v-menu>
                      </div>
                    </v-col>

                    <v-col md="4" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.car_number"
                        label="Car Number"
                        :hide-details="!errors.car_number"
                        :error-messages="
                          errors && errors.car_number
                            ? errors.car_number[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="4" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.car_branch"
                        label="Licence Issued By"
                        :hide-details="!errors.car_branch"
                        :error-messages="
                          errors && errors.car_branch
                            ? errors.car_branch[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="4" cols="12" sm="12" dense>
                      <v-text-field
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        v-model="branch.issued_city"
                        label="Issued City"
                        :hide-details="!errors.issued_city"
                        :error-messages="
                          errors && errors.issued_city
                            ? errors.issued_city[0]
                            : ''
                        "
                      ></v-text-field>
                    </v-col>

                    <v-col md="12" cols="12" sm="12" dense>
                      <label>Address</label>
                      <v-textarea
                        :disabled="disabled"
                        dense
                        outlined
                        type="text"
                        rows="3"
                        v-model="branch.address"
                        hide-details
                      >
                      </v-textarea>
                      <span
                        v-if="errors && errors.address"
                        class="text-danger mt-2"
                        >{{ errors.address[0] }}</span
                      >
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              v-if="formTitle == 'Create' && can('branch_create')"
              small
              :loading="loading"
              color="primary"
              @click="submit"
            >
              Submit
            </v-btn>
            <v-btn
              v-else-if="formTitle == 'Update'"
              small
              :loading="loading"
              color="primary"
              @click="update"
            >
              Update
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <div v-if="can(`branch_view`)">
        <v-card elevation="0" class="mt-2">
          <v-toolbar class="mb-2" dense flat>
            <v-toolbar-title>
              <span> {{ Model }} </span></v-toolbar-title
            >
            <span>
              <v-btn
                dense
                class="ma-0 px-0"
                x-small
                :ripple="false"
                text
                title="Reload"
              >
                <v-icon class="ml-2" @click="clearFilters" dark
                  >mdi mdi-reload</v-icon
                >
              </v-btn>
            </span>
            <span>
              <v-btn
                dense
                class="ma-0 px-0"
                x-small
                :ripple="false"
                text
                title="Filter"
              >
                <v-icon @click="toggleFilter" class="mx-1 ml-2"
                  >mdi mdi-filter</v-icon
                >
              </v-btn>
            </span>

            <v-spacer></v-spacer>

            <v-btn
              v-if="$store.state.loginType == 'company'"
              dense
              x-small
              class="ma-0 px-0"
              :ripple="false"
              text
              title="Add Contractor"
              @click="OpenDialog('Create')"
            >
              <v-icon right size="x-large" dark v-if="can('employee_create')"
                >mdi-plus-circle</v-icon
              >
            </v-btn>
          </v-toolbar>
          <v-data-table
            dense
            v-model="selectedItems"
            :headers="headers_table"
            :items="data"
            model-value="data.id"
            :loading="loadinglinear"
            :options.sync="options"
            :footer-props="{
              itemsPerPageOptions: [100, 500, 1000],
            }"
            class="elevation-1"
            :server-items-length="totalRowsCount"
          >
            <template v-slot:header="{ props: { headers } }">
              <tr v-if="isFilter">
                <td v-for="header in headers" :key="header.text">
                  <v-container>
                    <v-text-field
                      clearable
                      :hide-details="true"
                      v-if="header.filterable && !header.filterSpecial"
                      v-model="filters[header.value]"
                      :id="header.value"
                      @input="applyFilters(header.key, $event)"
                      outlined
                      dense
                      autocomplete="off"
                    ></v-text-field>

                    <v-autocomplete
                      :id="header.key"
                      :hide-details="true"
                      v-if="header.filterSpecial && header.value == 'branch_id'"
                      clearable
                      outlined
                      dense
                      small
                      v-model="filters[header.key]"
                      item-text="branch_name"
                      item-value="id"
                      :items="[
                        { branch_name: `All Contractores`, id: `` },
                        ...branchesList,
                      ]"
                      placeholder="Contractor"
                      @change="applyFilters(header.key, $event)"
                    ></v-autocomplete>
                    <v-select
                      :id="header.key"
                      :hide-details="true"
                      v-if="header.filterSpecial && header.value == 'frequency'"
                      clearable
                      outlined
                      dense
                      small
                      v-model="filters[header.key]"
                      item-text="name"
                      item-value="id"
                      :items="[
                        { name: `All`, id: `` },
                        { name: `Daily`, id: `Daily` },
                        { name: `Weekly`, id: `Weekly` },
                        { name: `Monthly`, id: `Monthly` },
                      ]"
                      placeholder="Report Type"
                      @change="applyFilters(header.key, id)"
                    ></v-select>
                    <v-select
                      :id="header.key"
                      :hide-details="true"
                      v-if="header.filterSpecial && header.value == 'medium'"
                      clearable
                      outlined
                      dense
                      small
                      v-model="filters[header.key]"
                      item-text="name"
                      item-value="id"
                      :items="[
                        { name: `All`, id: `` },
                        { name: `Email`, id: `Email` },
                        { name: `Whatsapp`, id: `Whatsapp` },
                      ]"
                      placeholder="Medium"
                      @change="applyFilters(header.key, id)"
                    ></v-select>
                  </v-container>
                </td>
              </tr>
            </template>
            <template
              v-slot:item.branch_id="{ item, index }"
              style="width: 300px"
            >
              <v-row no-gutters>
                <v-col
                  style="
                    padding: 5px;
                    padding-left: 0px;
                    width: 50px;
                    max-width: 50px;
                  "
                >
                  <v-img
                    style="
                      border-radius: 50%;
                      height: auto;
                      width: 50px;
                      max-width: 50px;
                    "
                    :src="item.logo ? item.logo : '/no-image.PNG'"
                  >
                  </v-img>
                </v-col>
                <v-col style="padding: 10px">
                  <strong> {{ item.branch_name }}</strong>
                  <div>
                    {{ item.branch_code }}
                  </div>
                </v-col>
              </v-row>
            </template>
            <template v-slot:item.location_address="{ item }">
              {{ item.location || "---" }}
              <br />
              {{ item.address || "---" }}
            </template>

            <template v-slot:item.manager_mobile="{ item }">
              <v-row no-gutters>
                <v-col
                  style="
                    padding: 5px;
                    padding-left: 0px;
                    width: 40px;
                    max-width: 40px;
                  "
                >
                  <v-img
                    style="
                      border-radius: 50%;
                      height: auto;
                      width: 40px;
                      max-width: 40px;
                    "
                    :src="
                      item.user && item.user.employee
                        ? item.user.employee.profile_picture
                        : '/no-profile-image.jpg'
                    "
                  >
                  </v-img>
                </v-col>
                <v-col style="padding: 10px">
                  {{
                    (item.user &&
                      item.user.employee &&
                      item.user.employee.first_name +
                        " " +
                        item.user.employee.last_name) ||
                    "---"
                  }}
                  <br />

                  {{ (item.phone_number && item.phone_number) || "---" }}
                </v-col>
              </v-row>
              <br />
            </template>

            <template v-slot:item.options="{ item }">
              <v-menu bottom left v-if="$auth.user.user_type == 'company'">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark-2 icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list width="120" dense>
                  <v-list-item
                    v-if="can('branch_view')"
                    @click="viewItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-eye </v-icon>
                      View
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    v-if="can('branch_edit')"
                    @click="editItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="secondary" small> mdi-pencil </v-icon>
                      Edit
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    v-if="can('branch_delete')"
                    @click="deleteItem(item)"
                  >
                    <v-list-item-title style="cursor: pointer">
                      <v-icon color="error" small> mdi-delete </v-icon>
                      Delete
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </v-card>
      </div>
    </div>
    <Preloader v-else />
  </div>

  <NoAccess v-else />
</template>

<script>
import headers_table from "../../menus/branch.json";

import "cropperjs/dist/cropper.css";
import VueCropper from "vue-cropperjs";

export default {
  components: {
    VueCropper,
  },

  data: () => ({
    departments: [],
    shifts: [],
    timezones: [],
    joiningDate: null,
    joiningDateMenuOpen: false,
    totalRowsCount: 0,
    showFilters: false,
    filters: {},
    isFilter: false,
    sortBy: "branch_id",
    sortDesc: false,
    server_datatable_totalItems: 1000,
    snack: false,
    snackColor: "",
    snackText: "",
    selectedItems: [],
    datatable_search_textbox: "",
    datatable_searchById: "",
    loadinglinear: true,
    displayErrormsg: false,
    image: "",
    mime_type: "",
    cropedImage: "",
    cropper: "",
    autoCrop: false,
    dialogCropping: false,
    comp: "branchEdit",
    tab: "0",
    branchId: 0,
    branchObject: {},
    branchesList: [],
    attrs: [],
    dialog: false,
    editDialog: false,
    selectedFile: "",
    branchDialog: false,
    m: false,
    expand: false,
    expand2: false,
    boilerplate: false,
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
    max_branch: 0,
    branch: {},
    upload: {
      name: "",
    },
    previewImage: null,
    payload: {},
    personalItem: {},
    contactItem: {},
    emirateItems: {},
    setting: {},

    pagination: {
      current: 1,
      total: 0,
      per_page: 10,
    },
    options: {},
    Model: "Contractor Company",
    endpoint: "branch",
    search: "",
    snackbar: false,
    ids: [],
    loading: false,
    //total: 0,
    headers: [],
    titleItems: ["Mr", "Mrs", "Miss", "Ms", "Dr"],
    editedIndex: -1,
    editedItem: { name: "" },
    defaultItem: { name: "" },
    response: "",
    data: [],
    errors: [],
    departments: [],
    sub_departments: [],
    designations: [],
    managers: [],
    department_filter_id: "",
    dialogVisible: false,
    headers_table,
    formTitle: "Create",
    disabled: false,
  }),

  async created() {
    this.loading = false;
    this.boilerplate = true;

    let options = {
      params: {
        per_page: 1000,
        company_id: this.$auth.user.company_id,
        // //department_ids: this.$auth.user.assignedDepartments,
      },
    };

    this.getDataFromApi();
    //this.getManagers(options);
    //this.getDepartments(options);
    this.getbranchesList();
  },
  mounted() {
    //this.getDataFromApi();

    this.headers = [
      // { text: "#" },
      { text: "E.ID" },
      { text: "Profile" },
      { text: "Name" },
      { text: "Email" },
      { text: "Timezone" },
      { text: "Dept" },
      { text: "Sub Dept" },
      { text: "Desgnation" },
      { text: "Role" },
      { text: "Mobile" },
      { text: "Shift" },
      { text: "Actions" },
    ];
  },
  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  methods: {
    getbranchesList() {
      this.payloadOptions = {
        params: {
          company_id: this.$auth.user.company_id,
        },
      };

      this.$axios.get(`branches_list`, this.payloadOptions).then(({ data }) => {
        this.branchesList = data;
      });
    },
    getManagers(options) {
      //
      // this.$axios.get(`assigned-employee-list`, options).then(({ data }) => {
      //   this.managers = data;
      // });

      this.$axios
        .get(`employee_managers_list_all`, options)
        .then(({ data }) => {
          this.managers = data;
        });
    },
    OpenDialog(action) {
      this.formTitle = action;
      this.branch = {};
      this.branchDialog = true;
      this.disabled = false;
    },

    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },

    datatable_cancel() {
      this.datatable_search_textbox = "";
    },
    datatable_open() {
      this.datatable_search_textbox = "";
    },
    datatable_close() {
      this.loading = false;
      //this.datatable_search_textbox = '';
    },
    closePopup() {
      //croppingimagestep5
      this.$refs.attachment_input.value = null;
      this.dialogCropping = false;
    },
    saveCroppedImageStep2() {
      this.cropedImage = this.$refs.cropper.getCroppedCanvas().toDataURL();

      this.image_name = this.cropedImage;
      this.previewImage = this.cropedImage;

      this.dialogCropping = false;
    },

    close() {
      this.dialog = false;
      this.errors = [];
      setTimeout(() => {}, 300);
    },
    json_to_csv(json) {
      let data = json.map((e) => ({
        first_name: e.first_name,
        last_name: e.last_name,
        display_name: e.display_name,
        email: e.user.email,
        phone_number: e.phone_number,
        whatsapp_number: e.whatsapp_number,
        phone_relative_number: e.phone_relative_number,
        whatsapp_relative_number: e.whatsapp_relative_number,
        branch_id: e.branch_id,
        department_code: e.department_id,
        designation_code: e.designation_id,
        department: e.department.name,
        designation: e.designation.name,
      }));
      let header = Object.keys(data[0]).join(",") + "\n";
      let rows = "";
      data.forEach((e) => {
        rows += Object.values(e).join(",").trim() + "\n";
      });
      return header + rows;
    },
    export_submit() {
      if (this.data.length == 0) {
        this.snackbar = true;
        this.response = "No record to download";
        return;
      }

      let csvData = this.json_to_csv(this.data);
      let element = document.createElement("a");
      element.setAttribute(
        "href",
        "data:text/csv;charset=utf-8, " + encodeURIComponent(csvData)
      );
      element.setAttribute("download", "download.csv");
      document.body.appendChild(element);
      element.click();
      document.body.removeChild(element);
    },
    importbranch() {
      let payload = new FormData();
      payload.append("branchs", this.files);
      payload.append("company_id", this.$auth?.user?.company?.id);
      let options = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };
      this.btnLoader = true;
      this.$axios
        .post("/branch/import", payload, options)
        .then(({ data }) => {
          this.btnLoader = false;
          if (!data.status) {
            this.errors = data.errors;
            payload.delete("branchs");
          } else {
            this.errors = [];
            this.snackbar = true;
            this.response = "branchs imported successfully";
            this.getDataFromApi();
            this.close();
          }
        })
        .catch((e) => {
          if (e.toString().includes("Error: Network Error")) {
            this.errors = [
              "File is modified.Please cancel the current file and try again",
            ];
            this.btnLoader = false;
          }
        });
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    onPageChange() {
      this.getDataFromApi();
    },
    applyFilters() {
      this.getDataFromApi();
    },
    toggleFilter() {
      // this.filters = {};
      this.isFilter = !this.isFilter;
    },
    clearFilters() {
      this.filters = {};

      this.isFilter = false;
      this.getDataFromApi();
    },
    getDataFromApi(url = this.endpoint) {
      //this.loading = true;
      this.loadinglinear = true;

      let { sortBy, sortDesc, page, itemsPerPage } = this.options;

      let sortedBy = sortBy ? sortBy[0] : "";
      let sortedDesc = sortDesc ? sortDesc[0] : "";
      let options = {
        params: {
          page: page,
          sortBy: sortedBy,
          sortDesc: sortedDesc,
          per_page: itemsPerPage, //this.pagination.per_page,
          company_id: this.$auth.user.company_id,
          department_id: this.department_filter_id,
          //department_ids: this.$auth.user.assignedDepartments,

          ...this.filters,
        },
      };

      this.$axios.get(url, options).then(({ data }) => {
        this.data = data.data;
        this.pagination.current = data.current_page;
        this.pagination.total = data.last_page;

        this.totalRowsCount = data.total;

        this.data.length == 0
          ? (this.displayErrormsg = true)
          : (this.displayErrormsg = false);

        this.loadinglinear = false;
      });
    },

    getDepartments(options) {
      this.$axios.get(`departments`, options).then(({ data }) => {
        this.departments = data.data;
        // this.departments.unshift({ name: "All Departments", id: "" });
      });
    },

    editItem(item) {
      this.previewImage = item.logo;
      this.formTitle = "Update";
      this.branch = item;
      this.branchDialog = true;
      this.disabled = false;

      let options = {
        params: {
          per_page: 1000,
          company_id: this.$auth.user.company_id,
          branch_id: item.id,
          // //department_ids: this.$auth.user.assignedDepartments,
        },
      };

      this.getManagers(options);
    },
    viewItem(item) {
      this.previewImage = item.logo;
      this.formTitle = "View";
      this.branch = item;
      this.branchDialog = true;
      this.disabled = true;
    },
    deleteItem(item) {
      confirm(
        "Are you sure you wish to delete , to mitigate any inconvenience in future."
      ) &&
        this.$axios
          .delete(`${this.endpoint}/${item.id}`)
          .then(({ data }) => {
            if (!data.status) {
              this.errors = data.errors;
            } else {
              this.getDataFromApi();
              this.snackbar = data.status;
              this.response = data.message;
            }
          })
          .catch((err) => console.log(err));
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    save() {
      let payload = {
        name: this.editedItem.name.toLowerCase(),
        company_id: this.$auth.user.company_id,
      };
      if (this.editedIndex > -1) {
        this.$axios
          .put(this.endpoint + "/" + this.editedItem.id, payload)
          .then(({ data }) => {
            if (!data.status) {
              this.errors = data.errors;
            } else {
              const index = this.data.findIndex(
                (item) => item.id == this.editedItem.id
              );
              this.data.splice(index, 1, {
                id: this.editedItem.id,
                name: this.editedItem.name,
              });
              this.snackbar = data.status;
              this.response = data.message;
              this.close();
            }
          })
          .catch((err) => console.log(err));
      } else {
        this.$axios
          .post(this.endpoint, payload)
          .then(({ data }) => {
            if (!data.status) {
              this.errors = data.errors;
            } else {
              this.getDataFromApi();
              this.snackbar = data.status;
              this.response = data.message;
              this.close();
              this.errors = [];
              this.search = "";
            }
          })
          .catch((res) => console.log(res));
      }
    },
    onpick_attachment() {
      this.$refs.attachment_input.click();
    },
    attachment(e) {
      this.upload.name = e.target.files[0] || "";

      let input = this.$refs.attachment_input;
      let file = input.files;

      if (file[0].size > 1024 * 1024) {
        e.preventDefault();
        this.errors["logo"] = ["File too big (> 1MB). Upload less than 1MB"];
        return;
      }

      if (file && file[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          //croppedimage step6
          this.previewImage = e.target.result;

          // this.selectedFile = event.target.result;

          // this.$refs.cropper.replace(this.selectedFile);
        };
        reader.readAsDataURL(file[0]);
        this.$emit("input", file[0]);

        // this.dialogCropping = true;
      }
    },
    submit() {
      let branch = new FormData();
      branch.append("company_id", this.$auth.user.company_id);
      branch.append("branch_name", this.branch.branch_name);
      // branch.append("user_id", this.branch.user_id);
      branch.append("user_id", 0);

      branch.append("licence_number", this.branch.licence_number);
      branch.append(
        "licence_issue_by_department",
        this.branch.licence_issue_by_department
      );
      branch.append("licence_expiry", this.branch.licence_expiry);
      branch.append("lat", this.branch.lat);
      branch.append("lon", this.branch.lon);
      branch.append("address", this.branch.address);

      if (this.upload.name) {
        branch.append("logo", this.upload.name);
      }

      this.$axios
        .post("/branch", branch)
        .then(({ data }) => {
          //this.loading = false;

          if (!data.status) {
            this.errors = [];
            if (data.errors) this.errors = data.errors;
            this.color = "red";

            this.snackbar = true;
            this.response = data.message;
          } else {
            this.color = "background";
            this.errors = [];
            this.snackbar = true;
            this.response = "Contractor inserted successfully";
            this.getDataFromApi();
            this.branchDialog = false;
          }
        })
        .catch((e) => console.log(e));
    },

    update() {
      let branch = new FormData();
      branch.append("company_id", this.$auth.user.company_id);
      branch.append("branch_name", this.branch.branch_name);
      branch.append("user_id", this.branch.user_id);

      branch.append("licence_number", this.branch.licence_number);
      branch.append(
        "licence_issue_by_department",
        this.branch.licence_issue_by_department
      );
      branch.append("licence_expiry", this.branch.licence_expiry);
      branch.append("lat", this.branch.lat);
      branch.append("lon", this.branch.lon);
      branch.append("address", this.branch.address);

      if (this.upload.name) {
        branch.append("logo", this.upload.name);
      }

      this.$axios
        .post(`/branch/${this.branch.id}`, branch)
        .then(({ data }) => {
          //this.loading = false;

          if (!data.status) {
            this.errors = [];
            if (data.errors) this.errors = data.errors;
            this.color = "red";

            this.snackbar = true;
            this.response = data.message;
          } else {
            this.errors = [];
            this.snackbar = true;
            this.response = "Contractor updated successfully";
            this.getDataFromApi();
            this.branchDialog = false;
          }
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
