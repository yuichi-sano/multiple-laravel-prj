import {getModule, Module, Mutation, VuexModule} from 'vuex-module-decorators';
import store from '@/store';

@Module({dynamic: true, store, name: 'process', namespaced: true})
class Process extends VuexModule {
  status: boolean = false;

  @Mutation
  public async start(): Promise<void> {
    this.status = true;
  }

  @Mutation
  public async end(): Promise<void> {
    this.status = false;
  }

  get isProcessing(): boolean {
    return this.status;
  }
}

export const processModule = getModule(Process);
