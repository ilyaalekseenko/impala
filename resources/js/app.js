/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import { createApp } from 'vue'
import { createPinia } from 'pinia'

const pinia = createPinia()
const app = createApp({})

app.use(pinia)

import axios from 'axios'
import VueAxios from 'vue-axios'

app.use(VueAxios, axios)
app.provide('axios', app.config.globalProperties.axios)  // provide 'axios'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import HeaderMenuComponent from './components/HeaderMenuComponent.vue';
app.component('header-menu-component', HeaderMenuComponent);

import OrdersComponent from './components/OrdersComponent.vue';
app.component('orders-component', OrdersComponent);

import CreateOrdersComponent from './components/CreateOrdersComponent.vue';
app.component('create-orders-component', CreateOrdersComponent);

import GradeComponent from './components/GradeComponent.vue';
app.component('grade-component', GradeComponent);

import ModalPogruzkaComponent from './components/ModalPogruzkaComponent.vue';
app.component('modal-pogruzka-component', ModalPogruzkaComponent);

import ModalZakazchikComponent from './components/ModalZakazchikComponent.vue';
app.component('modal-zakazchik-component', ModalZakazchikComponent);

import ModalAuthorComponent from './components/ModalAuthorComponent.vue';
app.component('modal-author-component', ModalAuthorComponent);

import ModalPerevozchikiComponent from './components/ModalPerevozchikiComponent.vue';
app.component('modal-perevozchiki-component', ModalPerevozchikiComponent);

import ModalVoditelComponent from './components/ModalVoditelComponent.vue';
app.component('modal-voditel-component', ModalVoditelComponent);

import UsersListComponent from './components/Settings/UsersListComponent.vue';
app.component('users-list-component', UsersListComponent);

import MainSettingsComponent from './components/Settings/MainSettingsComponent.vue';
app.component('main-settings-component', MainSettingsComponent);

import DocsSettingsComponent from './components/Settings/DocsSettingsComponent.vue';
app.component('docs-settings-component', DocsSettingsComponent);

import PerevozkaSettingsComponent from './components/Settings/PerevozkaSettingsComponent.vue';
app.component('perevozka-settings-component', PerevozkaSettingsComponent);

import FormaGruzootpravitelSettingsComponent from './components/Settings/FormaGruzootpravitelSettingsComponent.vue';
app.component('forma-gruzootpravitel-settings-component', FormaGruzootpravitelSettingsComponent);

import AddUsersSettingsComponent from './components/Settings/AddUsersSettingsComponent.vue';
app.component('add-users-settings-component', AddUsersSettingsComponent);

import GruzootpravitelComponent from './components/GruzootpravitelComponent.vue';
app.component('gruzootpravitel-component', GruzootpravitelComponent);

import PerevozchikiComponent from './components/PerevozchikiComponent.vue';
app.component('perevozchiki-component', PerevozchikiComponent);

import StavkiComponent from './components/StavkiComponent.vue';
app.component('stavki-component', StavkiComponent);

import AutoInputComponent from './components/SupportComponents/AutoInputComponent.vue';
app.component('auto-input-component', AutoInputComponent);

import AutoInputAuthorComponent from './components/SupportComponents/AutoInputAuthorComponent.vue';
app.component('auto-input-author-component', AutoInputAuthorComponent);

import AutoInputGlobalComponent from './components/SupportComponents/AutoInputGlobalComponent.vue';
app.component('auto-input-global-component', AutoInputGlobalComponent);

import AutoInputPerevozkaComponent from './components/SupportComponents/AutoInputPerevozkaComponent.vue';
app.component('auto-input-perevozka-component', AutoInputPerevozkaComponent);

import AutoInputVoditelComponent from './components/SupportComponents/AutoInputVoditelComponent.vue';
app.component('auto-input-voditel-component', AutoInputVoditelComponent);

import AutoInputStavkiTipTSComponent from './components/SupportComponents/AutoInputStavkiTipTSComponent.vue';
app.component('auto-input-stavki-tip-t-s-component', AutoInputStavkiTipTSComponent);

import AutoInputTSComponent from './components/SupportComponents/AutoInputTSComponent.vue';
app.component('auto-input-t-s-component', AutoInputTSComponent);

import AutoInputTipTSComponent from './components/SupportComponents/AutoInputTipTSComponent.vue';
app.component('auto-input-tip-t-s-component', AutoInputTipTSComponent);

import AutoInputPPComponent from './components/SupportComponents/AutoInputPPComponent.vue';
app.component('auto-input-p-p-component', AutoInputPPComponent);

import AutoInputPerevozkaModalComponent from './components/SupportComponents/AutoInputPerevozkaModalComponent.vue';
app.component('auto-input-perevozka-modal-component', AutoInputPerevozkaModalComponent);

