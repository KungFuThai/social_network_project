<template>
  <div class="d-flex align-center flex-column mt-2 position-relative">
    <v-card
        width="60%"
        position="absolute"
    >
      <v-card>
        <v-card class="mx-auto" tile :elevation="2">
          <v-img height="400" cover :src="getImageSrc(info.cover_image)"></v-img>
          <v-row class="pb-10">
            <v-col cols="6" class="d-flex">
              <v-avatar
                  size="150"
                  rounded="100"
                  style="position:absolute; bottom: 0"
              >
                <v-img cover :src="getImageSrc(info.avatar)"></v-img>
              </v-avatar>
              <v-list-item color="black" style="left: 130px">
                <v-list-item>
                  <v-list-item-title class="title text-h4">{{
                      getFullName(info.last_name, info.first_name)
                    }}
                  </v-list-item-title>
                </v-list-item>
              </v-list-item>
            </v-col>
            <v-col cols="6" class="d-flex align-center justify-end" v-if="id == currentUser.id">
              <v-col cols="4" class="d-flex align-center justify-end">
                <v-dialog
                    transition="dialog-top-transition"
                    scrollable
                    width="500"
                    height="600"
                >
                  <template v-slot:activator="{ props }">
                    <VBtn dark depressed color="lightblue" variant="tonal" class="text-none mr-5" v-bind="props"
                          @click="getInfo()">
                      <v-icon>mdi-account-edit</v-icon>
                      Chỉnh sửa
                    </VBtn>
                  </template>
                  <template v-slot:default="{ isActive }">
                    <v-card>
                      <v-toolbar
                          color="lightpink"
                          title="Chỉnh sửa thông tin"
                      ></v-toolbar>
                      <v-card-text>
                        <v-form enctype="multipart/form-data">
                          <v-container>
                            <v-row>
                              <v-col
                                  cols="12"
                                  md="6"
                              >
                                <v-text-field
                                    v-model="formData.last_name"
                                    :counter="10"
                                    label="Họ và lót"
                                    required
                                    :error-messages="errors.last_name"
                                    hide-details="auto"
                                ></v-text-field>
                              </v-col>

                              <v-col
                                  cols="12"
                                  md="6"
                              >
                                <v-text-field
                                    v-model="formData.first_name"
                                    :counter="10"
                                    label="Tên"
                                    :error-messages="errors.first_name"
                                    hide-details="auto"
                                    required
                                ></v-text-field>
                              </v-col>

                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-row>
                                  <v-file-input v-on:change="onFileAvatarChange" label="Ảnh đại diện"
                                                :error-messages="errors.avatar"></v-file-input>
                                  <div class="col-6" v-if="avatarImage">
                                    <img :src="avatarImage" class="img-responsive" height="70" width="90">
                                  </div>
                                </v-row>
                              </v-col>
                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-row>
                                  <v-file-input v-on:change="onFileCoverChange" label="Ảnh bìa"
                                                :error-messages="errors.cover_image"></v-file-input>
                                  <div class="col-6" v-if="coverImage">
                                    <img :src="coverImage" class="img-responsive" height="70" width="90">
                                  </div>
                                </v-row>
                              </v-col>
                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-radio-group inline label="Giới tính" v-model="formData.gender"
                                               :error-messages="errors.gender">
                                  <v-radio label="Nam" :value="0"></v-radio>
                                  <v-radio label="Nữ" :value="1"></v-radio>
                                </v-radio-group>
                              </v-col>
                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-text-field label="Số điện thoại" v-model="formData.phone"
                                              :error-messages="errors.phone"></v-text-field>
                              </v-col>
                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-text-field label="Ngày sinh" type="date" :error-messages="errors.birth_date"
                                              v-model="formData.birth_date"></v-text-field>
                              </v-col>
                              <v-col
                                  cols="12"
                                  md="12"
                              >
                                <v-text-field label="Địa chỉ" type="text" :error-messages="errors.address"
                                              v-model="formData.address"></v-text-field>
                              </v-col>
                              <v-col>
                                <div class="d-flex justify-center">
                                  <v-btn color="blue" @click="updateProfile" @keyup.enter=updateProfile
                                         prepend-icon="mdi-account-edit">
                                    Chỉnh sửa
                                  </v-btn>
                                </div>
                              </v-col>

                            </v-row>
                          </v-container>
                        </v-form>
                      </v-card-text>
                      <v-card-actions class="justify-end">
                        <v-btn
                            variant="text"
                            @click="isActive.value = false"
                        >Close
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </template>
                </v-dialog>
              </v-col>
              <v-col cols="4" class="d-flex align-center justify-end">
                <v-dialog
                    transition="dialog-bottom-transition"
                    scrollable
                    width="500"
                    height="700"
                >
                  <template v-slot:activator="{ props }">
                    <v-btn color="red" :elevation="1" rounded v-bind="props" @click="getTrashBin">
                      <v-icon>mdi-delete</v-icon>
                      Thùng Rác
                    </v-btn>
                  </template>
                  <template v-slot:default="{ isActive }">
                    <v-card>
                      <v-toolbar
                          color="lightpink"
                          title="Bài đăng đã xoá"
                      ></v-toolbar>
                      <v-card-text>
                        <v-list lines="one">
                          <v-list-item
                              v-for="(trashPost,index)  in trashPosts"
                              class="mb-2"
                              rounded="lg"
                              :elevation="1"
                              :prepend-avatar="trashPost.image == null ? null : getImage(trashPost.image)"
                              link
                          >
                        <span>
                          {{ trashPost.content }}
                        </span>
                            <template v-slot:append>
                              <v-btn
                                  color="cyan"
                                  rounded
                                  @click="restorePost(trashPost.id)"
                              >
                                <v-icon>
                                  mdi-restore
                                </v-icon>
                              </v-btn>
                              <v-btn
                                  color="red"
                                  rounded
                                  @click="forceDeletePost(trashPost.id)"
                              >
                                <v-icon>
                                  mdi-delete
                                </v-icon>
                              </v-btn>
                            </template>
                          </v-list-item>
                        </v-list>
                      </v-card-text>
                      <v-card-actions class="justify-end">
                        <v-btn
                            variant="text"
                            @click="isActive.value = false"
                        >
                          Đóng
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </template>
                </v-dialog>
              </v-col>
            </v-col>
            <v-col cols="6" class="d-flex align-center justify-end" v-else>
              <v-col cols="4" class="d-flex align-center justify-end" v-if="checkFriend"
                     @click="reject(id)">
                <v-btn color="lightgray" :elevation="1">
                  <v-icon>mdi-account-check</v-icon>
                  Bạn bè
                </v-btn>
              </v-col>
              <v-col cols="4" class="d-flex align-center justify-end" v-else>
                <v-btn color="lightblue" :elevation="1" v-if="checkRequest" @click="accept(id)">
                  <v-icon>mdi-check</v-icon>
                  Chấp nhận
                </v-btn>
                <v-btn color="lightgray" :elevation="1" v-else-if="checkSent"
                       @click="removeFriendRequest(id)">
                  <v-icon>mdi-send</v-icon>
                  Đã gửi
                </v-btn>
                <v-btn color="lightgreen" :elevation="1" v-else-if="checkSend" @click="sendRequest(id)">
                  <v-icon>mdi-account-plus</v-icon>
                  Thêm bạn bè
                </v-btn>
              </v-col>
              <v-col cols="4" class="d-flex align-center justify-end">
                <v-btn color="lightblue" :elevation="1" @click="toMessage(id)">
                  <v-icon>mdi-facebook-messenger</v-icon>
                  Nhắn tin
                </v-btn>
              </v-col>
            </v-col>
          </v-row>
        </v-card>
        <v-divider></v-divider>
        <v-card>
          <v-container>
            <v-row>
              <v-col cols="5">
                <v-card :elevation="2" class="mb-2">
                  <v-card-item>
                    <div>
                      <div class="text-overline mb-1">
                        Giới thiệu
                      </div>
                    </div>
                    <div>
                      {{ formatDate(info.birth_date) }}
                    </div>
                    <div>
                      {{ getGender(info.gender) }}
                    </div>
                    <div>
                      {{ getAddress(info.address) }}
                    </div>
                  </v-card-item>
                </v-card>
                <v-card :elevation="2" height="500" class="overflow-y-auto">
                  <v-card-item>
                    <div>
                      <div class="text-overline mb-1">
                        Bạn bè
                      </div>
                    </div>
                    <v-row v-if="friends">
                      <v-col
                          v-for="(friend,index) in friends"
                          class="d-flex child-flex"
                          cols="4"
                          position="relative"
                      >
                        <v-img
                            @click="toProfile(friend.id)"
                            :src="getImageSrc(friend.avatar)"
                            aspect-ratio="1"
                            cover
                            class="bg-grey-lighten-2"
                        >
                          <div>
                            <v-card
                                :rounded="0"
                                color="rgba(240, 240, 240, 0.9)"
                                position="absolute"
                                class="w-100" style="bottom: 0; height: 20px;">
                              <v-card-subtitle>
                                <b>
                                  {{ getFullName(friend.last_name, friend.first_name) }}
                                </b>
                              </v-card-subtitle>
                            </v-card>
                          </div>
                        </v-img>
                      </v-col>
                    </v-row>
                    <v-row v-else>
                      <div>Người này không có bạn</div>
                    </v-row>

                  </v-card-item>
                </v-card>
              </v-col>
              <v-col cols="7" v-if="posts" class="overflow-y-auto">
                <PostSection :profilePosts="this.posts" :currentUser="this.currentUser"></PostSection>
              </v-col>
              <v-col cols="7" v-else>
                Không có bài đăng nào
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-card>
    </v-card>
  </div>
  <v-snackbar
      v-if="notification !== ''"
      v-model="snackbar"
      :timeout=2000
      color="green"
  >
    {{ notification }}
  </v-snackbar>
