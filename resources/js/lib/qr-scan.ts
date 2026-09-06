import jsQR from 'jsqr';

type BarcodeDetectorLike = {
    detect: (source: ImageBitmapSource) => Promise<Array<{ rawValue: string }>>;
};

type BarcodeDetectorCtor = {
    new (options: { formats: string[] }): BarcodeDetectorLike;
    getSupportedFormats?: () => Promise<string[]>;
};

export type QrFrameDetector = (
    video: HTMLVideoElement,
    canvas: HTMLCanvasElement,
) => Promise<string | null>;

const JSQR_MAX_WIDTH = 640;

function barcodeDetectorCtor(): BarcodeDetectorCtor | null {
    const Detector = (
        window as Window & { BarcodeDetector?: BarcodeDetectorCtor }
    ).BarcodeDetector;

    return typeof Detector === 'function' ? Detector : null;
}

function withTimeout<T>(promise: Promise<T>, ms: number): Promise<T> {
    return new Promise<T>((resolve, reject) => {
        const timer = window.setTimeout(() => {
            reject(new Error('timeout'));
        }, ms);

        promise.then(
            (value) => {
                window.clearTimeout(timer);
                resolve(value);
            },
            (error: unknown) => {
                window.clearTimeout(timer);
                reject(error);
            },
        );
    });
}

async function createBarcodeDetector(): Promise<BarcodeDetectorLike | null> {
    const Detector = barcodeDetectorCtor();

    if (!Detector) {
        return null;
    }

    try {
        const formats =
            typeof Detector.getSupportedFormats === 'function'
                ? await withTimeout(Detector.getSupportedFormats(), 800)
                : ['qr_code'];

        if (!formats.includes('qr_code')) {
            return null;
        }

        return new Detector({ formats: ['qr_code'] });
    } catch {
        return null;
    }
}

function canvasContext(canvas: HTMLCanvasElement): CanvasRenderingContext2D | null {
    return canvas.getContext('2d', { willReadFrequently: true });
}

function even(value: number): number {
    return Math.max(2, value - (value % 2));
}

function enhanceContrast(data: Uint8ClampedArray): void {
    for (let index = 0; index < data.length; index += 4) {
        const gray = Math.round(
            data[index] * 0.299 + data[index + 1] * 0.587 + data[index + 2] * 0.114,
        );
        const boosted = gray < 110 ? 0 : gray > 170 ? 255 : gray;
        data[index] = boosted;
        data[index + 1] = boosted;
        data[index + 2] = boosted;
    }
}

function detectWithJsQr(image: ImageData): string | null {
    enhanceContrast(image.data);

    const result = jsQR(image.data, image.width, image.height, {
        inversionAttempts: 'attemptBoth',
    });

    return result?.data?.trim() || null;
}

function detectCanvasWithJsQr(canvas: HTMLCanvasElement): string | null {
    const context = canvasContext(canvas);

    if (!context) {
        return null;
    }

    const full = context.getImageData(0, 0, canvas.width, canvas.height);
    const fullValue = detectWithJsQr(full);

    if (fullValue) {
        return fullValue;
    }

    const crop = Math.floor(Math.min(canvas.width, canvas.height) * 0.72);
    const x = Math.floor((canvas.width - crop) / 2);
    const y = Math.floor((canvas.height - crop) / 2);

    return detectWithJsQr(context.getImageData(x, y, crop, crop));
}

async function detectWithBarcode(
    detector: BarcodeDetectorLike,
    source: ImageBitmapSource,
): Promise<string | null> {
    try {
        const codes = await withTimeout(detector.detect(source), 600);
        const value = codes[0]?.rawValue?.trim();

        return value || null;
    } catch {
        return null;
    }
}

