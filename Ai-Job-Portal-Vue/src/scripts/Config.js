let Production = false;

// Default backend URL (can be overridden from .env if available)
let baseURL = Production ? 'https://example.com/api/' : 'http://127.0.0.1:8000/api/';

// Try to get the API URL from environment variables if available
if (import.meta.env && import.meta.env.VITE_API_URL) {
    baseURL = import.meta.env.VITE_API_URL;
}

// Make sure the URL ends with a trailing slash
if (!baseURL.endsWith('/')) {
    baseURL += '/';
}

console.log('Using API baseURL:', baseURL);

export default {
    baseURL,
    // Add additional config values as needed
    production: Production,
    authEndpoints: {
        register: 'user/register',  // Default registration endpoint
        login: 'user/login',        // Default login endpoint
    }
}