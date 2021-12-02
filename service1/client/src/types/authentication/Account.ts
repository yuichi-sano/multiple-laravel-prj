export interface AuthorizedAccount {
  id: string;
  name: string;
}

export const EmptyAuthorizedAccount: AuthorizedAccount = {
  id: '',
  name: '',
};
