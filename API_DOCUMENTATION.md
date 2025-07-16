# API Documentation – Laravel AI Chatbot Application

## Base URL

```
https://localhost:8000/api/
```

_Replace with your actual domain or localhost URL_

---

## Authentication

All endpoints (except login/register) require authentication via Bearer token (Laravel Passport/Sanctum).

**Header Example:**

```
Authorization: Bearer {access_token}
```

---

## Endpoints

### 1. User Authentication

#### Register

-   **POST** `/register`
-   **Body:**
    ```json
    {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "password",
        "password_confirmation": "password"
    }
    ```
-   **Response:**
    ```json
    {
      "user": { ... },
      "token": "..."
    }
    ```

---

#### Login

-   **POST** `/login`
-   **Body:**
    ```json
    {
        "email": "john@example.com",
        "password": "password"
    }
    ```
-   **Response:**
    ```json
    {
      "user": { ... },
      "token": "..."
    }
    ```

---

#### Logout

-   **POST** `/logout`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    {
        "message": "Logged out successfully"
    }
    ```

---

### 2. Chatbot

#### Send Message

-   **POST** `/chat`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "message": "Hello, AI!",
        "provider": "openai" // or "ollama" or "rasa"
    }
    ```
-   **Response:**
    ```json
    {
        "reply": "Hello! How can I help you today?",
        "conversation_id": "abc123"
    }
    ```

---

### 3. Tickets

#### Create Ticket

-   **POST** `/tickets`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "question": "How do I reset my password?"
    }
    ```
-   **Response:**
    ```json
    {
        "ticket": {
            "id": 1,
            "question": "How do I reset my password?",
            "answer": null,
            "user_id": 1,
            "created_at": "...",
            "updated_at": "..."
        }
    }
    ```

---

#### List Tickets

-   **GET** `/tickets`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    [
      {
        "id": 1,
        "question": "How do I reset my password?",
        "answer": "Click on 'Forgot Password' at login.",
        "user_id": 1,
        "created_at": "...",
        "updated_at": "..."
      },
      ...
    ]
    ```

---

#### Get Single Ticket

-   **GET** `/tickets/{id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    {
        "id": 1,
        "question": "How do I reset my password?",
        "answer": "Click on 'Forgot Password' at login.",
        "user_id": 1,
        "created_at": "...",
        "updated_at": "..."
    }
    ```

---

#### Answer Ticket

-   **PUT** `/tickets/{id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "answer": "Click on 'Forgot Password' at login."
    }
    ```
-   **Response:**
    ```json
    {
        "ticket": {
            "id": 1,
            "question": "How do I reset my password?",
            "answer": "Click on 'Forgot Password' at login.",
            "user_id": 1,
            "created_at": "...",
            "updated_at": "..."
        }
    }
    ```

---

#### Delete Ticket

-   **DELETE** `/tickets/{id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    {
        "message": "Ticket deleted successfully"
    }
    ```

---

### 4. Chat History

#### Get Chat History

-   **GET** `/chat/history`
-   **Headers:** `Authorization: Bearer {token}`
-   **Query Params (optional):**
    -   `conversation_id`: Filter by conversation
-   **Response:**
    ```json
    [
      {
        "id": 1,
        "user_id": 1,
        "message": "Hello, AI!",
        "reply": "Hello! How can I help you today?",
        "provider": "openai",
        "conversation_id": "abc123",
        "created_at": "...",
        "updated_at": "..."
      },
      ...
    ]
    ```

---

## Error Responses

-   **401 Unauthorized**
    ```json
    { "message": "Unauthenticated." }
    ```
-   **422 Validation Error**
    ```json
    { "errors": { "field": ["Error message"] } }
    ```
-   **404 Not Found**
    ```json
    { "message": "Resource not found." }
    ```

---

## Postman Collection

-   Postman collections are provided in the project root:
    -   `Chatbot API Auth Collection.postman_collection.json`
    -   `Products_Runner_TEST_API.json`

---

## Notes

-   All endpoints expect and return JSON.
-   Use the `provider` field in `/chat` to select the AI backend (`openai`, `ollama`, or `rasa`).
-   All timestamps are in ISO 8601 format.
