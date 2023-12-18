<template>
  <div class="d-flex align-center justify-center" style="height: 100vh">
    <v-sheet width="400" class="mx-auto">
      <v-form fast-fail @submit.prevent="register">
        <div class="text-center mb-5">
          <h2>Đăng ký</h2>
        </div>
        <v-text-field v-model="formData.last_name" label="Họ và lót" :error-messages="errors.last_name"
                      hide-details="auto"></v-text-field>
        <v-text-field v-model="formData.first_name" label="Tên" :error-messages="errors.first_name"
                      hide-details="auto"></v-text-field>
        <v-text-field v-model="formData.phone" label="Số điện thoại" :error-messages="errors.phone"
                      hide-details="auto"></v-text-field>
        <v-text-field v-model="formData.email" label="Email" :error-messages="errors.email"
                      hide-details="auto"></v-text-field>
        <v-text-field
            :type="showPassword ? 'text' : 'password'"
            :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showPassword = !showPassword"
            v-model="formData.password"
            label="Mật khẩu"
            :error-messages="errors.password"
            hide-details="auto"
        ></v-text-field>


        <router-link :to="`/forget`" class="text-body-2 font-weight-regular">Quên mật khẩu?</router-link>
        <v-btn type="submit" color="lightblue" block class="mt-2">Đăng ký</v-btn>

      </v-form>
      <div class="mt-2">
        <p class="text-body-2">Đã có tài khoản?
          <router-link :to="`/login`">Đăng nhập</router-link>
        </p>
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
  name: "Register.vue",
  data() {
    return {
      showPassword: false,
      snackbar: false,

      formData: {
        email: '',
        password: '',
        last_name: '',
        first_name: '',
        phone: '',
      },
      errors: {
        email: '',
        password: '',
        last_name: '',
        first_name: '',
        phone: '',
      },
      message: '',
    };
  },
  methods: {
    register() {
      axios.post('/api/register', this.formData)
          .then((response) => {
            localStorage.setItem('token', response.data.data);
            this.$router.push('/')
          }).catch((errors) => {
        if (errors.response.data.success === false) {
          this.message = errors.response.data.message
          this.snackbar = true
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