# test-api

```shell
# Make sure Docker Desktop is running

make init # build image, containers, composer install, symfony cache
make fixture # creates SQL tables and inserts fixtures

# Open http://127.0.0.1:8080/notifications?user_id=2 to test app response
make test # run PHPUnit test
make style # run PHPCS

# Other commands for development
make stop # stop all project containers
make start # start all project containers
make destroy # destroys containers, images, volumes, networks
make restart # stop + start
make recreate # destroy, force rebuild, composer install, symfony cache
make shell # ssh into container for debug
make cache # symfony cache:clear
```

## Comments

## Issues
- Split database table creation from seed
- Need Auth system for user_id
- If authenticated, middleware could capture and save detected user devices.
- Prevent pushing duplicate notifications
