<template>
  <div>
    <vs-input
      v-validate="'required|email|min:3'"
      data-vv-validate-on="blur"
      name="email"
      icon-no-border
      icon="icon icon-user"
      icon-pack="feather"
      label-placeholder="Email"
      v-model="email"
      class="w-full"
    />
    <span class="text-danger text-sm">{{ errors.first("email") }}</span>

    <vs-input
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:10'"
      type="password"
      name="password"
      icon-no-border
      icon="icon icon-lock"
      icon-pack="feather"
      label-placeholder="Password"
      v-model="password"
      class="w-full mt-6"
    />
    <span class="text-danger text-sm">{{ errors.first("password") }}</span>

    <div class="flex flex-wrap justify-between my-5">
      <vs-checkbox v-model="checkbox_remember_me" class="mb-3"
        >Remember Me</vs-checkbox
      >
      <router-link to="/pages/forgot-password">Forgot Password?</router-link>
    </div>
    <div class="flex flex-wrap justify-between mb-3">
      <vs-button type="border" @click="registerUser">Register</vs-button>
      <vs-button :disabled="!validateForm" @click="loginJWT">Login</vs-button>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import authService from "../../../services/auth.service";

export default {
  data() {
    return {
      email: "nguyentranlinh31@gmail.com",
      password: "123123",
      checkbox_remember_me: false,
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any() && this.email !== "" && this.password !== "";
    },
  },
  methods: {
    ...mapActions("auth", ["login"]),
    checkLogin() {
      // If user is already logged in notify
      if (this.$store.state.auth.isAuthenticated) {
        // Close animation if passed as payload
        // this.$vs.loading.close()

        this.$vs.notify({
          title: "Cảnh báo",
          text: "Bạn đã đăng nhập",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "warning",
        });

        return false;
      }
      return true;
    },
    async loginJWT() {
      if (!this.checkLogin()) return;

      this.$vs.loading();

      const payload = {
        email: this.email,
        password: this.password,
      };

      try {
        const response = await authService.login(payload);
        const { token, user } = response.data;
        this.login({ token, user });

        this.$vs.notify({
          title: "Thành công",
          text: "Đăng nhập thành công",
          iconPack: "feather",
          icon: "icon-check",
          color: "success",
        });

        this.$router.push("/");
      } catch (error) {
        this.$vs.notify({
          title: "Thất bại",
          text: "Đăng nhập thất bại",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger",
        });
      } finally {
        this.$vs.loading.close();
      }
    },
    registerUser() {
      if (!this.checkLogin()) return;
      this.$router.push("/pages/register").catch(() => {});
    },
  },
};
</script>
