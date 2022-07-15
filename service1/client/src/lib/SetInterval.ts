import { VueConstructor } from 'vue';

export default {
  install(vue: VueConstructor): void {
    vue.prototype.$intervals = [];
    vue.prototype.$setInterval = (func: Function, intervalMilliSec: number) => {
      if (typeof process.env.VUE_APP_DISABLE_SET_INTERVAL !== 'undefined') {
        return null;
      }
      const id = setInterval(() => {
        if (document.visibilityState === 'visible') {
          func();
        }
      }, intervalMilliSec);
      vue.prototype.$intervals.push(id);
      return id;
    };
    vue.prototype.$clearInterval = (id: number) => {
      clearInterval(id);
      vue.prototype.$intervals = vue.prototype.$intervals.filter((i: number) => i !== id);
    };
    vue.prototype.$clearAllIntervals = () => {
      vue.prototype.$intervals.forEach(clearInterval);
      vue.prototype.$intervals = [];
    };
  },
};
