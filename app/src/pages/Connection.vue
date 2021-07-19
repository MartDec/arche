<template>
  <div class="connection-wrapper">
    <div class="form-wrapper">
      <div class="header">
        <div class="links">
          <a @click.prevent="displayLogin" :class="isActive('login')">login</a> - <a @click.prevent="displayRegister" :class="isActive('register')">register</a>
        </div>
        <Title/>
      </div>
      <div class="forms">
        <Login v-if="currentForm === 'login'"/>
        <Register v-else-if="currentForm === 'register'"/>
      </div>
    </div>
    <div class="background"></div>
  </div>
</template>

<script>
import Login from "@/components/connection/Login"
import Register from "@/components/connection/Register"
import Title from "@/components/content/Title"

export default {
  name: "Connection",
  data: function () {
    return {
      currentForm: 'login'
    }
  },
  components: {
    Login,
    Register,
    Title
  },
  methods: {
    displayLogin: function () {
      this.currentForm = 'login'
    },
    displayRegister: function () {
      this.currentForm = 'register'
    },
    isActive: function (formType) {
      return formType === this.currentForm ? 'active' : null
    }
  }
}
</script>

<style lang="scss">
.connection-wrapper {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-between;

  .form-wrapper {
    padding: 0 10%;
    display: flex;
    flex-wrap: wrap;

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      align-self: start;
      width: 100%;

      .links {
        font-size: 2rem;

        a {
          cursor: pointer;
        }

        &, a {
          color: var(--black);
        }

        a.active {
          text-decoration: underline;
        }
      }

      .title {
        text-align: right;

        h1 {
          font-size: 4rem;
          color: var(--red);
        }

        p {
          font-size: 1rem;
        }
      }
    }

    .forms {
      width: 80%;
    }
  }

  .background {
    width: 35%;
    height: 100%;
    background-image: url("../assets/login-background.jpg");
    background-position: center;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.5);
  }
}
</style>
