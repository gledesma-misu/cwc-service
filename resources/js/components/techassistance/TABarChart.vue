<template>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-md-3 col-sm-6">
                        <div class="kpi-card bg-soft-purple">
                            <div class="kpi-title">Completed Request</div>
                            <div class="kpi-value text-primary">{{ ta_count.completed }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="kpi-card bg-soft-orange">
                            <div class="kpi-title">Pending Request</div>
                            <div class="kpi-value text-warning">{{ ta_count.pending }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="kpi-card bg-soft-green">
                            <div class="kpi-title">Ongoing Requests</div>
                            <div class="kpi-value text-success">{{ ta_count.ongoing }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="kpi-card bg-soft-red">
                            <div class="kpi-title">Deleted/Disregard Request</div>
                            <div class="kpi-value text-danger">{{ ta_count.disregard }}</div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="current_permissions.has('technicalassistance-misu')">
                    <div class="col-md-8">
                        <h5>Technical Assistance</h5>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" v-model="year" @change.prevent="getBarChartData">
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                </div>

                <div class="row" v-if="current_permissions.has('technicalassistance-misu')">
                    <div class="chart-wrapper">
                        <canvas id="ta-bar-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.chart-wrapper {
  position: relative;
  height: 300px; /* or 100%, vh, etc */
  width: 100%;
}
.kpi-card {
    border-radius: 12px;
    padding: 20px;
    height: 100%;
}

.kpi-title {
    font-size: 14px;
    color: #6c757d;
}

.kpi-value {
    font-size: 32px;
    font-weight: 700;
}

.bg-soft-purple {
    background-color: #f4f1ff;
}

.bg-soft-orange {
    background-color: #fff3e6;
}

.bg-soft-green {
    background-color: #eef9f0;
}

.bg-soft-red {
    background-color: #f1baac;
}
</style>
<script>
import axios from 'axios';
import Chart from 'chart.js/auto'

export default {
    data() {
        return {
            year: '2025',
            myChart: null,
            chartData: {
                labels: [],
                datasets: [],
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
            },
        }
    },
    computed: {

        ta_count() {
            return this.$store.getters.ta_count
        },
        current_roles() {
            return this.$store.getters.current_roles
        },
        current_permissions() {
            return this.$store.getters.current_permissions
        },
    },
    mounted() {
        this.$store.dispatch('getAuthRolesAndPermissions').then(() => {
            this.getBarChartData()
        });
        this.$store.dispatch('countTAPending');
        window.Echo.channel("dashboard").listen("DashboardEvent", (e) => {
            this.$store.dispatch('countTAPending');
        });

    },
    methods: {
        getBarChartData() {
            axios.get(`${window.url}api/getBarChartData/${this.year}`).then((response) => {

                let datasets = []
                if (this.current_permissions.has('technicalassistance-create')) {
                    datasets.push({
                        label: `Pending Requests -  + ${response.data.year}`,
                        data: response.data.ta_request,
                        backgroundColor: 'lightgray',
                        borderColor: 'gray',
                        borderWidth: 2
                    })
                }
                if (this.current_permissions.has('technicalassistance-create')) {
                    datasets.push({
                        label: `Completed Requests -  + ${response.data.year}`,
                        data: response.data.ta_request_completed,
                        backgroundColor: 'lightgray',
                        borderColor: 'green',
                        borderWidth: 2
                    })
                }
                if (this.myChart) this.myChart.destroy();
                if (this.current_permissions.has('technicalassistance-create')) {
                    this.myChart = new Chart(document.getElementById('ta-bar-chart').getContext("2d"), {
                        type: 'bar',
                        data: {
                            labels: response.data.months,
                            datasets: datasets
                        },
                        options: this.chartOptions
                    })
                }

            }).catch(err => console.log(err));
        }
    }
}
</script>
