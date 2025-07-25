#!/bin/sh
set -e

echo "Waiting for database connection..."

# Get database connection details from Laravel configuration
MAX_TRIES=30
WAIT_SECONDS=2

# Function to check database connection
check_db_connection() {
    php artisan db:monitor > /tmp/log 2>&1
    return $?
}

# Wait for database to be ready
TRIES=0
until check_db_connection || [ $TRIES -eq $MAX_TRIES ]; do
    echo "Waiting for database connection... ($((TRIES+1))/$MAX_TRIES)"
    sleep $WAIT_SECONDS
    TRIES=$((TRIES+1))
done

if [ $TRIES -eq $MAX_TRIES ]; then
    echo "Error: Could not connect to database after $MAX_TRIES attempts!"
    exit 1
fi

echo "Database connection established"

# Determine if we need to run initial setup
DB_READY=false
if php artisan migrate:status > /dev/null 2>&1; then
    DB_READY=true
    echo "Migration table exists. Database is ready."
else
    echo "Migration table not found. Setting up database..."
fi

if [ "$DB_READY" = false ]; then
    # First time setup - run migrations and initial seeds
    echo "Running migrations..."
    php artisan migrate --force
    
    echo "Running seeds..."
    php artisan db:seed --class=ProductionSeeder --force
    
    echo "Setting up Passport..."
    # Generate Passport keys only if they don't exist
    if [ ! -f "./storage/oauth-private.key" ]; then
        echo 'generating keys'
        php artisan passport:keys --force
    fi
    
    # Create a personal access client if none exists
    php artisan passport:client --personal --name="Personal Access Client" --no-interaction

    # Check if link exists, create if not
    [ ! -L public/storage ] && php artisan storage:link
else
    # Database exists - just run any pending migrations
    echo "Running any pending migrations..."
    php artisan migrate --force
    
    # Ensure Passport keys exist
    if [ ! -f "./storage/oauth-private.key" ]; then
        echo "Passport keys not found, regenerating..."
        php artisan passport:keys --force
    fi
fi

php artisan storage:link || true

# Set correct permissions
chown -R www-data:www-data storage bootstrap/cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground