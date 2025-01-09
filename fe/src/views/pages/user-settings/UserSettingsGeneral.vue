<template>
  <vx-card no-shadow>
    <div class="flex flex-wrap items-center mb-base">
      <vs-avatar :src="avt" size="70px" class="mr-4 mb-4" />
      <div class="flex justify-between flex-1">
        <vs-button class="sm:mb-0 mb-2" type="border">Thay đổi ảnh</vs-button>
        <vs-button class="sm:mb-0 mb-2">Nạp VIP</vs-button>
      </div>
    </div>

    <!-- Info -->
    <div class="mb-4">
      <vs-input
        size="large"
        class="w-full mb-base"
        label-placeholder="Họ và tên"
        v-model="name"
      ></vs-input>
      <vs-input
        size="large"
        class="w-full mb-base"
        label-placeholder="Email"
        v-model="email"
      ></vs-input>
      <vs-input
        size="large"
        class="w-full"
        label-placeholder="Số điện thoại"
        v-model="phone"
      ></vs-input>
    </div>

    <div class="flex flex-wrap items-center justify-end">
      <vs-button class="ml-auto mt-2">Lưu thay đổi</vs-button>
    </div>
  </vx-card>
</template>

<script>
export default {
  data() {
    return {
      avt: "",
      name: "",
      phone: "",
      email: "",
    };
  },
  computed: {
    activeUserInfo() {
      return this.$store.state.auth.user;
    },
  },
  mounted() {
    if (this.activeUserInfo) {
      this.name = this.activeUserInfo.name || "";
      this.avt = this.activeUserInfo.avt || "";
      this.phone = this.activeUserInfo.phone || "";
      this.email = this.activeUserInfo.email || "";
    }
  },
  watch: {
    activeUserInfo: {
      immediate: true,
      handler(newUserInfo) {
        if (newUserInfo) {
          this.name = newUserInfo.name || "";
          this.avt = newUserInfo.avt || "";
          this.phone = newUserInfo.phone || "";
          this.email = newUserInfo.email || "";
        }
      },
    },
  },
};
</script>
