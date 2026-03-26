/**
 * Authentication API Client
 * Handles all authentication-related API calls
 */

const Auth = {
    /**
     * Register new user
     * @param {object} userData - {name, username, email, password, password_confirmation}
     * @returns {Promise}
     */
    async register(userData) {
        return await post('/auth/register', userData);
    },

    /**
     * Login user
     * @param {string} email
     * @param {string} password
     * @returns {Promise}
     */
    async login(email, password) {
        return await post('/auth/login', { email, password });
    },

    /**
     * Logout user
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async logout(token) {
        return await post('/auth/logout', {}, token);
    },

    /**
     * Forgot password
     * @param {string} email
     * @returns {Promise}
     */
    async forgotPassword(email) {
        return await post('/auth/forgot-password', { email });
    },

    /**
     * Reset password
     * @param {object} resetData - {token, email, password, password_confirmation}
     * @returns {Promise}
     */
    async resetPassword(resetData) {
        return await post('/auth/reset-password', resetData);
    },

    /**
     * Save token to localStorage
     * @param {string} token
     */
    saveToken(token) {
        localStorage.setItem('auth_token', token);
    },

    /**
     * Get token from localStorage
     * @returns {string|null}
     */
    getToken() {
        return localStorage.getItem('auth_token');
    },

    /**
     * Remove token from localStorage
     */
    removeToken() {
        localStorage.removeItem('auth_token');
    },

    /**
     * Check if user is authenticated
     * @returns {boolean}
     */
    isAuthenticated() {
        return !!this.getToken();
    }
};

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Auth;
}
