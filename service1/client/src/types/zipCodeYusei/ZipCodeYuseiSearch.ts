export interface ZipCodeYuseiSearchRequest {
    zipCode: string;
}



export interface ZipCodeYuseiSearchResponse {
    zipCodeList: [
        {
          id: number,
          zipCode: string,
          kenmei: string,
          kenCode: number,
          sikumei: string,
          sikuCode: number,
        }
    ];
}

// @ts-ignore
export const EmptyZipCodeYuseiSearchResponse: ZipCodeYuseiSearchResponse = {
  zipCodeList: [
    {
      id: 0,
      zipCode: '',
      kenmei: '',
      kenCode: 0,
      sikumei: '',
      sikuCode: 0,
    },
  ],
};
