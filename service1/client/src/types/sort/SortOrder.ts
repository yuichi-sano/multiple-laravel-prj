export enum SortOrder {
  NONE = 'NONE',
  ASC = 'ASC',
  DESC = 'DESC',
}

export interface SortOrderRequest {
  sort: string;
  order: string;
}
