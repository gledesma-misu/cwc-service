<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h5 class="float-start text-light">Staff List</h5>
          <button class="btn btn-success float-end" @click="createStaff" v-if="current_permissions.has('users-create')">
            New Staff
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="search_type">Search Type</label>
                <select name="search_type" class="form-control" v-model="searchData.search_type">
                  <option value="name">Name</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="search_value">Search Value</label>
                <input type="text" name="search_value" class="form-control" v-model="searchData.search_value"
                  @keyup="searchUser">
              </div>
            </div>

          </div>
          <div class="table-responsive">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name </th>
                  <th>Username </th>
                  <th>Email </th>
                  <th>Division/Unit </th>
                  <th v-if="current_permissions.has('users-update') || current_permissions.has('users-delete')">
                    Actions </th>
                </tr>

              </thead>
              <tbody>
                <tr v-for="(staff, index) in staffs.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ staff.fname + ' ' + staff.mname + ' ' + staff.lname }}</td>
                  <td>{{ staff.username }}</td>
                  <td>{{ staff.email }}</td>
                  <td>{{ staff.division != null ? staff.division.name : '...' }}</td>
                  <td v-if="current_permissions.has('users-update') || current_permissions.has('users-delete')">
                    <button class="btn btn-primary mr-2" @click="editStaff(staff)"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-danger" @click="deleteStaff(staff)">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- table -->
          <!-- Pagination -->
          <div class="d-flex justify-content-center" v-if="userLinks.length > 3">
            <nav aria-label="Page navigation example">
              <ul class="pagination">
                <li :class="`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''
            }`" v-for="(link, index) in userLinks" :key="index">
                  <a class="page-link" href="#" v-html="link.label" @click.prevent="getResults(link)"></a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- End Pagination -->
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" 
            >
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    {{ !editMode ? "Add Staff" : "Edit Staff" }}
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">First Name</label>
                        <input type="text" class="form-control" v-model="staffData.fname">
                        <div class="text-danger" v-if="staffData.errors.has('fname')"
                          v-html="staffData.errors.get('fname')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Middle Name</label>
                        <input type="text" class="form-control" v-model="staffData.mname">
                        <div class="text-danger" v-if="staffData.errors.has('mname')"
                          v-html="staffData.errors.get('mname')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Last Name</label>
                        <input type="text" class="form-control" v-model="staffData.lname">
                        <div class="text-danger" v-if="staffData.errors.has('lname')"
                          v-html="staffData.errors.get('lname')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" v-model="staffData.username">
                        <div class="text-danger" v-if="staffData.errors.has('username')"
                          v-html="staffData.errors.get('username')"></div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" v-model="staffData.email">
                        <div class="text-danger" v-if="staffData.errors.has('email')"
                          v-html="staffData.errors.get('email')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" v-model="staffData.password">
                        <div class="text-danger" v-if="staffData.errors.has('password')"
                          v-html="staffData.errors.get('password')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="emp_id">Employee ID</label>
                        <input type="text" class="form-control" v-model="staffData.emp_id">
                        <div class="text-danger" v-if="staffData.errors.has('emp_id')"
                          v-html="staffData.errors.get('emp_id')"></div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="division_id">Division/Unit</label>
                        <multi-select :options="filtered_divisions" v-model="staffData.division_id"
                          :searchable="true"></multi-select>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="selected_roles">Roles</label>
                        <multi-select :options="filtered_roles" v-model="staffData.selected_roles" :searchable="true"
                          mode="tags"></multi-select>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="permission_categories">Permission Categories</label>
                        <multi-select :options="filtered_permission_categories"
                          v-model="staffData.selected_permission_categories" :searchable="true" mode="tags"
                          @input="getFilteredPermissions"></multi-select>
                      </div>

                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="filtered_permissions">Permission</label>
                        <multi-select :options="filtered_permissions" v-model="staffData.selected_permissions"
                          :searchable="true" mode="tags"></multi-select>

                      </div>

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                  </button>
                  <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="!editMode ? addStaff() : updateStaff()">
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
import { Modal } from "bootstrap";
import Form from "vform";

export default {
  data() {
    return {
      exampleModal: null,
      editMode: false,
      staffData: new Form({
        id: '',
        division_id: '',
        fname: '',
        mname: '',
        lname: '',
        emp_id: '',
        username: '',
        password: '',
        selected_roles: [],
        selected_permission_categories: [],
        selected_permissions: [],
        email: '',
      }),
      searchData: {
        search_type: 'name',
        search_value: '',
      }
    };
  },
  methods: {
    searchUser() {
      this.$store.dispatch('searchUser', this.searchData)
    },
    getResults(link) {
      if (!link.url || link.active) {
        return;
      } else {
        this.$store.dispatch("getStaffsResults", link);
      }
    },
    getFilteredPermissions(values) {
      this.$store.dispatch('getFilteredPermissions', { values: values }).then(() => {
        this.staffData.selected_permissions = [];
        this.filtered_permissions.forEach(item => {
          if (!item.label.includes('delete')) {
            this.staffData.selected_permissions.push(item.value);
          }
        });
      });
    },
    createStaff() {
      this.editMode = false;
      this.staffData.clear();
      this.staffData.reset();
      this.staffData.name = "";
      try {
        this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
        this.exampleModal.show();
        // $("#exampleModal").modal("show");
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
      this.staffData.mname = staff.mname;
      this.staffData.lname = staff.lname;
      this.staffData.fname = staff.fname;
      this.staffData.username = staff.username;
      this.staffData.emp_id = staff.emp_id;
      this.staffData.division_id = staff.division_id == 0 ? '' : staff.division_id;
      this.staffData.email = staff.email;
      this.staffData.password = staff.password;

      this.staffData.selected_roles = [];
      this.staffData.selected_permission_categories = [];
      this.staffData.selected_permissions = [];
      staff.roles.forEach((role) => {
        this.staffData.selected_roles.push(role.id);
      });

      let permissionsArray = [];
      staff.permissions.forEach((permission) => {
        let permissions = permission.name.split("-");
        permissionsArray.push(permissions[0]);
      });
      let uniqueItems = [...new Set(permissionsArray)];
      this.staffData.selected_permission_categories = uniqueItems;

      this.$store
        .dispatch("getFilteredPermissions", { values: uniqueItems })
        .then(() => {
          staff.permissions.forEach((permission) => {
            this.staffData.selected_permissions.push(permission.id);
          });
        });
      this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
      this.exampleModal.show();
      // $("#exampleModal").modal("show");
    },
    updateStaff() {
      // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false
      // if (this.divisionData.name) {

      this.$store.dispatch("updateStaff", this.staffData);

      // }
    },
    deleteStaff(staff) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          this.$store.dispatch("deleteStaff", staff);
        }
      });
    },
  },
  mounted() {
    this.$store.dispatch("getStaffs");
    this.$store.dispatch("getAllDivisions");
    this.$store.dispatch("getAllRoles");
    this.$store.dispatch("getAllPermissions");
    this.$store.dispatch("getAuthRolesAndPermissions");
  },
  computed: {
    staffs() {
      return this.$store.getters.staffs;
    },
    userLinks() {
      return this.$store.getters.userLinks;
    },
    filtered_permissions() {
      return this.$store.getters.filtered_permissions;
    },
    filtered_permission_categories() {
      return this.$store.getters.filtered_permission_categories;
    },
    filtered_roles() {
      return this.$store.getters.filtered_roles;
    },
    filtered_divisions() {
      return this.$store.getters.filtered_divisions;
    },
    current_roles() {
      return this.$store.getters.current_roles;
    },
    current_permissions() {
      return this.$store.getters.current_permissions;
    },
  },
};
</script>