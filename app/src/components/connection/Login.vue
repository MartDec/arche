<template>
  <form @submit.prevent="login" class="login-form">
    <div class="input">
      <label for="email">Email</label>
      <input type="email" id="email" v-model="email" placeholder="example@mail.com">
    </div>
    <div class="input">
      <label for="password">Password</label>
      <input type="password" id="password" v-model="password" placeholder="password">
    </div>
    <input type="submit" value="Login">
  </form>
</template>

<script>
import FetchRequest from "@/classes/FetchRequest"

export default {
  name: "Login",
  data: function () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login: async function () {
      const request = new FetchRequest('login', 'POST', {
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

<style lang="scss">
form.login-form {
  width: 100%;

  .input {
    margin-bottom: 1.5rem;

    label {
      font-size: 2rem;
      line-height: 1;
    }

    input {
      border: solid 1px black;
      padding: .7rem;
      background: var(--white);
      font-size: 1.5rem;
    }
  }

  input[type=submit] {
    background: none;
    border: none;
    font-family: var(--titleFont);
    font-size: 2rem;
    cursor: pointer;
    padding: 0;
    text-decoration: underline;
  }
}
</style>
