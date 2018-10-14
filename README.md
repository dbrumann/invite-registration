Messenger Demo: Registration with invite code.
==============================================

This demo application showcases Symfony's messenger component in a fictitious,
but realistic usage scenario.

Please refer to the open [Pull Requests](https://github.com/dbrumann/invite-registration/pulls) to see the actual messenger implementation.

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

- Docker

Installation
------------

1. Clone the repository

    ```
    git clone git@github.com:dbrumann/invite-registration.git
    ```

2. Set up development environment with Docker

    ```
    docker-compose up
    ```

3. Install dependencies

    ```
    docker-compose exec app bash
    composer install
    ```

4. Setup database and load fixtures

    ```
    docker-compose exec app bash
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:fixtures:load
    ```

6. Open browser

    http://localhost:8000
