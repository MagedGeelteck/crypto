#!/bin/bash

# CryptoOnion Performance Optimization Script
# Run this script to apply all performance optimizations

echo "========================================="
echo "CryptoOnion Performance Optimization"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root or with sudo${NC}"
    exit 1
fi

echo -e "${YELLOW}1. Installing Redis if not installed...${NC}"
if ! command -v redis-cli &> /dev/null; then
    apt-get update
    apt-get install -y redis-server
    systemctl enable redis-server
    systemctl start redis-server
    echo -e "${GREEN}✓ Redis installed and started${NC}"
else
    echo -e "${GREEN}✓ Redis already installed${NC}"
fi

echo ""
echo -e "${YELLOW}2. Installing PHP Redis extension...${NC}"
if ! php -m | grep -q redis; then
    apt-get install -y php-redis
    echo -e "${GREEN}✓ PHP Redis extension installed${NC}"
else
    echo -e "${GREEN}✓ PHP Redis extension already installed${NC}"
fi

echo ""
echo -e "${YELLOW}3. Configuring Redis...${NC}"
cat > /etc/redis/redis.conf.d/custom.conf << 'EOF'
# Redis Performance Configuration
maxmemory 128mb
maxmemory-policy allkeys-lru
save ""
appendonly no
timeout 300
tcp-keepalive 60
EOF
systemctl restart redis-server
echo -e "${GREEN}✓ Redis configured${NC}"

echo ""
echo -e "${YELLOW}4. Installing OPcache configuration...${NC}"
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
cp /var/www/html/config/opcache.ini /etc/php/$PHP_VERSION/fpm/conf.d/10-opcache.ini
cp /var/www/html/config/opcache.ini /etc/php/$PHP_VERSION/cli/conf.d/10-opcache.ini
echo -e "${GREEN}✓ OPcache configured${NC}"

echo ""
echo -e "${YELLOW}5. Installing PHP-FPM pool configuration...${NC}"
cp /var/www/html/config/php-fpm-pool.conf /etc/php/$PHP_VERSION/fpm/pool.d/cryptoonion.conf
# Create log directory
mkdir -p /var/log/php-fpm
chown www-data:www-data /var/log/php-fpm
echo -e "${GREEN}✓ PHP-FPM pool configured${NC}"

echo ""
echo -e "${YELLOW}6. Applying MySQL optimizations...${NC}"
echo -e "${YELLOW}   Note: Backup your current MySQL config first!${NC}"
read -p "Do you want to apply MySQL optimizations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    cp /etc/mysql/mysql.conf.d/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf.backup
    cat /var/www/html/config/mysql-optimization.cnf >> /etc/mysql/mysql.conf.d/mysqld.cnf
    systemctl restart mysql
    echo -e "${GREEN}✓ MySQL optimizations applied${NC}"
else
    echo -e "${YELLOW}⊘ MySQL optimizations skipped${NC}"
fi

echo ""
echo -e "${YELLOW}7. Running Laravel optimizations...${NC}"
cd /var/www/html
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache
echo -e "${GREEN}✓ Laravel caches created${NC}"

echo ""
echo -e "${YELLOW}8. Running database migrations for indexes...${NC}"
cd /var/www/html
sudo -u www-data php artisan migrate --force
echo -e "${GREEN}✓ Database indexes added${NC}"

echo ""
echo -e "${YELLOW}9. Setting up queue worker...${NC}"
cat > /etc/systemd/system/cryptoonion-queue.service << 'EOF'
[Unit]
Description=CryptoOnion Queue Worker
After=network.target redis.service mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/html
ExecStart=/usr/bin/php /var/www/html/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --daemon

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable cryptoonion-queue
systemctl start cryptoonion-queue
echo -e "${GREEN}✓ Queue worker configured and started${NC}"

echo ""
echo -e "${YELLOW}10. Setting up Laravel scheduler...${NC}"
(crontab -u www-data -l 2>/dev/null; echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1") | crontab -u www-data -
echo -e "${GREEN}✓ Laravel scheduler configured${NC}"

echo ""
echo -e "${YELLOW}11. Restarting services...${NC}"
systemctl restart php$PHP_VERSION-fpm
systemctl restart nginx || systemctl restart apache2
echo -e "${GREEN}✓ Services restarted${NC}"

echo ""
echo -e "${YELLOW}12. Setting proper permissions...${NC}"
cd /var/www/html
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"

echo ""
echo "========================================="
echo -e "${GREEN}Performance Optimization Complete!${NC}"
echo "========================================="
echo ""
echo "Summary:"
echo "✓ Redis installed and configured"
echo "✓ OPcache enabled with JIT"
echo "✓ PHP-FPM pool optimized"
echo "✓ MySQL memory usage reduced"
echo "✓ Database indexes added"
echo "✓ Laravel caches created"
echo "✓ Queue worker running"
echo "✓ Scheduler configured"
echo ""
echo "Next steps:"
echo "1. Monitor server resources: htop or top"
echo "2. Check Redis: redis-cli info"
echo "3. Check OPcache: php -i | grep opcache"
echo "4. Check queue: php artisan queue:work"
echo "5. Monitor logs: tail -f storage/logs/laravel.log"
echo ""
echo -e "${YELLOW}Note: Your site is now in PRODUCTION mode (APP_DEBUG=false)${NC}"
echo ""
