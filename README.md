# simpleapi

## Create table "api_user" with

|Field|Type|Nul|Default|Extra|
|-----|----|---|-------|-----|
|user_id|int (unsigned)|NO|NULL|
|api_key|varchar(50)|NO|NULL|
|api_password|varchar(255)|NO|NULL|
|user_type|char(1)|NO|NULL|
|privileges|varchar(50)|YES|NULL|

## ENV file setup
add DB connection configs

## .htaccess file setup
add `RewriteBase /your_project_folder_name/` for local
add `RewriteBase /` for production