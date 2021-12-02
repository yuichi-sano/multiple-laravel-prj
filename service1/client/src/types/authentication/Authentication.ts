export interface AuthenticationToken {
  accessToken: string;
  refreshToken: string;
}

export interface AuthenticationRequest {
  access_id: string;
  password: string;
}
