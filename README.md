# REST API in PHP - João De Macedo
#### DTT BACKEND ASSESSMENT

### What is this?

This is a `REST API` made in `PHP` that stores and retrieves information about catering facilities. `MySQL` is used for storage of the data and both the request body and response data are in `JSON format`.
This API has `CRUD` implemented and other functionalities such as `search criteria` - _allowing partial matches_ -, `cursor pagination`, `JWT authentication` and `integration tests`.

### Structure `Assessment_Setup/App/` 

- `Controllers`: Handle incoming requests and sends the response data back to the client.
- `Entities`: Objects that correspond to the tables in the database.
- `Exceptions`: Exceptions created to handle errors recieved by database.
- `Repositories`: Like a wrapper for the database. Read and write data to the database.
- `Services`: Layer capable of managing data from Controllers and comunicating to Repositories.
- `Utils`: This folder contains a class named `PaginationParser`to get Query Parameters in order to allow searching with simultaneous filters and pagination. 
- `Tests`: Integration Tests to validate the API codebase.

#### Other Files

- `HourLog.pdf` : File containg how much time I needed to build this API and more details about the development.
- `Database.sql` : My database dump created in phpMyAdmin
- `PostmanCollection/`: API collection and environment.

### User Storys

- Database created with 5 tables:

![Alt text](/schemaDatabase.png?raw=true "Optional Title")

  - `facility`: store each Facility.
  - `location`: store each Location of each Facility.
  - `employee`: store each Employee of Facilities 
  - `tag`: store all the Tags
  - `facility_tag` (M:N table): table to store the information about all Tags of each Facility.
- `CRUD` implemented for both _Facilities_ and _Employees_.
- Ability to search for Facilities, Tags and Locations in a _single API call_ by their names allowing partial matches.

### Optional Extras

- `Postman collection` included.
- `Cursor Pagination` implemented. 
- `Table employees` added.
- `Json Web Token Authentication` added with _Hashed Password_ saved in database.
- `Integration Tests` using _PHPUnit_.

### Comments

- When deleting a facility, database has `ON DELETE CASCADE` on _facility_id_ column in _facility_tag_ and _employees_ tables. Thus, it is not needed to perform a transaction to delete all relationships with this facility.
- To have a database more secure, all passwords, before being saved are hashed using a strong one-way hashing algorithm called _ARGON2I_.
- _Pagination_ is implemented on Facilities only.
- Passwords of created _Employees_ are equals to their _username_.
- The `App secret key` should be placed as an environment variable due to security issues.

### Online Resources

- `JWT implementation`: https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/ 
- `Cursor Pagination`: https://slack.engineering/evolving-api-pagination-at-slack/

### Future Work

- Implement User roles to control the access to certain requests.
- Have the ability to change and restore passwords.

### Informations

#### Local Server Setup

1. Install a web server: [`XAMPP`](https://www.apachefriends.org/index.html), [`WAMP`](https://www.wampserver.com/en/) or [`MAMP`](https://www.mamp.info/en/mac/).
2. Install [`Composer`](https://getcomposer.org/).
3. Put the project folder `Assessment_setup` in the `htdocs` folder of your web server.
4. Run the terminal command `composer install` in the project folder.

#### Project Setup

5. Import the database into `phpMyAdmin`.
6. Import the API collection into `Postman`.

#### How To Test

7. Run the terminal command `'./vendor/bin/phpunit ./App/Tests/FacilityTest.php'` in the project folder.

Now, you are ready to go! Use Postman collection to make the requests to the API.

<h3>Where to find me</h3>
<p><a href="https://github.com/joaogdemacedo" target="_blank"><img alt="Github" src="https://img.shields.io/badge/GitHub-%2312100E.svg?&style=for-the-badge&logo=Github&logoColor=white" /></a> <a href="https://twitter.com/joaodemacedo134" target="_blank"><img alt="Twitter" src="https://img.shields.io/badge/twitter-%231DA1F2.svg?&style=for-the-badge&logo=twitter&logoColor=white" /></a> <a href="https://www.linkedin.com/in/joaodemacedo134" target="_blank"><img alt="LinkedIn" src="https://img.shields.io/badge/linkedin-%230077B5.svg?&style=for-the-badge&logo=linkedin&logoColor=white" /></a></p>
