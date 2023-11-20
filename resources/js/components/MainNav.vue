<template>
  <VNavigationDrawer :width="341">
    <VListItem link prepend-icon="mdi-home-outline">
      <b>Trang chủ</b>
    </VListItem>
    <VListItem link prepend-icon="mdi-email-outline">
      <b>Tin nhắn</b>
    </VListItem>
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
          <img src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"/>
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