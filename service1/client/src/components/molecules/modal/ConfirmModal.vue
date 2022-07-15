<template>
  <b-modal
    header-bg-variant="warning"
    header-text-variant="dark"
    ref="modal"
  >
    <template #modal-header="{ close }" >
      <slot name="header"/>
    </template>
    <template #default="{ hide }">
      <slot name="content"/>
      <div v-if="showDetail">
        <div class="row border-top " v-if="hasMapKey(key)" v-for="(confirmDataUnit,key,index) in confirmData" :key="index+key">
          <div class="col-4 font-weight-bold">{{mapKey(key)}}</div>
          <div class="col-2">:</div>
          <div class="col-6  font-weight-bold">
            <div v-if="Array.isArray(confirmDataUnit)">
              <b-table thead-class="d-none" :items="confirmDataUnit" :fields="childKeyMap(key)"></b-table>
            </div>
            <div v-else>
              <div v-if="hasKeyMapValues(key)">
                {{keyMapValue(key,confirmDataUnit)}}
              </div>
              <div v-else>{{confirmDataUnit}}</div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template #modal-footer="{ ok, cancel, hide }">
      <b-button
        variant="success"
        @click="confirm(true)"
        @mousedown="confirm(true)"
        @touchstart="confirm(true)">はい</b-button>
      <b-button @click="confirm(false)"
                variant="danger"
                @mousedown="confirm(false)"
                @touchstart="confirm(false)">いいえ</b-button>
    </template>
  </b-modal>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue} from 'vue-property-decorator';

@Component({})
export default class ConfirmModal extends Vue {
  confirmData: any = null;
  /**
   * showDetail をtrueに設定すると更新対象オブジェクトをモーダル内に表示します。
   * confirmDataのうち、detailKeyMapにkeyが定義されているフィールドのみをレンダリング対象としています。
   * 基本的には1階層目のみ体裁を整えてレンダリングします。
   * 2階層目が配列の場合、テーブルレイアウトにてレンダリングします。
   * 3階層目以降はobject,配列をそのままdumpするかたちでレンダリングします。
   * 階層の多い更新オブジェクトの場合はfalseに設定するのが無難と思います。
   */
  @Prop({type: Boolean})
  showDetail: boolean | undefined;
  @Prop({type: Array})
  detailKeyMap: any;
  @Prop({type: Array})
  keyMapValues: any;
  //@ts-ignore @FIXME
  @Prop({ type: Function, required: true }) private bindConfirm: Function;
  confirm(result: boolean) {
    this.bindConfirm(result, this.confirmData);
  }

  showModal(data: any): void {
    this.confirmData = data;
    // @ts-ignore
    this.$refs.modal.show();
  }
  hideModal(): void {
    this.confirmData = null;
    // @ts-ignore
    this.$refs.modal.hide();
  }
  mapKey(key: string) {
    if (this.detailKeyMap) {
      const map  = this.detailKeyMap.find((data: { key: string; }) => data.key == key);
      if (map) {
        return map.label;
      }
    }
    return key;
  }
  hasMapKey(key: string) {
    if (this.detailKeyMap) {
      const map  = this.detailKeyMap.find((data: { key: string; }) => data.key == key);
      if (map) {
        return true;
      }
    }
    return false;
  }
  childKeyMap(key: string) {
    if (this.detailKeyMap) {
      const map  = this.detailKeyMap.find((data: { key: string; }) => data.key == key);
      if (map) {
        return map.child;
      }
    }
    return key;
  }
  hasKeyMapValues(key: string) {
    if (this.keyMapValues) {
      const map  = this.keyMapValues.find((data: { key: string; }) => data.key == key);
      if (map) {
        return true;
      }
    }
    return false;
  }
  keyMapValue(key: string, value: any) {
    if (this.keyMapValues) {
      const map  = this.keyMapValues.find((data: { key: string; }) => data.key == key);
      const mapValue = map.values.find((mapData: { [x: string]: any; }) => mapData[map.mapDefinition.id] == value);
      if (mapValue) {
        return mapValue[map.mapDefinition.value];
      }
    }
    return value;
  }
}
</script>
