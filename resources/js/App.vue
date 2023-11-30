<template>
  <VApp>
    <router-view></router-view>
  </VApp>
</template>

<script>
import Header from "./components/Header.vue";
import MainNav from "./components/MainNav.vue";
// import { storeToRefs } from "pinia";
// import { useAuthStore } from "./stores/useAuth";

export default {
  // setup(){
  //   const store = useAuthStore();
  //
  //   return {
  //     ...storeToRefs(store)
  //   }
  // },
  name: "App",
  created() {

  },
  mounted() {

  },
  methods: {
    // logout(e) {
    //   e.preventDefault()
    //   this.$axios.get('/sanctum/csrf-cookie').then(response => {
    //     this.$axios.post('/api/logout')
    //         .then(response => {
    //           if (response.data.success) {
    //             window.location.href = '/'
    //           } else {
    //             console.log(response);
    //           }
    //         })
    //         .catch(function (error) {
    //           console.error(error);
    //         })
    //   })
    // }
  },
  components: {
    Header,
    MainNav
  }
};
</script>