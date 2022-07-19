export interface DevicePostRequest {
    HostName: string;
    HostIp: string;
    facilityCode: string;
    location: string;
    DeviceList: [
        {
            DeviceIp: string,
             location: string,
        }
    ];
}

export interface DevicePostResponse {

}

export const EmptyDevicePostResponse: DevicePostResponse = {

};

export const EmptyDevicePostRequest: DevicePostRequest = {
    HostName: '',
    HostIp: '',
    facilityCode: '',
    location: '',
    DeviceList: [
        {
            DeviceIp: '',
             location: '',
        },
    ],

};
