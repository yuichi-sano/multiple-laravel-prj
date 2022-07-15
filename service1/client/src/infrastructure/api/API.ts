import Auth from '@/infrastructure/api/helper/Auth';
import Client from '@/infrastructure/api/helper/Client';

import {AuthenticationRequest, AuthenticationToken} from '@/types/authentication/Authentication';
import {AuthorizedAccount} from '@/types/authentication/Account';
import { ZipCodeIndividualRegisterRequest, ZipCodeIndividualRegisterResponse } from '@/types/zipCodeYusei/ZipCodeIndividualRegister';
import { HtDeviceGetRequest, HtDeviceGetResponse } from '@/types/htDevice/htDeviceGet';
import { HtDevicePostRequest, HtDevicePostResponse } from '@/types/htDevice/htDevicePost';
import { HtDeviceUpdateRequest, HtDeviceUpdateResponse } from '@/types/htDevice/htDeviceUpdate';
import { BumonCode } from '@/types/htDevice/BumonCode';
import { HtDeviceDetails } from '@/types/htDevice/HtDeviceDetails';
import { SlipType } from '@/types/htDevice/SlipType';

import { ZipCodeYuseiUpdate } from '@/types/zipCodeYusei/ZipCodeYuseiUpdate';
import { ZipCodeKenAll } from '@/types/zipCodeYusei/ZipCodeKenAll';
import { ZipCodeYuseiBulk } from '@/types/zipCodeYusei/ZipCodeYuseiBulk';
import { ZipCodeYuseiSearchRequest, ZipCodeYuseiSearchResponse } from '@/types/zipCodeYusei/ZipCodeYuseiSearch';
import { Prefecture } from '@/types/prefecture/Prefecture';
import { ZipCodeYuseiDeleteResponse } from '@/types/zipCodeYusei/ZipCodeYuseiDelete';



export default {

  async authenticate(request: AuthenticationRequest): Promise<AuthenticationToken> {
    const client = new Client();
    const response = await client.post('/login', request);
    return response.data.result;
  },

  async refresh(refreshToken: string): Promise<string> {
    const client = new Client();
    const response = await client.post('/refresh', {
      refreshToken,
    });
    return response.data.result.accessToken;
  },

  async healthCheck(): Promise<string> {
      const client = new Client();
      const response = await client.get('/health_check');
      return response.data.state;
  },


  async getAccount(): Promise<AuthorizedAccount> {
    const auth = new Auth();
    const response = await auth.get('/sample');
    return response.data.result;
  },
  async getSample(): Promise<AuthorizedAccount> {
    const auth = new Auth();
    const response = await auth.get('/sample');
    return response.data.result;
  },


  // 郵政
  async getZipCodeYuseiUpdate(): Promise<ZipCodeYuseiUpdate> {
    const auth = new Auth();
    const response = await auth.get('/zip_code/yusei/last_update');
    return response.data.result;
  },

  async getZipCodeKenAll(): Promise<ZipCodeKenAll> {
    const auth = new Auth();
    const response = await auth.get('/zip_code/ken_all');
    return response.data.result;
  },

  async updateZipCodeYuseiBulk(): Promise<ZipCodeYuseiBulk> {
    const auth = new Auth();
    const response = await auth.put('/zip_code/yusei/bulk');
    return response.data.result;
  },

  async getZipCodeYuseiSearch(request: ZipCodeYuseiSearchRequest): Promise<ZipCodeYuseiSearchResponse> {
    const auth = new Auth();
    const response = await auth.get('/zip_code/yusei/search', {params: {zipCode: request.zipCode}});
    return response.data.result;
  },

  async sendZipCodeYusei(request: ZipCodeIndividualRegisterRequest): Promise<ZipCodeIndividualRegisterResponse> {
    const auth = new Auth();
    const response = await auth.post('/zip_code/yusei', request);
    return response.data.result;
  },

  async updateZipCodeYusei(request: ZipCodeIndividualRegisterRequest, id: number): Promise<ZipCodeIndividualRegisterResponse> {
    const auth = new Auth();
    const response = await auth.put('/zip_code/yusei/' + id, request);
    return response.data.result;
  },

  async deleteZipCodeYusei(id: number): Promise<ZipCodeYuseiDeleteResponse> {
    const auth = new Auth();
    const response = await auth.delete('/zip_code/yusei/' + id);
    return response.data.result;
  },

  async getPrefecture(): Promise<Prefecture> {
    const auth = new Auth();
    const response = await auth.get('/prefecture');
    return response.data.result;
  },

  // 配送端末
  async getHtDevice(request: HtDeviceGetRequest): Promise<HtDeviceGetResponse> {
    const auth = new Auth();
    const response = await auth.get('/ht_device', {params: request});
    return response.data.result;
  },

  async sendHtDevice(request: HtDevicePostRequest): Promise<HtDevicePostResponse> {
    const auth = new Auth();
    const response = await auth.post('/ht_device', request);
    return response.data.result;
  },

  async updateHtDevice(request: HtDeviceUpdateRequest, id: number): Promise<HtDeviceUpdateResponse> {
    const auth = new Auth();
    const response = await auth.put('/ht_device/' + id, request);
    return response.data.result;
  },

  async getHtDeviceDetails(id: number): Promise<HtDeviceDetails> {
    const auth = new Auth();
    const response = await auth.get('/ht_device/' + id);
    return response.data.result;
  },

  async getBumonCode(): Promise<BumonCode> {
    const auth = new Auth();
    const response = await auth.get('/bumon_code');
    return response.data.result;
  },

  async getSlipType(): Promise<SlipType> {
    const auth = new Auth();
    const response = await auth.get('/slip/type');
    return response.data.result;
  },

};
