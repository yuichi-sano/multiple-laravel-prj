import {SortOrderRequest} from '@/types/sort/SortOrder';

export interface DeviceGetRequest {
    workplaceId: string|null;
    page: number | null;
    perPage: number | null;
    sorts: SortOrderRequest[];
}

export interface DeviceGetResponse {
    page: {
        resultCount: number,
        totalPages: number,
        previousPage: number,
        currentPage: number,
        nextPage: number,
    };
    deviceList: [
        {
            id: null,
            name: '',
            ip: '',
            workplaceName: '',
            workplaceId: '',
        }
    ];
}

export const EmptyDeviceGetResponse: DeviceGetResponse = {
    page: {
        resultCount: 0,
        totalPages: 0,
        previousPage: 0,
        currentPage: 1,
        nextPage: 0,
    },
    deviceList: [
        {
            id: null,
            name: '',
            ip: '',
            workplaceName: '',
            workplaceId: '',
        },
    ],
};
