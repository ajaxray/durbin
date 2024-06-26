# Durbin - Docker Container Manager

Tiny PHP script for monitoring Docker Containers running on a VPS/remote server.

<img src="https://github.com/ajaxray/durbin/assets/439612/0c0e0bdb-8d97-4050-b5d6-2c4920c330b6"  alt="Durbin Screenshot" />


## Installation Steps

1. `git clone git@github.com:ajaxray/durbin.git`, 
2. `composer install --prefer-dist`
3. Copy the `inc/config.php`.
```shell
cp inc/config.php inc/config.local.php
```
Then adjust your deployment environment in `inc/config.local.php`.  

4. Now you can run it like *any php application* using nginx, apache, or even with PHP development server<sup>1</sup>.
```shell
# Assuming you are in Durbin base directory
cd public
php -S localhost:8081
```
Durbin is built using [Framework-X](https://framework-x.org/). See [Production deployment](https://framework-x.org/docs/best-practices/deployment/) options from their docs. 

### Running in a subdirectory

If your domain is _example.com_ and you want to keep this tool at _example.com/durbin_,
just keep this **durbin** directory in your _example.com_'s web root directory.  
Also, in the `inc/config.php` file, set `base_url` to 'http://example.com/durbin/'.

## Security

Secured with Basic Auth by default.
Check the `auth` values in `inc/config.php` to get/change the username and password.

If you want to disable it, just remove `BasicAuthMiddleware` from `FrameworkX\App` instantiation in `index.php`.
Disabling auth is _NOT RECOMMENDED!_ unless you are using some other kind of security.

## Roadmap

- [x] Show running containers
- [x] Show all containers (including stopped)
- [x] Show container status (CPU/Memory uses etc.)
- [x] Secure with Basic Auth
- [x] Ability to install in subdirectory
- [x] Show latest logs of a container
- [ ] Show streaming logs of a container
- [x] Start a stopped container
- [x] Stop a running container

## Notes/Cautions
1. PHP development server `php -S` is not suitable for showing streaming logs.

### Additional things to remember
- Docker should be in running state in your server.
- This app itself should not be running inside Docker container.

## Credits

- Built using [Framework-X](https://framework-x.org/), a modern PHP microframework on top of [ReactPHP](https://reactphp.org/).
- The background illustration was taken from [here](https://www.behance.net/gallery/41119883/Docker-Whale/modules/248250921).
- The logo was a [free icon](https://www.iconfinder.com/icons/7204507/binoculars_find_search_zoom_magnifier_army_military_icon) on iconfinder.com.
