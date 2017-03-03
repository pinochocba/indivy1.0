# update segments
php app/console mautic:segments:update

# update campaigns and rebuild
php app/console mautic:campaigns:rebuild

# trigger
php app/console mautic:campaigns:trigger

# emails sent
php app/console mautic:emails:send

# emails fetch
php app/console mautic:email:fetch

# social:monitoring
php app/console mautic:social:monitoring

# webhooks
php app/console mautic:webhooks:process

# iplookup:download
php app/console mautic:iplookup:download

# iplookup:download
#php app/console mautic:iplookup:download

# mautic:broadcasts:send
php app/console mautic:broadcasts:send

# Clean up data
php app/console mautic:maintenance:cleanup --days-old=365 --dry-run
