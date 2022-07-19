import MockAdapter from 'axios-mock-adapter';
import {AxiosInstance} from 'axios';
import moment from 'moment';
import { ZipCodeYuseiUpdate } from '@/types/zipCodeYusei/ZipCodeYuseiUpdate';
import { ZipCodeKenAll } from '@/types/zipCodeYusei/ZipCodeKenAll';
import { ZipCodeYuseiBulk } from '@/types/zipCodeYusei/ZipCodeYuseiBulk';
import { ZipCodeYuseiSearchResponse } from '@/types/zipCodeYusei/ZipCodeYuseiSearch';
import { Prefecture } from '@/types/prefecture/Prefecture';
import { WorkPlace } from '@/types/Device/WorkPlace';
import { SlipType } from '@/types/Device/SlipType';
import { ZipCodeIndividualRegisterResponse } from '@/types/zipCodeYusei/ZipCodeIndividualRegister';
import { ZipCodeYuseiDeleteResponse } from '@/types/zipCodeYusei/ZipCodeYuseiDelete';
import { DeviceGetResponse } from '@/types/Device/DeviceGet';
import { DeviceUpdateResponse } from '@/types/Device/DeviceUpdate';
import { DeviceDetails } from '@/types/Device/DeviceDetails';



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

    mock.onGet('/device').reply((config) => {
      const data: DeviceGetResponse = {
        page: {
          resultCount: 0,
          totalPages: 0,
          previousPage: 0,
          currentPage: 1,
          nextPage: 0,
        },
        DeviceList: [
          {
            HostId: null,
            HostName: '',
            HostIp: '',
            deliveryWorkPlaceName: '',
            facilityCode: '',
            location: '',
            addressList: [
              {
                DeviceIp: '',
                location: '',
              },
            ],
          },
        ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onPut('/device/' + id).reply((config) => {
      const data: DeviceUpdateResponse = {

      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/device/' + id).reply((config) => {
      const data: DeviceDetails = {
        DeviceList: [
          {
            HostName: 'ホスト名',
            HostIp: 'ホストIP',
            deliveryWorkPlaceName: '工場',
            facilityCode: '000',
            location: '設置場所',
            addressList: [
              {
                DeviceIp: '192.0.0.0',
                location: 'ロケーション',
              },
            ],
          },
      ],
      };
      // @ts-ignore
      return this.__success(data, config);
    });

    mock.onGet('/workplace').reply((config) => {
      const data: WorkPlace = {
        facilityCodeList: [
          {
            facilityCode: '00',
            deliveryWorkPlaceName: '',
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
