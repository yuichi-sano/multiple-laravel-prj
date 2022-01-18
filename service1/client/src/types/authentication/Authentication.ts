export interface AuthenticationToken {
  accessToken: string;
  refreshToken: string;
}

export interface AuthenticationRequest {
  accessId: string;
  password: string;
}
