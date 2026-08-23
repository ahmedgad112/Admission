/**
 * Compact QR Code generator (byte mode, ECC Medium) that returns an SVG string.
 * Suitable for kiosk tokens of typical length.
 */
const EXP: number[] = [];
const LOG: number[] = [];

(function initializeGaloisField(): void {
    let value = 1;

    for (let i = 0; i < 255; i++) {
        EXP[i] = value;
        LOG[value] = i;
        value *= 2;

        if (value > 255) {
            value ^= 285;
        }
    }

    for (let i = 255; i < 512; i++) {
        EXP[i] = EXP[i - 255];
    }
})();

function gfMul(a: number, b: number): number {
    if (a === 0 || b === 0) {
        return 0;
    }

    return EXP[LOG[a] + LOG[b]];
}

function reedSolomon(data: number[], ecCount: number): number[] {
    const generator = [1];

    for (let i = 0; i < ecCount; i++) {
        const next = new Array<number>(generator.length + 1).fill(0);

        for (let j = 0; j < generator.length; j++) {
            next[j] ^= gfMul(generator[j], EXP[i]);
            next[j + 1] ^= generator[j];
        }

        generator.splice(0, generator.length, ...next);
    }

    const remainder = new Array<number>(ecCount).fill(0);

    for (const byte of data) {
        const factor = byte ^ remainder[0];
        remainder.shift();
        remainder.push(0);

        for (let i = 0; i < ecCount; i++) {
            remainder[i] ^= gfMul(generator[i + 1] ?? 0, factor);
        }
    }

    return remainder;
}

const VERSIONS: Array<{
    version: number;
    size: number;
    dataBytes: number;
    ecBytes: number;
    align: number[];
}> = [
    { version: 1, size: 21, dataBytes: 16, ecBytes: 10, align: [] },
    { version: 2, size: 25, dataBytes: 28, ecBytes: 16, align: [18] },
    { version: 3, size: 29, dataBytes: 44, ecBytes: 26, align: [22] },
    { version: 4, size: 33, dataBytes: 64, ecBytes: 36, align: [26] },
    { version: 5, size: 37, dataBytes: 86, ecBytes: 46, align: [30] },
    { version: 6, size: 41, dataBytes: 108, ecBytes: 60, align: [34] },
];

function chooseVersion(payloadLength: number): (typeof VERSIONS)[number] {
    const needed = payloadLength + 2;

    for (const version of VERSIONS) {
        if (needed <= version.dataBytes) {
            return version;
        }
    }

    throw new Error('QR payload is too long for this encoder.');
}

function setModule(modules: boolean[][], reserved: boolean[][], row: number, col: number, dark: boolean): void {
    if (row < 0 || col < 0 || row >= modules.length || col >= modules.length) {
        return;
    }

    modules[row][col] = dark;
    reserved[row][col] = true;
}

function addFinder(modules: boolean[][], reserved: boolean[][], row: number, col: number): void {
    for (let r = -1; r <= 7; r++) {
        for (let c = -1; c <= 7; c++) {
            const dark =
                r >= 0 && r <= 6 && c >= 0 && c <= 6
                    ? r === 0 || r === 6 || c === 0 || c === 6 || (r >= 2 && r <= 4 && c >= 2 && c <= 4)
                    : false;
            setModule(modules, reserved, row + r, col + c, dark);
        }
    }
}

function addAlignment(modules: boolean[][], reserved: boolean[][], row: number, col: number): void {
    for (let r = -2; r <= 2; r++) {
        for (let c = -2; c <= 2; c++) {
            setModule(modules, reserved, row + r, col + c, Math.max(Math.abs(r), Math.abs(c)) !== 1);
        }
    }
}

function reserveFormat(modules: boolean[][], reserved: boolean[][]): void {
    const size = modules.length;

    for (let i = 0; i < 8; i++) {
        reserved[8][i] = true;
        reserved[i][8] = true;
        reserved[8][size - 1 - i] = true;
        reserved[size - 1 - i][8] = true;
    }

    reserved[8][8] = true;
}

