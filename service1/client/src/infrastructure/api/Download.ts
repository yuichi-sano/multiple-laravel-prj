import Auth from '@/infrastructure/api/helper/Auth';

export default {


  async getPDFData(): Promise<Blob> {
    const auth = new Auth();
    const response = await auth.post('/api/examinee/completion/pdf', {}, {responseType: 'blob'});
    return response.data;
  },

  async getPDFDataUrl(data: Blob): Promise<string> {
    return URL.createObjectURL(new Blob([data], {type: 'application/pdf'}));
  },

  isSafari(): boolean {
    const ua = window.navigator.userAgent.toLowerCase();
    return ua.indexOf('safari') !== -1 && ua.indexOf('chrome') === -1 && ua.indexOf('edge') === -1;
  },
  isIOs(): boolean {
    const ua = window.navigator.userAgent.toLowerCase();
    return (ua.indexOf('iphone') > -1 || ua.indexOf('ipad') > -1
        || ua.indexOf('macintosh') > -1 && 'ontouchend' in document);
  },
  hasWebkitBlobResourceBug(): boolean {
    const ua = window.navigator.userAgent.toLowerCase();
    const versionPlane = ua.match(/version\/(.+?) /);
    if (!(versionPlane && versionPlane.length > 1)) {
      return false;
    }
    const version = parseFloat(versionPlane[1].split('.')[0]);
    if (this.isIOs() && (version <= 13)) {
      return true;
    } else {
      return false;
    }
  },

  async downloadPdf(data: Blob, fileName: string): Promise<void> {
    if (this.isSafari() && this.hasWebkitBlobResourceBug()) {
      return;
    }
    const url = await this.getPDFDataUrl(data);
    if (typeof window.navigator.msSaveOrOpenBlob !== 'undefined') {
      const blobObject = new Blob([data], {type: 'application/pdf'});
      window.navigator.msSaveOrOpenBlob(blobObject, fileName);
    } else {
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', fileName);
      document.body.appendChild(link);
      link.click();
      URL.revokeObjectURL(url);
    }
  },

};
