<template>
  <div class="d-flex align-center flex-column mt-2 position-relative">
    <div class="text-subtitle-2"></div>

    <VCard
        width="500"
        position="absolute"
    >
      <VLayout>
        <!-- <v-system-bar color="deep-purple darken-3"></v-system-bar> -->

        <VAppBar
            color="lightgray"
            prominent
        >
          <VBtn link variant="text">
            <VAvatar size="30" class="mr-1">
              <img
                  src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"/>
            </VAvatar>
            <b class="text-none">Em gái xinh tươi</b>
          </VBtn>
          <VSpacer></VSpacer>

          <v-btn variant="text" icon="mdi-dots-horizontal"></v-btn>
        </VAppBar>
        <VMain style="height: auto;">
          <VCardItem>
            <VRow>
              <VCol
                  v-for="n in 9"
                  :key="n"
                  class="d-flex child-flex"
                  cols="4"
              >
                <VImg
                    :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"
                    :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`"
                    aspect-ratio="1"
                    cover
                    class="bg-grey-lighten-2"
                >
                  <template v-slot:placeholder>
                    <VRow
                        class="fill-height ma-0"
                        align="center"
                        justify="center">
                    </VRow>
                  </template>
                </VImg>
              </VCol>
            </VRow>
          </VCardItem>
        </VMain>
        <v-app-bar
            color="white"
            location="bottom"
        >
          <VBtn link variant="text">
            <VAvatar size="30" class="mr-1">
              <img
                  src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"/>
            </VAvatar>
            <b class="text-none">Em gái xinh tươi</b>
          </VBtn>
          <v-spacer></v-spacer>

          <v-btn variant="text" icon="mdi-dots-horizontal"></v-btn>
        </v-app-bar>
      </VLayout>
    </VCard>
  </div>
</template>

<script>
export default {
name: "PostSection"
}
</script>

<style scoped>

</style>