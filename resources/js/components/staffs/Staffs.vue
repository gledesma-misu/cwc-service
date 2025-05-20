<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h5 class="float-start text-light">Staff List</h5>
          <button
            class="btn btn-success float-end"
            @click="createStaff"
            v-if="current_permissions.has('users-create')"
          >
            New Staff
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <!-- <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name </th>
                                    <th
                                        v-if="current_permissions.has('divisions-update') || current_permissions.has('divisions-delete')">
                                        Actions </th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(division, index) in divisions" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ division.name }}</td>
                                    <td v-if="current_permissions.has('divisions-update') || current_permissions.has('divisions-delete')">
                                        <button class="btn btn-primary mr-2" @click="editDivision(division)"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger" @click="deleteDivision(division)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
          </div>
          <!-- Modal -->
          <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    {{ !editMode ? "Add Staff" : "Edit Staff" }}
                  </h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" v-model="staffData.name">
                            <div class="text-danger" v-if="staffData.errors.has('name')" v-html="staffData.errors.get('name')"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" v-model="staffData.email">
                            <div class="text-danger" v-if="staffData.errors.has('email')" v-html="staffData.errors.get('email')"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" v-model="staffData.password">
                            <div class="text-danger" v-if="staffData.errors.has('password')" v-html="staffData.errors.get('password')"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="division_id">Division/Unit</label>
                            <multi-select :options="filtered_divisions" v-model="staffData.division_id" :searchable="true"></multi-select>
                           
                        </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    class="btn btn-success"
                    @click="!editMode ? addStaff() : updateStaff()"
                  >
                    {{ !editMode ? "Add" : "Save Changes" }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";

export default {
  data() {
    return {
      editMode: false,
      staffData: new Form({
        id: '',
        division_id: '',
        name: '',
        password: '',
        email: '',
      }),
    };
  },
  methods: {
    createStaff() {
      this.editMode = false;
      this.staffData.name = "";
      try {
        $("#exampleModal").modal("show");
      } catch (error) {
        console.log(error);
      }
    },
    addStaff() {
      // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false

      // if (this.divisionData.name) {
      this.$store.dispatch("addStaff", this.staffData);
      // }
    },
    editStaff(staff) {
      this.editMode = true;
      this.staffData.id = staff.id;
      this.staffData.name = staff.name;

      $("#exampleModal").modal("show");
    },
    updateStaff() {
      // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false
      // if (this.divisionData.name) {
      this.$store.dispatch("updateDivision", this.staffData);

      // }
    },
    deleteStaff(staff) {
      this.$store.dispatch("deleteDivision", staff);
    },
  },
  mounted() {
    this.$store.dispatch("getDivisions");
    this.$store.dispatch("getAuthRolesAndPermissions");
  },
  computed: {
    current_roles() {
      return this.$store.getters.current_roles;
    },
    current_permissions() {
      return this.$store.getters.current_permissions;
    },
  },
};
</script>