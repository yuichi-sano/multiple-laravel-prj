export interface HtDevicePostRequest {
    htHostName: string;
    htHostIp: string;
    facilityCode: string;
    location: string;
    slipType: number;
    htDeviceList: [
        {
            htDeviceIp: string,
             location: string,
        }
    ];
}

export interface HtDevicePostResponse {

}

export const EmptyHtDevicePostResponse: HtDevicePostResponse = {

};

export const EmptyHtDevicePostRequest: HtDevicePostRequest = {
    htHostName: '',
    htHostIp: '',
    facilityCode: '',
    location: '',
    slipType: 0,
    htDeviceList: [
        {
            htDeviceIp: '',
             location: '',
        },
    ],

};
