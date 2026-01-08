# Welcome to the "Website-But-Better" vZJX ARTCC repository

Please don't forget to configure a local .env file. Also, please note to use daisyUI for UI consistency.

## How to develop
- run composer install
- run npm install
- run composer run dev

## Style Guide
- All views should be named appropriate to their route (ie. index.blade.php)
- All models should be singular and CapitalCase (eg: TrainingSession)
- All database tables should be pluralized and snake_case (eg: training_sessions)
- Classes (basically anything in the App directory) should be CapitalCase (eg HomeController)

- migrate and seed the database

In the local environment, ensure that your URL is http://localhost:8000/, not http://127.0.0.1:8000/. This ensures that you are able to log in with OAUTH. 
