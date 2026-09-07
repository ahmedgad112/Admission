import QRCode from 'qrcode';

const qrStyle = {
    errorCorrectionLevel: 'H' as const,
    margin: 4,
    color: {
        dark: '#000000',
        light: '#ffffff',
    },
};

export async function encodeQrSvg(text: string): Promise<string> {
    return QRCode.toString(text, {
        type: 'svg',
        ...qrStyle,
    });
}

export async function encodeQrPng(text: string): Promise<string> {
    const qr = QRCode.create(text, { errorCorrectionLevel: 'H' });
    const moduleCount = qr.modules.size;
    const scale = 16;
    const width = (moduleCount + qrStyle.margin * 2) * scale;

    return QRCode.toDataURL(text, {
        ...qrStyle,
        width,
    });
}
