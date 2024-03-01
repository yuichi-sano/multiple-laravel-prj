import Vue from 'vue';
import Router, {NavigationGuard, RouterOptions} from 'vue-router';
import {tokenModule} from '@/stores/authentication/Authentication';
import {loginModule} from '@/stores/authentication/Account';
import {AuthenticationToken} from '@/types/authentication/Authentication';
import {AuthorizedAccount} from '@/types/authentication/Account';
import {RouteConfig} from 'vue-router/types/router';
// views
import SignIn from '@/views/sign/SignIn.vue';
import DeviceDetails from '@/views/facility/device/DeviceDetails.vue';
import DeviceMaintenance from '@/views/facility/device/DeviceMaintenance.vue';
import DeviceRegister from '@/views/facility/device/DeviceRegister.vue';
import TopMenu from '@/views/TopMenu.vue';
import ZipCodeMaintenanceMenu from '@/views/zipcode/ZipCodeMaintenanceMenu.vue';
import YuuseiZipCodeMaintenance from '@/views/zipcode/yusei/YuuseiZipCodeMaintenance.vue';
import Expire from '@/views/Expire.vue';
import Maintenance from '@/views/Maintenance.vue';
import SystemError from '@/views/SystemError.vue';
import NotFound from '@/views/NotFound.vue';
import TestMenu from '@/views/TestMenu.vue';
import MasterMenu1 from '@/views/managements/MasterMenu.vue';
import MasterMenu2 from '@/views/managements/NoAuthenticationMasterMenu.vue';


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

const routes: RouteConfig[] = [
  {
    path: '/sign',
    redirect: '/signin',
  },
  {
    path: '/signin',
    name: 'signin',
    component: SignIn,
    meta: {requiresAuth: false},
    props: true,
  },
  {
    path: '/',
    redirect: '/menu',
  },
  {
    path:'/testMenu',
    name:'testMenu',
    component: TestMenu,
    meta: {title: 'テストメニュー',requiresAuth :true},
  },
  {
    path: '/menu',
    name: 'menu',
    component: TopMenu,
    meta: {title: 'メニュー', requiresAuth: true},
  },

  {
    path:'/masterMenu1',
    name:'masterMenu1',
    component: MasterMenu1,
    meta: {title: 'マスターメニュー1',requiresAuth :true},
  },
  {
    path:'/masterMenu2',
    name:'masterMenu2',
    component: MasterMenu2,
    meta: {title: 'マスターメニュー2',requiresAuth :false},
  },

  {
    path: '/zipCodeMaintenance/menu',
    name: 'zipCodeMaintenanceMenu',
    component: ZipCodeMaintenanceMenu,
    meta: {title: '郵便番号メンテナンス一覧', requiresAuth: true},
  },

  {
    path: '/zipCodeMaintenance/yuusei',
    name: 'yuuseiZipCodeMaintenance',
    component: YuuseiZipCodeMaintenance,
    meta: {title: '郵政郵便番号マスタメンテナンス', requiresAuth: true},
  },

  {
    path: '/deviceMaintenance',
    name: 'DeviceDetails',
    component: DeviceMaintenance,
    meta: {title: '端末情報詳細', requiresAuth: true},
  },
  {
    path: '/deviceMaintenance/details/:id',
    name: 'DeviceDetails',
    component: DeviceDetails,
    meta: {title: '端末情報詳細', requiresAuth: true},
  },

  {
    path: '/deviceMaintenance/register',
    name: 'DeviceRegister',
    component: DeviceRegister,
    meta: {title: '端末情報新規登録', requiresAuth: true},
  },

  {
    path: '/expire',
    name: 'expire',
    component: Expire,
  },
  {
    path: '/maintenance',
    name: 'maintenance',
    component: Maintenance,
  },
  {
    path: '/systemerror',
    name: 'systemerror',
    component: SystemError,
  },
  {
    path: '*',
    name: 'notFound',
    component: NotFound,
  },
];

const routeOptions: RouterOptions = {
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
};
const router = new Router(routeOptions);
router.beforeEach(guards);

export default router;
