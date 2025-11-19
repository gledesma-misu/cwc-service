import "./bootstrap";
import "./sidebar/main";

// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import { createApp } from "vue";
import { store } from "./store/store";
// import PrimeVue from "primevue/config";
// import Aura from "@primeuix/themes/aura";

import LogoutComponent from "./components/auth/LogoutComponent.vue";
import NotificationsComponent from "./components/NotificationsComponent.vue";
import Divisions from "./components/Divisions.vue";
import Staffs from "./components/staffs/Staffs.vue";
import TechAssistance from "./components/techassistance/TechAssistance.vue";
import PermissionsCreate from "./components/permissions/PermissionsCreate.vue";
import Form from "vform";
window.Form = Form;

import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";

import moment from "moment"; // moment.js

import Swal from "sweetalert2";
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});
window.Toast = Toast;

const app = createApp({});

app.component("logout-component", LogoutComponent);
app.component("notifications-component", NotificationsComponent);
app.component("divisions", Divisions);
app.component("staffs", Staffs);
app.component("techassistance", TechAssistance);
app.component("multi-select", Multiselect);
app.component("permissions-create", PermissionsCreate);

app.config.globalProperties.$filter = {
    myDate(date) {
        return moment(date).startOf("hour").fromNow();
    },
};
window.url = "http://localhost:8000/";
window.path = window.location.pathname;
app.use(store);

// app.use(PrimeVue, {
//     theme: {
//         preset: Aura,
//     },
//     ripple: true,
// });
// app.use(PrimeVue, { unstyled: true, ripple: true });
// app.use(ToastService)
app.mount("#app");
