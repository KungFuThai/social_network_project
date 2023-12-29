<template>
  <div class="d-flex align-center justify-center" style="height: 100vh">
    <v-sheet width="400" class="mx-auto" v-if="reset !== true">
      <div class="text-center mb-5">
        <h2>Quên mật khẩu</h2>
      </div>
      <div v-if="forget">
        <v-text-field v-model="email" label="Email" :error-messages="message"
                      hide-details="auto"></v-text-field>
        <v-btn type="submit" color="lightblue" block class="mt-2" @click="forgetPassword">Quên mật khẩu</v-btn>
      </div>
<!--      kiểm tra token-->
      <div v-else>
        <v-text-field v-model="forgetToken" label="Mã" :error-messages="checkTokenMessage"
                      hide-details="auto"></v-text-field>
        <v-btn type="submit" color="lightblue" block class="mt-2" @click="checkToken">Xác minh mã</v-btn>
      </div>
      <div class="mt-2">
        <p class="text-body-2">Nhớ mật khẩu rồi?
          <router-link :to="`/login`">Đăng nhập</router-link>
        </p>
      </div>
    </v-sheet>
<!--    đặt lại mật khẩu-->
    <v-sheet width="400" class="mx-auto" v-else>
      <div class="text-center mb-5">
        <h2>Đặt lại mật khẩu</h2>
      </div>
      <v-text-field
          :type="showPassword ? 'text' : 'password'"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPassword = !showPassword"
          v-model="password"
          label="Mật khẩu"
          hide-details="auto"></v-text-field>
      <v-btn type="submit" color="lightblue" block class="mt-2" @click="resetPassword">Đặt lại mật khẩu</v-btn>
      <div class="mt-2">
        <p class="text-body-2">Nhớ mật khẩu rồi?
          <router-link :to="`/login`">Đăng nhập</router-link>
        </p>
      </div>
    </v-sheet>
  </div>
  <v-snackbar
      v-if="snackbarMess !== ''"
      v-model="snackbar"
      :timeout=2000
      color="green"
  >
    {{ snackbarMess }}
  </v-snackbar>
</template>

<script>
export default {
  name: "Login.vue",
  data() {
    return {
      snackbar: false,
      snackbarMess: '',
      showPassword: false,
      forget: true,
      reset: false,
      email: '',
      token: '',
      forgetToken: '',
      message: '',
      checkTokenMessage: '',
      password: '',
    };
  },
  methods: {
    forgetPassword() {
      axios.post('/api/forget-password', {
        email: this.email
      })
          .then((response) => {
            // console.log(response.data.message)
            this.token = response.data.data
            this.forget = false
            this.snackbarMess = response.data.message
            this.snackbar = true
          }).catch((errors) => {
        if (errors.response.data.success === false) {
          this.message = errors.response.data.message
        }
      })
    },
    checkToken() {
      if (this.forgetToken == this.token){
        this.reset = true
        this.snackbarMess = "Xác minh mã thành công! Mời bạn đặt lại mật khẩu!"
        this.snackbar = true
      }
      else{
        this.checkTokenMessage = "Sai rồi"
      }
    },
    resetPassword() {
      axios.post('/api/reset-password', {
        token: this.token,
        password: this.password
      })
          .then((response) => {
            this.$router.push('/login')
          })
          .catch((errors) => {
            console.log(errors)
          })
    },
  },
}
</script>

<style scoped>

</style>