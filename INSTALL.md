## Installation

Chatter is a fairly straightforward Laravel 8 (https://laravel.com/docs/8.x) app, meaning that it's PHP on the server side, and uses NPM/webpack to build client-side assets (CSS/JS).  If you're familiar with Laravel, you won't find too many surprises here.  If not, we'll try to lay out a few basic installation scenarios here, but you can always refer to the Laravel docs for more details. 

### Docker installation
* Clone this Github repo and build the included `Dockerfile` yourself, or use one of the [prebuilt Docker releases](https://github.com/longhornopen/chatter/pkgs/container/chatter)
* Supply your environment variables. `.env.example` describes the environment variables that you'll need to give.
* `php artisan migrate` to create your database schema.

### Manual installation
* Clone this Github repo.
* Install PHP (7.4 or higher) and Composer (https://getcomposer.org).
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

### Broadcasting updates
Chatter can broadcast data updates between users.  (For example, when a comment is added to a post, all currently logged-on users can see the comment count change immediately.)
In order to do this, you'll need to tell Chatter about the pub/sub service you want to broadcast with.  `config/broadcasting.php` lists the services available, and the environment variables for each.  Some pub/sub services are commercial and require a subscription.

### Sending activity notifications via email
Chatter can offer users the option to receive email updates listing new posts or comments.  To turn this feature on, you'll need to do three things:
* Set the environment variable 'APP_FEATURE_MAIL_ACTIVITY_DIGESTS' to 'true'
* Fill in the MAIL_* environment variables with info about your SMTP server
* Set up a schedule runner, as described under 'Configuring' below.

## Configuring

The above will give you a server that can be used in several ways.

### As a web server

You'll need at least one web server to run Chatter.  The Docker image runs one by default, or you can start one manually with Apache/PHP or `php artisan serve`.

### As a schedule runner

Several features need a schedule runner.  Two ways to get one up and running are:
* The Docker image, using the command `php artisan schedule:work`, or
* A crontab similar to the one at https://laravel.com/docs/8.x/scheduling#running-the-scheduler

### LMS Setup
Full docs about how to generate the info you need to install this tool in an LMS are at https://github.com/longhornopen/laravel-celtic-lti/wiki/LTI-Key-Generation

But for most users, something like this will suffice:

#### LTI 1.2
* `cd web`
* `php artisan lti:add_platform_1.2 my_lms_name my_consumer_key my_shared_secret`
* Install into LMS with launch URL: `{YOUR_SERVER_URL}/lti` and the consumer key/secret you created above.

#### LTI 1.3
Coming soon...