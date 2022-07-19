export interface DeviceUpdateRequest {
    name: string;
    ip: string;
    workplaceId: string;
}

export interface DeviceUpdateResponse {

}

export const EmptyDeviceUpdateResponse: DeviceUpdateResponse = {

};

export const EmptyDeviceUpdateRequest: DeviceUpdateRequest = {
    name: '',
    ip: '',
    workplaceId: '',
};
