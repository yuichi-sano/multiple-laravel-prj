import {SortOrderRequest} from '@/types/sort/SortOrder';

export interface HtDeviceGetRequest {
    facilityCode: string|null;
    page: number | null;
    perPage: number | null;
    sorts: SortOrderRequest[];
}

export interface HtDeviceGetResponse {
    page: {
        resultCount: number,
        totalPages: number,
        previousPage: number,
        currentPage: number,
        nextPage: number,
    };
    htDeviceList: [
        {
            htHostId: null,
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
                }
            ],
        }
    ];
}

export const EmptyHtDeviceGetResponse: HtDeviceGetResponse = {
    page: {
        resultCount: 0,
        totalPages: 0,
        previousPage: 0,
        currentPage: 1,
        nextPage: 0,
    },
    htDeviceList: [
        {
            htHostId: null,
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
