export default [
  {
    url: null,
    name: "Dashboard",
    tagColor: "warning",
    icon: "PieChartIcon",
    i18n: "Dashboard",
    url: "/dashboard",
  },
  {
    header: "AppPost",
    icon: "PackageIcon",
    i18n: "AppPost",
    items: [
      {
        url: "/admin/posts",
        name: "post",
        slug: "post",
        icon: "ListIcon",
        i18n: "Post",
      },
      {
        url: "/admin/add-post",
        name: "add-post",
        slug: "add-post",
        icon: "PlusCircleIcon",
        i18n: "AddPost",
      },
    ],
  },
  {
    header: "AppUser",
    icon: "PackageIcon",
    i18n: "AppUser",
    items: [
      {
        url: "/admin/user/list",
        name: "User List",
        slug: "app-user-list",
        i18n: "UserList",
        icon: "ListIcon",
      },
      {
        url: "/admin/user/add-user",
        name: "AddUser",
        slug: "app-user-add",
        i18n: "AddUser",
        icon: "PlusCircleIcon",
      },
    ],
  },
];
