<template>
  <form>
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="w-full mb-base">
          <vx-card>
            <vs-button
              color="primary"
              type="border"
              size="base"
              to="/admin/user/list"
            >
              Hủy
            </vs-button>
            <vs-button
              color="primary"
              type="filled"
              size="base"
              class="ml-2"
              @click.prevent="handleSubmit"
            >
              Lưu thông tin
            </vs-button>
          </vx-card>
        </div>
      </div>
      <div class="vx-col w-full">
        <div class="w-full mb-base">
          <vx-card title="Thông tin tài khoản">
            <div class="mb-6">
              <p>Email</p>
              <vs-input
                type="email"
                class="w-full"
                v-model="form.email"
                v-validate="'required|email'"
                name="email"
              />
              <span class="text-danger text-sm" v-show="errors.has('email')">
                {{ errors.first("email") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Họ và tên</p>
              <vs-input
                type="text"
                class="w-full"
                v-model="form.name"
                v-validate="'required'"
                name="name"
              />
              <span class="text-danger text-sm" v-show="errors.has('name')">
                {{ errors.first("name") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Số điện thoại</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.phone"
                v-validate="'required'"
                name="phone"
              />
              <span class="text-danger text-sm" v-show="errors.has('phone')">
                {{ errors.first("phone") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Mật khẩu</p>
              <vs-input
                type="password"
                class="w-full"
                v-model="form.password"
                v-validate="'required'"
                name="password"
              />
              <span class="text-danger text-sm" v-show="errors.has('password')">
                {{ errors.first("password") }}
              </span>
            </div>
          </vx-card>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import vSelect from "vue-select";
import userService from "@/services/user.service";
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
  },
};

Validator.localize("en", dict);

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      form: {
        email: "",
        name: "",
        password: "",
        phone: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$vs.loading();
          userService
            .addUser(this.form)
            .then(() => {
              this.$vs.notify({
                title: "Thành công",
                text: "Thêm nguười dùng thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
              this.$router.push({ name: "admin-user-list" });
            })
            .catch(() => {
              this.$vs.notify({
                title: "Thất bại",
                text: "Thêm nguồi dùng thất bại",
                iconPack: "feather",
                icon: "icon-alert-circle",
                color: "warning",
              });
            })
            .finally(() => {
              this.$vs.loading.close();
            });
        }
      });
    },
  },
};
</script>
