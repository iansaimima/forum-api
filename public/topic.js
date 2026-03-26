/**
 * Topic API Client
 * Handles all topic-related API calls
 */

const Topic = {
    /**
     * Get all topics
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getAll(page = 1, token) {
        return await get('/topics', { page }, token);
    },

    /**
     * Get single topic by ID
     * @param {number} id - Topic ID
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getById(id, token) {
        return await get(`/topics/${id}`, {}, token);
    },

    /**
     * Create new topic
     * @param {object} topicData - {title, body, category_name}
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async create(topicData, token) {
        return await post('/topics', topicData, token);
    },

    /**
     * Update topic
     * @param {number} id - Topic ID
     * @param {object} topicData - {title, body, category_name}
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async update(id, topicData, token) {
        return await put(`/topics/${id}`, topicData, token);
    },

    /**
     * Delete topic
     * @param {number} id - Topic ID
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async delete(id, token) {
        return await deleteRequest(`/topics/${id}`, token);
    },

    /**
     * Get topic comments
     * @param {number} topicId - Topic ID
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getComments(topicId, page = 1, token) {
        return await get(`/topics/${topicId}/comments`, { page }, token);
    },

    /**
     * Create comment on topic
     * @param {number} topicId - Topic ID
     * @param {string} body - Comment text
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async createComment(topicId, body, token) {
        return await post(`/topics/${topicId}/comments`, { body }, token);
    },

    /**
     * Update comment
     * @param {number} topicId - Topic ID
     * @param {number} commentId - Comment ID
     * @param {string} body - Updated comment text
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async updateComment(topicId, commentId, body, token) {
        return await put(`/topics/${topicId}/comments/${commentId}`, { body }, token);
    },

    /**
     * Delete comment
     * @param {number} topicId - Topic ID
     * @param {number} commentId - Comment ID
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async deleteComment(topicId, commentId, token) {
        return await deleteRequest(`/topics/${topicId}/comments/${commentId}`, token);
    },

    /**
     * Toggle like/unlike on topic
     * @param {number} topicId - Topic ID
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async toggleLike(topicId, token) {
        return await post(`/topics/${topicId}/like`, {}, token);
    },

    /**
     * Get users who liked topic
     * @param {number} topicId - Topic ID
     * @param {number} page - Page number (optional)
     * @param {string} token - Auth token
     * @returns {Promise}
     */
    async getLikes(topicId, page = 1, token) {
        return await get(`/topics/${topicId}/likes`, { page }, token);
    }
};

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Topic;
}
