export interface BumonCode {
    facilityCodeList: [
        {
          facilityCode: string,
          deliveryWorkplaceName: string,
        }
    ];
}

export const EmptyBumonCode: BumonCode = {
    facilityCodeList: [
        {
          facilityCode: '',
          deliveryWorkplaceName: '',
        },
    ],
};
