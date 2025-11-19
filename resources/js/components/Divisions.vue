<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h5 class="float-start text-light">Division List</h5>
                    <button class="btn btn-success float-end" @click="createDivision"
                        v-if="current_permissions.has('divisions-create')">New Division/Unit</button>
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
                                <input type="text" name="search_value" class="form-control"
                                    v-model="searchData.search_value" @keyup="searchDivision">
                            </div>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
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
                                <tr v-for="(division, index) in divisions.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ division.name }}</td>
                                    <td
                                        v-if="current_permissions.has('divisions-update') || current_permissions.has('divisions-delete')">
                                        <button class="btn btn-primary mr-2" @click="editDivision(division)"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger" @click="deleteDivision(division)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- pagination -->
                    <div class="d-flex justify-content-center" v-if="divisionLinks.length > 3">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li :class="`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''
                            }`" v-for="(link, index) in divisionLinks" :key="index">
                                    <a class="page-link" href="#" v-html="link.label"
                                        @click.prevent="getResults(link)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- end pagination -->
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        {{ !editMode ? 'Add Division' : 'Edit Division' }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name"
                                                    v-model="divisionData.name">
                                                <!-- <p class="text-danger" v-if="divisionErrors.name">
                                                    Name Required
                                                </p> -->
                                                <div class="text-danger" v-if="divisionData.errors.has('name')"
                                                    v-html="divisionData.errors.get('name')" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success"
                                        @click="!editMode ? addDivision() : updateDivision()" data-bs-dismiss="modal">
                                        {{ !editMode ? 'Add' : 'Save Changes' }}</button>
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
import Form from 'vform'
import { Modal } from "bootstrap";
export default {
    data() {
        return {
            exampleModal: null,
            editMode: false,
            divisionData: new Form({
                id: '',
                name: ''
            }),
            divisionErrors: {
                name: false
            },
            searchData: {
                search_type: 'name',
                search_value: '',
            }
        }
    },
    methods: {
        searchDivision() {
            this.$store.dispatch('searchDivision', this.searchData)
        },
        getResults(link) {
            if (!link.url || link.active) {
                return;
            } else {
                this.$store.dispatch("getDivisionsResults", link);
            }
        },
        createDivision() {
            this.editMode = false;
            this.divisionData.name = '';
            try {
                this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
                this.exampleModal.show();
                // $('#exampleModal').modal('show');
            } catch (error) {
                console.log(error);
            }

        },
        addDivision() {
            // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false

            // if (this.divisionData.name) {
            this.$store.dispatch('addDivision', this.divisionData);
            // }

        },
        editDivision(division) {
            this.editMode = true;
            this.divisionData.id = division.id;
            this.divisionData.name = division.name;

            this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
            this.exampleModal.show();
            // $('#exampleModal').modal('show');
        },
        updateDivision() {
            // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false
            // if (this.divisionData.name) {
            this.$store.dispatch('updateDivision', this.divisionData);

            // }
        },
        deleteDivision(division) {
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
                    this.$store.dispatch('deleteDivision', division);
                }
            });
        }

    },
    mounted() {
        this.$store.dispatch('getDivisions');
        this.$store.dispatch('getAuthRolesAndPermissions');
    },
    computed: {
        divisionLinks() {
            return this.$store.getters.divisionLinks;
        },
        divisions() {
            return this.$store.getters.divisions
        },
        current_roles() {
            return this.$store.getters.current_roles
        },
        current_permissions() {
            return this.$store.getters.current_permissions
        }

    }
}
</script>