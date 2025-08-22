<template>
    <h1>Technical Assistance</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h5 class="float-start text-light">Requests List</h5>
                    <button class="btn btn-success float-end" @click="submitRequest"
                        v-if="current_permissions.has('technicalassistance-create')">Request</button>
                </div>
                <div class="card-body">
                    <!-- <div class="row">
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

                    </div> -->
                    <ul class="nav nav-underline">
                        <li class="nav-item">
                            <a
                                :class="`nav-link ${windowpath == '/cwc-service/techassistance/index' ? 'active' : ''}`">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Accomplished</a>
                        </li>
                    </ul>
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Request </th>
                                    <th>Request By</th>
                                    <th>Request Type</th>
                                    <th>Status</th>
                                    <th
                                        v-if="current_permissions.has('technicalassistance-update') || current_permissions.has('technicalassistance-delete')">
                                        Actions </th>
                                </tr>

                            </thead>
                            <tbody>
                                <!-- <tr v-for="(division, index) in divisions.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ division.name }}</td>
                                    <td
                                        v-if="current_permissions.has('divisions-update') || current_permissions.has('divisions-delete')">
                                        <button class="btn btn-primary mr-2" @click="editDivision(division)"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger" @click="deleteDivision(division)">Delete</button>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>

                    <!-- pagination -->
                    <!-- <div class="d-flex justify-content-center" v-if="divisionLinks.length > 3">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li :class="`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''
                            }`" v-for="(link, index) in divisionLinks" :key="index">
                                    <a class="page-link" href="#" v-html="link.label"
                                        @click.prevent="getResults(link)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div> -->
                    <!-- end pagination -->
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Request Technical Assistance
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="request_by">Request By</label>
                                                <input type="text" class="form-control" name="request_by" :value="`${logged_user}`" v-bind:disabled="current_roles.has('employee')">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="request_type">Request Type</label>
                                                <select name="request_type" class="form-control">
                                                    <option value="0">Select Type</option>
                                                    <option value="1">Hardware</option>
                                                    <option value="2">Software</option>
                                                </select>
                                                <!-- <input type="text" class="form-control" name="request_type"> -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <input type="text" class="form-control" name="description">
                                                <div class="text-danger" v-if="requestData.errors.has('email')"
                                                    v-html="requestData.errors.get('description ')"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" @click="addRequest()">
                                        Add</button>
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

export default {
    data() {
        return {
            logged_user: window.auth_user,
            windowpath: window.path,
            requestData: new Form({
                request_by: "",
                request_type: "",
                description: ""
            }),
        }
    },

    methods: {
        submitRequest() {
            try {
                $("#exampleModal").modal("show");
            } catch (error) {
                console.log(error);
            }
        },
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

    },
    mounted() {
        this.$store.dispatch('getDivisions');
        this.$store.dispatch("getAllRoles");
        this.$store.dispatch("getAllPermissions");
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