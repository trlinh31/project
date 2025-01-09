<template>
  <div class="bg-white min-h-screen">
    <div class="relative">
      <div :class="classObj">
        <vs-navbar
          class="vx-navbar navbar-custom navbar-skelton"
          :color="navbarColorLocal"
        >
          <h1 class="font-black text-grey-dark">NhaTroGiaRe</h1>

          <vs-spacer />

          <profile-drop-down />
        </vs-navbar>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>
<!-- vs-navbar vx-navbar navbar-custom navbar-skelton vs-navbar-null vs-navbar-color-#fff -->
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
  },
  mounted() {
    this.getUserInfo();
  },
};
</script>
