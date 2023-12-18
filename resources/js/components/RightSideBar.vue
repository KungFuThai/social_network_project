<template>
  <VNavigationDrawer floating width="350" location="right">
    <VListItem title="Lời mời kết bạn"></VListItem>
    <VDivider></VDivider>
    <v-card class="overflow-y-auto" height="40%" :elevation="0">
      <VListItem
          :elevation="1"
          rounded="lg"
          v-if="Object.keys(friendRequests).length !== 0" v-for="(friendRequest, index) in friendRequests"
      >
        <div class="d-flex justify-space-between align-center">
          <VBtn link variant="text">
            <VAvatar size="30" class="mr-1">
              <img
                  width="40"
                  height="50"
                  cover
                  :src="this.getImageSrc(friendRequest.avatar)"/>
            </VAvatar>
            <b class="text-none">{{ getFullName(friendRequest.last_name, friendRequest.first_name) }}</b>
          </VBtn>
          <div class="d-flex justify-space-between">
            <VBtn icon="mdi-check" variant="plain" @click="accept(friendRequest.id,index)"></VBtn>
            <VBtn icon="mdi-close" variant="plain" @click="reject(friendRequest.id,index)"></VBtn>
          </div>
        </div>
      </VListItem>
      <v-list-item v-else>
        <div class="d-flex justify-space-between align-center">
          Không có gì cả lời mời kết bạn nào
        </div>
      </v-list-item>
    </v-card>
    <VListItem title="Gợi ý kết bạn"></VListItem>
    <VDivider></VDivider>
    <v-card height="40%" :elevation="0">
      <VListItem v-for="(suggestFriend, index) in suggestFriends">
        <div class="d-flex justify-space-between align-center">
          <VBtn link variant="text">
            <VAvatar size="30" class="mr-1">
              <img
                  :src="this.getImageSrc(suggestFriend.avatar)"/>
            </VAvatar>
            <b class="text-none">{{ getFullName(suggestFriend.last_name, suggestFriend.first_name) }}</b>
          </VBtn>
          <VBtn icon="mdi-plus" variant="plain" @click="sendRequest(suggestFriend.id, index)"></VBtn>
        </div>
      </VListItem>
    </v-card>
  </VNavigationDrawer>
  <v-snackbar
      v-model="snackbar"
      :timeout=2000
      color="green"
  >
    {{ notification }}
  </v-snackbar>
</template>

<script>
export default {
  name: "RightSideBar.vue",
  props: {
    token: String,
  },
  data() {
    return {
      snackbar: false,
      friendRequests: {},
      suggestFriends: {},
      notification: '',
    }
  },
  created() {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.getFriendRequests()
    this.getFriendSuggests()
  },
  mounted() {

  },
  methods: {
    async getFriendRequests() {
      await axios.get(`/api/friend-request`)
          .then((response) => {
            this.friendRequests = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async getFriendSuggests() {
      await axios.get(`/api/suggest-friends`)
          .then((response) => {
            this.suggestFriends = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    getFullName(lastName, firstName) {
      return lastName + " " + firstName;
    },
    getImageSrc(image) {
      if (image === null || image === '') {
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return '/storage/' + image
    },
    async sendRequest(id, index) {
      await axios.post(`/api/send-request-to/${id}`)
          .then((response) => {
            this.notification = response.data.message;
            this.snackbar = true;
            this.suggestFriends.splice(index, 1);
          })
          .catch((error) => {
            if (error.response.data.success === false) {
              this.message = error.response.data.message
              this.snackbar = true;
            }
            console.log(error)
          });
    },
    async accept(id, index) {
      await axios.post(`/api/accept/${id}`)
          .then((response) => {
            this.notification = response.data.message;
            this.snackbar = true;
            this.friendRequests.splice(index, 1);
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async reject(id, index) {
      await axios.post(`/api/reject/${id}`)
          .then((response) => {
            this.notification = response.data.message;
            this.snackbar = true;
            this.friendRequests.splice(index, 1);
          })
          .catch((error) => {
            console.log(error)
          });
    },
  },
}
</script>

<style scoped>

</style>