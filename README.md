Messenger Demo: Registration with invite code.
==============================================

This demo application showcases Symfony's messenger component in a fictitious,
but realistic usage scenario.

Usage
-----

Users can register on http://localhost:8000/register with an email-address,
password and an invite code, they received from a previously registered user.

On successful registration a new account will be created, the invite code used
for the registration will be marked as redeemed and a set of new invite codes
will be generated.

Afterwards the user can login on http://localhost:8000/login and then see their
invite codes on a dashboard, including the invite code status (open/redeemed).

The user who gave out the invite will be informed via email that their friend
has registered.

In other words the workflow for registration consists of the following steps:

    1. Check invite code, if it is still redeemable
    2. Create new user
    3. Redeem invite code used for registration
    4. Create invite codes for new user
    5. Send notification to invite code owner, that someone registered with one
       of their codes

Requirements
------------

- PHP 7.1.3 or higher
- sqlite3 and PHP extension: pdo_sqlite
- composer

Installation
------------

1. Clone the repository

    ```
    git clone git@github.com:dbrumann/messenger-invite-registration.git
    ```

2. Install dependencies (Composer must be installed)

    ```
    composer install
    ```

3. Setup database and load fixtures (SQLite must be installed)

    ```
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:fixtures:load
    ```

4. Start web server

    ```
    php -S localhost:8000 -t public/
    ```
    
5. Open browser

    http://localhost:8000
