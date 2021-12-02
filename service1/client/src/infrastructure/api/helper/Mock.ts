import MockAdapter from 'axios-mock-adapter';
import {AxiosInstance} from 'axios';
import moment from 'moment';
import {InquiryType} from '@/types/inquiry/InquiryType';
import {InquiryStatus} from '@/types/inquiry/InquiryStatus';
import {ProgressStatus} from '@/types/content/ProgressStatus';
import {EmptyMaterial} from '@/types/material/Material';

export default {
  run(axios: AxiosInstance): void {
    const mock = new MockAdapter(axios);

    mock.onGet(this.route('/api/examinee/:examineeNumber/office/:officeCode')).reply(200,
      {
        examineeId: '0015001',
        examineeNumber: '15001',
        officeCode: '00',
        companyOrSchoolName: 'インサイト',
      },
    );

    mock.onPost('/api/examinee').reply(200);

    mock.onPost('/api/authenticate').reply(200,
      {
        accessToken: 'eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiIwMDE1MDAxIiwiZXhwIjoxNjA1MjQxMTE2fQ.7AFsS9h4t9A_YET0vgocIA8jtFTm1W2Sq5h-9yvQ74PEDLkWuldAZWqFTO7zVq9q',
        refreshToken: '$2a$10$AH14t.K3pcRM1a1gWXaT/eP3f0aucj.xb/WtGO/jmuryl.N0nha2u',
      },
    );

    mock.onPost('/api/authenticate/token').reply(200,
      'eyJhbGciOiJIUzM4NCJ9.eyJzdWIiOiIwMDE1MDAxIiwiZXhwIjoxNjA1MjQxMTE2fQ.7AFsS9h4t9A_YET0vgocIA8jtFTm1W2Sq5h-9yvQ74PEDLkWuldAZWqFTO7zVq9q',
    );

    mock.onGet('/api/authenticate/account').reply(200,
      {
        examineeId: '0015001',
        examineeNumber: '15001',
        officeCode: '00',
        officeName: '本部',
        hasUnreadAnswer: false,
        hasUserInquiry: false,
        hasCompletion: true,
        isReadonly: false,
        isFastForwarding: false,
      },
    );

    mock.onGet('/api/offices').reply(200,
      [
        {
          code: '00',
          name: '本部',
          contact: '03-5776-0901',
        },
        {
          code: '01',
          name: '札幌支所',
          contact: '011-704-4195',
        },
        {
          code: '02',
          name: '旭川支所',
          contact: '0166-51-9456',
        },
        {
          code: '03',
          name: '函館支所',
          contact: '0138-49-4533',
        },
        {
          code: '04',
          name: '帯広支所',
          contact: '0155-33-2238',
        },
        {
          code: '05',
          name: '北見支所',
          contact: '0157-24-8321',
        },
        {
          code: '06',
          name: '釧路支所',
          contact: '0154-51-2231',
        },
        {
          code: '11',
          name: '青森県支所',
          contact: '017-776-4214',
        },
        {
          code: '12',
          name: '岩手県支所',
          contact: '019-638-0086',
        },
        {
          code: '13',
          name: '宮城県支所',
          contact: '022-284-5093',
        },
        {
          code: '14',
          name: '福島県支所',
          contact: '024-546-4012',
        },
        {
          code: '15',
          name: '秋田県支所',
          contact: '018-862-5721',
        },
        {
          code: '16',
          name: '山形県支所',
          contact: '023-686-4525',
        },
        {
          code: '21',
          name: '東京都支所',
          contact: '03-5418-7001',
        },
        {
          code: '31',
          name: '新潟県支所',
          contact: '025-285-6287',
        },
        {
          code: '32',
          name: '長野県支所',
          contact: '026-227-2236',
        },
        {
          code: '33',
          name: '茨城県支所',
          contact: '029-247-3633',
        },
        {
          code: '34',
          name: '栃木県支所',
          contact: '028-658-1639',
        },
        {
          code: '35',
          name: '群馬県支所',
          contact: '027-261-6331',
        },
        {
          code: '36',
          name: '埼玉県支所',
          contact: '048-622-0071',
        },
        {
          code: '37',
          name: '千葉県支所',
          contact: '043-242-0459',
        },
        {
          code: '38',
          name: '神奈川県支所',
          contact: '045-933-6011',
        },
        {
          code: '39',
          name: '山梨県支所',
          contact: '055-263-0351',
        },
      ],
    );

    mock.onPost('/api/inquiries').reply(200,
      [
        {
          seq: 1,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 2,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Answered,
        },
        {
          seq: 3,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Completed,
        },
        {
          seq: 4,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 5,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 6,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 7,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 8,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 9,
          type: InquiryType.ToOffice,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 10,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 11,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 12,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 13,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 14,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 15,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 16,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 17,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 18,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 19,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 20,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 21,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 22,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
        {
          seq: 23,
          type: InquiryType.ToSystem,
          inquiresAt: moment().toDate(),
          title: '問い合わせタイトル',
          status: InquiryStatus.Inquiry,
        },
      ],
    );

    mock.onGet(this.route('/api/inquiries/:inquirySeq')).reply(200,
      {
        seq: 1,
        type: InquiryType.ToSystem,
        inquiresAt: moment().toDate(),
        title: 'タイトル',
        content: '問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容問い合わせ内容',
        status: InquiryStatus.Inquiry,
        answer: {
          content: '',
        },
      },
    );

    mock.onPost(this.route('/api/inquiry')).reply(200);

    mock.onGet('/api/materials').reply(200,
      [
        {
          id: 1000,
          name: '中古自動車査定基準［Ⅰ］',
          statuses: [
            ProgressStatus.Unlearned,
            ProgressStatus.Learning,
            ProgressStatus.Testing,
            ProgressStatus.Unlearned,
            ProgressStatus.Learned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
          ],
          nextStatus: ProgressStatus.Unlearned,
          contents: null,
        },
        {
          id: 2000,
          name: '自動車構造知識',
          statuses: [
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
          ],
          nextStatus: ProgressStatus.Unlearned,
          contents: null,
        },
        {
          id: 3000,
          name: '査定の実務',
          statuses: [
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
          ],
          nextStatus: ProgressStatus.Learned,
          contents: null,
        },
        {
          id: 4000,
          name: '練習問題解説',
          statuses: [
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
          ],
          nextStatus: ProgressStatus.Unlearned,
          contents: null,
        },
      ],
    );

    mock.onGet(this.route('/api/materials/:materialId')).reply((config) => {
      // @ts-ignore
      const params = config.url.match(/\/api\/materials\/(.+)/) as RegExpMatchArray;
      if (Number(params[1]) === 2000) {
        return [200, {
          id: 2000,
          name: '自動車構造知識',
          statuses: [
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
          ],
          nextStatus: ProgressStatus.Unlearned,
          contents: [
            {
              id: 2001,
              name: '自動車構造知識',
              description: 'はじめに～10ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:16:43',
              hasTest: false,
              status: ProgressStatus.Unlearned,
              isViewable: true,
            },
            {
              id: 2002,
              name: '自動車構造知識',
              description: '11ページ～28ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:36:26',
              hasTest: false,
              status: ProgressStatus.Unlearned,
              isViewable: false,
            },
            {
              id: 2003,
              name: '自動車構造知識',
              description: '29ページ～45ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:40:43',
              hasTest: false,
              status: ProgressStatus.Unlearned,
              isViewable: false,
            },
            {
              id: 2004,
              name: '自動車構造知識',
              description: '46ページ～59ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:34:57',
              hasTest: false,
              status: ProgressStatus.Unlearned,
              isViewable: false,
            },
          ],
        }];
      }
      if (Number(params[1]) === 3000) {
        return [200, {
          id: 3000,
          name: '査定の実務',
          statuses: [
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
            ProgressStatus.Learned,
          ],
          nextStatus: ProgressStatus.Learned,
          contents: [
            {
              id: 3001,
              name: '査定の実務',
              description: '前書き～13ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:29:44',
              hasTest: true,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3002,
              name: '査定の実務',
              description: '14ページ～17ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:12:15',
              hasTest: false,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3003,
              name: '査定の実務',
              description: '17ページ～23ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:20:46',
              hasTest: true,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3004,
              name: '査定の実務',
              description: '24ページ～28ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:20:38',
              hasTest: false,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3005,
              name: '査定の実務',
              description: '29ページ～36ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:30:34',
              hasTest: true,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3006,
              name: '査定の実務',
              description: '37ページ～42ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:14:15',
              hasTest: false,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3007,
              name: '査定の実務',
              description: '43ページ～59ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:48:08',
              hasTest: true,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
            {
              id: 3008,
              name: '査定の実務',
              description: '60ページ～87ページまで',
              sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
              recordingTime: '00:27:30',
              hasTest: false,
              status: ProgressStatus.Learned,
              isViewable: true,
            },
          ],
        }];
      }
      return [200, {
        id: 1000,
        name: '中古自動車査定基準［Ⅰ］',
        statuses: [
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
          ProgressStatus.Unlearned,
        ],
        nextStatus: ProgressStatus.Unlearned,
        contents: [
          {
            id: 1001,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '目次～5ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:29:25',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: true,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1002,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '5ページ～13ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:28:01',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1003,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '18ページ～27ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:13:22',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1004,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '28ページ～32ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:27:52',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1005,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '33ページ～36ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:10:26',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1006,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '37ページ～39ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:14:18',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1007,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '40ページ～42ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:09:44',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1008,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '43ページ～46ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:17:27',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1009,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '47ページ～56ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:19:11',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1010,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '57ページ～60ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:18:32',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
          {
            id: 1011,
            name: '中古自動車査定基準及び細則〔1〕',
            description: '61ページ～97ページまで',
            sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
            recordingTime: '00:18:28',
            hasTest: false,
            status: ProgressStatus.Unlearned,
            isViewable: false,
            latestTimeSeconds: 0,
            material: EmptyMaterial,
          },
        ],
      }];
    });

    mock.onGet(this.route('/api/contents/:contentId')).reply(200,
      {
        id: 1011,
        name: '中古自動車査定基準及び細則〔1〕',
        description: '61ページ～97ページまで',
        sourceURL: 'https://jaai-media-convert-dev.s3-ap-northeast-1.amazonaws.com/el01_ppt01_001.m3u8',
        recordingTime: '00:18:28',
        hasTest: false,
        status: ProgressStatus.Unlearned,
        isViewable: false,
        latestTimeSeconds: 0,
        material: {
          id: 1000,
          name: '中古自動車査定基準［Ⅰ］',
          statuses: [
            ProgressStatus.Unlearned,
            ProgressStatus.Learning,
            ProgressStatus.Testing,
            ProgressStatus.Unlearned,
            ProgressStatus.Learned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
            ProgressStatus.Unlearned,
          ],
          nextStatus: ProgressStatus.Unlearned,
          contents: null,
        },
      },
    );

    mock.onGet('/api/signed/cookies').reply(200);

    mock.onPost('/api/examinee/test/content').reply(200);

    mock.onPost('/api/examinee/view/content').reply(200);

    mock.onPost('/api/examinee/view/content/latest').reply(200);

    mock.onPost('/api/examinee/completion').reply(200);

    mock.onGet('/api/information').reply(200,
      {
        id: 50,
        text: 'お知らせです\n本日メンテナンスを行います\nよろしくおねがいします。',
      },
    );
  },

  route(path = ''): RegExp {
    return new RegExp(path.replace(/:\w+/g, '[^/]+'));
  },

};
