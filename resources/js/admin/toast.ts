import { SnackbarConfig } from 'buefy/types/components';
import { BuefyNamespace } from "buefy/types";

export function showSuccess(buefy: BuefyNamespace ,message: string): void {
    buefy.toast.open({
        message: message,
        type: 'is-success',
    });
}

export function showError(buefy: BuefyNamespace ,message: string, onActionCallback?: () => void): void {
    const snackBarConfig: SnackbarConfig = {
        message: message,
        type: 'is-danger',
        position: 'is-top',
        actionText: onActionCallback ? 'Retry' : 'OK',
        onAction: onActionCallback,
    };
    buefy.snackbar.open(snackBarConfig);
}
