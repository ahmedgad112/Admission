import sharp from 'sharp';
import jsQR from 'jsqr';
import { encodeQrSvg } from '../resources/js/lib/qrcode.ts';

async function test(code) {
    const svg = encodeQrSvg(code);
    const { data, info } = await sharp(Buffer.from(svg))
        .raw()
        .ensureAlpha()
        .toBuffer({ resolveWithObject: true });
    const result = jsQR(new Uint8ClampedArray(data), info.width, info.height);

    console.log(`${code} => ${result?.data ?? 'FAILED'}`);
}

for (const code of ['123456', '000001', 'abcdef0123456789abcdef0123456789']) {
    await test(code);
}
