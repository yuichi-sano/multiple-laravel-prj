export interface Device {
    DeviceTmpId: number|null;
    DeviceIp: string;
    location: string;
}
export interface DeviceUpdateRequest {
    HostName: string;
    HostIp: string;
    facilityCode: string;
    location: string;
    DeviceList: Device[];
}

export interface DeviceUpdateResponse {

}

export const EmptyDeviceUpdateResponse: DeviceUpdateResponse = {

};

export const EmptyDeviceUpdateRequest: DeviceUpdateRequest = {
    HostName: '',
    HostIp: '',
    facilityCode: '',
    location: '',
    DeviceList: [],
};
