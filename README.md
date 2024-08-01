# Project Setup Instructions

## Prerequisites

-   Git
-   Composer
-   PHP
-   XAMPP

## Steps to Set Up the Project

1. **Clone the Repository**:

    ```bash
    git clone https://github.com/Jiysea/tu-efficient.git
    cd <directory-of-where-you-cloned-the-project>
    ```

2. **Install Dependencies**:

    ```bash
    composer install
    ```

3. **Set Up Environment Variables**:

    ```bash
    cp .env.example .env
    ```

    Open the `.env` file and update it with your local configuration.

4. **Generate Application Key**:

    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**:
    ```bash
    php artisan migrate --seed
    ```

## Running the Project

-   To start the local development server:
    ```bash
    Apache start (XAMPP)
    MySQL start (XAMPP)
    npm run dev
    php artisan serve
    ```