import AutoInputZakazchikComponent from './components/AutoInputZakazchikComponent.vue';
app.component('auto-input-zakazchik-component', AutoInputZakazchikComponent);

import AutoInputManagerComponent from './components/AutoInputManagerComponent.vue';
app.component('auto-input-manager-component', AutoInputManagerComponent);

import AutoInputAuthorGradeComponent from './components/SupportComponents/AutoInputAuthorGradeComponent.vue';
app.component('auto-input-author-grade-component', AutoInputAuthorGradeComponent);

import PhoneComponent from './components/SupportComponents/PhoneComponent.vue';
app.component('phone-component', PhoneComponent);

import AutoInputStavkiComponent from './components/SupportComponents/AutoInputStavkiComponent.vue';
app.component('auto-input-stavki-component', AutoInputStavkiComponent);

import AlertErrorListComponent from './components/SupportComponents/AlertErrorListComponent.vue';
app.component('alert-error-list-component', AlertErrorListComponent);

import PaginationComponent from './components/SupportComponents/PaginationComponent.vue';
app.component('pagination-component', PaginationComponent);

import VidTSComponent from './components/Settings/VidTSComponent.vue';
app.component('vid-t-s-component', VidTSComponent);

import VoditeliComponent from './components/Settings/VoditeliComponent.vue';
app.component('voditeli-component', VoditeliComponent);

import PPComponent from './components/Settings/PPComponent.vue';
app.component('p-p-component', PPComponent);

import ModalTSComponent from './components/ModalTSComponent.vue';
app.component('modal-t-s-component', ModalTSComponent);

import ModalPPComponent from './components/ModalPPComponent.vue';
app.component('modal-p-p-component', ModalPPComponent);

import TSModalComponent from './components/Settings/TSModalComponent.vue';
app.component('t-s-modal-component', TSModalComponent);

import ImpalaYrAdresComponent from './components/Settings/ImpalaYrAdresComponent.vue';
app.component('impala-yr-adres-component', ImpalaYrAdresComponent);

import NomenklaturaComponent from './components/files/NomenklaturaComponent.vue';
app.component('nomenklatura-component', NomenklaturaComponent);

import MainLoadingComponent from './components/Loading/MainLoadingComponent.vue';
app.component('main-loading-component', MainLoadingComponent);

import 'bootstrap/dist/css/bootstrap.css'


import vClickOutside from "click-outside-vue3"
app.use(vClickOutside)
app.directive('click-outside', vClickOutside.directive)

import { ModalsContainer, VueFinalModal, createVfm } from 'vue-final-modal'

app.component('modals-container', ModalsContainer)
app.component('vue-final-modal', VueFinalModal)
const vfm = createVfm()
app.use(vfm)
import 'vue-final-modal/style.css'

import VTailwindModal from './components/VTailwindModal.vue';
app.component('v-tailwind-modal', VTailwindModal);


import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
app.use(Autocomplete)

import {
    // Directives
    vTooltip,
    vClosePopper,
    // Components
    Dropdown,
    Tooltip,
    Menu
} from 'floating-vue'

app.directive('tooltip', vTooltip)
app.directive('close-popper', vClosePopper)
app.component('VDropdown', Dropdown)
app.component('VTooltip', Tooltip)
app.component('VMenu', Menu)

import Vue3ConfirmDialog from 'vue3-confirm-dialog';
import 'vue3-confirm-dialog/style';
app.use(Vue3ConfirmDialog)
app.component('vue3-confirm-dialog', Vue3ConfirmDialog.default)

import 'sweetalert2/dist/sweetalert2.min.css';
import VueSweetalert2 from 'vue-sweetalert2';
app.use(VueSweetalert2);

import moment from 'moment'
app.config.globalProperties.$filters = {
    formatDate(value) {
        if (value) {
            console.log('formatDate')
            return moment(String(value)).format('DD.MM.YYYY')
        }
    }
}

//mixins
import MixinGoTo from './mixins/MixinGoTo'
app.mixin(MixinGoTo);
import MixinListMethods from './mixins/MixinListMethods'
app.mixin(MixinListMethods);
import MixinAutocompliteMethods from './mixins/MixinAutocompliteMethods'
app.mixin(MixinAutocompliteMethods);
import MixinCheckShowBlock from './mixins/MixinCheckShowBlock'
app.mixin(MixinCheckShowBlock);
import MixinCheckOrderStatus from './mixins/MixinCheckOrderStatus'
app.mixin(MixinCheckOrderStatus);
import MixinConfirm from './mixins/MixinConfirm'
app.mixin(MixinConfirm);
import MixinNumbersToSet from './mixins/MixinNumbersToSet'
app.mixin(MixinNumbersToSet);

//импортирую свою директиву my-click-outside
import {myClickOutside} from "./directives/my-click-outside";
app.directive('my-click-outside', myClickOutside);


app.mount('#app');
