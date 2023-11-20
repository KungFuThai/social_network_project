<template>
  <MainNav></MainNav>
  <PostSection></PostSection>
  <RightSideBar></RightSideBar>
</template>

<script>
import PostSection from "../components/PostSection.vue";
import RightSideBar from "../components/RightSideBar.vue";
import MainNav from "../components/MainNav.vue";

export default {
  name: "Home.vue",
  components: {
    PostSection,
    RightSideBar,
    MainNav,
  },
}
</script>

<style scoped>

</style>