</template>

<script>
import moment from "moment"
import PostSection from "../components/PostSection.vue";

export default {
  name: "ProfilePage.vue",
  components: {PostSection},
  props: {
    currentUser: Object,
    token: String,
    id: String,
  },
  watch: {
    id: {
      handler(newValue, oldValue) {
        setTimeout(() => {
          window.location.reload();
        }, 1);
      },
      deep: true
    },
  },
  data() {
    return {
      snackbar: false,
      notification: '',
      friends: {},
      info: {},
      posts: {},
      friendRequests: {},
      requestFriends: {},
      checkFriend: Boolean,
      checkSent: Boolean,
      checkSend: Boolean,
      checkRequest: Boolean,
      userInfo: {},
      formData: {
        last_name: '',
        first_name: '',
        avatar: null,
        gender: '',
        cover_image: null,
        birth_date: '',
        address: '',
        phone: '',
      },
      errors: {
        last_name: '',
        first_name: '',
        avatar: '',
        gender: '',
        cover_image: '',
        birth_date: '',
        address: '',
        phone: '',
      },
      avatarImage: null,
      coverImage: null,
      trashPosts: {},
    }
  },
  created() {

  },
  mounted() {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    this.getProfile(this.id)
  },
  methods: {
    async getProfile(id) {
      await axios.get(`/api/profile/${id}`)
          .then((response) => {
            this.friends = response.data.data.friends
            this.posts = response.data.data.posts
            this.info = response.data.data.info
            this.requestFriends = response.data.data.requestFriends
            this.friendRequests = response.data.data.friendRequests
            this.isFriendWithCurrentUser()
            this.hasSentFriendRequest()
            this.hasSentFriendRequestToMe()
          })
          .catch((error) => {
            // console.log(error)
            this.$router.push('/')
          });
    },
    getImage(image) {
      return 'http://social_network_project.test/storage/' + image
    },
    getFullName(lastName, firstName) {
      return lastName + " " + firstName;
    },
    getImageSrc(image) {
      if (image === null || image === '') {
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return 'http://social_network_project.test/storage/' + image
    },
    formatDate(value) {
      if (value) {
        return moment(String(value)).format('DD/MM/YYYY')
      }
      return "Không có thông tin"
    },
    getAddress(address) {
      if (address !== null) {
        return address
      }
      return 'Không có thông tin'
    },
    getGender(gender) {
      if (gender !== null) {
        return gender === 0 ? 'Nam' : 'Nữ'
      }
      return 'Không có thông tin'
    },
    isFriendWithCurrentUser() {
      for (let key in this.friends) {
        if (this.friends.hasOwnProperty(key)) {
          const friend = this.friends[key];
          if (friend.pivot.friend_id === this.currentUser.id || friend.pivot.user_id === this.currentUser.id) {
            return this.checkFriend = true;
          }
        }
      }
      return this.checkFriend = false;
    },
    hasSentFriendRequest() {
      for (let key in this.friendRequests) {
        if (this.friendRequests.hasOwnProperty(key)) {
          const friendRequest = this.friendRequests[key];
          if (friendRequest.pivot.friend_id === this.currentUser.id) {
            return this.checkSent = true;
          }
        }
      }
      return this.checkSent = false;
    },
    hasSentFriendRequestToMe() {
      for (let key in this.requestFriends) {
        if (this.requestFriends.hasOwnProperty(key)) {
          const requestFriend = this.requestFriends[key];
          if (requestFriend.pivot.user_id === this.currentUser.id) {
            return this.checkRequest = true;
          }
        }
      }
      return this.checkRequest = false;
    },
    async sendRequest(id) {
      await axios.post(`/api/send-request-to/${id}`)
          .then((response) => {
            this.checkSent = true
            this.checkSend = false
            this.notification = response.data.message;
            this.snackbar = true;
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async accept(id) {
      await axios.post(`/api/accept/${id}`)
          .then((response) => {
            this.checkFriend = true
            this.notification = response.data.message;
            this.snackbar = true;
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async reject(id) {
      await axios.post(`/api/reject/${id}`)
          .then((response) => {
            this.checkSend = true
            this.checkFriend = false
            this.notification = response.data.message;
            this.snackbar = true;
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async removeFriendRequest(id) {
      await axios.post(`/api/remove/${id}`)
          .then((response) => {
            this.checkSent = false;
            this.checkSend = true;
            this.notification = response.data.message;
            this.snackbar = true;
          })
          .catch((error) => {
            console.log(error)
          });
    },
    toProfile(id) {
      this.$router.push(`${id}`)
    },
    toMessage(id) {
      this.$router.push(`/messages/${id}`)
    },
    getInfo() {
      this.formData.last_name = this.info.last_name;
      this.formData.first_name = this.info.first_name;
      this.formData.gender = this.info.gender;
      this.formData.birth_date = this.info.birth_date;
      this.formData.address = this.info.address;
      this.formData.phone = this.info.phone;
    },
    onFileAvatarChange(e) {
      const file = e.target.files[0];
      this.formData.avatar = file
      this.avatarImage = URL.createObjectURL(file);
    },
    onFileCoverChange(e) {
      const file = e.target.files[0];
      this.formData.cover_image = file
      this.coverImage = URL.createObjectURL(file);
    },
    updateProfile() {
      const formData = new FormData();
      if (this.formData.cover_image != null) {
        formData.append('avatar', this.formData.avatar, this.formData.avatar.name);
      }
      if (this.formData.avatar != null) {
        formData.append('cover_image', this.formData.cover_image, this.formData.cover_image.name);
      }
      formData.append('last_name', this.formData.last_name);
      formData.append('first_name', this.formData.first_name);
      formData.append('address', this.formData.address);
      formData.append('phone', this.formData.phone);
      formData.append('gender', this.formData.gender);
      formData.append('birth_date', this.formData.birth_date);
      axios.post('/api/profile/user/update', formData)
          .then((response) => {
            this.info = response.data.data
          })
          .catch((errors) => {
            this.errors = errors.response.data.errors
          })
    },
    async getTrashBin() {
      await axios.get(`/api/trash-bin`)
          .then((response) => {
            this.trashPosts = response.data.data
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async restorePost(id){
      await axios.post(`/api/trash-bin/restore/${id}`)
          .then((response) => {
            window.location.reload()
          })
          .catch((error) => {
            console.log(error)
          });
    },
    async forceDeletePost(id){
      await axios.delete(`/api/trash-bin/${id}`)
          .then((response) => {
            window.location.reload()
          })
          .catch((error) => {
            console.log(error)
          });
    },
    //next function
  }
}
</script>

<style scoped>

</style>