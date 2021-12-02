export interface AuthorizedAccount {
  accessId: string;
  name: string;
}

export const EmptyAuthorizedAccount: AuthorizedAccount = {
  accessId: '',
  name: '',
};
