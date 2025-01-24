<?php
include_once __DIR__ . '/../models/User.php';

class UserController
{
    private $user;
    public $errors = [];
    public $formData = [];

    public function __construct()
    {
        $this->user = new User();
    }

    // test input
    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function validateInput($formData)
    {
        $this->errors = [];
        $this->formData = $formData;

        if (empty($formData["name"])) {
            $this->errors["nameErr"] = "Name is required";
        } else {
            $this->formData["name"] = $this->test_input($formData["name"]);
        }

        if (empty($formData["email"])) {
            $this->errors["emailErr"] = "Email is required";
        } else {
            $this->formData["email"] = $this->test_input($formData["email"]);
        }

        if (empty($formData["gender"])) {
            $this->errors["genderErr"] = "Gender is required";
        } else {
            $this->formData["gender"] = $this->test_input($formData["gender"]);
        }

        if (empty($formData["hobbies"])) {
            $this->errors["hobbiesErr"] = "Select your hobbies";
        } else {
            $this->formData["hobbies"] = implode(',', $formData['hobbies']);
        }

        if (empty($formData["password"])) {
            $this->errors["passwordErr"] = "Password is required";
        } else {
            $this->formData["password"] = $this->test_input($formData["password"]);
        }

        if (empty($formData["fav_number"])) {
            $this->errors["fav_numberErr"] = "Enter your lucky number.";
        } else {
            $this->formData["fav_number"] = $this->test_input($formData["fav_number"]);
        }

        if (empty($formData["fav_color"])) {
            $this->errors["fav_colorErr"] = "Enter your favorite color.";
        } else {
            $this->formData["fav_color"] = $this->test_input($formData["fav_color"]);
        }

        if (empty($formData["dob"])) {
            $this->errors["dobErr"] = "Enter your Date of Birth.";
        } else {
            $this->formData["dob"] = $this->test_input($formData["dob"]);
        }

        // Validate new fields
        if (empty($formData["dtl"])) {
            $this->errors["dtlErr"] = "Enter the date and time.";
        } else {
            $this->formData["dtl"] = $this->test_input($formData["dtl"]);
        }

        if (!empty($_FILES['mfile']['name'][0])) {
            if (is_array($_FILES['mfile']['error'])) {
                foreach ($_FILES['mfile']['error'] as $key => $error) {
                    if ($error != UPLOAD_ERR_OK) {
                        $this->errors["mfileErr"] = "Failed to upload one or more files.";
                        break;
                    }
                }
            } else {
                $this->errors["mfileErr"] = "File upload error occurred.";
            }
        } else {
            $this->errors["mfileErr"] = "Please upload one or more files.";
        }

        if (empty($formData["month"])) {
            $this->errors["monthErr"] = "Enter the month.";
        } else {
            $this->formData["month"] = $this->test_input($formData["month"]);
        }

        if (empty($formData["search"])) {
            $this->errors["searchErr"] = "Search field cannot be empty.";
        } else {
            $this->formData["search"] = $this->test_input($formData["search"]);
        }

        if (empty($formData["pno"])) {
            $this->errors["pnoErr"] = "Enter your phone number.";
        } else {
            $this->formData["pno"] = $this->test_input($formData["pno"]);
        }

        if (empty($formData["time"])) {
            $this->errors["timeErr"] = "Enter the time.";
        } else {
            $this->formData["time"] = $this->test_input($formData["time"]);
        }

        if (empty($formData["website"])) {
            $this->errors["websiteErr"] = "Enter your website URL.";
        } else {
            $this->formData["website"] = $this->test_input($formData["website"]);
        }

        if (empty($formData["week"])) {
            $this->errors["weekErr"] = "Enter the week.";
        } else {
            $this->formData["week"] = $this->test_input($formData["week"]);
        }

        if (empty($formData["country"])) {
            $this->errors["countryErr"] = "Select your country.";
        } else {
            $this->formData["country"] = $this->test_input($formData["country"]);
        }

        return count($this->errors) === 0;
    }

