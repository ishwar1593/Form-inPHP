<?php
include_once __DIR__ . '/../db/Database.php';

class User
{
    public $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->createUsersTable();
        $this->db->createImgsTable();
    }

    // Create new user
    public function createUser($name, $email, $gender, $hobbies, $password, $fav_number, $fav_color, $profile_pic, $dob, $dtl, $mfile, $month, $range, $search, $pno, $time, $website, $week, $country, $editorContent)
    {
        $stmt = $this->db->connect()->prepare("INSERT INTO users (name, email, gender, hobbies, password, fav_number, fav_color, dob, dtl, month,range,search,pno,time,website,week,country,editorContent) 
        VALUES (:name, :email, :gender, :hobbies, :password, :fav_number, :fav_color, :dob,:dtl,:month,:range,:search,:pno,:time,:website,:week,:country,:editorContent)
        RETURNING id");

        $castedRange = (int) $range;
        $castedWebsite = (string) $website;

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':hobbies', $hobbies);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fav_number', $fav_number);
        $stmt->bindParam(':fav_color', $fav_color);
        $stmt->bindParam(':dob', $dob);
        // new fields
        $stmt->bindParam(':dtl', $dtl);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':range', $castedRange);
        $stmt->bindParam(':search', $search);
        $stmt->bindParam(':pno', $pno);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':website', $castedWebsite);
        $stmt->bindParam(':week', $week);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':editorContent', $editorContent);


        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $row ? $row['id'] : false;

            // If the user is successfully created, store the images in the images table
            if ($userId) {
                $images = [];

                // Add profile picture to the images array
                if ($profile_pic) {
                    $images[] = ['img_type' => 'profile_pic', 'image_path' => $profile_pic];
                }

                // Add multiple files to the images array
                if (is_array($mfile)) {
                    foreach ($mfile as $file) {
                        $images[] = ['img_type' => 'm_file', 'image_path' => $file];
                    }
                }


                // Insert all images into the images table
                foreach ($images as $image) {
                    $stmtImage = $this->db->connect()->prepare("INSERT INTO images (user_id, img_type, image_path) VALUES (:user_id, :img_type, :image_path)");
                    $stmtImage->bindParam(':user_id', $userId);
                    $stmtImage->bindParam(':img_type', $image['img_type']);
                    $stmtImage->bindParam(':image_path', $image['image_path']);

                    if (!$stmtImage->execute()) {
                        return false; // If any image insertion fails
                    }
                }
                return $userId; // Return the user ID if everything is successful
            }
        } else {
            return false;
        }
    }

    // Show user
    public function showUser($userId)
    {
        try {
            $stmt = $this->db->connect()->prepare("
         SELECT users.*, 
            array_agg(images.img_type ORDER BY images.img_type) AS image_types,
            array_agg(images.image_path ORDER BY images.img_type) AS image_paths
            FROM users
            LEFT JOIN images ON users.id = images.user_id AND images.is_deleted_img = false
            WHERE users.id = :id AND users.isdelete = false
            GROUP BY users.id;");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error fetching user data: " . $e->getMessage());
            return false;
        }
    }


    // Show all users
    public function showAllUsers()
    {
        try {
            $stmt = $this->db->connect()->prepare("SELECT * FROM users WHERE isdelete = false");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error fetching all users: " . $e->getMessage());
            return false;
        }
    }

    // Edit user
    public function updateUser($id, $updatedData)
    {
        try {
            $stmt = $this->db->connect()->prepare("UPDATE users SET name = :name, email = :email, gender = :gender, hobbies = :hobbies, fav_number = :fav_number, fav_color = :fav_color,  dob = :dob,dtl=:dtl,month=:month,range=:range,search=:search,pno=:pno,time=:time,website=:website,week=:week,country=:country, editorcontent = :editorcontent, updatedat = CURRENT_TIMESTAMP WHERE id = :id AND isdelete = false");

            // Bind parameters
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $updatedData['name']);
            $stmt->bindParam(':email', $updatedData['email']);
            $stmt->bindParam(':gender', $updatedData['gender']);
            $stmt->bindParam(':hobbies', $updatedData['hobbies']);
            $stmt->bindParam(':fav_number', $updatedData['fav_number']);
            $stmt->bindParam(':fav_color', $updatedData['fav_color']);
            // $stmt->bindParam(':profile_pic', $updatedData['profile_pic']);
            $stmt->bindParam(':dob', $updatedData['dob']);
            // new fields
            $stmt->bindParam(':dtl', $updatedData['dtl']);
            // $stmt->bindParam(':mfile', $updatedData['mfile']);
            $stmt->bindParam(':month', $updatedData['month']);
            $stmt->bindParam(':range', $updatedData['range']);
            $stmt->bindParam(':search', $updatedData['search']);
            $stmt->bindParam(':pno', $updatedData['pno']);
            $stmt->bindParam(':time', $updatedData['time']);
            $stmt->bindParam(':website', $updatedData['website']);
            $stmt->bindParam(':week', $updatedData['week']);
            $stmt->bindParam(':country', $updatedData['country']);
            $stmt->bindParam(':editorcontent', $updatedData['editorcontent']);

            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // $userId  = $id;
                $userId  = $row ? $row['id'] : false;

                // If the user is successfully created, store the images in the images table

                $images = [];

                // Handle profile picture
                if (isset($updatedData['profile_pic'])) {
                    $stmtDeleteImage = $this->db->connect()->prepare("UPDATE images SET is_deleted_img = true WHERE user_id = :user_id AND img_type = 'profile_pic'");
                    $stmtDeleteImage->bindParam(':user_id', $userId);
                    $stmtDeleteImage->execute();

                    $stmtInsertImage = $this->db->connect()->prepare("INSERT INTO images (user_id, img_type, image_path) VALUES (:user_id, 'profile_pic', :image_path)");
                    $stmtInsertImage->bindParam(':user_id', $userId);
                    $stmtInsertImage->bindParam(':image_path', $updatedData['profile_pic']);
                    $stmtInsertImage->execute();
                }

                // Handle multiple files
                if (isset($updatedData['mfile']) && is_array($updatedData['mfile'])) {
                    $stmtDeleteFiles = $this->db->connect()->prepare("UPDATE images SET is_deleted_img = true WHERE user_id = :user_id AND img_type = 'm_file'");
                    $stmtDeleteFiles->bindParam(':user_id', $userId);
                    $stmtDeleteFiles->execute();

                    foreach ($updatedData['mfile'] as $file) {
                        $stmtInsertFile = $this->db->connect()->prepare("INSERT INTO images (user_id, img_type, image_path) VALUES (:user_id, 'm_file', :image_path)");
                        $stmtInsertFile->bindParam(':user_id', $userId);
                        $stmtInsertFile->bindParam(':image_path', $file);
                        $stmtInsertFile->execute();
                    }
                }
                // return $userId; // Return the user ID if everything is successful
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }


    // delete user
    public function deleteUser($id)
    {
        try {
            $stmt = $this->db->connect()->prepare("UPDATE users 
                                                    SET isdelete = true, 
                                                        isactive = false,
                                                        deletedat = CURRENT_TIMESTAMP 
                                                    WHERE id = :id AND isdelete = false");

            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error soft deleting user: " . $e->getMessage());
            return false;
        }
    }

    // delete image
    public function deleteImage($image_path)
    {
        try {
            $stmt = $this->db->connect()->prepare("UPDATE images SET is_deleted_img = true,deleted_at = CURRENT_TIMESTAMP WHERE image_path = :image_path");
            $stmt->bindParam(':image_path', $image_path);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error soft deleting image: " . $e->getMessage());
            return false;
        }
    }

    // Search
    public function searchUsers($query)
    {
        try {
            $searchQuery = "%" . $query . "%";
            $stmt = $this->db->connect()->prepare("
            SELECT * FROM users
            WHERE (id::text LIKE :query OR name LIKE :query OR email LIKE :query)
            AND isdelete = false
        ");
            $stmt->bindParam(':query', $searchQuery);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error searching users: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $stmt = $this->db->connect()->prepare("SELECT * FROM users WHERE email = :email AND isdelete = false");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error fetching user by email: " . $e->getMessage());
            return false;
        }
    }
}
