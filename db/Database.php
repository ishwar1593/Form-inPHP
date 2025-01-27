<?php

class Database
{
    private $pdo;
    // Database connection parameters
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "task1";
    private $user = "postgres";
    private $password = "root";

    public function __construct()
    {
        $this->pdo = $this->connect();
    }

    // DB connection
    public function connect()
    {
        try {
            $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname";

            $this->pdo = new PDO($dsn, $this->user, $this->password);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "DB conn";
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Create user table if not exists
    public function createUsersTable()
    {
        $tableQuery = "CREATE TABLE IF NOT EXISTS users (
                            id SERIAL PRIMARY KEY,
                            name VARCHAR(100) NOT NULL,
                            email VARCHAR(100) UNIQUE NOT NULL,
                            gender VARCHAR(10),
                            hobbies TEXT,
                            password VARCHAR(255) NOT NULL,
                            fav_number INT,
                            fav_color VARCHAR(20),
                            dob DATE,
                            dtl TIMESTAMP,
                            month VARCHAR(255),
                            range INT,
                            search VARCHAR(255),
                            pno VARCHAR(10),
                            time TIME,
                            website VARCHAR(255),
                            week VARCHAR(255),
                            country VARCHAR(50),
                            editorcontent TEXT,
                            isactive BOOLEAN DEFAULT TRUE,
                            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- to track record creation time
                            updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- to track last update time
                            deletedAt TIMESTAMP,                            -- to store deletion time (if soft deleted)
                            isDelete BOOLEAN DEFAULT FALSE
                            );";

        return $this->pdo->exec($tableQuery);
    }

    public function createImgsTable()
    {
        $tableQuery = "CREATE TABLE IF NOT EXISTS images (
                            id SERIAL PRIMARY KEY,                      -- Primary key for the images table
                            img_type VARCHAR(50) NOT NULL,             -- To store the type of image (e.g., profile_pic, m_file)
                            image_path VARCHAR(255) NOT NULL,          -- To store the image path
                            deleted_at TIMESTAMP,                      -- To store deletion time for soft deletes
                            is_deleted_img BOOLEAN DEFAULT FALSE,           -- To mark whether the record is deleted (soft delete)
                            user_id INT NOT NULL,                      -- Foreign key referencing the users table
                            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE -- Ensures referential integrity
                        );";

        return $this->pdo->exec($tableQuery);
    }
}
