import Auth from '@/infrastructure/api/helper/Auth';
import Client from '@/infrastructure/api/helper/Client';

import {AuthenticationRequest, AuthenticationToken} from '@/types/authentication/Authentication';
import {AuthorizedAccount} from '@/types/authentication/Account';


export default {

  async authenticate(request: AuthenticationRequest): Promise<AuthenticationToken> {
    const client = new Client();
    const response = await client.post('/api/authenticate', request);
    return response.data;
  },

  async refresh(refreshToken: string): Promise<string> {
    const client = new Client();
    const response = await client.post('/api/authenticate/token', {
      refreshToken,
    });
    return response.data.accessToken;
  },

  async getAccount(): Promise<AuthorizedAccount> {
    const auth = new Auth();
    const response = await auth.get('/api/authenticate/account');
    return response.data;
  },
  async setSignedCookies(): Promise<void> {
    const auth = new Auth();
    await auth.get('/api/signed/cookies');
  },

};
