# Development Guide

## Branding and Templating

This repository contains the code for both the "Parcel Pro" _and_ "Shops United" plugins.
These are functionally the same, but are published with different branding.
To build the code, run:

```shell
composer build
```

A couple of files are used as templates, which are built into two versions:

- `bootstrap-template.php`: The plugin bootstrap file that will be loaded by WordPress.
- `readme.txt.hbs`: the readme for the WordPress plugin registry.

The template variables can be found in `template-vars.json`.

## Publishing

To publish a new version of the plugins, simply create a new tag from `main`.
The easiest way to so this is by creating a release on the GitHub [releases page](https://github.com/parcel-pro-nl/woocommerce-plugin/releases).

The WordPress assets are located in the `.wordpress-parcel-pro` and `.wordpress-shops-united` directories.
These are automatically published to the WordPress plugin registry from the `main` branch.

## Development Setup

A Docker Compose development setup is available, based around the [wordpress](https://hub.docker.com/_/wordpress) image.

### Starting Up

First, you need to start the Docker containers:

```shell
docker compose up -d
```

Once WordPress is running, you can view it on http://localhost/wp-admin.

To simplify the first-time setup, there is a setup script:

```shell
./setup.sh
```

This is used to initialize the WordPress configuration, and install the WooCommerce plugin.

You can now view WordPress on http://localhost, and the admin portal can be found on http://localhost/wp-admin.

Default credentials:

- Username: `admin`
- Password: `parcelpro1`

### ParcelPro Plugin

The ParcelPro plugin is mapped in the container from the project directory.
Any changes you make are automatically reflected in the container.

### PHPStorm WordPress support

To improve the development experience, you can enable WordPress integration in PHPStorm under:

```
Settings -> PHP -> Frameworks -> WordPress
```

The WordPress installation path should point to the `wp_data` directory.

### Shell and WP CLI

To get a shell in the running WordPress container, run `./cli.sh`.
Note that this container does not contain the WP CLI.

To get a shell with the WP CLI in the running WordPress container, run `./wp-cli.sh`.

For more info about the WP CLI, see: https://wp-cli.org

### Debug Log

Debug logging is enabled by the `setup.sh` script.
The log file can be found at `wp_data/wp-content/debug.log`.

### Installing a Previous WooCommerce Version

To install a previous version of the WooCommerce plugin, you can re-run the `setup.sh` script,
and pass a WooCommerce version number as the argument.
For example:

```shell
./setup.sh 7.9.0
```

For an overview of the versions, see: https://wordpress.org/plugins/woocommerce/advanced

### Shutting Down

Stop the containers, but keep their data:

```shell
docker compose stop
```

Stop and remove the containers and volumes:

```shell
docker compose down -v
sudo rm -r wp_data
```
