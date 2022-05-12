## Bisnow Coding Challenge

Simple laravel 9 instance that has 2 api endpoints, one to accept email address, message and attachment information, and another to output successful email sends that sent the email asynchronously. 

Clone the Repo

`git clone https://github.com/ksantoro/bisnow.git`

Install composer packages

`composer install`

Install npm packages

`npm install && npm run dev`

Run the artisan commands

`php artisan key:generate`

`php artisan migrate`

`php artisan optimize`

### API Endpoints

Endpoint (POST): `api/send_email`

Request Parameters (JSON): 

```
{
    "email_address": "email@test.com",
    "message": "here is my message",
    "attachment": "base64encoded string for image or file",
    "attachment_filename": "filename.png"
}
```

Endpoint (GET): `api/success_emails`

Response (JSON):

```
["email@test.com", "email@test2.com", "email@test3.com"]
```
