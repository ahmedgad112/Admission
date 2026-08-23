const DEVICE_KEY = 'attendance_device_uuid';

export function getDeviceUuid(): string {
    const existing = window.localStorage.getItem(DEVICE_KEY);

    if (existing) {
        return existing;
    }

    const generated = crypto.randomUUID();
    window.localStorage.setItem(DEVICE_KEY, generated);

    return generated;
}
