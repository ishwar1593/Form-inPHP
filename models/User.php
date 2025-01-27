<?php
include_once __DIR__ . '/../db/Database.php';

class User
{
    public $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->createUsersTable();
    }

    // Create new user
    public function createUser($name, $email, $gender, $hobbies, $password, $fav_number, $fav_color, $profile_pic, $dob, $dtl, $mfile, $month, $range, $search, $pno, $time, $website, $week, $country, $editorContent)
    {
        $stmt = $this->db->connect()->prepare("INSERT INTO users (name, email, gender, hobbies, password, fav_number, fav_color, profile_pic, dob,dtl,mfile,month,range,search,pno,time,website,week,country,editorContent) 
        VALUES (:name, :email, :gender, :hobbies, :password, :fav_number, :fav_color, :profile_pic, :dob,:dtl,:mfile,:month,:range,:search,:pno,:time,:website,:week,:country,:editorContent)
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
        $stmt->bindParam(':profile_pic', $profile_pic);
        $stmt->bindParam(':dob', $dob);
        // new fields
        $stmt->bindParam(':dtl', $dtl);
        $stmt->bindParam(':mfile', $mfile);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':range', $castedRange);
        $stmt->bindParam(':search', $search);
        $stmt->bindParam(':pno', $pno);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':website', $castedWebsite);
        $stmt->bindParam(':week', $week);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':editorContent', $editorContent);


        // Execute the query and check for success
        if ($stmt->execute()) {
            // Fetch the inserted ID using RETURNING
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['id'] : false;
        } else {
            return false;
        }
    }

    // Show user
    public function showUser($userId)
    {
        try {
            $stmt = $this->db->connect()->prepare("SELECT * FROM users WHERE id = :id AND isdelete = false");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error to fetch user data : " . $e->getMessage());
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
            $stmt = $this->db->connect()->prepare("UPDATE users SET name = :name, email = :email, gender = :gender, hobbies = :hobbies, fav_number = :fav_number, fav_color = :fav_color, profile_pic = :profile_pic, dob = :dob,dtl=:dtl,mfile=:mfile,month=:month,range=:range,search=:search,pno=:pno,time=:time,website=:website,week=:week,country=:country, editorcontent = :editorcontent, updatedat = CURRENT_TIMESTAMP WHERE id = :id AND isdelete = false");

            // Bind parameters
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $updatedData['name']);
            $stmt->bindParam(':email', $updatedData['email']);
            $stmt->bindParam(':gender', $updatedData['gender']);
            $stmt->bindParam(':hobbies', $updatedData['hobbies']);
            $stmt->bindParam(':fav_number', $updatedData['fav_number']);
            $stmt->bindParam(':fav_color', $updatedData['fav_color']);
            $stmt->bindParam(':profile_pic', $updatedData['profile_pic']);
            $stmt->bindParam(':dob', $updatedData['dob']);
            // new fields
            $stmt->bindParam(':dtl', $updatedData['dtl']);
            $stmt->bindParam(':mfile', $updatedData['mfile']);
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
                // Return the id after successful update
                return $id;
            } else {
                $error = $stmt->errorInfo();
                error_log("Error updating user: " . print_r($error, true));
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
}
