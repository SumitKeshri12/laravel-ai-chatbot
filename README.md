<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel AI Chatbot Application

---

## Objective

To streamline customer support and engagement by providing an AI-powered chatbot platform that automates responses, manages user queries, and integrates seamlessly with leading AI technologies—all built on a secure and scalable Laravel framework.

---

## Key Features

-   User authentication (API-based, Passport/Sanctum)
-   Multi-provider chatbot integration (OpenAI, Ollama, Rasa)
-   Ticketing system for user queries and support
-   Chat history and conversation management
-   API-first design (Postman collections provided)
-   Modern Laravel 11+ structure and best practices
-   Tailwind CSS for frontend styling (if UI is extended)

---

## Supported AI Providers

-   **OpenAI**: GPT-based conversational AI
-   **Ollama**: On-premise or custom LLM server
-   **Rasa**: Open-source conversational AI framework

---

## API Documentation

See [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for full details on endpoints, request/response examples, and usage.

---

## Setup Instructions

1. **Clone the repository**
    ```bash
    git clone <your-repo-url>
    cd Chatbot
    ```
2. **Install PHP dependencies**
    ```bash
    composer install
    ```
3. **Install Node dependencies and build assets**
    ```bash
    npm install && npm run dev
    ```
4. **Copy and configure environment**
    ```bash
    cp .env.example .env
    # Edit .env with your database and AI provider credentials
    ```
5. **Generate application key**
    ```bash
    php artisan key:generate
    ```
6. **Run migrations**
    ```bash
    php artisan migrate
    ```
7. **(Optional) Seed database**
    ```bash
    php artisan db:seed
    ```
8. **Start the development server**
    ```bash
    php artisan serve
    ```

---

## Basic Usage

-   Use the provided API endpoints to register, login, chat with AI, and manage tickets.
-   Authenticate using the Bearer token returned on login/registration.
-   Use the `provider` field in `/api/chat` to select the AI backend (`openai`, `ollama`, or `rasa`).
-   Postman collections are available for quick API testing.

---

## Contribution

Contributions are welcome! Please open issues or submit pull requests for improvements or bug fixes.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
