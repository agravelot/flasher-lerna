import {SnackbarConfig} from "buefy/types/components";

export function showSuccess(message: string): void {
    this.$buefy.toast.open({
        message: message,
        type: 'is-success',
    });
}

export function showError(message: string, onActionCallback?: () => void): void {
    const snackBarConfig: SnackbarConfig = {
        message: message,
        type: 'is-danger',
        position: 'is-top',
        actionText: 'Retry',
        onAction: onActionCallback,
    };
    this.$buefy.snackbar.open(snackBarConfig);
}
