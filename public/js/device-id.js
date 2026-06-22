async function generateFingerprint() {
    try {
        const navigatorInfo = [
            navigator.userAgent,
            navigator.language,
            navigator.platform,
            screen.width,
            screen.height,
            screen.colorDepth
        ].join('###');

        if (window.crypto && window.crypto.subtle) {
            const encoder = new TextEncoder();
            const data = encoder.encode(navigatorInfo);
            const hashBuffer = await crypto.subtle.digest('SHA-256', data);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        } else {
            return btoa(navigatorInfo);
        }
    } catch (e) {
        return Date.now() + "_" + Math.random().toString(36).substring(2);
    }
}

async function getDeviceId() {
    let deviceId = localStorage.getItem("device_id");

    if (!deviceId) {
        deviceId = await generateFingerprint();
        localStorage.setItem("device_id", deviceId);
    }

    return deviceId;
}
