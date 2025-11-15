// Configuration file for Absensi Kelas
const CONFIG = {
    // Replace with your actual Google Apps Script Web App ID
    // Get this from: Apps Script > Deploy > Web app > Current web app URL
    WEB_APP_ID: 'YOUR_WEB_APP_ID_HERE',

    // API endpoints
    get API_BASE_URL() {
        return `https://script.google.com/macros/s/${this.WEB_APP_ID}/exec`;
    }
};

// Make CONFIG available globally
window.CONFIG = CONFIG;