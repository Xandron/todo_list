#!/usr/bin/env bash

error='\e[1;41m' # Red with white background
info='\e[0;93m' # yellow
NC='\e[0m' # No Color

echo -e "${info}Composer install...${NC}"
composer install

echo -e "${info}Database created...${NC}"
php bin/console doctrine:database:create --if-not-exists

echo -e "${info}Migrations accepted...${NC}"
bin/console doctrine:migrations:migrate -n

echo -e "${info}Faker run...${NC}"
bin/console faker

echo -e "${info}Done!${NC}"
