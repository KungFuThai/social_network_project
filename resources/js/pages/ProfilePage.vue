<template>
  <div class="d-flex align-center flex-column mt-2 position-relative">
    <v-card
        width="60%"
        position="absolute">
      <v-card>
        <v-card class="mx-auto" tile :elevation="2">
          <v-img height="400" cover src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"></v-img>
          <v-row class="pb-10">
            <v-col cols="6" class="d-flex">
              <v-avatar
                  size="150"
                  rounded="100"
                  style="position:absolute; bottom: 0"
              >
                <v-img cover src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"></v-img>
              </v-avatar>
              <v-list-item color="black" style="left: 130px">
                <v-list-item>
                  <v-list-item-title class="title text-h4">Marcus Obrien</v-list-item-title>
                </v-list-item>
              </v-list-item>
            </v-col>
            <v-col cols="6" class="d-flex align-center justify-end">
              <v-btn color="lightgray" :elevation="1">
                Chỉnh sửa trang cá nhân
              </v-btn>
            </v-col>
          </v-row>
        </v-card>
        <v-divider></v-divider>
        <v-card>
          <v-container align="center" justify="center">
            <v-row>
              <v-col cols="5">
                <v-card>
                  <v-card-item>
                    <div>
                      <div class="text-overline mb-1">
                        Thông tin
                      </div>
                    </div>
                    <div>
                      10/11/2002
                    </div>
                    <div>
                      Nam
                    </div>
                    <div>
                      cần thơ
                    </div>
                  </v-card-item>
                </v-card>
              </v-col>
              <v-col cols="7">
                <v-card>
                  <VMain>
                    <v-card class="d-flex align-center" :elevation="2">
                      <v-avatar class="ml-2">
                        <v-img
                            width="30"
                            height="40"
                            cover
                            src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                        >
                        </v-img>
                      </v-avatar>
                      <div>
                        <div class="float-left text-h6">
                          Tên
                        </div>
                        <br>
                        <div class="float-left">
                          Thời gian
                        </div>
                      </div>
                      <v-col>
                      </v-col>
                      <v-btn size="40" rounded class="mr-3">
                        <v-icon color="red">
                          mdi-delete
                        </v-icon>
                      </v-btn>
                    </v-card>
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
                    <v-card class="d-flex align-center" :elevation="2">
                      <v-col cols="4">
                        <v-btn rounded width="200" :elevation="3">
                          <v-icon
                              color="gray">
                            mdi-thumb-up
                          </v-icon>
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn rounded width="200" :elevation="3">
                          <v-icon color="gray">
                            mdi-thumb-down
                          </v-icon>
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn rounded width="200" :elevation="3">
                          <v-icon color="gray">
                            mdi-comment
                          </v-icon>
                        </v-btn>
                      </v-col>
                    </v-card>
                  </VMain>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-card>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "ProfilePage.vue",
}
</script>

<style scoped>

</style>