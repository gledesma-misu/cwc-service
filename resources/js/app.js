import './bootstrap';

import {createApp} from 'vue';
import {store} from './store/store'

import Divisions from './components/Divisions.vue';
import Staffs from './components/staffs/Staffs.vue';
import PermissionsCreate from './components/permissions/PermissionsCreate.vue';
import Form from 'vform';
window.Form = Form;

import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const app = createApp({})
app.component('divisions', Divisions);
app.component('staffs', Staffs);
app.component('multi-select', Multiselect);
app.component('permissions-create', PermissionsCreate);
window.url = '/cwc-service/';
app.use(store);
app.mount('#app');