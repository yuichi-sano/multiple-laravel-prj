import Auth from '@/infrastructure/api/helper/Auth';
import Client from '@/infrastructure/api/helper/Client';

import {AuthenticationRequest, AuthenticationToken} from '@/types/authentication/Authentication';
import {AuthorizedAccount} from '@/types/authentication/Account';


export default {

  async authenticate(request: AuthenticationRequest): Promise<AuthenticationToken> {
    const client = new Client();
    const response = await client.post('/login', request);
    return response.data.result;
  },

  async refresh(refreshToken: string): Promise<string> {
    const client = new Client();
    const response = await client.post('/refresh', {
      refreshToken,
    });
    return response.data.result.accessToken;
  },

  async getAccount(): Promise<AuthorizedAccount> {
    const auth = new Auth();
    const response = await auth.get('/sample');
    return response.data;
  },
  async getSample(): Promise<AuthorizedAccount> {
    const auth = new Auth();
    const response = await auth.get('/sample');
    return response.data;
  },

};
