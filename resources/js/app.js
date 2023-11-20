import './bootstrap';

import {createApp} from "vue";
import App from "./App.vue";
import vuetify from "./vuetify";
import axios from "axios";
import router from "./router";

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.use(router)
app.use(vuetify)

app.mount("#app");