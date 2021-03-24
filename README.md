# Readonly update notifications
Show WordPress core and plugin updates on a custom updates page, which doesn't allow updating. Useful, for example, to prevent plugins from being installed through the admin panel, but still display update notifications.

## Installation
Install the plugin with Composer by adding the private repository and requiring the package:
```
composer config repositories.jmaekki/readonly-update-notifications git git@github.com:jmaekki/readonly-update-notifications.git
composer require jmaekki/readonly-update-notifications
```