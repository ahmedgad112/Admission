import QRCode from 'qrcode';

export async function encodeQrSvg(text: string): Promise<string> {
    return QRCode.toString(text, {
        type: 'svg',
        margin: 4,
        errorCorrectionLevel: 'M',
        color: {
            dark: '#000000',
            light: '#ffffff',
        },
    });
}
