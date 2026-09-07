import { createRequire } from 'node:module';
import process from 'node:process';

const require = createRequire(import.meta.url);
const jsQR = require('jsqr');
const QRCode = require('qrcode');

function renderQr(text) {
    const qr = QRCode.create(text, { errorCorrectionLevel: 'H' });
    const modules = qr.modules;
    const size = modules.size;
    const scale = 8;
    const margin = 4;
    const width = (size + margin * 2) * scale;
    const data = new Uint8ClampedArray(width * width * 4);

    for (let y = 0; y < width; y++) {
        for (let x = 0; x < width; x++) {
            const col = Math.floor(x / scale) - margin;
            const row = Math.floor(y / scale) - margin;
            const dark =
                row >= 0 &&
                col >= 0 &&
                row < size &&
                col < size &&
                Boolean(modules.get(row, col));
            const offset = (y * width + x) * 4;
            const value = dark ? 0 : 255;
            data[offset] = value;
            data[offset + 1] = value;
            data[offset + 2] = value;
            data[offset + 3] = 255;
        }
    }

    return { data, width };
}

function decode(text) {
    const { data, width } = renderQr(text);
    const result = jsQR(data, width, width, { inversionAttempts: 'attemptBoth' });

    return result?.data ?? null;
}

const payloads = [
    '123456',
    '000001',
    'https://clinic.example.com/q/482193',
    'http://admission.test/q/900111',
    'https://clinic.example.com/attendance/open?token=482193',
];

let failed = false;

for (const payload of payloads) {
    const decoded = decode(payload);

    if (decoded !== payload) {
        failed = true;
        console.error(`${payload} => ${decoded ?? 'FAILED'}`);
        continue;
    }

    console.log(`${payload} => OK`);
}

if (failed) {
    process.exit(1);
}
