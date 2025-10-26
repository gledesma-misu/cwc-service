<template>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            MISU Action
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <th style="width: 40%">Technical Assistance Number</th>
                        <td>{{ requestInfo.request_id }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Request Type</th>
                        <td>
                            <div v-if="requestInfo.request_type == 1"><span class="badge bg-primary">Hardware</span>
                            </div>
                            <div v-if="requestInfo.request_type == 2"><span class="badge bg-primary">Software</span>
                            </div>
                            <div v-if="requestInfo.request_type == 3"><span class="badge bg-primary">Hardware
                                    and Software
                                </span></div>
                            <div v-if="requestInfo.request_type == 4"><span class="badge bg-primary">Others</span></div>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 2cm">Description of Request</th>
                        <td>{{ requestInfo.description }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Request Date</th>
                        <td>{{ requestInfo.request_date }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Request By</th>
                        <td>{{ requestInfo.request_by.fname + ' ' + requestInfo.request_by.mname + ' ' +
                            requestInfo.request_by.lname }}</td>
                    </tr>
                    <tr>
                        <th style="width: 40%">File Attachement</th>
                        <td>
                            <a :href="`${url}public/requests/${requestInfo.file_attachement}`" class="btn btn-success "
                                v-if="requestInfo.file_attachement" target="_blank">
                                <i class="fa fa-download"></i>
                            </a>
                            <p v-else>No file uploaded yet</p>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Status</th>
                        <td>
                            <span v-if="requestInfo.status == 1" class="badge badge-success">Completed</span>
                            <span v-if="requestInfo.status == 2 || requestInfo.status == 0"
                                class="badge badge-warning">Pending</span>
                            <span v-if="requestInfo.status == 3" class="badge badge-info">Ongoing</span>
                            <span v-if="requestInfo.status == 4" class="badge badge-info">Disregard</span>
                        </td>
                    </tr>
                    <tr v-if="requestInfo.status == 1">
                        <th style="width: 40%">Performance Survey</th>
                        <td>
                            <span v-if="requestInfo.performance_survey == 1" class="badge badge-success">Very Dissatisfied</span>
                            <span v-if="requestInfo.performance_survey == 2"  class="badge badge-success">Dissatisfied</span>
                            <span v-if="requestInfo.performance_survey == 3" class="badge badge-success">Neutral</span>
                            <span v-if="requestInfo.performance_survey == 4" class="badge badge-success">Satisfied</span>
                            <span v-if="requestInfo.performance_survey == 5" class="badge badge-success">Very Satisfied</span>
                        </td>
                    </tr>
                    <tr v-if="current_roles.has('employee') && requestInfo.status == 3 || requestInfo.status == 1">
                        <th style="width: 40%">Findings</th>
                        <td>{{ tech_responses.findings}}</td>
                    </tr>
                    <tr v-if="current_roles.has('employee') && requestInfo.status == 3 || requestInfo.status == 1">
                        <th style="width: 40%">Recommendations</th>
                        <td>{{ tech_responses.recommendations}}</td>
                    </tr>
                    <tr v-if="current_roles.has('employee') && requestInfo.status == 3 || requestInfo.status == 1">
                        <th style="width: 40%">Remarks</th>
                        <td>{{ tech_responses.remarks}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="current_roles.has('employee') && requestInfo.status == 3 ">
            <div class="row">
                <label for="findings">Performance Survey</label>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-check-inline">
                                <input type="radio" class="form-check-input" id="inlineradio5" name="very_satisfied"
                                    v-model="requestRate.performance_survey" value="5" />
                                <label for="inlineradio5" class="form-check-label">Very Satisfied</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="radio" class="form-check-input" id="inlineradio4" name="satisfied"
                                    v-model="requestRate.performance_survey" value="4" />
                                <label for="inlineradio4" class="form-check-label">Satisfied</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="radio" class="form-check-input" id="inlineradio3" name="neutral"
                                    v-model="requestRate.performance_survey" value="3" />
                                <label for="inlineradio3" class="form-check-label">Neutral</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="radio" class="form-check-input" id="inlineradio2" name="dissatisfied"
                                    v-model="requestRate.performance_survey" value="2" />
                                <label for="inlineradio2" class="form-check-label">Dissatisfied</label>
                            </div>
                            <div class="form-check-inline">
                                <input type="radio" class="form-check-input" id="inlineradio1" name="very_dissatisfied"
                                    v-model="requestRate.performance_survey" value="1" />
                                <label for="inlineradio1" class="form-check-label">Very Dissatisfied</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="current_permissions.has('technicalassistance-misu') && requestInfo.status != 3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="findings">Findings</label>
                        <textarea class="form-control" v-model="requestAction.findings" />
                        <div class="text-danger" v-if="requestAction.errors.has('findings')"
                            v-html="requestAction.errors.get('findings')"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="recommendations">Recommendation</label>
                        <textarea class="form-control" v-model="requestAction.recommendations" />
                        <div class="text-danger" v-if="requestAction.errors.has('recommendations')"
                            v-html="requestAction.errors.get('recommendations')"></div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" v-model="requestAction.remarks" />
                        <div class="text-danger" v-if="requestAction.errors.has('remarks')"
                            v-html="requestAction.errors.get('remarks')"></div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" @click="takeAction(requestInfo)"
            v-if="current_permissions.has('technicalassistance-misu') && requestInfo.status != 3">
            Take Action</button>
        <button type="button"  :class="current_permissions.has('technicalassistance-misu') ? 'btn btn-info' : 'btn btn-success'" @click="completeRequest(requestInfo)"
            v-if="requestInfo.status == 3 && current_roles.has('employee')">
            Mark Complete</button>
    </div>

</template>
<script>
import Form from 'vform';
export default {
    props: ["requestInfo"],
    data() {
        return {
            url: "",
            requestAction: new Form({
                ta_request_id: '',
                findings: '',
                recommendations: '',
                remarks: '',
            }),
            requestRate: new Form({
                ta_request_id: '',
                performance_survey: '',
            }),
        };

    },
    methods: {
        takeAction(requestInfo) {
            this.requestAction.ta_request_id = requestInfo.id;
            this.$store.dispatch('takeAction', this.requestAction)
        },
        completeRequest(requestInfo) {
            this.requestRate.ta_request_id = requestInfo.id;
            this.$store.dispatch('completeRequest', this.requestRate)
        }
    },
    mounted() {
        this.url = window.url;
        this.$store.dispatch('getTechResponse', this.requestInfo.id);
        this.$store.dispatch('getAuthRolesAndPermissions');
    },
    computed: {
        accomplished_requests() {
            return this.$store.getters.accomplished_requests;
        },
        current_roles() {
            return this.$store.getters.current_roles
        },
        current_permissions() {
            return this.$store.getters.current_permissions
        },
        tech_responses() {
            return this.$store.getters.tech_responses
        },
    }
};
</script>