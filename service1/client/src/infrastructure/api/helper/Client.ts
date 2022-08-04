import Axios, {AxiosInstance, AxiosRequestConfig, AxiosResponse} from 'axios';
import axiosCookieJarSupport from 'axios-cookiejar-support';
import tough from 'tough-cookie';
import router from '@/router';
import Mock from '@/infrastructure/api/helper/Mock';

export default class Client {
  http: AxiosInstance;

  constructor() {
    axiosCookieJarSupport(Axios);
    const cookieJar = new tough.CookieJar();
    this.http = Axios.create(
      {
        jar: cookieJar,
        withCredentials: true,
      },
    );

    this.http.interceptors.response.use(
      this._succeedHandling.bind(this),
      this._errorHandling.bind(this),
    );

    if (process.env.VUE_APP_USE_MOCK === 'true') {
      Mock.run(this.http);
    }
  }

  async get<T = any, R = AxiosResponse<T>>(url: string, config?: AxiosRequestConfig): Promise<R> {
    return this.http.get(url);
  }

  async post<T = any, R = AxiosResponse<T>>(url: string, data?: any, config?: AxiosRequestConfig): Promise<R> {
    return this.http.post(url, data, config);
  }

  _succeedHandling(response: AxiosResponse): AxiosResponse {
    return response;
  }

  async _errorHandling(error: any): Promise<unknown> {
    // for NetWorkError
    if (error.response === undefined) {
      await router.push({name: 'systemerror'}).catch(() => {
        // @ts-ignore block is empty.
      });
      return Promise.reject(error.response);
    }
    if (401 === error.response.status) {
      // await router.push({name: 'expire'}).catch(() => {
        // @ts-ignore block is empty.
      // });
      return Promise.reject(error.response);
    }

    if (503 === error.response.status) {
      await router.push({name: 'maintenance'}).catch(() => {
        // @ts-ignore block is empty.
      });
      return Promise.reject(error.response);
    }

    if (500 <= error.response.status) {
      await router.push({name: 'systemerror'}).catch(() => {
        // @ts-ignore block is empty.
      });
      return Promise.reject(error.response);
    }
    return Promise.reject(error.response);
  }
}
