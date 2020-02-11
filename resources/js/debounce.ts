export function debounce(callback: any, text: string, time: number): void {
    (window as any).lastCall = (window as any).lastCall
        ? (window as any).lastCall
        : 0;

    if (Date.now() - (window as any).lastCall > time) {
        (window as any).timeout = setTimeout(() => callback(text), time);
    } else {
        clearTimeout((window as any).timeout as number);
        (window as any).timeout = setTimeout(() => callback(text), time);
    }
    (window as any).lastCall = Date.now();
}
