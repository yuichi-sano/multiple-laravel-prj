<template>
    <b-modal
        :id="successModalId"
        title="完了"
        header-bg-variant="white"
        ok-title="OK"
        ok-variant="success"
        footer-class="text-center"
        ok-only
        @ok="bindClose"
        @close="bindClose"
        @cancel="bindClose"
    >

        <b-row class="justify-content-center">
            <div>
                <p><b-icon icon="check-circle-fill" variant="success"></b-icon> 処理が正常に完了しました</p>
            </div>
        </b-row>
    </b-modal>
</template>


<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';

@Component({
    components: {
    },
})
export default class SuccessInfoModal extends Vue {
    @Prop({type: String})
    successModalId: string | undefined;
    // data

    // computed
    // @ts-ignore @FIXME
    @Prop({ type: Function, required: true }) private bindClose: Function;

}
</script>


