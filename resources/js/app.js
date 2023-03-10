/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-menu-component', require('./components/HeaderMenuComponent.vue').default);
Vue.component('orders-component', require('./components/OrdersComponent.vue').default);
Vue.component('create-orders-component', require('./components/CreateOrdersComponent.vue').default);
Vue.component('grade-component', require('./components/GradeComponent').default);
Vue.component('modal-pogruzka-component', require('./components/ModalPogruzkaComponent').default);
Vue.component('users-list-component', require('./components/Settings/UsersListComponent').default);
Vue.component('main-settings-component', require('./components/Settings/MainSettingsComponent').default);
Vue.component('docs-settings-component', require('./components/Settings/DocsSettingsComponent').default);
Vue.component('perevozka-settings-component', require('./components/Settings/PerevozkaSettingsComponent').default);
Vue.component('forma-gruzootpravitel-settings-component', require('./components/Settings/FormaGruzootpravitelSettingsComponent').default);
Vue.component('add-users-settings-component', require('./components/Settings/AddUsersSettingsComponent').default);
Vue.component('gruzootpravitel-component', require('./components/GruzootpravitelComponent').default);
Vue.component('perevozchiki-component', require('./components/PerevozchikiComponent').default);
Vue.component('auto-input-component', require('./components/SupportComponents/AutoInputComponent').default);
Vue.component('alert-error-list-component', require('./components/SupportComponents/AlertErrorListComponent').default);
Vue.component('pagination-component', require('./components/SupportComponents/PaginationComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap and BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
// import VueSidebarMenu from 'vue-sidebar-menu'
 //import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
 //Vue.use(VueSidebarMenu)
import  vueClickOutsideElement from 'vue-click-outside-element'
Vue.use(vueClickOutsideElement);

import vClickOutside from 'v-click-outside'

Vue.use(vClickOutside)

import VueTelInput from "vue-tel-input";
import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
Vue.use(VueTelInput);
Vue.component("vue-phone-number-input", VuePhoneNumberInput);
import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
Vue.use(Autocomplete)

//mixins
import MixinGoTo from './mixins/MixinGoTo'
Vue.mixin(MixinGoTo);
import MixinListMethods from './mixins/MixinListMethods'
Vue.mixin(MixinListMethods);
import MixinAutocompliteMethods from './mixins/MixinAutocompliteMethods'
Vue.mixin(MixinAutocompliteMethods);
const app = new Vue({
    el: '#app',
});
