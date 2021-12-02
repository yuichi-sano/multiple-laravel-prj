import moment from 'moment';

export default function dateFormat(value: Date): string {
  if (!value) {
    return '';
  }
  moment.locale('ja');
  return moment(value).format('YYYY年MM月DD日 HH:mm');
}
