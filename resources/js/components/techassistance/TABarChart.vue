<template>
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card-body">
        <div
          class="row"
          v-if="current_permissions.has('technicalassistance-misu')"
        >
          <div class="col-md-6">
            <h5>Technical Assistance based on Request Type</h5>
          </div>
          <div class="col-md-6">
            <h5>Technical Assistance based on Status</h5>
          </div>
        </div>
        <div class="row"></div>
        <!-- <div class="row">
          <div class="col-md-4">
            <label for="request-type">Request Type:</label>
            <select
              class="form-control request-type-select"
              v-model="request_type"
              @change.prevent="getRequestType"
            >
              <option value="1">Hardware</option>
              <option value="2">Software</option>
              <option value="3">Hardware and Software</option>
              <option value="4">Other</option>
            </select>
          </div>
        </div> -->
        <!-- <div
          class="row"
          v-if="current_permissions.has('technicalassistance-misu')"
        >
          <div class="chart-wrapper">
            <canvas id="ta-bar-chart"></canvas>
          </div>
        </div> -->
        <div
          class="row"
          v-if="current_permissions.has('technicalassistance-misu')"
        >
          <div class="col-md-6">
            <div class="chart-wrapper">
              <canvas id="ta-bar-chart-request-type"></canvas>
            </div>
          </div>
          <div class="col-md-6">
            <select
              class="form-control"
              v-model="year"
              @change.prevent="getLineChartData"
            >
              <option value="2025">2025</option>
              <option value="2026">2026</option>
            </select>

            <div class="chart-wrapper">
              <canvas id="ta-line-chart-status"></canvas>
            </div>
          </div>
        </div>
        <div
          v-if="current_permissions.has('technicalassistance-misu')"
          class="mt-4"
        >
          <div class="card">
            <h5 class="card-header">Reminder</h5>
            <div class="card-body">
              <h5 class="card-title">Special title treatment</h5>
              <p class="card-text">
                With supporting text below as a natural lead-in to additional
                content.
              </p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
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
import axios from "axios";
import Chart from "chart.js/auto";

export default {
  data() {
    return {
      year: "2025",
      myChart: null,
      myChart_2: null,
      chartData: {
        labels: [],
        datasets: [],
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },
  computed: {
    ta_count() {
      return this.$store.getters.ta_count;
    },
    current_roles() {
      return this.$store.getters.current_roles;
    },
    current_permissions() {
      return this.$store.getters.current_permissions;
    },
  },
  mounted() {
    this.$store.dispatch("getAuthRolesAndPermissions").then(() => {
      // this.getBarChartData();
      this.getLineChartData();
      this.getRequestType();
    });
    this.$store.dispatch("countTAPending");
    window.Echo.channel("dashboard").listen("DashboardEvent", (e) => {
      this.$store.dispatch("countTAPending");
      this.getLineChartData();
      this.getRequestType();
    });
  },
  methods: {
    getLineChartData() {
      axios
        .get(`${window.url}api/getLineChartData/${this.year}`)
        .then((response) => {
          let datasets = [];
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Pending Requests -  + ${response.data.year}`,
              data: response.data.ta_request,
              backgroundColor: "lightgray",
              borderColor: "gray",
              borderWidth: 2,
            });
          }
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Completed Requests -  + ${response.data.year}`,
              data: response.data.ta_request_completed,
              backgroundColor: "lightgray",
              borderColor: "green",
              borderWidth: 2,
            });
          }
          if (this.myChart) this.myChart.destroy();
          if (this.current_permissions.has("technicalassistance-create")) {
            this.myChart = new Chart(
              document.getElementById("ta-line-chart-status").getContext("2d"),
              {
                type: "line",
                data: {
                  labels: response.data.months,
                  datasets: datasets,
                },
                options: this.chartOptions,
              }
            );
          }
        })
        .catch((err) => console.log(err));
    },
    getRequestType() {
      axios
        .get(`${window.url}api/getRequestType`)
        .then((response) => {
          let datasets = [];
          let delayed;
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Completed Tickets`,
              data: response.data.ta_request_count_completed,
              backgroundColor: "#22C55E",
              borderColor: "gray",
              borderWidth: 2,
            });
          }
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Pending Tickets`,
              data: response.data.ta_request_count_pending,
              backgroundColor: "#F28E2B",
              borderColor: "gray",
              borderWidth: 2,
            });
          }
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Ongoing Tickets`,
              data: response.data.ta_request_count_ongoing,
              backgroundColor: "#06B6D4",
              borderColor: "gray",
              borderWidth: 2,
            });
          }
          if (this.current_permissions.has("technicalassistance-create")) {
            datasets.push({
              label: `Disregarded Tickets`,
              data: response.data.ta_request_count_disregard,
              backgroundColor: "#E15759",
              borderColor: "gray",
              borderWidth: 2,
            });
          }
          // if (this.current_permissions.has("technicalassistance-create")) {
          //   datasets.push({
          //     label: `Test2`,
          //     data: response.data.ta_request_completed,
          //     backgroundColor: "lightgray",
          //     borderColor: "green",
          //     borderWidth: 2,
          //   });
          // }
          if (this.myChart_2) this.myChart.destroy();
          if (this.current_permissions.has("technicalassistance-create")) {
            this.myChart_2 = new Chart(
              document
                .getElementById("ta-bar-chart-request-type")
                .getContext("2d"),
              {
                type: "bar",
                data: {
                  labels: response.data.numeric_request_type,
                  datasets: datasets,
                },
                options: {
                  animation: {
                    onComplete: () => {
                      delayed = true;
                    },
                    delay: (context) => {
                      let delay = 0;
                      if (
                        context.type === "data" &&
                        context.mode === "default" &&
                        !delayed
                      ) {
                        delay =
                          context.dataIndex * 300 + context.datasetIndex * 100;
                      }
                      return delay;
                    },
                  },
                },
              }
            );
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>
