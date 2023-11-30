<template>
  <VAppBar floating app clipped-right color="white" clipped>
    <div class="ml-2 v-col-2">
      <router-link to="/">
        <img width="103"
             src="https://1000logos.net/wp-content/uploads/2017/02/Logo-Instagram.png"
        />
      </router-link>

    </div>
    <VCard flat color="lighten-5" outlined width="300px" class="mt-2 ml-4 mx-auto h-100">
      <VAutocomplete
          clearable
          variant="solo-filled"
          prepend-inner-icon="mdi-magnify"
          label="Tìm kiếm"
      ></VAutocomplete>
    </VCard>
    <VSpacer></VSpacer>
    <v-dialog
        transition="dialog-top-transition"
        scrollable
        width="auto"
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
            <div class="text-h2 pa-12">Hello world!</div>
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
                height="60"
                cover
                src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"
            />
          </VAvatar>
          <VIcon v-bind="props">mdi-menu-down</VIcon>
        </template>

        <v-list>
          <v-list-item>
            Trang cá nhân
          </v-list-item>
          <v-list-item @click="logout">
            Đăng xuất
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
  </VAppBar>
</template>

<script>

export default {
  props: {
    currentUser: {},
    token: String
  },
  name: "Header.vue",
  methods: {
    logout() {
      axios.post('api/logout').then((response) => {
        localStorage.removeItem('token')
        this.$router.push('/login')
      }).catch((errors) => {
        console.log(errors)
      })
    },
  }
}
</script>

<style scoped>

</style>