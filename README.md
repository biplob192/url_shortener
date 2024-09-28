# Short URL Generator with Laravel

This Laravel application provides a simple and efficient way to shorten any URL. Users can easily input long URLs and generate concise, shareable links. The application features an intuitive interface, click tracking for monitoring link engagement, and customizable options for personalized short URLs. Designed for ease of use and built on Laravel’s powerful framework, this project is ideal for anyone looking to simplify their link sharing. 

Follow the steps below to set up and run the project locally.

## Let's Run the Application Locally

### Prerequisites

Ensure you have the following installed on your local environment:
- PHP (>= 8.2)
- Composer
- Node.js and npm
- MySQL or any other compatible database

### Cloning the Repository

Begin by cloning the repository to your local machine:

```bash
git clone https://github.com/biplob192/url_shortener.git
```

Navigate into the project directory:

```bash
cd url_shortener
```

### Install Composer Dependencies
Install all necessary PHP packages:

```bash
composer install --ignore-platform-reqs
```

### Environment Configuration
Copy the .env.example file to create your .env configuration file:

```bash
copy .env.example .env
```

Note: If you are using Git Bash, the above command may not work. Use Command Prompt or PowerShell instead.

### Database Configuration
Open the .env file and update the database credentials under the following section:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Generate Application Key
Run the following command to generate a unique application key:

```bash
php artisan key:generate
```

### Run Migrations and Seed Database
Set up your database by running migrations and seeding the database:

```bash
php artisan migrate:fresh --seed
```

### Start the Application
Start the Laravel development server:

```bash
php artisan serve
```

**Finally, browse the frontend project in a browser like Google Chrome.**

## Credentials for Login
Use any of the following credentials to log in:

Email: superadmin@gmail.com
Password: password

Email: admin@gmail.com
Password: password

Email: editor@gmail.com
Password: password

Email: employee@gmail.com
Password: password

**Note:** This Short URL Generator built with Laravel offers a user-friendly solution for simplifying link sharing. With its intuitive interface, click tracking, and customizable options, it streamlines the process of generating short URLs.

Follow the provided setup instructions to get your application up and running locally. Enjoy using the tool and feel free to contribute or customize it further to meet your needs!
