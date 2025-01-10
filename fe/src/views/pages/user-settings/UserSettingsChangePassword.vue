<template>
  <vx-card no-shadow>
    <vs-input class="w-full mb-base" label-placeholder="Mật khẩu" v-model="old_password" />
    <vs-input class="w-full mb-base" label-placeholder="Nhập mật khẩu mới" v-model="new_password" />
    <vs-input class="w-full mb-base" label-placeholder="Xác nhận mật khẩu" v-model="confirm_new_password" />

    <div class="flex flex-wrap items-center justify-end">
      <vs-button color="primary" type="filled" size="base" class="ml-2" @click.prevent="handleSubmit">
        Lưu thông tin
      </vs-button>
    </div>
  </vx-card>
</template>

<script>
import userService from "@/services/user.service";
export default {
  data() {
    return {
      old_password: "",
      new_password: "",
      confirm_new_password: "",
    };
  },
  computed: {
    activeUserInfo() {
      return this.$store.state.AppActiveUser;
    },
  },
  methods: {
    handleSubmit() {
      if (this.new_password !== this.confirm_new_password) {
      this.$vs.notify({
        title: "Thất bại",
        text: "Mật khẩu mới và xác nhận mật khẩu không khớp",
        iconPack: "feather",
        icon: "icon-alert-circle",
        color: "warning",
      });
      return;
    }
      userService
        .changePassword(this.$store.state.auth.user.id, { old_password: this.old_password, new_password: this.new_password, confirm_new_password: this.confirm_new_password })
        .then(() => {
          this.$vs.notify({
                title: "Thành công",
                text: "Đổi mật khẩu thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
        })
        .catch((error) => {
          this.$vs.notify({
                title: "Thất bại",
                text: "Mật khẩu cũ ko chính xác",
                iconPack: "feather",
                icon: "icon-alert-circle",
                color: "warning",
              });
        });
    }

  },
};
</script>
