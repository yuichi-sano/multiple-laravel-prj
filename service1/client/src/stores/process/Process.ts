import {getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from '@/store';
interface ProcessStatus {
    status: boolean;
}
@Module({dynamic: true, store, name: 'process', namespaced: true})
class Process extends VuexModule {
  // status: boolean = false;
  statusList: ProcessStatus[]  = [{status: false}];

  @Mutation
  public async add(): Promise<void> {
      const status: ProcessStatus = {status: false};
      this.statusList.push(status);
  }
  @Mutation
  public async start(idx: number): Promise<void> {
      this.statusList[idx].status = true;
  }

  @Mutation
  public async end(idx: number): Promise<void> {
      this.statusList[idx].status = false;
  }
  get count(): number {
      return this.statusList.length;
  }
  get isProcessing(): boolean {
    const processList = this.statusList.filter((unit) => unit.status === true);
    return processList.length > 0;
  }
}

export const processModule = getModule(Process);
