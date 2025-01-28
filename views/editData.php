<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Assuming you have a database connection file
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../controllers/userControllers.php';
// Navbar
include "./navbar.php";

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $userController->handleEditFormSubmit($userId, $_POST, $_FILES);
}
$errors = $userController->errors;
$formData = $userController->formData;

$user = new User();

// Fetch user data based on user ID
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $userData = $user->showUser($userId);

    if ($userData) {
        // set data in user field}
        $userData['hobbies'] = explode(',', $userData['hobbies']);

        // echo '<pre>';
        // print_r($userData);
        // echo '</pre>';
        // Decode image_paths and image_types as arrays (remove the curly braces first)
        $imageTypes = explode(',', trim($userData['image_types'], '{}')); // Convert to array
        $imagePaths = explode(',', trim($userData['image_paths'], '{}')); // Convert to array
        // Extract paths where the type is 'm_file'
        $mFilePaths = [];
        $profilePath = [];

        foreach ($imageTypes as $index => $type) {
            if ($type == 'm_file') {
                $mFilePaths[] = $imagePaths[$index];
            } else {
                $profilePath[] = $imagePaths[$index];
            }
        }
?>


        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User Data</title>
            <link rel="stylesheet" href="/task1/assets/style.css">
            <style>
                #htmlEditor {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }

                .toolbar {
                    display: flex;
                    gap: 10px;
                    margin-left: 20px;
                    margin-bottom: 10px;
                }

                .toolbar button {
                    padding: 5px 10px;
                    cursor: pointer;
                    border: 1px solid #ddd;
                    background: #f9f9f9;
                    font-size: 14px;
                }

                .toolbar button:hover {
                    background: #eaeaea;
                }

                #editor {
                    border: 1px solid #ddd;
                    padding: 10px;
                    height: 200px;
                    overflow-y: auto;
                }

                #editor[contenteditable="true"] {
                    outline: none;
                }
            </style>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.2.3/purify.min.js" integrity="sha512-Ll+TuDvrWDNNRnFFIM8dOiw7Go7dsHyxRp4RutiIFW/wm3DgDmCnRZow6AqbXnCbpWu93yM1O34q+4ggzGeXVA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        </head>

        <body>
            <h2>Edit User Data</h2>
            <form action="" method="POST" enctype="multipart/form-data" id="userEditForm">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
                <label for="name">Name :</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($userData['name'] ?? "") ?>">
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($userData['email'] ?? "") ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>


                <label>Gender :</label>
                <input type="radio" name="gender" id="male" value="male" <?= (isset($userData['gender']) && $userData['gender'] == "male") ? "checked" : "" ?>><label for="male">Male</label>
                <input type="radio" name="gender" id="female" value="female" <?= (isset($userData['gender']) && $userData['gender'] == "female") ? "checked" : "" ?>><label for="female">Female</label>
                <input type="radio" name="gender" id="other" value="other" <?= (isset($userData['gender']) && $userData['gender'] == "other") ? "checked" : "" ?>><label for="other">Other</label>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="hobbies">Hobbies :</label>
                <input type="checkbox" name="hobbies[]" id="Reading" value="Reading" <?= (isset($userData['hobbies']) && in_array('Reading', $userData['hobbies'])) ? "checked" : "" ?>><label for="Reading">Reading</label>
                <input type="checkbox" name="hobbies[]" id="Traveling" value="Traveling" <?= (isset($userData['hobbies']) && in_array('Traveling', $userData['hobbies'])) ? "checked" : "" ?>><label for="Traveling">Traveling</label>
                <input type="checkbox" name="hobbies[]" id="Cooking" value="Cooking" <?= (isset($userData['hobbies']) && in_array('Cooking', $userData['hobbies'])) ? "checked" : "" ?>><label for="Cooking">Cooking</label>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="password">Password :</label>
                <input type="password" name="password" id="password" value="<?= htmlspecialchars($userData['password'] ?? "") ?>" disabled>
                <br><br>

                <label for="fav_number">Favorite Number :</label>
                <input type="number" name="fav_number" id="fav_number" value="<?= htmlspecialchars($userData['fav_number'] ?? 0) ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="fav_color">Favorite Color :</label>
                <input type="color" name="fav_color" id="fav_color" value="<?= htmlspecialchars($userData['fav_color'] ?? "#000000") ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>
                <p style="font-weight: bold;">Profile Picture:</p>
                <div id="profile-picture-container">
                    <?php if (empty($profilePath)) { ?>
                        <input type="file" name="profile_pic" id="profile_pic">
                        <span class="error">* <?php echo $errors["profile_picErr"] ?? ''; ?></span>
                        <br><br>
                    <?php } else { ?>
                        <?php
                        // Loop over the image paths and check if any of them is a profile picture
                        foreach ($profilePath as $profile) { ?>
                            <?php if ($imageTypes[$index] == 'profile_pic') { ?>
                                <div id="<?= htmlspecialchars($profile) ?>">
                                    <img src="../uploads/<?= htmlspecialchars($profile) ?>" alt="Profile Picture" style="max-width: 200px; display: block; margin-bottom: 20px;">

                                    <button data-image-path="<?= htmlspecialchars($profile) ?>" type="submit" style="padding: 8px 12px; background-color: red; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        Remove
                                    </button>
                                </div>
                            <?php

                            } else { ?>
                                <input type="file" name="profile_pic" id="profile_pic">
                                <span class="error">* <?php echo $errors["profile_picErr"] ?? ''; ?></span>;
                                <br><br>
                    <?php }
                        }
                    }
                    ?>
                </div>

                <label for="dob">Date of Birth :</label>
                <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($userData['dob']) ?>" required>
                <span class="error">* <?php echo $errors["dobErr"] ?? ''; ?></span>
                <br><br>

                <!-- extra fields -->
                <label for="dtl">Date time-local :</label>
                <input type="datetime-local" name="dtl" id="dtl" value="<?= $userData['dtl'] ?>">
                <span class="error">* <?php echo $errors["dtlErr"] ?? ''; ?></span>
                <br><br>

                <p style="font-weight: bold;">Multiple Files:</p>
                <div id="multiple-files-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php foreach ($mFilePaths as $index => $mfile): ?>
                        <div id="multiple-file-<?= htmlspecialchars($mfile) ?>" style="text-align: center;">
                            <img class="remove-image-btn" src="../uploads/<?= htmlspecialchars($mfile) ?>" alt="<?= htmlspecialchars($mfile) ?>" style="max-width: 150px; max-height: 150px; object-fit: cover; border: 1px solid #ccc; margin-bottom: 10px;">
                            <button data-image-path="<?= htmlspecialchars($mfile) ?>" type="button" class="remove-multiple-file" style="padding: 8px 12px; background-color: red; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                Remove
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>


                <label for="month">Month :</label>
                <input type="month" name="month" id="month" value="<?= $userData['month'] ?>">
                <span class=" error">* <?php echo $errors["monthErr"] ?? ''; ?></span>
                <br><br>

                <label for="vol">Volume (0 to 100):</label>
                <input type="range" name="range" id="vol" min="0" max="100" value="<?= $userData['range'] ?>">
                <br><br>

                <label for="search">Search :</label>
                <input type="search" name="search" id="search" placeholder="Search something" value="<?= htmlspecialchars($userData['search']) ?>">
                <span class="error">* <?php echo $errors["searchErr"] ?? ''; ?></span>
                <br><br>

                <label for="pno">Phone Number :</label>
                <input type="tel" name="pno" id="pno" value="<?= htmlspecialchars($userData['pno']) ?>">
                <span class="error">* <?php echo $errors["pnoErr"] ?? ''; ?></span>
                <br><br>

                <label for="time">Time :</label>
                <input type="time" name="time" id="time" value="<?= $userData['time'] ?>">
                <span class="error">* <?php echo $errors["timeErr"] ?? ''; ?></span>
                <br><br>

                <label for="website">Your website url :</label>
                <input type="url" name="website" id="website" value="<?= htmlspecialchars($userData['website']) ?>">
                <span class="error">* <?php echo $errors["websiteErr"] ?? ''; ?></span>
                <br><br>

                <label for="">Week :</label>
                <input type="week" name="week" id="week" value="<?= htmlspecialchars($userData['week']) ?>">
                <span class="error">* <?php echo $errors["weekErr"] ?? ''; ?></span>
                <br><br>

                <label for="country">Country :</label>
                <select name="country" id="country">
                    <option value="India" <?= isset($userData['country']) && $userData['country'] === 'India' ? 'selected' : ''; ?>>India</option>
                    <option value="USA" <?= isset($userData['country']) && $userData['country'] === 'USA' ? 'selected' : ''; ?>>USA</option>
                    <option value="Dubai" <?= isset($userData['country']) && $userData['country'] === 'Dubai' ? 'selected' : ''; ?>>Dubai</option>
                </select>
                <br><br>

                <!-- Html editor -->
                <!-- Toolbar for Formatting Options -->
                <div class="toolbar">
                    <button type="button" onclick="execCmd('bold')"><b>B</b></button>
                    <button type="button" onclick="execCmd('italic')"><i>I</i></button>
                    <button type="button" onclick="execCmd('underline')"><u>U</u></button>
                    <button type="button" onclick="execCmd('strikeThrough')">Strike</button>
                    <button type="button" onclick="execCmd('insertOrderedList')">OL</button>
                    <button type="button" onclick="execCmd('insertUnorderedList')">UL</button>
                    <button type="button" onclick="execCmd('justifyLeft')">Left</button>
                    <button type="button" onclick="execCmd('justifyCenter')">Center</button>
                    <button type="button" onclick="execCmd('justifyRight')">Right</button>
                    <button type="button" onclick="execCmd('removeFormat')">Clear</button>
                </div>


                <!-- Editable Content Area -->
                <!-- Hidden input to store HTML editor content -->
                <input type="hidden" name="editorContent" id="editorContent">
                <div id="htmlEditor">
                    <div id="editor" contenteditable="true" onfocus="if(this.innerText === 'Start typing here...') this.innerText = '';" onblur="if(this.innerText === '') this.innerText = 'Start typing here...';">
                        <?= htmlspecialchars_decode($userData['editorcontent']) ?>
                    </div>

                </div>


                <input type="submit" value="Update">
            </form>


            <script>
                // Function to execute commands (e.g., bold, italic)
                function execCmd(command, value = null) {
                    document.execCommand(command, false, value);
                }

                // Capture the editor content before form submission
                document.getElementById("userEditForm").addEventListener("submit", function(event) {
                    var editorContent = DOMPurify.sanitize(document.getElementById("editor").innerHTML);
                    // alert(editorContent);
                    document.getElementById("editorContent").value = editorContent;
                });

                // Ajax for remov image
                document.addEventListener('DOMContentLoaded', function() {
                    // Event listener for remove button
                    const removeButtons = document.querySelectorAll('button[data-image-path]');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();

                            const imagePath = button.getAttribute('data-image-path');
                            var container = document.getElementById('profile-picture-container');

                            // Send AJAX request to remove the image
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', '/task1/controllers/delete_image.php', true); // Update the PHP file for image removal
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    // On success, remove the image element from the DOM
                                    const imageDiv = document.getElementById(imagePath);
                                    if (imageDiv) {
                                        imageDiv.remove();
                                    }

                                    // Check if there are no more profile pictures and show the file input
                                    if (container.children.length === 0) {
                                        container.innerHTML = `
                                                    <input type="file" name="profile_pic" id="profile_pic">
                                                    <span class="error">* <?php echo $errors["profile_picErr"] ?? ''; ?></span>
                                                    <br><br>
                                                `;
                                    }

                                } else {
                                    alert('Error removing the image');
                                }
                            };
                            xhr.send('imagePath=' + encodeURIComponent(imagePath));
                        });
                    });
                });

                // mlti file

                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.remove-multiple-file').forEach(function(button) {
                        button.addEventListener('click', function() {
                            var imagePath = this.getAttribute('data-image-path');
                            var container = document.getElementById('multiple-files-container');

                            // Perform AJAX request to remove the image
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '/task1/controllers/delete_image.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // Remove the image element from the DOM
                                    var fileDiv = document.getElementById('multiple-file-' + imagePath);
                                    if (fileDiv) {
                                        fileDiv.remove();
                                    }
                                } else if (xhr.readyState === 4) {
                                    alert('Error removing the image');
                                }
                            };
                            xhr.send('image_path=' + encodeURIComponent(imagePath));
                        });
                    });
                });
            </script>
        </body>

        </html>
<?php }
} ?>