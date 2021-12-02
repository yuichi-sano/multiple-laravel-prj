import MockAdapter from 'axios-mock-adapter';
import {AxiosInstance} from 'axios';
import moment from 'moment';


export default {
  run(axios: AxiosInstance): void {
    const mock = new MockAdapter(axios);



    mock.onPost('/api/authenticate/token').reply(200,
      'eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiIwMDE1MDAxIiwiZXhwIjoxNjA1MjQxMTE2fQ.7AFsS9h4t9A_YET0vgocIA8jtFTm1W2Sq5h-9yvQ74PEDLkWuldAZWqFTO7zVq9q',
    );

    mock.onGet('/api/authenticate/account').reply(200,
      {
        hasUnreadAnswer: false,
        hasUserInquiry: false,
        hasCompletion: true,
        isReadonly: false,
        isFastForwarding: false,
      },
    );
  },

  route(path = ''): RegExp {
    return new RegExp(path.replace(/:\w+/g, '[^/]+'));
  },

};
