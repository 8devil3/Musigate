#Clear Application Cache
php artisan cache:clear
wait

#Clear Route Cache
php artisan route:clear
wait

#Clear Configuration Cache
php artisan config:clear
wait

#Clear Compiled Views Cache
php artisan view:clear

#Clear Events Cache
php artisan event:clear

# Clear npm cache
npm cache clean --force

#Rebuild config cache
php artisan config:cache