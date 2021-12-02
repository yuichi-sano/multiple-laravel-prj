import {Module, VuexModule, Mutation, Action, getModule} from 'vuex-module-decorators';
import {AuthenticationToken, AuthenticationRequest} from '@/types/authentication/Authentication';
import api from '@/infrastructure/api/API';
import store from '@/store';

@Module({dynamic: true, store, name: 'token', namespaced: true})
class Token extends VuexModule {
  authenticationToken: AuthenticationToken = {
    accessToken: '',
    refreshToken: '',
  };

  @Mutation
  setAuthenticationToken(authenticationToken: AuthenticationToken) {
    this.authenticationToken = authenticationToken;
  }

  @Mutation
  setAccessToken(accessToken: string) {
    this.authenticationToken.accessToken = accessToken;
  }

  @Mutation
  clearToken() {
    this.authenticationToken = {
      accessToken: '',
      refreshToken: '',
    };
  }

  @Mutation
  clearAccessToken() {
    this.authenticationToken.accessToken = '';
  }

  @Action({rawError: true})
  async authenticate(request: AuthenticationRequest): Promise<void> {
    const token = await api.authenticate(request);
    this.setAuthenticationToken(token);
  }

  @Action({rawError: true})
  async refresh(): Promise<void> {
    await api.refresh(this.getRefreshToken)
      .then((response: any) => {
        this.setAccessToken(response);
      })
      .catch((response: any) => {
        this.clearToken();
      });
  }

  @Action
  clear(): void {
    this.clearToken();
  }

  get authorized(): boolean {
    if (!this.authenticationToken) {
      return false;
    }
    return this.authenticationToken.refreshToken.length > 0;
  }

  get getRefreshToken() {
    return this.authenticationToken.refreshToken;
  }

  get getAccessToken() {
    return this.authenticationToken.accessToken;
  }
}

export const tokenModule = getModule(Token);
