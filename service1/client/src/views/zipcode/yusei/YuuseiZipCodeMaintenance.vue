<template>
  <div id="yuusei-zip-code-maintenance" data-cy="郵政郵便番号マスタメンテナンス">
      <YuuseiZipCodeMaintenance004
          :update-history="updateHistory"
          :prefecture-list="prefecture"
      >
      </YuuseiZipCodeMaintenance004>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import {progress} from '@/infrastructure/script/Progress';
import YuuseiZipCodeMaintenance004 from '@/components/organisms/yusei/YuuseiZipCodeMaintenance004.vue';
import api from '@/infrastructure/api/API';
import {EmptyZipCodeYuseiUpdate, ZipCodeYuseiUpdate} from '@/types/zipCodeYusei/ZipCodeYuseiUpdate';
import {EmptyPrefecture, Prefecture} from '@/types/prefecture/Prefecture';

@Component({
  components: {
      YuuseiZipCodeMaintenance004,
  },
})
export default class YuuseiZipCodeMaintenance extends Vue {
  // data
  updateHistory: ZipCodeYuseiUpdate = EmptyZipCodeYuseiUpdate;
  prefecture: Prefecture[] = [EmptyPrefecture];


  // computed
  // method
  async initialize(): Promise<void> {
     await this.getUpdateHistory();
     await this.getPrefList();
  }

   async getUpdateHistory(): Promise<void> {
      const getHistory = async (): Promise<void> => {
          await api.getZipCodeYuseiUpdate()
              .then((response: any) => {
                  this.updateHistory = response;
                  if (!response) {
                      this.updateHistory = EmptyZipCodeYuseiUpdate;
                  }
              });
      };
      await progress(getHistory);
   }
    async getPrefList(): Promise<void> {
        const getPref = async (): Promise<void> => {
            await api.getPrefecture()
                .then((response: any) => {
                    this.prefecture = response.prefectureList;
                });
        };
        await progress(getPref);
    }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
