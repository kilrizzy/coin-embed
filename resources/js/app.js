require('./bootstrap');

import Vue from 'vue';
import 'livewire-vue';

window.Vue = Vue;

/*
import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);*/

Vue.component('field-group', require('./components/forms/field-group').default);
Vue.component('field-help', require('./components/forms/field-help').default);
Vue.component('field-label', require('./components/forms/field-label').default);
Vue.component('field-input-text', require('./components/forms/field-input-text').default);
Vue.component('field-input-textarea', require('./components/forms/field-input-textarea').default);
Vue.component('field-input-file', require('./components/forms/field-input-file').default);
Vue.component('field-input-select', require('./components/forms/field-input-select').default);

Vue.component('notification', require('./components/notification/notification').default);
Vue.component('button-ui', require('./components/button/button-ui').default);
Vue.component('payment-method-nano-setup', require('./components/payment-methods/nano/payment-method-nano-setup').default);
Vue.component('payment-method-nano-consolidate', require('./components/payment-methods/nano/payment-method-nano-consolidate').default);

//const app = document.getElementById('app');

/*
new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);*/


const app = new Vue({
    el: '#app',
    data: {

    },
    mounted: function(){
        //
    },
});

