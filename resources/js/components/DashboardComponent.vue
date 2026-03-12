<template>
  <h1>Dashboard</h1>
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
  <TechnicalAssistanceBarChart v-if="current_roles.has('misu')"/>
  <FaqsComponent v-if="current_roles.has('employee')" />
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
import TechnicalAssistanceBarChart from "./techassistance/TABarChart.vue";
import FaqsComponent from "./FaqsComponent.vue";

export default {
  components: {
    TechnicalAssistanceBarChart,
    FaqsComponent,
  },
  mounted() {
    this.$store.dispatch("getAuthRolesAndPermissions");
    this.$store.dispatch("countTAPending");
    window.Echo.channel("dashboard").listen("DashboardEvent", (e) => {
      this.$store.dispatch("countTAPending");
    });
  },
  computed: {
    ta_count() {
      return this.$store.getters.ta_count;
    },
    current_roles() {
      return this.$store.getters.current_roles;
    },
  },
};
</script>