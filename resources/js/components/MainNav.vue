<template>
  <VNavigationDrawer :width="341">
    <router-link to="/" class="text-decoration-none">
      <VListItem link prepend-icon="mdi-home-outline" class="text-black">
        <b>Trang chủ</b>
      </VListItem>
    </router-link>
    <router-link to="/messages" class="text-decoration-none">
      <VListItem link prepend-icon="mdi-email-outline" class="text-black">
        <b>Tin nhắn</b>
      </VListItem>
    </router-link>
    <VListItem link prepend-icon="mdi-heart-outline">
      <b>Thông báo</b>
    </VListItem>
    <VListItem link prepend-icon="mdi-cog-outline">
      <b>Cài đặt</b>
    </VListItem>
    <VDivider></VDivider>
    <VListItem link>
      <div class="d-flex align-center">
        <VAvatar size="25">
          <img
              src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"/>
        </VAvatar>
        <b class="ml-6">
          Trang cá nhân
        </b>
      </div>
    </VListItem>
  </VNavigationDrawer>
</template>

<script>
export default {
  name: "MainNav.vue",
}
</script>

<style scoped>

</style>