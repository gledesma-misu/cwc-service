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
                            <a href="#" class="nav-link" :class="{ active: currentTab === 'Pending' }"
                                @click.prevent="currentTab = 'Pending'">
                                Pending/Ongoing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" :class="{ active: currentTab === 'Accomplished' }"
                                @click.prevent="currentTab = 'Accomplished'">
                                Accomplished
                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Request </th>
                                    <th>Request By</th>
                                    <th v-if="current_permissions.has('technicalassistance-misu')">
                                        Division/Unit</th>
                                    <th>Request Type</th>
                                    <th>Status</th>
                                    <th>
                                        Actions </th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr v-for="(request, index) in active_tab.data" :key="index">
                                    <td>{{ request.request_id }}</td>
                                    <td>{{ request.description }}</td>
                                    <td>{{ request.request_by.fname }}</td>
                                    <td v-if="current_permissions.has('technicalassistance-misu')">
                                        {{ getDivisionName(request.division_id) }}
                                        <!-- {{ request.division_id }} -->

                                    </td>
                                    <td>
                                        <div v-if="request.request_type == 1"><span
                                                class="badge bg-primary">Hardware</span></div>
                                        <div v-if="request.request_type == 2"><span
                                                class="badge bg-primary">Software</span>
                                        </div>
                                        <div v-if="request.request_type == 3"><span class="badge bg-primary">Hardware
                                                and Software
                                            </span></div>
                                        <div v-if="request.request_type == 4"><span
                                                class="badge bg-primary">Others</span></div>
                                    </td>
                                    <td>
                                        <span v-if="request.status == 1" class="badge badge-success">Completed</span>
                                        <span v-if="request.status == 2 || request.status == 0"
                                            class="badge badge-warning">Pending</span>
                                        <span v-if="request.status == 3" class="badge badge-info">Ongoing</span>
                                        <span v-if="request.status == 4" class="badge badge-info">Disregard</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-info mx-1" @click="showRequest(request)"
                                            title="Show Request">
                                            <i class="fa fa-info"></i>
                                        </button>
                                        <button class="btn btn-danger mx-1" @click="disregardTask(request)"
                                            title="Delete Request" v-if="request.status == 2 || request.status == 0">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- pagination -->
                    <div class="d-flex justify-content-center" v-if="activeLinks.length > 3">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li :class="`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''
                            }`" v-for="(link, index) in activeLinks" :key="index">
                                    <a class="page-link" href="#" v-html="link.label"
                                        @click.prevent="getResults(link)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- end pagination -->
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <!-- Take Action -->
                            <div class="modal-content" v-if="showMode">
                                <TechAction :requestInfo="requestInfo" :key="requestInfo.id || Date.now()" />
                            </div>
                            <!-- end Take Action -->
                            <div class="modal-content" v-if="!showMode">
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
                                                <input type="text" class="form-control" name="request_by"
                                                    :value="logged_user.id === 1 ? 'administrator' : logged_user.fname"
                                                    v-bind:disabled="current_roles.has('employee')">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="request_type">Request Type</label>
                                                <select name="request_type" class="form-control"
                                                    v-model="requestData.request_type">
                                                    <option value="1">Hardware</option>
                                                    <option value="2">Software</option>
                                                    <option value="3">Both Hardware and Software</option>
                                                    <option value="4">Other</option>
                                                </select>
                                                <div class="text-danger" v-if="requestData.errors.has('request_type')"
                                                    v-html="requestData.errors.get('request_type')"></div>
                                                <p class="text-danger" v-if="requestDataErrors.request_type">
                                                    Request Type Required</p>
                                                <!-- <input type="text" class="form-control" name="request_type"> -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea type="text" class="form-control"
                                                    v-model="requestData.description" name="description" />
                                                <p class="text-danger" v-if="requestDataErrors.description">
                                                    Description Type Required</p>
                                                <div class="text-danger" v-if="requestData.errors.has('description')"
                                                    v-html="requestData.errors.get('description')"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="file">File</label>
                                                <input type="file" class="form-control" id="request_file"
                                                    @change="getPerformTaskFile($event)">
                                                <!-- <span> {{ requestData.file ? "Already uploaded a file!" : "No File uploaded yet!" }} </span> -->
                                            </div>
                                            <span v-if="requestData.file">
                                                File Name: {{ requestData.file }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <i>Incomplete request details will automatically decline.</i>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" @click="addRequest()"
                                        v-if="!showMode">
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
import { Modal } from "bootstrap";
import Form from 'vform';
import TechAction from "./TechAction.vue";
import { mapGetters } from 'vuex';
export default {
    components: {
        TechAction
    },
    data() {
        return {
            exampleModal: null,
            showMode: false,
            requestInfo: {},
            logged_user: {},
            currentTab: "Pending",
            windowpath: window.path,
            requestData: new Form({
                request_type: '',
                description: '',
                file: '',
            }),
            requestDataErrors: new Form({
                request_type: '',
                description: '',
                file: '',
            }),

        }
    },

    methods: {
        showRequest(request) {
            this.showMode = true;
            this.requestInfo = request;
            this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
            this.exampleModal.show();
            // $("#exampleModal").modal("show");
        },
        getResults(link) {
            if (!link.url || link.active) {
                return;
            } else {
                if (this.currentTab == 'Pending') {
                    this.$store.dispatch("getRequestResultsPending", link);
                } else {
                    this.$store.dispatch("getRequestResultsAccomplished", link);
                }

            }
        },
        submitRequest() {
            this.showMode = false;
            this.requestData.reset();
            this.requestData.clear();
            this.requestDataErrors.reset();
            this.requestDataErrors.clear();

            this.exampleModal = new Modal(document.getElementById("exampleModal"), { keyboard: false });
            this.exampleModal.show();
            // $("#exampleModal").modal("show");

        },
        disregardTask(request) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch("disregardTask", request);
                }
            });
        },
        getPerformTaskFile(event) {
            this.requestData.file = event.target.files[0];
        },
        addRequest() {
            this.requestData.request_type == '' ? this.requestDataErrors.request_type = true : this.requestDataErrors.request_type = false
            this.requestData.description == '' ? this.requestDataErrors.description = true : this.requestDataErrors.description = false

            let config = { headers: { 'content-type': 'multipart/form-data' } };

            if (this.requestData.request_type && this.requestData.description) {
                this.$store.dispatch("addRequest", {
                    requestData: this.requestData,
                    config: { headers: { "content-type": "multipart/form-data" } },
                }).then(() => {
                    this.exampleModal.hide();
                });
            }
        },
        searchDivision() {
            this.$store.dispatch('searchDivision', this.searchData)
        },

        getDivisionName(divisionId) {
            const current_permissions = this.$store.getters.current_permissions;
            if (current_permissions.has('technicalassistance-misu')) {
                const division = this.divisions.data.find(div => div.id == divisionId);
                // console.log(this.divisions);
                return division ? division.name : 'Unknown';
            }
        }
    },
    mounted() {
        this.logged_user = window.auth_user;
        window.Echo.channel("tarequest").listen("TAssistanceRequest", (e) => {
            this.$store.dispatch("getPendingRequests");
        });
        window.Echo.channel("tarequest").listen("TAssistanceRequest", (e) => {
            this.$store.dispatch("getAccomplishedRequests");
        });
        this.$store.dispatch('getPendingRequests');
        this.$store.dispatch('getAccomplishedRequests');
        const current_permissions = this.$store.getters.current_permissions;
        // if (current_permissions.has('technicalassistance-misu')) {
        //     this.$store.dispatch('getDivisions');
        // }
        this.$store.dispatch('getDivisions');
        this.$store.dispatch('getAuthRolesAndPermissions');
    },
    computed: {
        // getDivisionName() {
        //     return this.$store.getters.getDivisionName;
        // },
        accomplished_requests() {
            return this.$store.getters.accomplished_requests;
        },
        accomplished_requests_links() {
            return this.$store.getters.accomplished_requests_links;
        },
        pending_requests() {
            return this.$store.getters.pending_requests;
        },
        pending_requests_links() {
            return this.$store.getters.pending_requests_links;
        },
        divisions() {
            return this.$store.getters.divisions;
        },
        current_roles() {
            return this.$store.getters.current_roles
        },
        current_permissions() {
            return this.$store.getters.current_permissions
        },
        active_tab() {
            return this.currentTab == "Pending"
                ? this.pending_requests
                : this.accomplished_requests;
        },
        activeLinks() {
            return this.currentTab == "Pending"
                ? this.pending_requests_links
                : this.accomplished_requests_links;
        },
    }
}
</script>