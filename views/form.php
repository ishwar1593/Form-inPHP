<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
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
    <h1>User Registration</h1>
    <form action="" method="POST" enctype="multipart/form-data" id="userForm">
        <label for="name">Name :</label>
        <input type="text" name="name" id="name">
        <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
        <br><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email">
        <span class="error">* <?php echo $errors["emailErr"] ?? ''; ?></span>
        <br><br>

        <label>Gender :</label>
        <input type="radio" name="gender" id="male" value="male"><label for="male">Male</label>
        <input type="radio" name="gender" id="female" value="female"><label for="female">Female</label>
        <input type="radio" name="gender" id="other" value="other"><label for="other">Other</label>
        <span class="error">* <?php echo $errors["genderErr"] ?? ''; ?></span>
        <br><br>

        <label for="hobbies">Hobbies :</label>
        <input type="checkbox" name="hobbies[]" id="Reading" value="Reading"><label for="Reading">Reading</label>
        <input type="checkbox" name="hobbies[]" id="Traveling" value="Traveling"><label for="Traveling">Traveling</label>
        <input type="checkbox" name="hobbies[]" id="Cooking" value="Cooking"><label for="Cooking">Cooking</label>
        <span class="error">* <?php echo $errors["hobbiesErr"] ?? ''; ?></span>
        <br><br>


        <label for="password">Password :</label>
        <input type="password" name="password" id="password">
        <span class="error">* <?php echo $errors["passwordErr"] ?? '' ?></span>
        <br><br>

        <label for="fav_number">Favorite Number :</label>
        <input type="number" name="fav_number" id="fav_number">
        <span class="error">* <?php echo $errors["fav_numberErr"] ?? ''; ?></span>
        <br><br>

        <label for="fav_color">Favorite Color :</label>
        <input type="color" name="fav_color" id="fav_color">
        <span class="error">* <?php echo $errors["fav_colorErr"] ?? ''; ?></span>
        <br><br>

        <label for="profile_pic">Profile Picture :</label>
        <input type="file" name="profile_pic" id="profile_pic">
        <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
        <br><br>

        <label for="dob">Date of Birth :</label>
        <input type="date" name="dob" id="dob">
        <span class="error">* <?php echo $errors["dobErr"] ?? ''; ?></span>
        <br><br>

        <!-- extra fields -->
        <label for="dtl">Date time-local :</label>
        <input type="datetime-local" name="dtl" id="dtl">
        <span class="error">* <?php echo $errors["dtlErr"] ?? ''; ?></span>
        <br><br>

        <label for="mfile">Multiple Files :</label>
        <input type="file" multiple name="mfile[]" id="mfile">
        <span class="error">* <?php echo $errors["mfileErr"] ?? ''; ?></span>
        <br><br>

        <label for="month">Month :</label>
        <input type="month" name="month" id="month">
        <span class="error">* <?php echo $errors["monthErr"] ?? ''; ?></span>
        <br><br>

        <label for="vol">Volume (0 to 100):</label>
        <input type="range" name="range" id="vol" min="0" max="100" value="10">
        <br><br>

        <label for="search">Search :</label>
        <input type="search" name="search" id="search" placeholder="Search something">
        <span class="error">* <?php echo $errors["searchErr"] ?? ''; ?></span>
        <br><br>

        <label for="pno">Phone Number :</label>
        <input type="tel" name="pno" id="pno" value="">
        <span class="error">* <?php echo $errors["pnoErr"] ?? ''; ?></span>
        <br><br>

        <label for="time">Time :</label>
        <input type="time" name="time" id="time">
        <span class="error">* <?php echo $errors["timeErr"] ?? ''; ?></span>
        <br><br>

        <label for="website">Your website url :</label>
        <input type="url" name="website" id="website">
        <span class="error">* <?php echo $errors["websiteErr"] ?? ''; ?></span>
        <br><br>

        <label for="">Week :</label>
        <input type="week" name="week" id="week">
        <span class="error">* <?php echo $errors["weekErr"] ?? ''; ?></span>
        <br><br>

        <label for="country">Country :</label>
        <select name="country" id="country">
            <option value="India" default>India</option>
            <option value="USA">USA</option>
            <option value="Dubai">Dubai</option>
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
            <!-- <button type="button" onclick="execCmd('createLink', prompt('Enter a URL:', 'https://'))">Link</button> -->
            <button type="button" onclick="execCmd('removeFormat')">Clear</button>
        </div>


        <!-- Editable Content Area -->
        <!-- Hidden input to store HTML editor content -->
        <input type="hidden" name="editorContent" id="editorContent">
        <div id="htmlEditor">
            <div id="editor" contenteditable="true" onfocus="if(this.innerText === 'Start typing here...') this.innerText = '';" onblur="if(this.innerText === '') this.innerText = 'Start typing here...';">
                Start typing here...
            </div>
        </div>


        <input type="submit" value="Submit">
    </form>

    <script>
        // Function to execute commands (e.g., bold, italic)
        function execCmd(command, value = null) {
            document.execCommand(command, false, value);
        }

        // Capture the editor content before form submission
        document.getElementById("userForm").addEventListener("submit", function(event) {
            var editorContent = DOMPurify.sanitize(document.getElementById("editor").innerHTML);
            document.getElementById("editorContent").value = editorContent;
        });


    </script>
</body>

</html>