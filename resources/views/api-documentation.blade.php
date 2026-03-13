<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum API Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .base-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .base-info h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .base-info code {
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin: 5px 0;
            color: #495057;
            font-family: 'Courier New', monospace;
        }

        .section-title {
            font-size: 1.8em;
            color: #333;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        .accordion {
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .accordion:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .accordion-header {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .accordion-header:hover {
            background: linear-gradient(to right, #e9ecef, #f8f9fa);
        }

        .accordion-header.active {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
        }

        .accordion-title {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .method-badge {
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
        }

        .method-get {
            background: #28a745;
            color: white;
        }

        .method-post {
            background: #007bff;
            color: white;
        }

        .method-put {
            background: #ffc107;
            color: #333;
        }

        .method-delete {
            background: #dc3545;
            color: white;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #495057;
        }

        .accordion-header.active .endpoint-path {
            color: white;
        }

        .accordion-icon {
            transition: transform 0.3s ease;
            font-size: 1.2em;
        }

        .accordion-header.active .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #ffffff;
        }

        .accordion-content.active {
            max-height: 2000px;
            border-top: 1px solid #e0e0e0;
        }

        .accordion-body {
            padding: 25px;
        }

        .api-section {
            margin-bottom: 20px;
        }

        .api-section h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .code-block {
            background: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 6px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.6;
            margin: 10px 0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .code-block pre {
            margin: 0;
        }

        .description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .auth-required {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85em;
            margin-bottom: 15px;
        }

        .auth-required::before {
            content: "🔒";
        }

        .response-example {
            margin-top: 15px;
        }

        .note {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .note h4 {
            color: #2196F3;
            margin-bottom: 10px;
        }

        .note ul {
            margin-left: 20px;
            color: #555;
        }

        .note li {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }

            .content {
                padding: 20px;
            }

            .accordion-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📚 Forum API Documentation</h1>
            <p>Complete REST API documentation for Forum application</p>
        </div>

        <div class="content">
            <div class="base-info">
                <h3>Base URL</h3>
                <code>{{ env('APP_URL', 'https://api.forum.gutsylab.com') }}/api</code>

                <h3 style="margin-top: 20px;">Authentication</h3>
                <p style="margin: 10px 0; color: #666;">This API uses Laravel Sanctum for authentication. After
                    login/register, include the token in the header:</p>
                <code>Authorization: Bearer {your_token}</code>
            </div>

            <!-- Authentication -->
            <h2 class="section-title">🔐 Authentication</h2>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/auth/register</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <p class="description">Register a new user account</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (201):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "access_token": "1|abc123..."
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/auth/login</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <p class="description">Login to existing account</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "email": "john@example.com",
  "password": "password123"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com"
    },
    "access_token": "2|xyz789..."
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/auth/logout</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Logout from current session</p>

                        <div class="api-section">
                            <h4>Headers:</h4>
                            <div class="code-block">
                                <pre>Authorization: Bearer {token}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Logout successful"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/auth/forgot-password</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <p class="description">Request password reset link to be sent to user's email</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "email": "john@example.com"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Password reset link sent to your email"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200) - Validation Failed:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "Forgot password validation failed",
  "errors": {
    "email": ["No user found with this email address"]
  }
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>User will receive an email with password reset link</li>
                                <li>Reset link contains a token that expires after a certain time</li>
                                <li>Email must exist in the system</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/auth/reset-password</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <p class="description">Reset user password using the token from email</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Validation Rules:</h4>
                            <div class="code-block">
                                <pre>token: required
email: required, must be valid email
password: required, minimum 8 characters, must match confirmation
password_confirmation: required, must match password</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Password reset successfully"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (422) - Validation Error:</h4>
                            <div class="code-block">
                                <pre>{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["This password reset token is invalid."]
  }
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Token must be valid and not expired</li>
                                <li>Password must be at least 8 characters</li>
                                <li>Password confirmation must match</li>
                                <li>After successful reset, user must login with new password</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topics -->
            <h2 class="section-title">📝 Topics</h2>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/topics</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get topics from users you follow and your own topics with pagination</p>

                        <div class="api-section">
                            <h4>Query Parameters:</h4>
                            <div class="code-block">
                                <pre>page (optional): Page number for pagination</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Shows only topics from users you follow</li>
                                <li>Also includes your own topics</li>
                                <li>Use /topics/trending to see all trending topics</li>
                            </ul>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices...",
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments_count": 5,
        "likes_count": 10
      }
    ],
    "per_page": 20,
    "total": 50
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/topics/trending</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get top 10 trending topics with most comments</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Laravel Best Practices",
      "body": "What are the best practices...",
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "category": {
        "id": 1,
        "name": "Laravel"
      },
      "comments_count": 25,
      "likes_count": 10
    }
  ]
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Returns maximum 10 topics</li>
                                <li>Sorted by comments count (descending)</li>
                                <li>Includes all topic details with relationships</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/topics</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Create a new topic. Category will be created automatically if not exists.
                        </p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "title": "Laravel Best Practices",
  "body": "What are the best practices when using Laravel?",
  "category_name": "Laravel"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (201):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Topic created successfully",
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices...",
    "user_id": 1,
    "topic_category_id": 1
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/topics/{id}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get detailed information about a specific topic</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel Best Practices",
    "body": "What are the best practices...",
    "user": {...},
    "category": {...},
    "comments": [...],
    "comments_count": 5,
    "likes_count": 10
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-put">PUT</span>
                        <span class="endpoint-path">/topics/{id}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Update a topic. Only the owner can update their topic.</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "title": "Updated Title",
  "body": "Updated body content",
  "category_name": "PHP"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Topic updated successfully",
  "data": {...}
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-delete">DELETE</span>
                        <span class="endpoint-path">/topics/{id}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Delete a topic. Only the owner can delete their topic.</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Topic deleted successfully"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <h2 class="section-title">💬 Comments</h2>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/topics/{topicId}/comments</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get all comments for a specific topic</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "topic_id": 1,
        "user_id": 2,
        "body": "Great question!",
        "user": {
          "id": 2,
          "name": "Jane Doe"
        }
      }
    ]
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/topics/{topicId}/comments</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Create a new comment on a topic</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "body": "This is a great topic!"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (201):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Comment created successfully",
  "data": {
    "id": 1,
    "topic_id": 1,
    "body": "This is a great topic!",
    "user": {...}
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-put">PUT</span>
                        <span class="endpoint-path">/topics/{topicId}/comments/{commentId}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Update a comment. Only the owner can update their comment.</p>

                        <div class="api-section">
                            <h4>Request Body:</h4>
                            <div class="code-block">
                                <pre>{
  "body": "Updated comment text"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Comment updated successfully",
  "data": {
    "id": 1,
    "topic_id": 1,
    "user_id": 2,
    "body": "Updated comment text",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "username": "janedoe"
    }
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (403) - Unauthorized:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "You are not authorized to update this comment"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "Comment not found"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-delete">DELETE</span>
                        <span class="endpoint-path">/topics/{topicId}/comments/{commentId}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Delete a comment. Only the owner can delete their comment.</p>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Comment deleted successfully"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (403) - Unauthorized:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "You are not authorized to delete this comment"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "Comment not found"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Likes -->
            <h2 class="section-title">❤️ Likes</h2>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/topics/{topicId}/like</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Toggle like/unlike on a topic</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "Topic liked successfully",
  "data": {
    "liked": true,
    "likes_count": 11
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/topics/{topicId}/likes</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get list of users who liked a topic</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe"
      }
    ]
  }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <h2 class="section-title">👥 Users</h2>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/users/search</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Search users by username, email, or name</p>

                        <div class="api-section">
                            <h4>Query Parameters:</h4>
                            <div class="code-block">
                                <pre>query (required): Search term
