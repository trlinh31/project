<template>
  <div class="container pt-32">
    <h1
      :class="`text-center font-bold text-6xl mb-6 ${
        isSuccess ? 'text-success' : 'text-danger'
      }`"
    >
      {{ isSuccess ? "Thành công !" : "Thất bại !" }}
    </h1>
    <h4 v-if="message" class="text-center mb-6">{{ message }}</h4>
    <div class="text-center">
      <vs-button color="primary" type="filled" to="/">Về trang chủ</vs-button>
    </div>
  </div>
</template>
<script>
import paymentService from "../../services/payment.service";

export default {
  data() {
    return {
      message: "",
      isSuccess: false,
    };
  },
  computed: {
    activeUserInfo() {
      return this.$store.state.auth.user;
    },
  },
  mounted() {
    const { vnp_ResponseCode } = this.$route.query;

    this.isSuccess = vnp_ResponseCode === "00";

    if (vnp_ResponseCode === "00" && this.activeUserInfo) {
      this.$vs.loading();
      paymentService
        .vnpayReturn({ user_id: this.activeUserInfo.id, vnp_ResponseCode })
        .then((response) => {
          const { message } = response.data;
          this.message = message;
        })
        .catch((error) => {
          const { message } = error.response.data;
          this.message = message || "Thanh toán thất bại";
        })
        .finally(() => this.$vs.loading.close());
    }
  },
};
</script>
