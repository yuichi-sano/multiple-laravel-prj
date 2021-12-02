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

  get examineeId(): string {
    return this.authorizedAccount.examineeId;
  }

  get examineeNumber(): string {
    return this.authorizedAccount.examineeNumber;
  }

  get officeCode(): string {
    return this.authorizedAccount.officeCode;
  }

  get officeName(): string {
    return this.authorizedAccount.officeName;
  }

  get hasUnreadAnswer(): boolean {
    return this.authorizedAccount.hasUnreadAnswer;
  }

  get hasUserInquiry(): boolean {
    return this.authorizedAccount.hasUserInquiry;
  }

  get hasCompletion(): boolean {
    return this.authorizedAccount.hasCompletion;
  }

  get isReadOnly(): boolean {
    return this.authorizedAccount.isReadonly;
  }

  get isFastForwarding(): boolean {
    return this.authorizedAccount.isFastForwarding;
  }
}

export const loginModule = getModule(Account);
