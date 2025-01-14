import Vue from "vue";
import App from "./App.vue";

import Vuesax from "vuesax";
import "material-icons/iconfont/material-icons.css"; //Material Icons
import "vuesax/dist/vuesax.css"; // Vuesax
Vue.use(Vuesax);

// axios
import axios from "./axios.js";
Vue.prototype.$http = axios;

// API Calls
import "./http/requests";

// Theme Configurations
import "../themeConfig.js";

// Globally Registered Components
import "./globalComponents.js";

// Styles: SCSS
import "./assets/scss/main.scss";

// Tailwind
import "@/assets/css/main.css";

// Vue Router
import router from "./router";

// Vuex Store
import store from "./store/store";

// i18n
import i18n from "./i18n/i18n";

// Vuexy Admin Filters
import "./filters/filters";

// VeeValidate
import VeeValidate from "vee-validate";
Vue.use(VeeValidate);

// Google Maps
import * as VueGoogleMaps from "vue2-google-maps";
Vue.use(VueGoogleMaps, {
  load: {
    // Add your API key here
    key: "AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y",
    libraries: "places", // This is required if you use the Auto complete plug-in
  },
});

// Vuejs - Vue wrapper for hammerjs
import { VueHammer } from "vue2-hammer";
Vue.use(VueHammer);

// Feather font icon
require("./assets/css/iconfont.css");

// Vue select css
// Note: In latest version you have to add it separately
// import 'vue-select/dist/vue-select.css';

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  i18n,
  render: (h) => h(App),
}).$mount("#app");



