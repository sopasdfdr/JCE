# 🚀 Laravel & Vue.js Project  

This project was developed as part of a challenge and consists of a **Laravel backend**, **Vue.js frontend**, and **MySQL database**. The project runs on **Laragon** for local hosting but can be run on any compatible environment.  

---

## 📌 Prerequisites  

Before running the project, ensure you have installed:  

- **PHP 8.x** → [Download PHP](https://www.php.net/downloads.php)  
- **Composer** → [Download Composer](https://getcomposer.org/)  
- **Node.js & npm** → [Download Node.js](https://nodejs.org/)  
- **Laravel** (Installed via Composer)  
- **Vue.js** (Handled via npm)  
- **Laragon** → [Download Laragon](https://laragon.org/download/)  

If using **Laragon**, simply start **Apache & MySQL** via its interface.

---

## ⚙️ Installation Steps  

### 1️⃣ Clone the Repository  

```sh
git clone https://github.com/your-repository.git
cd your-repository
```

### 2️⃣ Install Dependencies  

#### **Backend (Laravel)**
```sh
composer install
```

#### **Frontend (Vue.js)**
```sh
npm install
```

### 3️⃣ Configure the Environment  

Update the database settings in `.env`:  

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Database Structure  

This project includes **3 tables**:  

### **🔹 `formulacoes` Table**  
```sql
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
);
```

### **🔹 `formulas` Table**  
```sql
CREATE TABLE `formulas` (
    `fml_id` INT NOT NULL AUTO_INCREMENT,
    `fml_nome` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
    `fml_codigo` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
    `fml_qtde` DECIMAL(10,2) NOT NULL,
    `fml_nrprodutos` INT NOT NULL,
    `fml_datacriacao` BIGINT NOT NULL,
    `fml_dataalteracao` BIGINT NOT NULL,
    PRIMARY KEY (`fml_id`) USING BTREE
);
```

### **🔹 `produtos` Table**  
```sql
CREATE TABLE `produtos` (
    `prd_id` INT NOT NULL AUTO_INCREMENT,
    `prd_nome` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
    `prd_codigo` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
    `prd_tipo` TINYINT NULL DEFAULT NULL,
    PRIMARY KEY (`prd_id`) USING BTREE,
    CONSTRAINT `produtos_chk_1` CHECK ((`prd_tipo` IN (0,1,2)))
);
```

---

## 🚀 Run the Project  

### **Start the Backend (Laravel)**
```sh
php artisan serve
```
This will serve the API at:  
👉 `http://127.0.0.1:8000`

### **Start the Frontend (Vue.js)**
```sh
npm run dev
```
The frontend will be available at:  
👉 `http://localhost:5173` (or the default Vue.js port)

---

## 🧪 Testing the Application  

Once the setup is complete, you can:  

- **Access API Endpoints** at `http://127.0.0.1:8000/api/`  
- **Use Vue.js UI** at `http://localhost:5173`  

To verify data in MySQL, open **phpMyAdmin**

---

## ❓ Troubleshooting  

🔹 **Database connection error?**  
- Ensure **MySQL is running** and that your `.env` file has the correct settings.  
- Try `php artisan config:clear` and `php artisan cache:clear` to refresh configurations.  

🔹 **Frontend not working?**  
- Run `npm install` again and restart `npm run dev`.

---

## 🎉 Done!  

Now your project is **fully set up and ready for testing!** 🚀  
