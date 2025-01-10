<template>
  <div class="clearfix">
    <vs-input
      v-validate="'required|alpha_dash|min:3'"
      data-vv-validate-on="blur"
      label-placeholder="Name"
      name="displayName"
      placeholder="Họ và tên"
      v-model="displayName"
      class="w-full"
    />
    <span class="text-danger text-sm">{{ errors.first("displayName") }}</span>

    <vs-input
      v-validate="'required|email'"
      data-vv-validate-on="blur"
      name="email"
      type="email"
      label-placeholder="Email"
      placeholder="Email"
      v-model="email"
      class="w-full mt-6"
    />
    <span class="text-danger text-sm">{{ errors.first("email") }}</span>

    <vs-input
      v-validate="'required|max:11'"
      data-vv-validate-on="blur"
      label-placeholder="Phone"
      name="phone"
      placeholder="Số điện thoại"
      v-model="phone"
      class="w-full"
    />
    <span class="text-danger text-sm">{{ errors.first("phone") }}</span>

    <vs-input
      ref="password"
      type="password"
      data-vv-validate-on="blur"
      v-validate="'required|min:6|max:10'"
      name="password"
      label-placeholder="Password"
      placeholder="Mật khẩu"
      v-model="password"
      class="w-full mt-6"
    />
    <span class="text-danger text-sm">{{ errors.first("password") }}</span>

    <vs-input
      type="password"
      v-validate="'min:6|max:10|confirmed:password'"
      data-vv-validate-on="blur"
      data-vv-as="password"
      name="confirm_password"
      label-placeholder="Confirm Password"
      placeholder="Nhập lại mật khẩu"
      v-model="confirm_password"
      class="w-full mt-6"
    />
    <span class="text-danger text-sm">{{
      errors.first("confirm_password")
    }}</span>
    <vs-button type="border" to="/auth/login" class="mt-6">Đăng nhập</vs-button>
    <vs-button
      class="float-right mt-6"
      @click="registerUserJWt"
      :disabled="!validateForm"
      >Đăng ký</vs-button
    >
  </div>
</template>

<script>
import authService from "../../../services/auth.service";
import { mapActions } from "vuex";
export default {
  ...mapActions("auth", ["login"]),

  data() {
    return {
      displayName: "",
      email: "",
      phone: "",
      password: "",
      confirm_password: "",
      isTermsConditionAccepted: true,
    };
  },
  computed: {
    validateForm() {
      return (
        !this.errors.any() &&
        this.displayName !== "" &&
        this.email !== "" &&
        this.password !== "" &&
        this.confirm_password !== "" &&
        this.isTermsConditionAccepted === true
      );
    },
  },
  methods: {
    checkLogin() {
      if (this.$store.state.auth.isAuthenticated) {
        this.$vs.notify({
          title: "Login Attempt",
          text: "You are already logged in!",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "warning",
        });

        return false;
      }
      return true;
    },
    async registerUserJWt() {
      // If form is not validated or user is already login return
      if (!this.validateForm || !this.checkLogin()) return;

      const payload = {
        name: this.displayName,
        email: this.email,
        phone: this.phone,
        password: this.password,
      };

      try {
        const response = await authService.register(payload);
        this.$vs.notify({
          title: "Thành công",
          text: "Đăng ký thành công",
          iconPack: "feather",
          icon: "icon-check",
          color: "success",
        });
        this.$router.push("/auth/login");
      } catch (error) {
        this.$vs.notify({
          title: "Thất bại",
          text: "Đăng ký thất bại",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger",
        });
      }
    },
  },
};
</script>
