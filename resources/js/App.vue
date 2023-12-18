<template>
  <VApp>
    <router-view></router-view>
  </VApp>
</template>

<script>
import Header from "./components/Header.vue";
import MainNav from "./components/MainNav.vue";
import { storeToRefs } from "pinia";
// import { useAuthStore } from "./stores/useAuth.js";

export default {
  name: "App",
  created() {

  },
  mounted() {

  },
  methods: {

  },
  components: {
    Header,
    MainNav
  }
};
</script>