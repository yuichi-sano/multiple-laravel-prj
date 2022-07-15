<template>
    <b-card border-variant="danger"
            header-tag="header"
            header-border-variant="danger"
            header-text-variant="danger"
            align="center"
            v-if="hasError()">
        <template #header>
            <b-icon-exclamation-triangle-fill variant="danger"></b-icon-exclamation-triangle-fill>エラーが発生しました。
        </template>
        <b-card-body>
            <li v-for="(message, index) in getMessage()" :key="index">{{ message }}</li>
        </b-card-body>
    </b-card>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
@Component
export default class ApiError extends Vue {

    @Prop()
    apiError?: any;

    @Prop({type: String, default: ''})
    errorMessage!: string;

    hasError(): boolean {
        if (this.apiError === null && !this.errorMessage) {
            return false;
        } else if (!!this.errorMessage) {
            return true;
        } else if (this.isBusinessError()) {
            return true;
        }
        return true;
    }

    isBusinessError(): boolean {
        return (this.apiError.status === 200 && this.apiError.data.message !== null)
            || (this.apiError.status === 400 && this.apiError.data.state !== 'V_0000000')
            || (this.apiError.status === 401 && this.apiError.data.message !== null);
    }

    getMessage(): string[] {
        const messages: string[] = [];
        if (!!this.errorMessage) {
            messages.push(this.errorMessage);
        } else if (this.isBusinessError()) {
            messages.push(this.apiError.data.message);
        } else {
            const responseMessages = this.apiError.data.result;
            for (const key of Object.keys(responseMessages)) {
                responseMessages[key].forEach((message: string) => {
                    messages.push(message);
                });
            }
        }
        return messages;
    }

}
</script>
