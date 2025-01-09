<template>
  <vx-card no-shadow>
    <vs-input
      class="w-full mb-base"
      label-placeholder="Mật khẩu"
      v-model="old_password"
    />
    <vs-input
      class="w-full mb-base"
      label-placeholder="Nhập mật khẩu mới"
      v-model="new_password"
    />
    <vs-input
      class="w-full mb-base"
      label-placeholder="Xác nhận mật khẩu"
      v-model="confirm_new_password"
    />

    <div class="flex flex-wrap items-center justify-end">
      <vs-button class="ml-auto mt-2">Tiếp tục</vs-button>
    </div>
  </vx-card>
</template>

<script>
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
};
</script>
