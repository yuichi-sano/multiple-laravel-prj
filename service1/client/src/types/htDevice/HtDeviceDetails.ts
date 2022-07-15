export interface HtDeviceDetails {
    htDeviceList: [
        {
          htHostName: string,
          htHostIp: string,
          deliveryWorkplaceName: string,
          facilityCode: string,
          location: string,
          slipType: string,
          addressList: [
            {
              htDeviceIp: string,
              location: string,
            }
          ],
        }
    ];
}

export const EmptyHtDeviceDetails: HtDeviceDetails = {
    htDeviceList: [
        {
          htHostName: '',
          htHostIp: '',
          deliveryWorkplaceName: '',
          facilityCode: '',
          location: '',
          slipType: '',
          addressList: [
            {
              htDeviceIp: '',
              location: '',
            },
          ],
        },
    ],
};
