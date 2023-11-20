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
          variant="solo-filled"
          prepend-inner-icon="mdi-magnify"
          label="Tìm kiếm"
      ></VAutocomplete>
    </VCard>
    <VSpacer></VSpacer>
    <VBtn dark depressed color="#e45364" variant="tonal" class="text-none mr-5">
        <span class="mr-2">
          <VIcon class="mr-2">mdi-plus</VIcon>Tạo bài đăng mới
        </span>
    </VBtn>
    <VBtn icon class="mr-2">
      <VIcon>mdi-email-outline</VIcon>
    </VBtn>
    <VBtn icon class="mr-5">
      <v-icon>mdi-heart-outline</v-icon>
    </VBtn>
    <VAvatar size="30">
      <img
          src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"
          alt="John"
      />
    </VAvatar>
    <VIcon>mdi-menu-down</VIcon>
  </VAppBar>
</template>

<script>

export default {
  name: "Header.vue",
}
</script>

<style scoped>

</style>