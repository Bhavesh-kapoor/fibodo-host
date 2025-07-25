# Fibodo

Fibodo is a web application built with the Laravel framework. It provides a robust and secure platform for managing user authentication, including OTP (One-Time Password) verification.

## About Fibodo

Fibodo leverages Laravel's powerful features to deliver a seamless and secure user experience. Key features include:

- User authentication with OTP verification
- Role-based access control
- Integration with third-party services
- Robust background job processing

## Getting Started

### Prerequisites

- PHP ^8.2
- Composer
- Node.js
- PostgreSQL

### Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/Fibodo.git
    cd Fibodo
    ```

2. Install PHP dependencies:
    ```sh
    composer install
    ```

3. Copy the example environment file and configure it:
    ```sh
    cp .env.example .env
    ```

4. Generate an application key:
    ```sh
    php artisan key:generate
    ```

5. Set up the database:
    ```sh
    php artisan migrate --seed
    ```

6. Start the development server:
    ```sh
    php artisan serve
    ```

## Configuration

### Environment Variables

- `OTP_MAX_ATTEMPTS`: Maximum number of OTP attempts (default: 5)
- `OTP_RESEND_TIMEOUT`: Timeout for resending OTP in seconds (default: 120)

### Third-Party Services

Configure third-party services in [services.php](http://_vscodecontentref_/0).

## Running Tests

To run the tests, use the following command:
```sh
php artisan test

Contributing
Thank you for considering contributing to Fibodo! Please read the contribution guide for details on how to contribute.

License
Fibodo is software licensed under the Fibodo Ltd. license. ```