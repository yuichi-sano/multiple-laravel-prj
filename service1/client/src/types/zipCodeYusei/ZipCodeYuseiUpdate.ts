export interface ZipCodeYuseiUpdate {
    bulkUpdateDate: string;
    bulkUser: string;
    addDate: string;
    user: string;
    zipCode: string;
    kenmei: string;
    kenCode: number;
    sikumei: string;
    sikuCode: number;
  }

export const EmptyZipCodeYuseiUpdate: ZipCodeYuseiUpdate = {
    bulkUpdateDate: '',
    bulkUser: '',
    addDate: '',
    user: '',
    zipCode: '',
    kenmei: '',
    kenCode: 0,
    sikumei: '',
    sikuCode: 0,
  };

