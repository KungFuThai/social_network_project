<template>
  <VApp>
    <Header></Header>
    <VMain>
      <router-view></router-view>
    </VMain>
  </VApp>
</template>

<script>
import Header from "./components/Header.vue";
import MainNav from "./components/MainNav.vue";
import Echo from "laravel-echo";

export default {
  name: "App",
  data(){
    return {
      isLoggedIn: false,
    }
  },
  created() {
    if (window.Laravel.isLoggedIn){
      this.isLoggedIn = true;
    }
  },
  mounted() {
    let echo = new Echo({
      broadcaster: 'socket.io',
      host: 'http://seekers-backend.com:6001',
      auth: { headers: { Authorization: this.token } }
    })
    // echo.private('user.' + this.$auth.user.id)
    //     .listen('MessageEvent', (e) => {
    //       // Do stuff
    //     })
  },
  methods: {
    logout(e){
      e.preventDefault()
      this.$axios.get('/sanctum/csrf-cookie').then(response => {
        this.$axios.post('/api/logout')
        .then(response => {
          if(response.data.success){
            window.location.href = '/'
          }else{
            console.log(response);
          }
        })
        .catch(function (error) {
          console.error(error);
        })
      })
    }
  },
  components: {
    Header,
    MainNav
  }
};
</script>