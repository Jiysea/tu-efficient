# Project Setup Instructions

## Prerequisites
##### Before setting up the project, I assume that you've already installed the following:
-   [Git](https://git-scm.com/downloads)
-   [Composer](https://getcomposer.org/download/)
-   [Laravel v11](https://laravel.com/docs/11.x)
-   [XAMPP](https://www.apachefriends.org/download.html) (also includes PHP v8.2)

<br>

## Steps to Set Up the Project

1. **Configure XAMPP `php.ini` File**
    - Go to your XAMPP Control Panel
    - Click on `Config` for the `Apache` module
    - Click on the `PHP (php.ini)`
    - `Ctrl+F` and we need to search for 3 important lines:
        - `extension=zip`
        - `extension=gd`
        - `extension=pdo_mysql` 
    - Enable them by removing ```;```
    - if they're already removed, then that's better! :)
    <br>
2. **Clone the Repository**:
    - Open a `Command Terminal` or `Git Bash`
    - Choose the destination you want to clone to project:
        ```bash
        # UNIX-based Systems / Windows
        cd <your-destination>
        
        # Example: cd documents/tu-efficient
        ```
    - Clone it!
        ```bash
        git clone https://github.com/Jiysea/tu-efficient.git
        ```
    <br>
3. **Install Dependencies**:
    - Install composer dependencies:
        ```bash
        composer install
        ```
    - Install npm packages/dependencies:
        ```bash
        npm install
        ```
    <br>
4. **Set Up Environment Variables**:
    - Copy `.env.example` and paste to `.env` file
    - (if you didn't have any `.env` file, just create one on the project root destination)
    - ```bash
      # In Unix/Win Powershell
      cp .env.example .env

      # In Windows
      copy .env.example .env
    - Open the `.env` file
    - Generate an application key:
        ```php
        php artisan key:generate
        ```
    <br>
5. **Encountering an Error**
    - When you encounter an error when trying to generate an application key, try the following:
        - Open the project in your preferred IDE
        - Search for the file in this destination and open it: 
            ```php
            app -> Providers -> AppServiceProvider.php
            ```
        - Temporarily [comment](https://www.w3schools.com/php/php_comments.asp) this line: 
            ```php
            DB::statement('SET SESSION  innodb_lock_wait_timeout = 5');
            ```
        - And run the application key generation again
        - Note: ***Don't forget to uncomment it after the seeding and migration! (refer to Step 6)***
        <br>
6. **Run Migrations and Generate with a Seeder**:
    - Before running the command, you need to Start the service from the XAMPP Control Panel first:
        - Click on `Start` for the `MySQL` module
        - Click on `Start` for the `Apache` module
    - Then run this command:
        ```bash
        php artisan migrate --seed
        ```
    - And wait for it to finish
    - ***Additional Notes:*** 
        - You can modify some values to change the amount of `implementations`, `assignments`, and `batches` in the `DatabaseSeeder.php` file.
        - Also note that you can't change the `max` value for `assignments` more than the amount of `Coordinator` users generated as it would more like return an `ArrayIndexOutOfBoundsException`, probably. (`Coordinator Users Generated: 7`)
        - And for the Coordinator Users, you can remove or add more as much as you want on the `initCoordinators()` function in the `DatabaseSeeder.php` class. Just copy-paste this format (values wrapped around `<>` are the modifiable):
            ```php
            $user = User::factory()->create(
                [
                    'first_name' => '<First_Name_Here>',
                    'middle_name' => '<Middle_Name_Here or just use null>',
                    'last_name' => '<Last_Name_Here>',
                    'extension_name' => '<Ext_Name_Here or just use null>',
                    'email' => '<any valid email>',
                    'contact_num' => '<should start with +639>',
                    'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                    'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ],
            );

            LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

            $settingsCoordinator = [
                'duplication_threshold' => config('settings.duplication_threshold'),
                'default_show_duplicates' => config('settings.default_show_duplicates'),
            ];

            foreach ($settingsCoordinator as $key => $setting) {
                $initSetting = UserSetting::factory()->create([
                    'users_id' => $user->id,
                    'key' => $key,
                    'value' => $setting,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->created_at,
                ]);

                LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
            }
            ```
            - Notice that only `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, and `contact_num` are the modifiable fields.
    <br>
## Running the Project
- If you haven't done it already, you need to Start the service from the XAMPP Control Panel first:
    - Click on `Start` for the `MySQL` module
    - Click on `Start` for the `Apache` module
- Then you need 3 terminals (opened to the Project Root Directory ex. `cd documents/tu-efficient`) to run 3 different commands:
    ```bash
    npm run dev
    php artisan serve
    php artisan queue:listen
- Access the page at `http://127.0.0.1:8000`
- Note: All accounts generated have the same password: `password`
<br>
