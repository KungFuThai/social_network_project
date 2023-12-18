<template>
  <Header :currentUser="currentUser" :token="token"></Header>
  <VMain>
    <router-view :currentUser="currentUser" :token="token"></router-view>
  </VMain>

</template>

<script>

import Header from "../components/Header.vue";
import {io} from "socket.io-client";
import ChatLayout from "./ChatLayout.vue";

export default {
  name: "Home.vue",
  data() {
    return {
      currentUser: {},
      token: localStorage.getItem('token'),
      userStatus: '',
      socket: null,
      isConnected: false,
    }
  },
  created() {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.getAuth()
  },
  mounted() {
    this.socket = io('http://localhost:3000');
    // //
    // this.socket.on('connect', () => {
    //   this.socket.emit('user_connected', this.currentUser.id);
    // })
    // this.socket.on('updateUserStatus', (data) => {
    //   this.userStatus = data.status;
    // })
    // this.socket.on("private-channel:App\\Events\\SendMessage", function (message)
    // {
    //   console.log(message);
    // });

  },
  methods: {
    async getAuth() {
      try {
        const response = await axios.get('/api/get-auth');
        this.currentUser = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
  components: {
    ChatLayout,
    Header,
  },
}
</script>

<style scoped>

</style>