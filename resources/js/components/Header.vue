<template>
  <VAppBar floating app clipped-right color="white" clipped>
    <div class="ml-2 v-col-2">
      <router-link to="/">
        <v-btn class="text-center text-h5 text-pink">
          FakeBok
        </v-btn>
      </router-link>

    </div>
    <VCard flat color="lighten-5" outlined width="300px" class="mt-2 ml-4 mx-auto h-100">
      <VAutocomplete
          :value="searchTerm"
          @input="handleInput"
          clearable
          no-filter
          variant="solo-filled"
          prepend-inner-icon="mdi-magnify"
          label="Tìm kiếm"
          hide-no-data
          :items="users"
          return-object
      >
        <template v-slot:item="{ iprops, item }">
          <v-list-item
              link
              :to="`/profile/${item?.raw?.id}`"
              v-bind="iprops"
              :prepend-avatar="getImageSrc(item?.raw?.avatar)"
              :title="item?.raw?.last_name + ' ' + item.raw?.first_name"
          ></v-list-item>
        </template>
      </VAutocomplete>
    </VCard>
    <VSpacer></VSpacer>
    <v-dialog
        transition="dialog-top-transition"
        scrollable
        width="600"
        height="500"
    >
      <template v-slot:activator="{ props }">
        <VBtn dark depressed color="#e45364" variant="tonal" class="text-none mr-5" v-bind="props">
        <span class="mr-2">
          <VIcon class="mr-2">mdi-plus</VIcon>Tạo bài đăng mới
        </span>
        </VBtn>
      </template>
      <template v-slot:default="{ isActive }">
        <v-card>
          <v-toolbar
              color="lightpink"
              title="Tạo bài đăng mới"
          ></v-toolbar>
          <v-card-text>
            <v-form enctype="multipart/form-data">
              <v-container>
                <v-row>
                  <v-col
                      cols="12"
                      md="12"
                  >
                    <v-textarea
                        label="Nội dung bài đăng"
                        hide-details="auto"
                        required
                        v-model="formData.content"
                    ></v-textarea>
                  </v-col>

                  <v-col
                      cols="12"
                      md="12"
                  >
                    <v-row>
                      <v-file-input label="Ảnh của bài đăng" @change="onFileImageChange"
                                    :error-messages="errors.image"></v-file-input>
                    </v-row>
                    <v-row class="d-flex justify-center" v-if="postImage">
                      <img class="img-responsive" :src="postImage" height="200" width="200">
                    </v-row>
                  </v-col>
                  <v-col
                      cols="12"
                      md="12"
                  >
                    <v-radio-group inline label="Trạng thái bài đăng"
                                   v-model="formData.status"
                                   :error-messages="errors.status">
                      <v-radio label="Ẩn" :value="0"></v-radio>
                      <v-radio label="Hiện" :value="1"></v-radio>
                    </v-radio-group>
                  </v-col>
                  <v-col>
                    <div class="d-flex justify-center">
                      <v-btn color="blue"
                             prepend-icon="mdi-plus" @click="createPost" @keyup.enter="createPost">
                        Tạo bài đăng
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
    <div icon class="text-center" style="width: 100px">
      <span class="mr-2">
      {{ currentUser.first_name }}
    </span>
    </div>

    <div class="text-center">
      <v-menu location="bottom">
        <template v-slot:activator="{ props }">
          <VAvatar size="40" v-bind="props">
            <img
                width="40"
                height="40"
                cover
                :src="getImageSrc(currentUser.avatar)"
            />
          </VAvatar>
          <VIcon v-bind="props">mdi-menu-down</VIcon>
        </template>

        <v-list>
          <v-list-item link :to="`/profile/${currentUser.id}`">
            <v-icon>mdi-account</v-icon>
            Trang cá nhân
          </v-list-item>
          <v-list-item @click="logout">
            <v-icon color="red">mdi-logout</v-icon>
            Đăng xuất
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
  </VAppBar>
  <v-snackbar
      v-if="message !== ''"
      v-model="snackbar"
      :timeout=2000
      color="green"
  >
    {{ message }}
  </v-snackbar>
</template>

<script>

export default {
  props: {
    currentUser: Object,
    token: String
  },
  data() {
    return {
      snackbar: false,
      message: '',
      users: [],
      searchTerm: "",
      formData: {
        content: '',
        image: null,
        status: 1,
      },
      errors: {
        content: '',
        image: '',
        status: '',
      },
      postImage: '',
    }
  },
  created() {
  },
  watch: {
    searchTerm: function (newVal, oldVal) {
      console.log(this.searchTerm)
      setTimeout(() => {
        this.performSearch();
      }, 500)
    },
  },
  name: "Header.vue",
  methods: {
    handleInput(event) {
      this.searchTerm = event.target.value;
    },
    logout() {
      axios.post('/api/logout').then((response) => {
        localStorage.removeItem('token')
        this.$router.push('/login')
      }).catch((errors) => {
        console.log(errors)
      })
    },
    getImageSrc(image) {
      if (image === null || image === '') {
        return 'https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
      }
      return '/storage/' + image
    },
    performSearch() {
      if (!this.searchTerm) {
        this.users = [];
        return;
      }
      setTimeout(() => {
        axios.get(`/api/search?search=${this.searchTerm}`)
            .then((response) => {
              this.users = response.data.data;
            })
            .catch((error) => {
              this.users = [];
            });
      }, 500)

    },

    onFileImageChange(e) {
      const file = e.target.files[0];
      this.formData.image = file
      this.postImage = URL.createObjectURL(file);
    },
    createPost() {
      const formData = new FormData();
      if (this.formData.image != null) {
        formData.append('image', this.formData.image, this.formData.image.name);
      }
      formData.append('status', this.formData.status);
      formData.append('content', this.formData.content);
      axios.post('/api/posts/add', formData)
          .then((response) => {
            window.location.reload()
            this.message = response.data.data.message
            this.snackbar = true
          })
          .catch((errors) => {
            this.errors = errors.response.data.errors
          })
    },
  }
}
</script>

<style scoped>

</style>