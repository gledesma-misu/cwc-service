<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h5 class="float-start text-light">Division List</h5>
                    <button class="btn btn-success float-end" @click="createDivision"
                    v-if="current_permissions.has('divisions-create')"
                    >New Division/Unit</button>
                </div>
                <div class="card-body">
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
                        </table>
                    </div>
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
                                        @click="!editMode ? addDivision() : updateDivision()">
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

export default {
    data() {
        return {
            editMode: false,
            divisionData: new Form({
                id: '',
                name: ''
            }),
            divisionErrors: {
                name: false
            }
        }
    },
    methods: {

        createDivision() {
            this.editMode = false;
            this.divisionData.name = '';
            try {
                $('#exampleModal').modal('show');
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

            $('#exampleModal').modal('show');
        },
        updateDivision() {
            // this.divisionData.name == '' ? this.divisionErrors.name = true : this.divisionErrors.name = false
            // if (this.divisionData.name) {
            this.$store.dispatch('updateDivision', this.divisionData);

            // }
        },
        deleteDivision(division) {
            this.$store.dispatch('deleteDivision', division);
        }

    },
    mounted() {
        this.$store.dispatch('getDivisions');
        this.$store.dispatch('getAuthRolesAndPermissions');
    },
    computed: {
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