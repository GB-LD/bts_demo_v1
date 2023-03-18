web: heroku-php-apache2 public/
heroku config:set APP_ENV=prod
heroku config:set NODE_MODULES_CACHE=false
heroku buildpacks:add heroku/php
heroku buildpacks:add --index 1 heroku/nodejs