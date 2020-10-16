# STI Project 1
Authors: Bécaud Arthur & Egremy Bruno
## How to run the website
1. Clone the repository.
2. Execute the 'launch_\<OS>.<sh|bat>' script.
3. Access the following web page : http://localhost:8080/login.php.
## User manual
You can find a user manual on how to navigate the website and use all the functionalities [here](./ressources/user_manual.md).
## Credentials
There are two defaults accounts with the provided SQLITE database.

| Username | Password     | Role          |
|----------|--------------|---------------|
| patrick  | symp-pat0che | Collaborator  |
| richard  | r1.c4rd      | Administrator |
## How to set up the SQLITE database
By default, the SQLITE is already setup correctly but just in case you messed something up here is how to set it up again.

1. Run the website, follow the steps from the 'How to run the website' section.
2. Access the following web page : http://localhost:8080/phpliteadmin.php.
3. Log in with the password '9M1fXq0Tx&vlDm^d3ej%gTrM#nAMLc'.
4. Go to the 'Import' tab, browse and import './ressources/database/generate_database.sql' file.
