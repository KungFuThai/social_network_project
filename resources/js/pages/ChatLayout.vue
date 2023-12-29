<template>
  <v-row>
    <div class="v-col v-col-2">
      <v-scroll-y-transition>
        <div class="v-list bg-white">
          <v-list v-for="user in userList">
            <v-list-item link height="60px" @click="setReceiver(user.user_id)">
              <!--            <v-list-item link height="60px" @click="getMessage(parseInt(user.user_id))">-->
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
                :src="getImage(currentReceiver.avatar)"
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
              <v-card max-width="50%" width="fit-content" variant="tonal" color="lightblue">
                <v-card-item>
                  <div class="text-black">
                    <div class="text-overline">
                      {{ this.getFullName(currUser.last_name, currUser.first_name) }}
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
    currentUser: {
      type: Object,
    },
    token: String,
    id: String,
  },
  name: "ChatLayout",
  data() {
    return {
      message: "",
      receiverId: Number,
      userList: {},
      // token: localStorage.getItem('token'),
      messages: [],
      socket: null,
      currentReceiver: {},
      currUser: {},
    }
  },
  created() {
      this.currUser = this.currentUser
  },
  mounted() {
    this.receiverId = this.id
    // this.socket = io('http://localhost:3000');
    this.socket = io('https://4ead-14-237-246-92.ngrok-free.app/');
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.$axios.post('/api/messages')
        .then(response => {
          this.userList = response.data.data.userList
        })
        .catch(function (error) {
          console.error(error);
        })

    this.socket.on('connect', () => {
      this.socket.emit('user_connected', this.currUser.id);
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
    },
    receiverId: {
      handler(newValue, oldValue) {
        setTimeout(() => {
          this.getMessage(this.id)
        }, 1000)
      },
      deep: true
    },
    id: {
      handler(newValue, oldValue) {
        this.getMessage(this.id)
      },
      deep: true
    },
  },
  methods: {
    async getAuth() {
      try {
        const response = await axios.get('/api/get-auth');
        this.currUser = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
    getFullName(lastName, firstName) {
      return lastName + " " + firstName
    },
    async sendMessage() {
      if (this.message === '') return;
      if (this.receiverId === null) return;
      await axios.post(`/api/messages/store/${this.receiverId}`, {
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
            this.currentReceiver = response.data.data.receiver
          })
      await this.$nextTick(() => {
        this.scrollToBottomOfCard();
      }).catch((error) => {
        $this.router.push('/')
      });
    },
    scrollToBottomOfCard() {
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
    getImageSrc(image) {
      if (image === null || image === '') {
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return '/storage/' + image
    },
    setReceiver(receiverId) {
      this.$router.push(`/messages/${receiverId}`)
    },
    getImage(image) {
      return '/storage/' + image
    },
  },
}
</script>

<style scoped>
</style>