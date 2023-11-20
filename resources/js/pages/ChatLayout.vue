<template>
  <v-row>
    <div class="v-col v-col-2">
      <v-scroll-y-transition>
        <div class="v-list bg-white">
          <v-list-item link height="60px">
            <VAvatar size="30">
              <img
                  src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"
                  alt="John"
              />
            </VAvatar>
            <span>
                  Nguyễn văn a
                </span>
          </v-list-item>
        </div>
      </v-scroll-y-transition>
    </div>
    <div class="v-col bg-lightgray">
      <div class="v-col-12 w-100 bg-gray">
        <v-avatar>
          <img width="40"
               src="https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"
          />
        </v-avatar>
        <span>Nguyễn văn a</span>
      </div>
      <v-main class="pt-16" style="height: 690px">
        <v-scroll-y-transition>

        </v-scroll-y-transition>
      </v-main>
      <v-text-field label="Aa" :autofocus="true" variant="outlined"></v-text-field>
      <!--        <input :autofocus="true" size="1" type="text" class="v-field__input">-->
    </div>
  </v-row>
</template>

<script>
export default {
  name: "ChatLayout"
}
</script>

<style scoped>

</style>