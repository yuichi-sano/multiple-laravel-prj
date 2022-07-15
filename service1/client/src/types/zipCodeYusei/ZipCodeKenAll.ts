export interface ZipCodeKenAll {
  differenceNumber: number;
  zipCodeList: [
    {
      old: string,
      new: string,
    }
  ];
  yuseiList: [
      {
          old: string,
          new: string,
      }
  ];
}

export const EmptyZipCodeKenAll: ZipCodeKenAll = {
  differenceNumber: -1,
  zipCodeList: [
    {
      old: '',
      new: '',
    },
  ],
  yuseiList: [
    {
      old: '',
      new: '',
    },
  ],
};
