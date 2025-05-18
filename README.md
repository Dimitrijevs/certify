# 1. Clone the repository
git clone https://github.com/Dimitrijevs/certify.git
cd certify

# 2. Install PHP dependencies
composer install

# 3. Run the automated setup script
php artisan certify:setup
# This will:
# - Create .env file from .env.example
# - Generate application key
# - Create storage link
# - Install NPM dependencies
# - Build frontend assets
# - Run migrations
# - Seed the database
# - Optimize the application

# 4. Start the development server
php artisan serve

# Open a second terminal and start the queue worker
# This processes background jobs like sending emails
php artisan queue:work