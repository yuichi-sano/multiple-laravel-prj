export interface AuthorizedAccount {
  userId: string;
  name: string;
}

export const EmptyAuthorizedAccount: AuthorizedAccount = {
  userId: '',
  name: '',
};
