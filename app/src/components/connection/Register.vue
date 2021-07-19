<template>
  <form @submit.prevent="register" class="login-form">
    <div class="input">
      <label for="username">Username</label>
      <input type="text" id="username" v-model="username" placeholder="Username">
    </div>
    <div class="input">
      <label for="email">Email</label>
      <input type="email" id="email" v-model="email" placeholder="example@mail.com">
    </div>
    <div class="input">
      <label for="password">Password</label>
      <input type="password" id="password" v-model="password" placeholder="password">
    </div>
    <input type="submit" value="Register">
  </form>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"

export default {
  name: "Register",
  data: function () {
    return {
      username: '',
      email: '',
      password: ''
    }
  },
  methods: {
    register: async function () {
      const request = new FetchRequest('register', 'POST', {
        username: this.username,
        email: this.email,
        password: this.password
      })
      const response = await request.send()

      if (response.error)
        return this.$notify.error({
          title: 'Oops',
          msg: response.message
        })

      localStorage.user = JSON.stringify(response.user)
      this.$router.push({ name: 'index' })
    }
  }
}
</script>

<style scoped>

</style>
