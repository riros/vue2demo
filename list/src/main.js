import Vue from "vue";
import Vuex from 'vuex';
// import {Store} from 'vuex';
// import createPersistedState from 'vuex-persistedstate'


// const app_name = 'test_app_name';

// Vue.use(Vuex);
// const store = new Store({
//     plugins: [
//         createPersistedState({
//             getState: (key) => {
//                 let key_name = app_name + "_" + key;
//                 let i = localStorage.getItem(key_name);
//                 return (i) ? String(i) : null;
//             },
//             setState: (key, state) => {
//                 localStorage[ app_name + '_' + key] = state;
//             },
//         })
//     ]
// });
import CustomTable from "./CustomTable.vue";
import createPersistedState from 'vuex-persistedstate';
import {mapVuexModels, genVuexModels} from 'vuex-models';

// require("./main.css");
Vue.use(Vuex);
const featureModule = {
    namespaced: true,
    ...CustomTable.staticModels
};

const store = new Vuex.Store({
    plugins: [createPersistedState()],
    modules: {expertPage: featureModule},

});

// CustomTable.staticModels = staticModels;
// CustomTable.featureModule = featureModule;
CustomTable.store = store;


new Vue({
    el: "#test_app",
    data: {
        static_path: 'vendor/markup/'
    },
    render: (h) => h(CustomTable, {
        featureModule: featureModule,
        store,
        props: {
            initial_lines_on_page: 3,
            static_path: 'vendor/narkp_markup/build/',
            rows: [{
                'ID': '23',
                'NAME': 'Клиент',
                'get_subject_rf_label': 'Вологодская область',
                'get_org_type_name': 'Физическое лицо',
                'get_active_requests_count': '11',
                'get_total_requests_count': '13',
                'get_payed_sum': '999',
                'get_pay_wait_sum': '999'
            }, {
                'ID': '24',
                'NAME': 'Клиент Юридическое лицо',
                'get_subject_rf_label': 'Республика Коми',
                'get_org_type_name': 'Юридическое лицо',
                'get_active_requests_count': '1',
                'get_total_requests_count': '1',
                'get_payed_sum': '999',
                'get_pay_wait_sum': '999'
            }, {
                'ID': '39',
                'NAME': 'fdsfasd',
                'get_subject_rf_label': 'Краснодарский край',
                'get_org_type_name': 'Физическое лицо',
                'get_active_requests_count': '1',
                'get_total_requests_count': '1',
                'get_payed_sum': '999',
                'get_pay_wait_sum': '999'
            }, {
                'ID': '40',
                'NAME': 'Тестовая регистрация',
                'get_subject_rf_label': 'Краснодарский край',
                'get_org_type_name': 'Физическое лицо',
                'get_active_requests_count': '0',
                'get_total_requests_count': '0',
                'get_payed_sum': '999',
                'get_pay_wait_sum': '999'
            }, {
                'ID': '41',
                'NAME': 'Тестовая организация Юр',
                'get_subject_rf_label': 'Краснодарский край',
                'get_org_type_name': 'Юридическое лицо',
                'get_active_requests_count': '0',
                'get_total_requests_count': '0',
                'get_payed_sum': '999',
                'get_pay_wait_sum': '999'
            }],
            cols: {
                'ID': {'title': 'id', 'key': true},
                'NAME': {'title': 'ФИО / Ораганизация'},
                'get_org_type_name': {'title': 'Тип клиента'},
                'get_subject_rf_label': {'title': 'Субъект РФ'},
                'get_active_requests_count': {'title': 'активные заявки'},
                'get_total_requests_count': {'title': 'Общее кол-во заявок'},
                'get_payed_sum': {'title': 'Оплачено'},
                'get_pay_wait_sum': {'title': 'Ожидает оплаты'}
            },
            ///
            // initial_lines_on_page: 5,
            lines_on_page_options: [{'id': '0', 'value': '5'}, {
                'id': '1',
                'value': '10'
            }, {
                'id': '2',
                'value': '15'
            }, {'id': '3', 'value': '20'}, {
                'id': '4',
                'value': '30'
            }, {'id': '5', 'value': '100'}, {
                'id': '6',
                'value': '1000'
            }],
            lines_on_page_options: [1, 5, 10, 50, 100, 1000],
            ///
            active_count: 0,
            total_unfiltered_count: 5,
            // filterKey: "",
            open_link: "#",
            open_link__field_name: "ID",
            app_name: 'clients',
            uid: '5dadabe18a3e2',
            sortable_fields: {
                'ID': true,
                'NAME': false,
                'get_org_type_name': true,
                'get_subject_rf_label': true,
                'get_payed_sum': true,
                'get_pay_wait_sum': true
            },
            back_uri: '',
            //searchable_field_names: //,
            // filterable_field_names: ['get_org_type_name']

        }
    })
});
