How to use:

- first create a user for your databse && set a password for it (& save them in a safe place)

- second replace all defigners in './systemir_my_db.sql' with that username

- third import the './systemir_m_db.sql' in mysql workbench for create the database

- forth rename the config file on '/includes/other/config-sample.php' to  'config.php'

- finally set these attributes on it:
    
        1- DB_USER => your database username
        2- DB_PASS => your database password
        3- EMAIL_USER_NAME => your email username
        4- EMAIL_PASSWORD => your email password