page (optional): Page number</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Example:</h4>
                            <div class="code-block">
                                <pre>GET /users/search?query=john&page=1</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "name": "John Smith",
        "username": "johnsmith",
        "email": "johnsmith@example.com",
        "created_at_formatted": "15 Feb 2026, 10:00",
        "created_at_ago": "1 month ago",
        "updated_at_formatted": "12 Mar 2026, 14:20",
        "updated_at_ago": "1 day ago",
        "is_follow": false
      }
    ],
    "per_page": 20,
    "total": 5,
    "last_page": 1
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (422) - Validation Error:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "Query parameter is required"
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Searches in username, email, and name fields</li>
                                <li>Results exclude the currently authenticated user</li>
                                <li>is_follow indicates if you're following that user</li>
                                <li>Returns 20 results per page</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/users/{id}</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get user profile with stats</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "created_at_formatted": "01 Jan 2026, 00:00",
    "created_at_ago": "2 months ago",
    "updated_at_formatted": "10 Mar 2026, 15:30",
    "updated_at_ago": "3 days ago",
    "topics_count": 15,
    "followers_count": 20,
    "following_count": 10,
    "is_following": false,
    "is_you": false
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>is_following: indicates if the authenticated user is following this profile</li>
                                <li>is_you: true if viewing your own profile</li>
                                <li>Includes counts for topics, followers, and following</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/users/{id}/follow</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Follow a user</p>

                        <div class="api-section">
                            <h4>Response (200) - Success:</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "User followed successfully"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (422) - Already Following:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "You are already following this user"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (422) - Cannot Follow Self:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "You cannot follow yourself"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-delete">DELETE</span>
                        <span class="endpoint-path">/users/{id}/follow</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Unfollow a user</p>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "message": "User unfollowed successfully"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (422) - Not Following:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "You are not following this user"
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/users/{id}/followers</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get list of user's followers with pagination</p>

                        <div class="api-section">
                            <h4>Query Parameters:</h4>
                            <div class="code-block">
                                <pre>page (optional): Page number for pagination</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "name": "Jane Doe",
        "username": "janedoe",
        "email": "jane@example.com",
        "created_at_formatted": "01 Feb 2026, 14:30",
        "created_at_ago": "1 month ago",
        "updated_at_formatted": "10 Mar 2026, 09:15",
        "updated_at_ago": "3 days ago",
        "is_follow": true
      }
    ],
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Returns paginated list of followers (20 per page)</li>
                                <li>Includes is_follow field indicating if you follow them back</li>
                                <li>Timestamps are formatted in both human-readable and relative formats</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/users/{id}/following</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get list of users that this user is following with pagination</p>

                        <div class="api-section">
                            <h4>Query Parameters:</h4>
                            <div class="code-block">
                                <pre>page (optional): Page number for pagination</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 3,
        "name": "Bob Smith",
        "username": "bobsmith",
        "email": "bob@example.com",
        "created_at_formatted": "15 Jan 2026, 08:00",
        "created_at_ago": "2 months ago",
        "updated_at_formatted": "12 Mar 2026, 16:45",
        "updated_at_ago": "1 day ago",
        "is_follow": false
      }
    ],
    "per_page": 20,
    "total": 50,
    "last_page": 3
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Returns paginated list of following (20 per page)</li>
                                <li>Includes is_follow field indicating if you also follow them</li>
                                <li>Can view following list of any user</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion(this)">
                    <div class="accordion-title">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/users/{id}/topics</span>
                    </div>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <div class="auth-required">Authentication Required</div>
                        <p class="description">Get all topics created by a specific user with pagination</p>

                        <div class="api-section">
                            <h4>Query Parameters:</h4>
                            <div class="code-block">
                                <pre>page (optional): Page number for pagination</pre>
                            </div>
                        </div>

                        <div class="note">
                            <h4>📌 Note</h4>
                            <ul>
                                <li>Shows all topics created by the specified user</li>
                                <li>Can view topics from any user (not limited to following)</li>
                                <li>Includes full topic details with user, category, comments, and likes</li>
                                <li>Returns 20 topics per page</li>
                            </ul>
                        </div>

                        <div class="api-section">
                            <h4>Response (200):</h4>
                            <div class="code-block">
                                <pre>{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Laravel Best Practices",
        "body": "What are the best practices...",
        "created_at_formatted": "13 Mar 2026, 10:30",
        "created_at_ago": "2 hours ago",
        "user": {
          "id": 5,
          "name": "John Doe",
          "username": "johndoe",
          "email": "john@example.com",
          "created_at_formatted": "01 Jan 2026, 00:00",
          "created_at_ago": "2 months ago"
        },
        "category": {
          "id": 1,
          "name": "Laravel"
        },
        "comments": [],
        "likes": [],
        "comments_count": 5,
        "likes_count": 10,
        "is_like": false
      }
    ],
    "per_page": 20,
    "total": 50,
    "last_page": 3
  }
}</pre>
                            </div>
                        </div>

                        <div class="api-section">
                            <h4>Response (404) - User Not Found:</h4>
                            <div class="code-block">
                                <pre>{
  "success": false,
  "message": "User not found"
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="note">
                <h4>📌 Important Notes</h4>
                <ul>
                    <li>All endpoints requiring authentication must include <code>Authorization: Bearer {token}</code>
                        header</li>
                    <li>Default pagination is 20 items per page</li>
                    <li>Only topic/comment owners can update or delete their content</li>
                    <li>Categories are automatically created when creating a topic</li>
                    <li>Like endpoint uses toggle mechanism (same endpoint for like/unlike)</li>
                    <li>Users cannot follow themselves</li>
                    <li>Search excludes the currently logged-in user</li>
                    <li><strong>GET /topics</strong> only shows topics from users you follow and your own topics</li>
                    <li><strong>GET /topics/trending</strong> shows top 10 topics with most comments from all users</li>
                    <li><strong>GET /users/{id}/topics</strong> shows all topics created by a specific user (any user, not limited to following)</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const isActive = header.classList.contains('active');

            // Close all accordions
            document.querySelectorAll('.accordion-header').forEach(h => {
                h.classList.remove('active');
            });
            document.querySelectorAll('.accordion-content').forEach(c => {
                c.classList.remove('active');
            });

            // Open clicked accordion if it wasn't active
            if (!isActive) {
                header.classList.add('active');
                content.classList.add('active');
            }
        }

        // Close accordion when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.accordion')) {
                document.querySelectorAll('.accordion-header').forEach(h => {
                    h.classList.remove('active');
                });
                document.querySelectorAll('.accordion-content').forEach(c => {
                    c.classList.remove('active');
                });
            }
        });
    </script>
</body>

</html>
