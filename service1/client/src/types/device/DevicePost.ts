export interface DevicePostRequest {
    name: string;
    ip: string;
    workplaceId: string;
}

export interface DevicePostResponse {

}

export const EmptyDevicePostResponse: DevicePostResponse = {

};

export const EmptyDevicePostRequest: DevicePostRequest = {
    name: '',
    ip: '',
    workplaceId: '',
};
