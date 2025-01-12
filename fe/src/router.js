import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

const router = new Router({
  mode: "history",
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes: [
    {
      path: "/",
      component: () => import("./layouts/client/Client.vue"),
      children: [
        {
          path: "",
          name: "home-page",
          component: () => import("./views/HomePage.vue"),
        },
        {
          path: "room/:id",
          name: "room-page",
          component: () => import("./views/Room.vue"),
        },
        {
          path: "profile",
          name: "profile-page",
          component: () =>
            import("./views/pages/user-settings/UserSettings.vue"),
          meta: { requiresAuth: true },
        },
        {
          path: "transaction-result",
          name: "transaction-result",
          component: () => import("./views/pages/TransactionResult.vue"),
        },
      ],
    },
    {
      path: "/admin",
      component: () => import("./layouts/main/Main.vue"),
      meta: { requiresAuth: true },
      children: [
        {
          path: "",
          redirect: "posts",
        },
        {
          path: "posts",
          name: "admin-post-list",
          component: () => import("./views/admin/post/PostList.vue"),
        },
        {
          path: "add-post",
          name: "admin-post-add",
          component: () => import("./views/admin/post/AddPost.vue"),
        },
        {
          path: "post/:id",
          name: "admin-post-edit",
          component: () => import("./views/admin/post/EditPost.vue"),
        },
        {
          path: "user/list",
          name: "admin-user-list",
          component: () => import("./views/admin/user/UserList.vue"),
        },
        {
          path: "user/add-user",
          name: "admin-user-add",
          component: () => import("./views/admin/user/AddUser.vue"),
        },
        {
          path: "user/:id",
          name: "admin-user-edit",
          component: () => import("./views/admin/user/EditUser.vue"),
        },
      ],
    },
    {
      path: "/auth",
      component: () => import("@/layouts/full-page/FullPage.vue"),
      children: [
        {
          path: "login",
          name: "page-login",
          component: () => import("@/views/pages/login/Login.vue"),
        },
        {
          path: "register",
          name: "page-register",
          component: () => import("@/views/pages/register/Register.vue"),
        },
      ],
    },

    {
      path: "*",
      redirect: "/pages/error-404",
    },
  ],
});

router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById("loading-bg");
  if (appLoading) {
    appLoading.style.display = "none";
  }
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("token");
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next("/auth/login");
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