    // Handle create user form submission
    public function handleFormSubmit($formData)
    {
        $isValid = $this->validateInput($formData);

        if ($isValid) {
            // Handle image upload
            $profile_pic = null;
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
                $uploadDir = __DIR__ . '/../uploads/';
                $profile_pic = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
                $uploadPath = $uploadDir . $profile_pic;

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                    // File uploaded successfully
                } else {
                    $this->errors["profile_picErr"] = "Failed to upload profile picture.";
                    return;
                }
            }
            // Handle multiple file uploads for 'mfile'
            $mfile_paths = [];
            if (isset($_FILES['mfile']['name']) && is_array($_FILES['mfile']['name']) && count($_FILES['mfile']['name']) > 0) {
                $uploadDir = __DIR__ . '/../uploads/';
                foreach ($_FILES['mfile']['name'] as $key => $fileName) {
                    $fileTmpName = $_FILES['mfile']['tmp_name'][$key];
                    if (!empty($fileTmpName)) {
                        $fileUniqueName = uniqid() . '___' . basename($fileName);
                        $uploadPath = $uploadDir . $fileUniqueName;

                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $mfile_paths[] = $fileUniqueName; // Save uploaded file path
                        } else {
                            $this->errors["mfileErr"] = "Failed to upload one or more files.";
                            return;
                        }
                    } else {
                        $this->errors["mfileErr"] = "Invalid file upload.";
                        return;
                    }
                }
            } else {
                $this->errors["mfileErr"] = "No files uploaded.";
            }


            // Convert uploaded file paths array into a comma-separated string for database storage
            $mfile_paths_string = implode(',', $mfile_paths);

            // Ensure all required fields are filled
            if ($this->formData['name'] && $this->formData['email'] && $this->formData['password']) {
                $hashedPassword = password_hash($this->formData['password'], PASSWORD_DEFAULT);
                $userId = $this->user->createUser(
                    $this->formData['name'],
                    $this->formData['email'],
                    $this->formData['gender'],
                    $this->formData['hobbies'],
                    $hashedPassword,
                    $this->formData['fav_number'],
                    $this->formData['fav_color'],
                    $profile_pic,
                    $this->formData['dob'],
                    $this->formData['dtl'],      // New field
                    json_encode($mfile_paths_string),
                    $this->formData['month'],    // New field
                    $this->formData['range'],
                    $this->formData['search'],
                    $this->formData['pno'],
                    $this->formData['time'],
                    $this->formData['website'],
                    $this->formData['week'],
                    $this->formData['country'],
                );

                if ($userId) {
                    // Redirect to the showData.php page with userId
                    header("Location: views/showData.php?user_id=$userId");
                    exit;
                } else {
                    $this->errors["formErr"] = "Failed to create user.";
                }
            } else {
                $this->errors["formErr"] = "Please fill in all required fields.";
            }
        }
    }
    public function handleEditFormSubmit($userId, $formData)
    {
        if (!empty($formData) && $userId) {
            // Extract and validate form data
            $name = $formData['name'] ?? null;
            $email = $formData['email'] ?? null;
            $gender = $formData['gender'] ?? null;
            $hobbies = isset($formData['hobbies']) ? implode(',', $formData['hobbies']) : null;
            $fav_number = $formData['fav_number'] ?? null;
            $fav_color = $formData['fav_color'] ?? null;
            $dob = $formData['dob'] ?? null;
            $dtl = $formData['dtl'] ?? null;
            $month = $formData['month'] ?? null;
            $range = $formData['range'] ?? null;
            $search = $formData['search'] ?? null;
            $pno = $formData['pno'] ?? null;
            $time = $formData['time'] ?? null;
            $website = $formData['website'] ?? null;
            $week = $formData['week'] ?? null;
            $country = $formData['country'] ?? null;
    
            // Handle image upload for profile picture
            $profile_pic = null;
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
                $uploadDir = __DIR__ . '/../uploads/';
                $profile_pic = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
                $uploadPath = $uploadDir . $profile_pic;
    
                if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                    echo "Failed to upload profile picture.";
                    return;
                }
            }
    
            // Handle multiple file uploads for 'mfile'
            $mfile_paths = [];
            if (isset($_FILES['mfile']['name']) && is_array($_FILES['mfile']['name']) && count($_FILES['mfile']['name']) > 0) {
                $uploadDir = __DIR__ . '/../uploads/';
                foreach ($_FILES['mfile']['name'] as $key => $fileName) {
                    $fileTmpName = $_FILES['mfile']['tmp_name'][$key];
                    if (!empty($fileTmpName)) {
                        $fileUniqueName = uniqid() . '___' . basename($fileName);
                        $uploadPath = $uploadDir . $fileUniqueName;
    
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $mfile_paths[] = $fileUniqueName; // Save uploaded file path
                        } else {
                            echo "Failed to upload one or more files.";
                            return;
                        }
                    } else {
                        echo "Invalid file upload.";
                        return;
                    }
                }
            } else {
                echo "No files uploaded.";
                return;
            }
    
            // Convert uploaded file paths array into a comma-separated string for database storage
            $mfile_paths_string = implode(',', $mfile_paths);
    
            // Ensure all required fields are filled
            if ($name && $email) {
                $updateData = [
                    'name' => $name,
                    'email' => $email,
                    'gender' => $gender,
                    'hobbies' => $hobbies,
                    'fav_number' => $fav_number,
                    'fav_color' => $fav_color,
                    'profile_pic' => $profile_pic,
                    'dob' => $dob,
                    'dtl' => $dtl,
                    'month' => $month,
                    'range' => $range,
                    'search' => $search,
                    'pno' => $pno,
                    'time' => $time,
                    'website' => $website,
                    'week' => $week,
                    'country' => $country,
                    'mfile' => $mfile_paths_string // Directly store the string, no need for json_encode
                ];
    
                $isUpdated = $this->user->updateUser($userId, $updateData);
    
                if ($isUpdated) {
                    // Redirect to the showData.php page with userId
                    header("Location: showData.php?user_id=$userId");
                    exit;
                } else {
                    echo "Failed to update user.";
                }
            } else {
                echo "Please fill in all required fields.";
            }
        }
    }
    
}