function bitsFromBytes(bytes: number[]): number[] {
    const bits: number[] = [];

    for (const byte of bytes) {
        for (let i = 7; i >= 0; i--) {
            bits.push((byte >> i) & 1);
        }
    }

    return bits;
}

function maskBit(row: number, col: number): boolean {
    return (row + col) % 2 === 0;
}

function placeData(modules: boolean[][], reserved: boolean[][], bits: number[]): void {
    const size = modules.length;
    let bitIndex = 0;
    let upward = true;

    for (let col = size - 1; col > 0; col -= 2) {
        if (col === 6) {
            col -= 1;
        }

        for (let offset = 0; offset < size; offset++) {
            const row = upward ? size - 1 - offset : offset;

            for (let delta = 0; delta < 2; delta++) {
                const currentCol = col - delta;

                if (reserved[row][currentCol]) {
                    continue;
                }

                const bit = bits[bitIndex] ?? 0;
                modules[row][currentCol] = (bit === 1) !== maskBit(row, currentCol);
                bitIndex += 1;
            }
        }

        upward = !upward;
    }
}

function setFormatInfo(modules: boolean[][]): void {
    // ECC M (01) + mask 0 (000) = 0b01000, BCH encoded format bits for mask 0 / M.
    const bits = 0b101010000010010;
    const size = modules.length;

    for (let i = 0; i < 15; i++) {
        const dark = ((bits >> i) & 1) === 1;

        if (i < 6) {
            modules[i][8] = dark;
            modules[8][size - 1 - i] = dark;
        } else if (i < 8) {
            modules[i + 1][8] = dark;
            modules[8][size - 1 - i] = dark;
        } else {
            modules[8][14 - i] = dark;
            modules[size - 15 + i][8] = dark;
        }
    }

    modules[size - 8][8] = true;
}

export function encodeQrSvg(text: string): string {
    const payload = Array.from(new TextEncoder().encode(text));
    const version = chooseVersion(payload.length);
    const header = [0b01000000 | ((payload.length >> 4) & 0x0f), ((payload.length & 0x0f) << 4)];
    const data = [...header];

    if (payload.length > 0) {
        data[1] = (data[1] ?? 0) | ((payload[0] ?? 0) >> 4);
        for (let i = 0; i < payload.length; i++) {
            const current = ((payload[i] ?? 0) << 4) & 0xf0;
            const next = (payload[i + 1] ?? 0) >> 4;
            data.push(current | next);
        }
    }

    while (data.length < version.dataBytes) {
        data.push(data.length % 2 === 0 ? 0xec : 0x11);
    }

    data.length = version.dataBytes;

    const ecc = reedSolomon(data, version.ecBytes);
    const bits = bitsFromBytes([...data, ...ecc]);
    const size = version.size;
    const modules = Array.from({ length: size }, () => Array.from({ length: size }, () => false));
    const reserved = Array.from({ length: size }, () => Array.from({ length: size }, () => false));

    addFinder(modules, reserved, 0, 0);
    addFinder(modules, reserved, 0, size - 7);
    addFinder(modules, reserved, size - 7, 0);

    for (const position of version.align) {
        addAlignment(modules, reserved, position, position);
    }

    for (let i = 8; i < size - 8; i++) {
        setModule(modules, reserved, i, 6, i % 2 === 0);
        setModule(modules, reserved, 6, i, i % 2 === 0);
    }

    reserveFormat(modules, reserved);
    placeData(modules, reserved, bits);
    setFormatInfo(modules);

    const quiet = 4;
    const scale = 8;
    const canvas = (size + quiet * 2) * scale;
    const rects: string[] = [];

    for (let row = 0; row < size; row++) {
        for (let col = 0; col < size; col++) {
            if (!modules[row][col]) {
                continue;
            }

            rects.push(
                `<rect x="${(col + quiet) * scale}" y="${(row + quiet) * scale}" width="${scale}" height="${scale}" />`,
            );
        }
    }

    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${canvas} ${canvas}" shape-rendering="crispEdges">${rects.join('')}</svg>`;
}
