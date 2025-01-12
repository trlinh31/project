<template>
  <div class="clearfix">
    <div class="mb-6">
      <vs-input
        v-validate="'required'"
        data-vv-validate-on="blur"
        label-placeholder="Name"
        name="name"
        placeholder="Họ và tên"
        v-model="name"
        class="w-full"
      />
      <span class="text-danger text-sm">{{ errors.first("name") }}</span>
    </div>

    <div class="mb-6">
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
    </div>

    <div class="mb-6">
      <vs-input
        v-validate="'required'"
        data-vv-validate-on="blur"
        label-placeholder="Phone"
        name="phone"
        placeholder="Số điện thoại"
        v-model="phone"
        class="w-full"
      />
      <span class="text-danger text-sm">{{ errors.first("phone") }}</span>
    </div>

    <div class="mb-6">
      <vs-input
        ref="password"
        type="password"
        data-vv-validate-on="blur"
        v-validate="'required'"
        name="password"
        label-placeholder="Password"
        placeholder="Mật khẩu"
        v-model="password"
        class="w-full mt-6"
      />
      <span class="text-danger text-sm">{{ errors.first("password") }}</span>
    </div>

    <div>
      <vs-input
        type="password"
        v-validate="'required|confirmed:password'"
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
    </div>

    <div>
      <vs-button type="border" to="/auth/login" class="mt-6"
        >Đăng nhập</vs-button
      >
      <vs-button class="float-right mt-6" @click="registerUserJWt"
        >Đăng ký</vs-button
      >
    </div>
  </div>
</template>

<script>
import authService from "../../../services/auth.service";
import { mapActions } from "vuex";
import { Validator } from "vee-validate";

const dict = {
  custom: {
    email: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
      email: "Email không đúng định dạng. Vui lòng nhập lại!",
    },
    name: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    phone: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    password: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    confirm_password: {
      required: "Vui mã nhập đủ các thông tin bắt buộc",
      confirmed: "Mật khẩu không trùng khớp",
    },
  },
};

Validator.localize("en", dict);

export default {
  ...mapActions("auth", ["login"]),

  data() {
    return {
      name: "",
      email: "",
      phone: "",
      password: "",
      confirm_password: "",
    };
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
    registerUserJWt() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          const payload = {
            name: this.name,
            email: this.email,
            phone: this.phone,
            password: this.password,
          };

          this.$vs.loading();
          authService
            .register(payload)
            .then(() => {
              this.$router.push("/auth/login");
              this.$vs.notify({
                title: "Thành công",
                text: "Đăng ký thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
            })
            .catch((error) => {
              this.$vs.notify({
                title: "Thất bại",
                text: error.response.data.message,
                iconPack: "feather",
                icon: "icon-alert-circle",
                color: "danger",
              });
            })
            .finally(() => this.$vs.loading.close());
        }
      });
    },
  },
};
</script>
