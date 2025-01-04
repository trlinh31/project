import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import { WHITE_LIST_ROUTE, isUserLoggedIn } from './utils'
import routes from '~pages'
import { profile } from '@/api/auth'
import { HTTP_STATUS } from '@/constants/common'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: _ => {
        return { name: 'post' }
      },
    },
    {
      path: '/pages/user-profile',
      redirect: () => ({ name: 'pages-user-profile-tab', params: { tab: 'profile' } }),
    },
    {
      path: '/pages/account-settings',
      redirect: () => ({ name: 'pages-account-settings-tab', params: { tab: 'account' } }),
    },
    ...setupLayouts(routes),
  ],
})

router.beforeEach(async (to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  const { useUserStore } = await import('@/pinia/userStore')

  if (isLoggedIn) {
    if (!useUserStore.userInfo.name) {
      try {
        const res = await profile()
        if (res.status === HTTP_STATUS.OK)
          useUserStore.setUserInfo(res.data.user)
      }
      catch (e) {
        await useUserStore.logout()
        next('/login')

        return
      }
    }
    next()
  }
  else {
    return WHITE_LIST_ROUTE.includes(to.name as string) ? next() : next('/login')
  }
})

export default router
