import Vue from "vue";
import Custom from "./CustomMultiselectField.vue";
// require("./css/main.css");
// require("vendor/vendor.js");
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate);

const vue = new Vue({
    component: {
        Custom,
        // Vuelidate: window.vuelidate.default
    },
    el: "#test_app",
    render: (h) => h(Custom)
});
