<template>
  <vx-card no-shadow>
    <div class="flex flex-wrap items-center mb-base">
      <vs-avatar :src="avt" size="70px" class="mr-4 mb-4" />
      <div class="flex justify-between flex-1">
        <div class="flex flex-col">
          <p class="mb-2 font-bold">VIP: {{ activeUserInfo.vip_level }}</p>
          <label class="upload-label" for="upload-file" style="height: 50px">
            Upload avatar
            <input
              hidden
              id="upload-file"
              type="file"
              accept="image/*"
              @change="handleFileChange"
            />
            <img
              v-if="base64Image"
              :src="base64Image"
              width="0"
              height="0"
              class="shadow-md cursor-pointer block object-cover"
              alt="Preview"
            />
          </label>
        </div>
        <vs-button
          class="sm:mb-0 mb-2"
          color="warning"
          @click="handleDirectToPaymentPage()"
        >
          Nạp VIP
        </vs-button>
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
      <vs-button
        color="primary"
        type="filled"
        size="base"
        class="ml-2"
        @click.prevent="handleSubmit"
      >
        Lưu thông tin
      </vs-button>
    </div>
  </vx-card>
</template>

<script>
import paymentService from "../../../services/payment.service";
import vSelect from "vue-select";
import userService from "../../../services/user.service";

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      avt: "",
      base64Image: "",
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
      this.base64Image = this.activeUserInfo.avt || "";
    }
  },
  methods: {
    handleDirectToPaymentPage() {
      paymentService
        .vnpay(this.activeUserInfo.id)
        .then((response) => {
          const { url } = response.data;
          window.location.href = url;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.convertToBase64(file);
      }
    },
    convertToBase64(file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        this.base64Image = reader.result;
        this.avt = reader.result;
      };
      reader.readAsDataURL(file);
    },
    handleSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          const payload = {
            avt: this.base64Image, // Send base64 image to the backend
            name: this.name,
            phone: this.phone,
            email: this.email,
          };

          userService
            .updateUser(this.$store.state.auth.user.id, payload)
            .then((response) => {
              this.$vs.notify({
                title: "Thành công",
                text: "Cập nhật thông tin thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
              this.$store.dispatch("auth/setUser", response.data);
            })
            .catch(() => {
              this.$vs.notify({
                title: "Thất bại",
                text: "Cập nhật thông tin thất bại",
                iconPack: "feather",
                icon: "icon-alert-circle",
                color: "warning",
              });
            });
        }
      });
    },
  },
};
</script>
