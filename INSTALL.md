## Installation

Chatter is a fairly straightforward Laravel 8 (https://laravel.com/docs/8.x) app, meaning that it's PHP on the server side, and uses NPM/webpack to build client-side assets (CSS/JS).  If you're familiar with Laravel, you won't find too many surprises here.  If not, we'll try to lay out a few basic installation scenarios here, but you can always refer to the Laravel docs for more details. 

### Docker installation
* Clone this Github repo and build the included `Dockerfile` yourself, or use one of the [prebuilt Docker releases](https://github.com/longhornopen/chatter/pkgs/container/chatter)
* Supply your environment variables. `.env.example` describes the environment variables that you'll need to give.
* `php artisan migrate` to create your database schema.

### Manual installation
* Clone this Github repo.
* Install PHP (8.0 or higher) and Composer (https://getcomposer.org).
* Install NPM.
* `cd web`, `composer install`, `npm install`, `npm run production`
* Supply your environment variables by adding a `.env` file. `.env.example` describes the environment variables that you'll need to give.
* `php artisan migrate` to create your database schema.

### Local installation (for developers)
* See https://laravel.com/docs/8.x/installation

### Updates
When updating to a new version, run `php artisan migrate` again to apply new database schema changes.

## Features
Chatter runs a fairly basic web server with a no-frills user experience by default.  Additional Chatter features require some setup.

## Configuring

The above will give you a server that can be used in several ways.

### As a web server

You'll need at least one web server to run Chatter.  The Docker image runs one by default, or you can start one manually with Apache/PHP or `php artisan serve`.

### As a scheduled-task runner

You'll also need one scheduled-task runner, in order to send emails.  Two ways to get one up and running are:
* The Docker image, using the command `php artisan schedule:work`, or
* A crontab similar to the one at https://laravel.com/docs/8.x/scheduling#running-the-scheduler

### LMS Setup
Full docs about how to generate the info you need to install this tool in an LMS are at https://github.com/longhornopen/laravel-celtic-lti/wiki/LTI-Key-Generation

LTI 1.3 installations are preferred over 1.2 or earlier.  Most LTI 1.3 installations will probably want a 'Course Navigation' placement (as opposed to an assignment-level placement).

Give the LTI 1.3 key appropriate permissions:
* If the LMS requires it, set the Privacy Level to 'Public'.  (In Canvas, Addition Settings > Privacy Level.)
* Grant access to the LTI Service corresponding to "https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly".  (In Canvas, this'll be the 'Can retrieve user data associated with the context' setting, under 'LTI Advantage Services').