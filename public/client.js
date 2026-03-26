/**
 * API Client using jQuery Ajax
 * Simple HTTP client for making API requests
 */

const API_BASE_URL = 'http://localhost:8000/api';

/**
 * GET request
 * @param {string} endpoint - API endpoint (e.g., '/topics', '/users/1')
 * @param {object} params - Query parameters (optional)
 * @param {string} token - Authorization token (optional)
 * @returns {Promise}
 */
function get(endpoint, params = {}, token = null) {
    return $.ajax({
        url: `${API_BASE_URL}${endpoint}`,
        method: 'GET',
        data: params,
        headers: token ? { 'Authorization': `Bearer ${token}` } : {},
        dataType: 'json'
    });
}

/**
 * POST request
 * @param {string} endpoint - API endpoint
 * @param {object} data - Request body data
 * @param {string} token - Authorization token (optional)
 * @returns {Promise}
 */
function post(endpoint, data = {}, token = null) {
    return $.ajax({
        url: `${API_BASE_URL}${endpoint}`,
        method: 'POST',
        data: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {})
        },
        dataType: 'json'
    });
}

/**
 * PUT request
 * @param {string} endpoint - API endpoint
 * @param {object} data - Request body data
 * @param {string} token - Authorization token (optional)
 * @returns {Promise}
 */
function put(endpoint, data = {}, token = null) {
    return $.ajax({
        url: `${API_BASE_URL}${endpoint}`,
        method: 'PUT',
        data: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {})
        },
        dataType: 'json'
    });
}

/**
 * DELETE request
 * @param {string} endpoint - API endpoint
 * @param {string} token - Authorization token (optional)
 * @returns {Promise}
 */
function deleteRequest(endpoint, token = null) {
    return $.ajax({
        url: `${API_BASE_URL}${endpoint}`,
        method: 'DELETE',
        headers: token ? { 'Authorization': `Bearer ${token}` } : {},
        dataType: 'json'
    });
}

// Export functions (if using modules)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { get, post, put, deleteRequest };
}