export async function createQrDetector(): Promise<QrFrameDetector> {
    const barcodeDetector = await createBarcodeDetector();

    return async (
        video: HTMLVideoElement,
        canvas: HTMLCanvasElement,
    ): Promise<string | null> => {
        if (barcodeDetector) {
            const nativeValue = await detectWithBarcode(barcodeDetector, video);

            if (nativeValue) {
                return nativeValue;
            }
        }

        if (!drawVideoFrame(video, canvas)) {
            return null;
        }

        if (barcodeDetector) {
            const canvasValue = await detectWithBarcode(barcodeDetector, canvas);

            if (canvasValue) {
                return canvasValue;
            }
        }

        return detectCanvasWithJsQr(canvas);
    };
}

export function drawVideoFrame(
    video: HTMLVideoElement,
    canvas: HTMLCanvasElement,
): boolean {
    if (
        video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA ||
        video.videoWidth === 0
    ) {
        return false;
    }

    const scale = Math.min(1, JSQR_MAX_WIDTH / video.videoWidth);
    canvas.width = even(Math.floor(video.videoWidth * scale));
    canvas.height = even(Math.floor(video.videoHeight * scale));

    const context = canvasContext(canvas);

    if (!context) {
        return false;
    }

    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    return true;
}

export async function waitForVideo(video: HTMLVideoElement): Promise<void> {
    if (video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA && video.videoWidth > 0) {
        return;
    }

    await new Promise<void>((resolve) => {
        const finish = (): void => resolve();

        video.addEventListener('loadeddata', finish, { once: true });
        window.setTimeout(finish, 2000);
    });
}

export async function startRearCamera(): Promise<MediaStream> {
    if (!navigator.mediaDevices?.getUserMedia) {
        throw new Error('Camera is not available.');
    }

    const attempts: MediaStreamConstraints[] = [
        { audio: false, video: { facingMode: { ideal: 'environment' } } },
        { audio: false, video: { facingMode: 'environment' } },
        { audio: false, video: true },
    ];

    let lastError: unknown;

    for (const constraints of attempts) {
        try {
            return await navigator.mediaDevices.getUserMedia(constraints);
        } catch (error) {
            lastError = error;
        }
    }

    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const cameras = devices.filter((device) => device.kind === 'videoinput');
        const rear =
            cameras.find((device) => /back|rear|environment/i.test(device.label)) ??
            cameras.at(-1);

        if (rear?.deviceId) {
            return await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: { deviceId: { exact: rear.deviceId } },
            });
        }
    } catch (error) {
        lastError = error;
    }

    throw lastError instanceof Error
        ? lastError
        : new Error('Unable to start camera.');
}

export async function detectQrFromImageFile(file: File): Promise<string | null> {
    const bitmap = await createImageBitmap(file);
    const canvas = document.createElement('canvas');
    const scale = Math.min(1, JSQR_MAX_WIDTH / Math.max(bitmap.width, 1));
    canvas.width = even(Math.floor(bitmap.width * scale));
    canvas.height = even(Math.floor(bitmap.height * scale));

    const context = canvasContext(canvas);

    if (!context) {
        bitmap.close();

        return null;
    }

    context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
    bitmap.close();

    const detector = await createBarcodeDetector();

    if (detector) {
        const nativeValue = await detectWithBarcode(detector, canvas);

        if (nativeValue) {
            return nativeValue;
        }
    }

    return detectCanvasWithJsQr(canvas);
}

export function kioskScanUrl(token: string, origin = window.location.origin): string {
    const url = new URL('/attendance/open', origin);
    url.searchParams.set('token', token);

    return url.toString();
}

export function normalizeScannedValue(value: string): string {
    const trimmed = value.replace(/\s+/g, '').trim();

    try {
        const url = new URL(trimmed);
        const token = url.searchParams.get('token');

        if (token) {
            return token.trim();
        }
    } catch {
        // Not an absolute URL.
    }

    const match = trimmed.match(/[?&]token=([^&#]+)/i);

    if (match?.[1]) {
        try {
            return decodeURIComponent(match[1]).trim();
        } catch {
            return match[1];
        }
    }

    return trimmed;
}
