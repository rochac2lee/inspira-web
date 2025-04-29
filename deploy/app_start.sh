#!/bin/bash
aws s3 sync /home/ubuntu/opet/inspira8/public/fr s3://static.opetinspira.com/fr/ --acl public-read --cache-control "max-age=31536000" --metadata-directive REPLACE --size-only
find /home/ubuntu/opet/inspira8 -type f -name "*.php" -exec touch {} +
php /home/ubuntu/opet/inspira8/artisan migrate --force
php /home/ubuntu/opet/inspira8/artisan optimize
php /home/ubuntu/opet/inspira8/artisan up
php /home/ubuntu/opet/inspira8/artisan queue:restart