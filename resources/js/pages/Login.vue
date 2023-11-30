<template>
  <div class="d-flex align-center justify-center" style="height: 100vh">
    <v-sheet width="400" class="mx-auto">
      <v-form fast-fail @submit.prevent="login">
        <div class="text-center mb-5">
          <h2>Đăng nhập</h2>
        </div>
        <v-text-field v-model="formData.email" label="Email" :error-messages="errors.email"
                      hide-details="auto"></v-text-field>

        <v-text-field v-model="formData.password" label="Mật khẩu" :error-messages="errors.password"
                      hide-details="auto"></v-text-field>
        <a href="#" class="text-body-2 font-weight-regular">Quên mật khẩu?</a>
        <v-btn type="submit" color="lightblue" block class="mt-2" @click="snackbar = true">Đăng nhập</v-btn>

      </v-form>
      <div class="mt-2">
        <p class="text-body-2">Không có tài khoản? <a href="#">Đăng ký</a></p>
      </div>
    </v-sheet>
  </div>
  <v-snackbar
      v-if="message !== ''"
      v-model="snackbar"
      :timeout=2000
      color="red"
  >
    {{ message }}
  </v-snackbar>
</template>

<script>
export default {
  name: "Login.vue",
  data() {
    return {
      snackbar: false,

      formData: {
        email: '',
        password: '',
      },
      errors: {
        email: '',
        password: '',
      },
      message: '',
    };
  },
  methods: {
    login() {
      axios.post('api/login', this.formData).then((response) => {
        localStorage.setItem('token', response.data.data);
        this.$router.push('/')
      }).catch((errors) => {
        if (errors.response.data.success === false) {
          this.message = errors.response.data.message
        } else {
          this.errors = errors.response.data.errors
        }
      })
    },
  },
}
</script>

<style scoped>

</style>