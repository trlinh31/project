<template>
  <div class="bg-white min-h-screen">
    <div class="relative">
      <div :class="classObj">
        <vs-navbar
          class="vx-navbar navbar-custom navbar-skelton"
          :color="navbarColorLocal"
        >
          <h1 class="font-black text-grey-dark" @click="$router.push('/')">
            NhaTroGiaRe
          </h1>

          <vs-spacer />

          <template v-if="!checkLogin()">
            <vs-button color="primary" type="filled" to="/auth/register"
              >Đăng ký</vs-button
            >
            <vs-button
              color="primary"
              type="flat"
              class="ml-3"
              to="/auth/login"
            >
              Đăng nhập
            </vs-button>
          </template>

          <template v-else>
            <profile-drop-down />
          </template>
        </vs-navbar>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>
<script>
import ProfileDropDown from "../components/navbar/components/ProfileDropDown.vue";
import userService from "../../services/user.service";

export default {
  components: {
    ProfileDropDown,
  },
  computed: {
    navbarColorLocal() {
      return this.$store.state.theme === "dark" && this.navbarColor === "#fff"
        ? "#10163a"
        : this.navbarColor;
    },
    verticalNavMenuWidth() {
      return this.$store.state.verticalNavMenuWidth;
    },
    windowWidth() {
      return this.$store.state.windowWidth;
    },

    // NAVBAR STYLE
    classObj() {
      if (this.verticalNavMenuWidth === "default") return "navbar-default";
      else if (this.verticalNavMenuWidth === "reduced") return "navbar-reduced";
      else if (this.verticalNavMenuWidth) return "navbar-full";
    },
  },
  methods: {
    getUserInfo() {
      if (this.$store.state.auth.isAuthenticated) {
        userService.getProfile().then((response) => {
          this.$store.commit("auth/SET_USER", response.data.user);
        });
      }
    },
    checkLogin() {
      return this.$store.state.auth.isAuthenticated;
    },
  },
  mounted() {
    this.getUserInfo();
    console.log(this.checkLogin());
  },
};
</script>
