/**
 * User API Client
 * Handles all user-related API calls
 */

const User = {
    /**
     * Search users by username, email, or name
     * @param {string} query - Search term
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async search(query, page = 1, token) {
        return await get('/users/search', { query, page }, token);
    },

    /**
     * Get user profile by ID
     * @param {number} id - User ID
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getProfile(id, token) {
        return await get(`/users/${id}`, {}, token);
    },

    /**
     * Follow a user
     * @param {number} userId - User ID to follow
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async follow(userId, token) {
        return await post(`/users/${userId}/follow`, {}, token);
    },

    /**
     * Unfollow a user
     * @param {number} userId - User ID to unfollow
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async unfollow(userId, token) {
        return await deleteRequest(`/users/${userId}/follow`, token);
    },

    /**
     * Get user's followers
     * @param {number} userId - User ID
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getFollowers(userId, page = 1, token) {
        return await get(`/users/${userId}/followers`, { page }, token);
    },

    /**
     * Get users that user is following
     * @param {number} userId - User ID
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getFollowing(userId, page = 1, token) {
        return await get(`/users/${userId}/following`, { page }, token);
    }
};

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = User;
}
