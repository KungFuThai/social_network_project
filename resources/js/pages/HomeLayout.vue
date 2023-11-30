<template>
  <MainNav></MainNav>
  <PostSection :token="token" :currentUser="currentUser"></PostSection>
  <RightSideBar :token="token"></RightSideBar>
</template>

<script>
import PostSection from "../components/PostSection.vue";
import RightSideBar from "../components/RightSideBar.vue";
import MainNav from "../components/MainNav.vue";

export default {
  name: "HomeLayout",
  props: {
    currentUser: Object,
    token: String,
  },
  components: {
    PostSection,
    RightSideBar,
    MainNav,
  },
}
</script>

<style scoped>

</style>