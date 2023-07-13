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
Vue.component('modal-perevozchiki-component', require('./components/ModalPerevozchikiComponent').default);
Vue.component('modal-voditel-component', require('./components/ModalVoditelComponent').default);
Vue.component('users-list-component', require('./components/Settings/UsersListComponent').default);
Vue.component('main-settings-component', require('./components/Settings/MainSettingsComponent').default);
Vue.component('docs-settings-component', require('./components/Settings/DocsSettingsComponent').default);
Vue.component('perevozka-settings-component', require('./components/Settings/PerevozkaSettingsComponent').default);
Vue.component('forma-gruzootpravitel-settings-component', require('./components/Settings/FormaGruzootpravitelSettingsComponent').default);
Vue.component('add-users-settings-component', require('./components/Settings/AddUsersSettingsComponent').default);
Vue.component('gruzootpravitel-component', require('./components/GruzootpravitelComponent').default);
Vue.component('perevozchiki-component', require('./components/PerevozchikiComponent').default);
Vue.component('stavki-component', require('./components/StavkiComponent').default);
Vue.component('auto-input-component', require('./components/SupportComponents/AutoInputComponent').default);
Vue.component('auto-input-global-component', require('./components/SupportComponents/AutoInputGlobalComponent').default);
Vue.component('auto-input-perevozka-component', require('./components/SupportComponents/AutoInputPerevozkaComponent').default);
Vue.component('auto-input-voditel-component', require('./components/SupportComponents/AutoInputVoditelComponent').default);
Vue.component('auto-input-stavki-tip-t-s-component', require('./components/SupportComponents/AutoInputStavkiTipTSComponent').default);
Vue.component('auto-input-t-s-component', require('./components/SupportComponents/AutoInputTSComponent').default);
Vue.component('auto-input-tip-t-s-component', require('./components/SupportComponents/AutoInputTipTSComponent').default);
Vue.component('auto-input-p-p-component', require('./components/SupportComponents/AutoInputPPComponent').default);
Vue.component('auto-input-perevozka-modal-component', require('./components/SupportComponents/AutoInputPerevozkaModalComponent').default);
Vue.component('auto-input-stavki-component', require('./components/SupportComponents/AutoInputStavkiComponent').default);
Vue.component('alert-error-list-component', require('./components/SupportComponents/AlertErrorListComponent').default);
Vue.component('pagination-component', require('./components/SupportComponents/PaginationComponent').default);
Vue.component('vid-t-s-component', require('./components/Settings/VidTSComponent').default);
Vue.component('voditeli-component', require('./components/Settings/VoditeliComponent').default);
Vue.component('p-p-component', require('./components/Settings/PPComponent').default);
Vue.component('modal-t-s-component', require('./components/ModalTSComponent').default);
Vue.component('modal-p-p-component', require('./components/ModalPPComponent').default);
Vue.component('t-s-modal-component', require('./components/Settings/TSModalComponent').default);
Vue.component('impala-yr-adres-component', require('./components/Settings/ImpalaYrAdresComponent').default);

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

// import VueTelInput from "vue-tel-input";
import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
// Vue.use(VueTelInput);
Vue.component("vue-phone-number-input", VuePhoneNumberInput);
import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
Vue.use(Autocomplete)

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);
//маска для мобильн
// import VueInputMask from 'vue-inputmask';
// Vue.use(VueInputMask);

//mixins
import MixinGoTo from './mixins/MixinGoTo'
Vue.mixin(MixinGoTo);
import MixinListMethods from './mixins/MixinListMethods'
Vue.mixin(MixinListMethods);
import MixinAutocompliteMethods from './mixins/MixinAutocompliteMethods'
Vue.mixin(MixinAutocompliteMethods);
import MixinCheckShowBlock from './mixins/MixinCheckShowBlock'
Vue.mixin(MixinCheckShowBlock);
import MixinCheckOrderStatus from './mixins/MixinCheckOrderStatus'
Vue.mixin(MixinCheckOrderStatus);
//импортирую свою директиву my-click-outside
import './directives/my-click-outside';

const app = new Vue({
    el: '#app',
});
