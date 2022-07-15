<template>
    <b-modal
        :id="errorModalId"
        hide-header
        ok-title="OK"
        ok-variant="danger"
        footer-class="text-center"
        ok-only
        @ok="bindClose"
        @close="bindClose"
        @cancel="bindClose"
    >

        <b-row class="justify-content-center">
            <slot name="error"></slot>
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
    errorModalId: string | undefined;
    // data

    // computed
    // @ts-ignore @FIXME
    @Prop({ type: Function, required: true }) private bindClose: Function;

}
</script>


