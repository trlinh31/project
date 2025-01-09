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
      path: "",
      component: () => import("./layouts/main/Main.vue"),
      meta: { requiresAuth: true },
      children: [
        {
          path: "/",
          redirect: "/dashboard",
        },
        {
          path: "/dashboard",
          name: "dashboard",
          component: () => import("./views/DashboardECommerce.vue"),
        },
        {
          path: "/admin/posts",
          name: "admin-post-list",
          component: () => import("./views/admin/post/PostList.vue"),
        },
        {
          path: "/admin/add-post",
          name: "admin-post-add",
          component: () => import("./views/admin/post/AddPost.vue"),
        },
        {
          path: "/admin/post/:id",
          name: "admin-post-edit",
          component: () => import("./views/admin/post/EditPost.vue"),
        },
        {
          path: "user/list",
          name: "admin-user-list",
          component: () => import("./views/admin/user/UserList.vue"),
        },
        {
          path: "/user/add-user",
          name: "admin-user-add",
          component: () => import("./views/admin/user/AddUser.vue"),
        },
        {
          path: "/admin/user/:id",
          name: "admin-user-edit",
          component: () => import("./views/admin/user/EditUser.vue"),
        },
      ],
    },

    {
      path: "",
      component: () => import("@/layouts/full-page/FullPage.vue"),
      children: [
        {
          path: "/pages/login",
          name: "page-login",
          component: () => import("@/views/pages/login/Login.vue"),
        },
        {
          path: "/pages/register",
          name: "page-register",
          component: () => import("@/views/pages/register/Register.vue"),
        },
        {
          path: "/pages/forgot-password",
          name: "page-forgot-password",
          component: () => import("@/views/pages/ForgotPassword.vue"),
        },
        {
          path: "/pages/reset-password",
          name: "page-reset-password",
          component: () => import("@/views/pages/ResetPassword.vue"),
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
      next("/pages/login");
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
