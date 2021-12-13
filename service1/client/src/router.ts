import Vue from 'vue';
import Router, {NavigationGuard} from 'vue-router';
import {tokenModule} from '@/stores/authentication/Authentication';
import {loginModule} from '@/stores/authentication/Account';
import {AuthenticationToken} from '@/types/authentication/Authentication';
import {AuthorizedAccount} from '@/types/authentication/Account';

Vue.use(Router);

const samplestorekey: string | null = sessionStorage.getItem('samplestorekey');

if (samplestorekey) {
  const token: AuthenticationToken = JSON.parse(samplestorekey).token.authenticationToken;
  tokenModule.setAuthenticationToken(token);
}

const guards: NavigationGuard<Vue> = (to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!tokenModule.authorized) {
      next({path: '/signin'});
      return;
    }
    const storage: string | null = sessionStorage.getItem('samplestorekey');
    if (storage) {
      const account: AuthorizedAccount = JSON.parse(storage).account.authorizedAccount;
      if (account) {
        loginModule.setAccount(account);
      }
    }
  }
  next();
};

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/sign',
      redirect: '/signin',
    },
    {
      path: '/signin',
      name: 'signin',
      component: () => import('@/views/sign/SignIn.vue'),
      meta: {requiresAuth: false},
      props: true,
    },
    
    {
      path: '/',
      redirect: '/mypage',
    },
    {
      path: '/mypage',
      name: 'mypage',
      component: () => import('@/views/mypage/MyPage.vue'),
      meta: {title: 'マイページ', requiresAuth: true},
    },
    
    {
      path: '/expire',
      name: 'expire',
      component: () => import('@/views/Expire.vue'),
    },
    {
      path: '/maintenance',
      name: 'maintenance',
      component: () => import('@/views/Maintenance.vue'),
    },
    {
      path: '/systemerror',
      name: 'systemerror',
      component: () => import('@/views/SystemError.vue'),
    },
    {
      path: '*',
      name: 'notFound',
      component: () => import('@/views/NotFound.vue'),
    },
  ],
});

router.beforeEach(guards);

export default router;
