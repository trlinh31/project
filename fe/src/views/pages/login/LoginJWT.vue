<template>
  <div>
    <div class="mb-base">
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
    </div>

    <div class="mb-base">
      <vs-input
        data-vv-validate-on="blur"
        v-validate="'required'"
        type="password"
        name="password"
        icon-no-border
        icon="icon icon-lock"
        icon-pack="feather"
        label-placeholder="Mật khẩu"
        v-model="password"
        class="w-full mt-6"
      />
      <span class="text-danger text-sm">{{ errors.first("password") }}</span>
    </div>
    <div class="flex flex-wrap justify-between mb-3">
      <vs-button type="border" @click="registerUser">Đăng ký</vs-button>
      <vs-button :disabled="!validateForm" @click="loginJWT"
        >Đăng nhập</vs-button
      >
    </div>
  </div>
</template>

<script>
import authService from "../../../services/auth.service";

export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any() && this.email !== "" && this.password !== "";
    },
  },
  methods: {
    checkLogin() {
      if (this.$store.state.auth.isAuthenticated) {
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
    loginJWT() {
      if (!this.checkLogin()) {
        this.$router.push("/");
        return;
      }

      const payload = {
        email: this.email,
        password: this.password,
      };

      this.$vs.loading();
      authService
        .login(payload)
        .then((response) => {
          const { token, user } = response.data;
          localStorage.setItem("token", token);
          this.$store.dispatch("auth/login", { token, user });

          this.$vs.notify({
            title: "Thành công",
            text: "Đăng nhập thành công",
            iconPack: "feather",
            icon: "icon-check",
            color: "success",
          });

          this.$router.push(
            `${user.role === "ADMIN" ? "/admin/dashboard" : "/"}`
          );
        })
        .catch(() => {
          this.$vs.notify({
            title: "Thất bại",
            text: "Thông tin đăng nhập không đúng, vui lòng thử lại",
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger",
          });
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    registerUser() {
      this.$router.push("/auth/register");
    },
  },
};
</script>
