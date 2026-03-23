<template>
  <h1 class="text-2xl font-bold mb-4">Technical Assistance Reports</h1>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card-header bg-dark">
        <h5 class="text-light">
          Reports
        </h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="type">Report</label>
              <select class="form-control" v-model="reportData.type">
                <option value="#" disabled>Select Report</option>
                <option value="pending" v-if="current_permissions.has('technicalassistance-read')">Pending Report
                </option>
                <option value="completed" v-if="current_permissions.has('technicalassistance-read')">Completed Report
                </option>
                <div class="text-danger" v-if="reportData.errors.has('type')" v-html="reportData.errors.get('type')">
                </div>
                <!-- <option value="weekly">Weekly Report</option>
                <option value="monthly">Monthly Report</option> -->
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input type="date" v-model="reportData.start_date" class="form-control">
              <div class="text-danger" v-if="reportData.errors.has('start_date')"
                v-html="reportData.errors.get('start_date')">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input type="date" v-model="reportData.end_date" class="form-control">
              <div class="text-danger" v-if="reportData.errors.has('end_date')"
                v-html="reportData.errors.get('end_date')">
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-start mt-3"
          v-if="current_permissions.has('reports-create') && reportData.type != '#' && reportData.start_date != '' && reportData.end_date != ''">
          <button type="button" class="btn btn-success" @click.prevent="exportExcel">
            <i class="fa fa-file-excel-o"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// const xlsx = require('xlsx');


export default {
  data() {
    return {
      reportData: new Form({
        type: '#',
        start_date: '',
        end_date: '',
      })
    };
  },
  name: "Reports",

  mounted() {
    this.$store.dispatch('getDivisions');
    this.$store.dispatch('getAuthRolesAndPermissions');
  },
  computed: {
    current_roles() {
      return this.$store.getters.current_roles
    },
    current_permissions() {
      return this.$store.getters.current_permissions
    },
  },
  methods: {
    exportExcel(){
      let data = [];

      this.reportData.post(`${window.url}api/exportExcel`).then(response => {
        data = response.data;
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');
        XLSX.writeFile(workbook, `${this.reportData.type}_report_${this.reportData.start_date}_to_${this.reportData.end_date}.xlsx`);
        
      }).catch(error => {
        console.log(error);
      });
    }
  }
};
</script>