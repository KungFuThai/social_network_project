<template>
  <v-row>
    <div class="v-col v-col-2">
      <v-scroll-y-transition>
        <div class="v-list bg-white">
          <v-list v-for="user in userList">
            <v-list-item link height="60px" @click="getMessage(parseInt(user.user_id))">
              <v-list v-for="info in user.info">
                <VAvatar>
                  <img
                      width="40"
                      height="50"
                      cover
                      :src="this.getImageSrc(info.avatar)"/>
                </VAvatar>
                <span class="ml-3 position-relative">
                {{ this.getFullName(info.last_name, info.first_name) }}
              </span>
              </v-list>
            </v-list-item>
          </v-list>
        </div>
      </v-scroll-y-transition>
    </div>
    <div class="v-col bg-lightgray" v-if="Object.keys(currentReceiver).length !== 0">
      <div class="v-col-12 w-100 bg-white">
        <v-row style="margin: 0 !important;">
          <v-avatar v-if="currentReceiver.avatar === null">
            <v-img
                width="40"
                height="50"
                cover
                src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"
            />
          </v-avatar>
          <v-avatar v-else>
            <img
                width="40"
                height="50"
                cover
                :src="'http://social_network_project.test/storage/'+ currentReceiver.avatar"
            />
          </v-avatar>
          <span class="pl-1">
              {{ this.getFullName(currentReceiver.last_name, currentReceiver.first_name) }}
            </span>
        </v-row>
      </div>
      <v-main style="height: 700px">
        <v-card class="overflow-y-auto fill-height" id="chat-box">
          <v-card-text v-for="mes in messages">
            <div class="d-flex flex-row bg-variant" v-if="mes.receiver_id != this.receiverId">
              <v-card max-width="50%" width="fit-content"
                      variant="tonal">
                <v-card-item>
                  <div>
                    <div class="text-overline">
                      {{ this.getFullName(currentReceiver.last_name, currentReceiver.first_name) }}
                    </div>
                    <div class="text-h6 mb-1">{{ mes.message }}</div>
                    <div class="text-muted">{{ formatDate(mes.created_at) }}</div>
                  </div>
                </v-card-item>
              </v-card>
            </div>

            <div class="d-flex flex-row-reverse bg-variant" v-else>
              <v-card max-width="50%" width="fit-content" variant="tonal">
                <v-card-item>
                  <div>
                    <div class="text-overline">
                      {{ this.getFullName(currentUser.last_name, currentUser.first_name) }}
                    </div>
                    <div class="text-h6 mb-1">{{ mes.message }}</div>
                    <div class="text-muted">{{ formatDate(mes.created_at) }}</div>
                  </div>
                </v-card-item>
              </v-card>
            </div>
          </v-card-text>
        </v-card>
      </v-main>
      <VTextField label="Aa" type="text" variant="outlined" v-model="message"
                  @keyup.enter="sendMessage" append-icon="mdi-send"
                  @click:append="sendMessage"></VTextField>

    </div>
    <div class="v-col bg-lightgray" v-else>
      <v-carousel height="840">
        <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
            cover
        ></v-carousel-item>

        <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/hotel.jpg"
            cover
        ></v-carousel-item>

        <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
            cover
        ></v-carousel-item>
      </v-carousel>
    </div>
  </v-row>
</template>

<script>
import {io} from 'socket.io-client';
import moment from 'moment';

export default {
  props: {
    currentUser: Object,
    token: String,
  },
  name: "ChatLayout",
  data() {
    return {
      message: "",
      receiverId: null,
      userList: {},
      // token: localStorage.getItem('token'),
      messages: [],
      socket: null,
      currentReceiver: {},
    }
  },
  mounted() {
    this.socket = io('http://localhost:3000');
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.$axios.post('/api/messages')
        .then(response => {
          this.userList = response.data.data.userList
        })
        .catch(function (error) {
          console.error(error);
        })

    this.socket.on('connect', () => {
      this.socket.emit('user_connected', this.currentUser.id);
    })

    this.socket.on("private-channel:App\\Events\\SendMessage", (message) => {
      this.messages.push(message)
    });

  },
  watch: {
    messages: {
      handler(newValue, oldValue) {
        setTimeout(() => {
          this.scrollToBottomOfCard()
        }, 1);
      },
      deep: true
    }
  },
  methods: {
    getFullName(lastName, firstName) {
      return lastName + " " + firstName
    },
    async sendMessage() {
      if (this.message === '') return;
      if (this.receiverId === null) return;
      await axios.post(`api/messages/store/${this.receiverId}`, {
        message: this.message
      })
          .then((response) => {
            this.messages.push(response.data.data)
            this.message = '';
          });
    },
    async getMessage(receiverId) {
      this.receiverId = receiverId;
      await axios.post(`/api/messages/${receiverId}`)
          .then(response => {
            this.messages = response.data.data.messages;
          })
      await this.$nextTick(() => {
        this.getCurrentReceiver()
      })
      await this.$nextTick(() => {
        this.scrollToBottomOfCard();
      });
    },
    getCurrentReceiver() {
      this.$axios.post(`api/messages/current_receiver/${this.receiverId}`)
          .then(response => {
            this.currentReceiver = response.data.data
          })
    },
    scrollToBottomOfCard() {
      console.log('Scrolling to bottom...');
      let element = document.querySelector('#chat-box');
      if (element) {
        element.scrollTo({
          top: element.scrollHeight,
          behavior: 'smooth'
        });
      }
    },
    formatDate(value) {
      return moment(String(value)).format('hh:mm DD/MM/YYYY')
    },
    getImageSrc(image){
      if(image === null || image === ''){
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return 'http://social_network_project.test/storage/'+ image
    }
  },
}
</script>

<style scoped>
</style>