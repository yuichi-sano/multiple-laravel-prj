export interface HtDevice {
    htDeviceTmpId: number|null;
    htDeviceIp: string;
    location: string;
}
export interface HtDeviceUpdateRequest {
    htHostName: string;
    htHostIp: string;
    facilityCode: string;
    location: string;
    slipType: number;
    htDeviceList: HtDevice[];
}

export interface HtDeviceUpdateResponse {

}

export const EmptyHtDeviceUpdateResponse: HtDeviceUpdateResponse = {

};

export const EmptyHtDeviceUpdateRequest: HtDeviceUpdateRequest = {
    htHostName: '',
    htHostIp: '',
    facilityCode: '',
    location: '',
    slipType: 0,
    htDeviceList: [],
};
