# Symfony e-learning project documentation: Knowledge learning

## 1. Introduction

This project is an e-learning application developed in
Symfony. It allows you to purchase individual training and lessons when a user is connected. The training or lessons purchased can be done and validated to obtain a certification at the end.

In administrator mode, it is possible to create, modify or delete users, content and view purchases made by customers.

## 2. Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
5. [Project Structure](#project-structure)
6. [Main Features](#main-features)

## 3. Prerequisites

Before you start, make sure that the following tools are
installed:
- PHP 8.x (8.2.12 used for the project)
- Composer
- Symfony CLI
- MySQL or MariaDB
- A Stripe account

## 4. Installation

## 4.1. Cloning the project

git clone https://github.com/Drocinera/Knowledge_Learning.git
cd my-project

## 4.2. Installing dependencies

composer install

## 4.3. Configuring the environment

Create the .env file and configure the following variables:

- APP_ENV 
- APP_SECRET 
- DATABASE_URL 
- MESSENGER_TRANSPORT_DSN 
- STRIPE_PUBLIC_KEY 
- STRIPE_SECRET_KEY 
- STRIPE_WEBHOOK_SECRET 
- MAILER_DSN 

Create the .env.test and configure the following variables :

- KERNEL_CLASS 
- APP_SECRET 
- APP_ENV 
- DATABASE_URL 
- SYMFONY_DEPRECATIONS_HELPER 
- PANTHER_APP_ENV 
- PANTHER_ERROR_SCREENSHOT_DIR 
- MESSENGER_TRANSPORT_DSN 
- MAILER_DSN 
- STRIPE_PUBLIC_KEY 
- STRIPE_SECRET_KEY 
- STRIPE_WEBHOOK_SECRET 

## 4.4. Creating the database

- symfony console doctrine:database:create
- symfony console doctrine:migrations:migrate
- Symfony console doctrine:fixtures:load

## 4.5. Starting the server

symfony serve:start

## 5. Configuration
## 5.1. .env file

Environment variables to configure:

DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
STRIPE_SECRET_KEY="your_stripe_secret_key"
STRIPE_PUBLIC_KEY="your_stripe_public_key"
STRIPE_WEBHOOK_SECRET= "your_Webhook_key"
MESSENGER_TRANSPORT_DSN = "doctrine://default?auto_setup=0"
MAILER_DSN =
- **Gmail** : `smtp://your-address@gmail.com:your-password@smtp.gmail.com:587`
- **Mailgun** : `smtp://user:password@smtp.mailgun.org:587`
- **SendGrid** : `smtp://user:password@smtp.sendgrid.net:587`

> - If you are using Gmail, you will need to set an app password or allow access to less secure apps.

> - Replace username, password and database_name with your own information.

- For .env.test : 
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name

## 6. Usage

• Access the interface: http://localhost:8000.
• To log in, you can:
- click on "register" and enter a valid email address and password (you must be able to receive a confirmation email to validate your account. You can override this rule by going directly through the database and modifying "is active")
- Or you can directly click on "log in" and enter one of the two accounts used to load fixed data.
fakeadmin@fakemail.com/adminpassword
fakeuser@fakemail.com/userpassword

## 6.1 Using a user account (ROLE_USER )

Training page: Access to training available on the site

- If you are logged in without having validated your email, you will not be able to purchase training or lessons
- If you have validated your email, you will be able to purchase, view and validate training or a lesson.

For validating an email, after register an account with a true email, use the following command line for sending the email verifier : 
- php bin/console messenger:consume --all

Certifications page: Summarizes all certifications obtained with their date of obtaining.

- To make a payment, use the test bank cards provided by Stripe (https://docs.stripe.com/testing?locale=fr-FR)

- Before making any purchases, you will need to enable Stripe Webhooks to retrieve metadata:

stripe listen --forward-to localhost:8000/stripe/webhook

## 6.2 Using an administrator account (ROLE_ADMIN)

The Admin page located in the drop-down menu allows:

- access the user management page. You can modify, add or delete a user from the database.
- Access the content management page. You can modify, add or delete a theme, a course or a lesson from the database
- Access the purchases page. You can view all purchases made by customers.

## 7. Project structure

- src/Controller/: Action management.
- src/Entity/: Management of tables in the database
- src/Service/: Services like StripeService.
- templates/: HTML templates with Twig.

## 9. Main features

- E-learning training
- Purchase, visualization and validation of training
- Secure payment with Stripe
- Obtaining certification
