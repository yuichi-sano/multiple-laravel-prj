import Axios, {AxiosRequestConfig, AxiosResponse} from 'axios';
import {tokenModule} from '@/stores/authentication/Authentication';
import router from '@/router';
import RetryCounter from '@/infrastructure/api/helper/RetryCounter';
import Mock from '@/infrastructure/api/helper/Mock';

export default class Auth {
  retryCounter = new RetryCounter();
  http = Axios.create();

  constructor() {
    this.http.interceptors.request.use(
      this._setupToken,
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
    return this.http.get(url, config);
  }

  async post<T = any, R = AxiosResponse<T>>(url: string, data?: any, config?: AxiosRequestConfig): Promise<R> {
    return this.http.post(url, data, config);
  }

  async put<T = any, R = AxiosResponse<T>>(url: string, data?: any, config?: AxiosRequestConfig): Promise<R> {
    return this.http.put(url, data, config);
  }

  async delete<T = any, R = AxiosResponse<T>>(url: string, config?: AxiosRequestConfig): Promise<R> {
    return this.http.delete(url, config);
  }

  _setupToken(config: AxiosRequestConfig): AxiosRequestConfig {
    config.headers.Authorization = `Bearer ${tokenModule.getAccessToken}`;
    return config;
  }

  _succeedHandling(response: AxiosResponse): AxiosResponse {
    this.retryCounter.reset();
    return response;
  }

  async _errorHandling(error: any): Promise<unknown> {
    if (401 === error.response.status) {
      await tokenModule.refresh();
      if (tokenModule.authorized) {
        error.config.headers.Authorization = `Bearer ${tokenModule.getAccessToken}`;
        return this.http.request(error.config);
      }
      await router.push({name: 'expire'}).catch(() => {
        // @ts-ignore block is empty.
      });
      return Promise.reject(error.response);
    }

    if (503 === error.response.status) {
      await router.push({name: 'maintenance'}).catch(() => {
        // @ts-ignore block is empty.
      });
      return Promise.reject(error.response);
    }

    if (500 <= error.response.status) {
      if (this.retryCounter.isLimit()) {
        this.retryCounter.reset();
        await router.push({name: 'systemerror'}).catch(() => {
          // @ts-ignore block is empty.
        });
        return Promise.reject(error.response);
      }
      if (error.config.method !== 'get') {
        await router.push({name: 'systemerror'}).catch(() => {
          // @ts-ignore block is empty.
        });
        return Promise.reject(error.response);
      }
      setTimeout(this.http.request, 1000, error.config);
      await this.retryCounter.countUp();
      return Promise.reject(error.response);
    }

    if (
      error.request.responseType === 'blob' &&
      error.response.data instanceof Blob &&
      error.response.data.type &&
      error.response.data.type.toLowerCase().indexOf('json') !== -1
    ) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => {
          if (typeof reader.result === 'string') {
            error.response.data = JSON.parse(reader.result);
            resolve(Promise.reject(error.response));
          } else {
            return Promise.reject({
              data: {
                message: 'Invalid response.',
              },
            });
          }
        };

        reader.onerror = () => {
          reject(error);
        };

        reader.readAsText(error.response.data);
      });
    }
    return Promise.reject(error.response);
  }
}
