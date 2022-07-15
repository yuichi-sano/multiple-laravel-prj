export interface AuthenticationToken {
  accessToken: string;
  refreshToken: string;
}

export interface AuthenticationRequest {
  userId: string;
  password: string;
}
