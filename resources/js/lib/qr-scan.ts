import jsQR from 'jsqr';

type BarcodeDetectorLike = {
    detect: (source: ImageBitmapSource) => Promise<Array<{ rawValue: string }>>;
};

type BarcodeDetectorCtor = {
    new (options: { formats: string[] }): BarcodeDetectorLike;
    getSupportedFormats?: () => Promise<string[]>;
};

export type QrFrameDetector = (canvas: HTMLCanvasElement) => Promise<string | null>;

function barcodeDetectorCtor(): BarcodeDetectorCtor | null {
    const Detector = (window as Window & { BarcodeDetector?: BarcodeDetectorCtor }).BarcodeDetector;

    return typeof Detector === 'function' ? Detector : null;
}

async function createBarcodeDetector(): Promise<BarcodeDetectorLike | null> {
    const Detector = barcodeDetectorCtor();

    if (!Detector) {
        return null;
    }

    try {
        const formats =
            typeof Detector.getSupportedFormats === 'function'
                ? await Detector.getSupportedFormats()
                : ['qr_code'];

        if (!formats.includes('qr_code')) {
            return null;
        }

        return new Detector({ formats: ['qr_code'] });
    } catch {
        return null;
    }
}

export async function createQrDetector(): Promise<QrFrameDetector> {
    const barcodeDetector = await createBarcodeDetector();

    return async (canvas: HTMLCanvasElement): Promise<string | null> => {
        if (barcodeDetector) {
            try {
                const codes = await barcodeDetector.detect(canvas);
                const value = codes[0]?.rawValue?.trim();

                if (value) {
                    return value;
                }
            } catch {
                // Native detection is unavailable on this frame; try jsQR.
            }
        }

        const context = canvas.getContext('2d', { willReadFrequently: true });

        if (!context) {
            return null;
        }

        const image = context.getImageData(0, 0, canvas.width, canvas.height);
        const result = jsQR(image.data, image.width, image.height, {
            inversionAttempts: 'attemptBoth',
        });

        return result?.data?.trim() || null;
    };
}

export function drawVideoFrame(video: HTMLVideoElement, canvas: HTMLCanvasElement): boolean {
    if (video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA || video.videoWidth === 0) {
        return false;
    }

    const maxWidth = 720;
    const scale = Math.min(1, maxWidth / video.videoWidth);
    canvas.width = Math.max(1, Math.floor(video.videoWidth * scale));
    canvas.height = Math.max(1, Math.floor(video.videoHeight * scale));

    const context = canvas.getContext('2d', { willReadFrequently: true });

    if (!context) {
        return false;
    }

    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    return true;
}

export async function startRearCamera(): Promise<MediaStream> {
    try {
        return await navigator.mediaDevices.getUserMedia({
            audio: false,
            video: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
        });
    } catch {
        return navigator.mediaDevices.getUserMedia({
            audio: false,
            video: true,
        });
    }
}

export function normalizeScannedValue(value: string): string {
    return value.replace(/\s+/g, '').trim();
}
