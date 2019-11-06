import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import {mapVuexModels, genVuexModels} from 'vuex-models';


import CustomTable from './CustomTable.vue';

// Объявление функции установки, выполняемой Vue.use()
export function install(Vue) {
    if (install.installed) return;
    install.installed = true;


// }, 'xx');

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

    Vue.component('custom-table', CustomTable);
}

// Создание значения модуля для Vue.use()
const plugin = {
    install
};

// Автоматическая установка, когда vue найден (например в браузере с помощью тега <script>)
let GlobalVue = null;
if (typeof window !== 'undefined') {
    GlobalVue = window.Vue;
} else if (typeof global !== 'undefined') {
    GlobalVue = global.Vue;
}
if (GlobalVue) {
    GlobalVue.use(plugin);
    // if (!GlobalVue.$store) {
    // console.log("install vuex...");
    // GlobalVue.use(Vuex);
    // }
}

// Экспорт компонента, для использования в качестве модуля (npm/webpack/etc.)
export default CustomTable;
