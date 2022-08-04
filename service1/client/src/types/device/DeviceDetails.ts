export interface DeviceDetails {
    DeviceList: [
        {
          name: string,
          ip: string,
          workplaceName: string,
          workplaceId: number,
        }
    ];
}

export const EmptyDeviceDetails: DeviceDetails = {
    DeviceList: [
        {
          name: '',
          ip: '',
          workplaceName: '',
          workplaceId: 0,
        },
    ],
};
