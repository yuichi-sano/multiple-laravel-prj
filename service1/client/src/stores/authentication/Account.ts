import {Module, VuexModule, Mutation, Action, getModule} from 'vuex-module-decorators';
import {AuthorizedAccount, EmptyAuthorizedAccount} from '@/types/authentication/Account';
import api from '@/infrastructure/api/API';
import store from '@/store';

@Module({dynamic: true, store, name: 'account', namespaced: true})
class Account extends VuexModule {
  authorizedAccount: AuthorizedAccount = {...EmptyAuthorizedAccount};

  @Mutation
  setAccount(authorizedAccount: AuthorizedAccount) {
    this.authorizedAccount = authorizedAccount;
  }

  @Mutation
  clearAccount() {
    this.authorizedAccount = {...EmptyAuthorizedAccount};
  }

  @Action({rawError: true})
  async getAccount(): Promise<void> {
    const account = await api.getAccount();
    this.setAccount(account);
  }

  @Action({})
  clear(): void {
    this.clearAccount();
  }

  get userId(): string {
    return this.authorizedAccount.userId;
  }

}

export const loginModule = getModule(Account);
