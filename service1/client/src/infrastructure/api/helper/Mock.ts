import MockAdapter from 'axios-mock-adapter';
import {AxiosInstance} from 'axios';
import moment from 'moment';
import { ZipCodeYuseiUpdate } from '@/types/zipCodeYusei/ZipCodeYuseiUpdate';
import { ZipCodeKenAll } from '@/types/zipCodeYusei/ZipCodeKenAll';
import { ZipCodeYuseiBulk } from '@/types/zipCodeYusei/ZipCodeYuseiBulk';
import { ZipCodeYuseiSearchResponse } from '@/types/zipCodeYusei/ZipCodeYuseiSearch';
import { Prefecture } from '@/types/prefecture/Prefecture';
import { BumonCode } from '@/types/htDevice/BumonCode';
import { SlipType } from '@/types/htDevice/SlipType';
import { ZipCodeIndividualRegisterResponse } from '@/types/zipCodeYusei/ZipCodeIndividualRegister';
import { ZipCodeYuseiDeleteResponse } from '@/types/zipCodeYusei/ZipCodeYuseiDelete';
import { HtDeviceGetResponse } from '@/types/htDevice/htDeviceGet';
import { HtDeviceUpdateResponse } from '@/types/htDevice/htDeviceUpdate';
import { HtDeviceDetails } from '@/types/htDevice/HtDeviceDetails';



export default {
  run(axios: AxiosInstance): void {
    const mock = new MockAdapter(axios);
    const id = 10;



    mock.onPost('/api/authenticate/token').reply(200,
      'eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiIwMDE1MDAxIiwiZXhwIjoxNjA1MjQxMTE2fQ.7AFsS9h4t9A_YET0vgocIA8jtFTm1W2Sq5h-9yvQ74PEDLkWuldAZWqFTO7zVq9q',
    );

    mock.onGet('/api/authenticate/account').reply(200,
      {
        hasUnreadAnswer: false,
        hasUserInquiry: false,
        hasCompletion: true,
        isReadonly: false,
        isFastForwarding: false,
      },
    );


    // 郵政

    mock.onGet('/zip_code/yusei/last_update').reply((config) => {
      const data: ZipCodeYuseiUpdate = {
        bulkUpdateDate: '2022/04/04',
        bulkUser: 'test user',
        addDate: '2022/04/04',
        user: 'test user',
        zipCode: '1000000',
        kenmei: '県名',
        kenCode: 10 ,
        sikumei: '市区名',
        sikuCode: 10,
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/zip_code/ken_all').reply((config) => {
      const data: ZipCodeKenAll = {
        differenceNumber: 1,
        zipCodeList: [
          {
            old: 'old',
            new: 'new',
          },
        ],
        yuseiList: [
          {
            old: 'old',
            new: 'new',
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onPut('/zip_code/yusei/bulk').reply((config) => {
      const data: ZipCodeYuseiBulk = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });


    mock.onGet('/zip_code/yusei/search').reply((config) => {
      const data: ZipCodeYuseiSearchResponse = {
        zipCodeList: [
          {
            id: 0,
            zipCode: '1000000',
            kenmei: '県名',
            kenCode: 0,
            sikumei: '市区名',
            sikuCode: 0,
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onPost('/zip_code/yusei').reply((config) => {
      const data: ZipCodeIndividualRegisterResponse = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onPut('/zip_code/yusei/' + id).reply((config) => {
      const data: ZipCodeIndividualRegisterResponse = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onDelete('/zip_code/yusei/' + id).reply((config) => {
      const data: ZipCodeYuseiDeleteResponse = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/prefecture').reply((config) => {
      const data: Prefecture = {

            prefectureId: '001',
            prefectureName: 'prefecture',

      };
      // @ts-ignore
      return this.__success(data, config);
    });


    // 配送端末

    mock.onGet('/ht_device').reply((config) => {
      const data: HtDeviceGetResponse = {
        page: {
          resultCount: 0,
          totalPages: 0,
          previousPage: 0,
          currentPage: 1,
          nextPage: 0,
        },
        htDeviceList: [
          {
            htHostId: null,
            htHostName: '',
            htHostIp: '',
            deliveryWorkplaceName: '',
            facilityCode: '',
            location: '',
            slipType: '',
            addressList: [
              {
                htDeviceIp: '',
                location: '',
              },
            ],
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onPut('/ht_device/' + id).reply((config) => {
      const data: HtDeviceUpdateResponse = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/ht_device/' + id).reply((config) => {
      const data: HtDeviceDetails = {
        htDeviceList: [
          {
            htHostName: 'ホスト名',
            htHostIp: 'ホストIP',
            deliveryWorkplaceName: '工場',
            facilityCode: '000',
            location: '設置場所',
            slipType: '伝票種別',
            addressList: [
              {
                htDeviceIp: '192.0.0.0',
                location: 'ロケーション',
              },
            ],
          },
      ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/bumon_code').reply((config) => {
      const data: BumonCode = {
        facilityCodeList: [
          {
            facilityCode: '00',
            deliveryWorkplaceName: '',
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/slip/type').reply((config) => {
      const data: SlipType = {
        slipTypeList: [
          {
            slipTypeId: 0,
            slipTypeName: '伝票',
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

  },



  route(path = ''): RegExp {
    return new RegExp(path.replace(/:\w+/g, '[^/]+'));
  },

};
