export interface ZipCodeIndividualRegisterRequest {
    zipCode: string;
    kenmei: string;
    kenmeiKana: string;
    kenCode: number;
    sikumei: string;
    sikumeiKana: string;
    sikuCode: number;
    town: string;
    townKana: string;
}

export interface ZipCodeIndividualRegisterResponse {

}


export const EmptyZipCodeIndividualRegisterRequest: ZipCodeIndividualRegisterRequest = {
    zipCode: '',
    kenmei: '',
    kenmeiKana: '',
    kenCode: 0,
    sikumei: '',
    sikumeiKana: '',
    sikuCode: 0,
    town: '',
    townKana: '',
};

export const EmptyZipCodeIndividualRegisterResponse: ZipCodeIndividualRegisterResponse = {

};
