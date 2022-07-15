export interface SlipType {
    slipTypeList: [
        {
          slipTypeId: number,
          slipTypeName: string,
        }
    ];
}

export const EmptySlipType: SlipType = {
    slipTypeList: [
        {
          slipTypeId: 0,
          slipTypeName: '',
        },
    ],
};
