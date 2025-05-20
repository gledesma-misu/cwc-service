import './bootstrap';

import {createApp} from 'vue';
import {store} from './store/store'

import Divisions from './components/Divisions.vue';
import PermissionsCreate from './components/permissions/PermissionsCreate.vue';
import Form from 'vform';
window.Form = Form;

const app = createApp({})
app.component('divisions', Divisions);
app.component('permissions-create', PermissionsCreate);
window.url = '/cwc-service/';
app.use(store);
app.mount('#app');