export interface DeviceDetails {
    DeviceList: [
        {
          HostName: string,
          HostIp: string,
          deliveryWorkPlaceName: string,
          facilityCode: string,
          location: string,
          addressList: [
            {
              DeviceIp: string,
              location: string,
            }
          ],
        }
    ];
}

export const EmptyDeviceDetails: DeviceDetails = {
    DeviceList: [
        {
          HostName: '',
          HostIp: '',
          deliveryWorkPlaceName: '',
          facilityCode: '',
          location: '',
          addressList: [
            {
              DeviceIp: '',
              location: '',
            },
          ],
        },
    ],
};
