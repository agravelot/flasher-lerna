import { SnackbarConfig } from "buefy/types/components";
import { BuefyNamespace } from "buefy/types";

export function showSuccess(buefy: BuefyNamespace, message: string): void {
    const snackBarConfig: SnackbarConfig = {
        message: message,
        type: "is-success",
        position: "is-bottom-right",
        queue: false
    };
    buefy.snackbar.open(snackBarConfig);
}

export function showError(
    buefy: BuefyNamespace,
    message: string,
    onActionCallback?: () => void
): void {
    const snackBarConfig: SnackbarConfig = {
        message: message,
        type: "is-danger",
        position: "is-bottom-right",
        queue: false,
        actionText: onActionCallback ? "Retry" : "OK",
        onAction: onActionCallback
    };
    buefy.snackbar.open(snackBarConfig);
}
