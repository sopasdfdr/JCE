# Laravel & Vue.js Project  

This project was developed as part of a challenge and consists of a **Laravel backend**, **Vue.js frontend**, and **MySQL database**. The project runs on **Laragon** for local hosting but can be run on any compatible environment.

## **Prerequisites**  
Before running the project, ensure you have installed:  

- PHP 8.x ([Download PHP](https://www.php.net/downloads.php))  
- Composer ([Download Composer](https://getcomposer.org/))  
- Node.js & npm ([Download Node.js](https://nodejs.org/))  
- Laravel (Installed via Composer)  
- Vue.js (Handled via npm)  

If using **Laragon**, just start Apache & MySQL via its interface.

composer install
npm install

Then, update the database settings in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=

I created 3 tables: 
CREATE TABLE `formulacoes` (
	`fmc_id` INT NOT NULL AUTO_INCREMENT,
	`fml_id` INT NOT NULL,
	`prd_id` INT NOT NULL,
	`fmc_qtde` DECIMAL(10,2) NOT NULL,
	`fmc_prioridade` INT NOT NULL,
	PRIMARY KEY (`fmc_id`) USING BTREE,
	INDEX `fk_formulacoes_formulas` (`fml_id`) USING BTREE,
	INDEX `fk_formulacoes_produtos` (`prd_id`) USING BTREE,
	CONSTRAINT `fk_formulacoes_formulas` FOREIGN KEY (`fml_id`) REFERENCES `formulas` (`fml_id`) ON UPDATE NO ACTION ON DELETE CASCADE,
	CONSTRAINT `fk_formulacoes_produtos` FOREIGN KEY (`prd_id`) REFERENCES `produtos` (`prd_id`) ON UPDATE NO ACTION ON DELETE CASCADE
)

CREATE TABLE `formulas` (
	`fml_id` INT NOT NULL AUTO_INCREMENT,
	`fml_nome` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`fml_codigo` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`fml_qtde` DECIMAL(10,2) NOT NULL,
	`fml_nrprodutos` INT NOT NULL,
	`fml_datacriacao` BIGINT NOT NULL,
	`fml_dataalteracao` BIGINT NOT NULL,
	PRIMARY KEY (`fml_id`) USING BTREE
)

CREATE TABLE `produtos` (
	`prd_id` INT NOT NULL AUTO_INCREMENT,
	`prd_nome` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`prd_codigo` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`prd_tipo` TINYINT NULL DEFAULT NULL,
	PRIMARY KEY (`prd_id`) USING BTREE,
	CONSTRAINT `produtos_chk_1` CHECK ((`prd_tipo` in (0,1,2)))
)

Start the backend with:

php artisan serve
This will serve the API at:
http://127.0.0.1:8000

Compile and start the frontend:
npm run dev