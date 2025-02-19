<?php 
     require_once('../configs/config.php');
     require_once('../classes/courseCRUD.php');
     session_start();
     if(!isset($_SESSION['username']) && !isset($_SESSION['is_admin'])){
         header("Location:".BASE_PATH."/admin");
     }
     $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light'; 
     $course=new courseCRUD();
     $courseinfo=$course->getCourseId();

     if(isset($_POST['submit'])){
        $subject_id=$_POST['subject-id'];
        $result=$course->getSubjectById($subject_id);
        if($result){
            echo("<script>alert('Subject already exist')</script>");
        }else{
            $added=$course->addSubject($_POST);
            if($added){
                header("Location:".BASE_PATH."/admin/course.php");
            }else{
                echo("<script>alert('Subject not added')</script>");
            }
        }
       

     }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Faculty</title>
    <!--STYLESHEET-->
    <link rel="stylesheet" href="./css/addSubject.css" />
    <!-- <script src="https://unpkg.com/htmx.org"></script> -->
    <script src="./htmx.js"></script>
    <!--MATERIAL  CDN -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="<?= $theme === 'dark' ? 'dark-theme-variables' : '' ?>">
    <div class="container">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-section">
            <!-- Right section at the top -->
            <?php include_once('includes/right.php'); ?>

            <!--MAIN SECTION-->
            <main>
                <!-- Faculty and Subject Forms Container -->
                <div class="forms-container">
                    <!-- Subject Form Section -->
                    <div class="subject-form">
                        <h2>Add Subject</h2>
                        <form id="subjectForm" action="" method="POST">
                            <div class="form-group">
                                <label for="subject-id">Subject ID</label>
                                <input type="text" id="subject-id" name="subject-id" required />
                            </div>
                            <div class="form-group">
                                <label for="subject-name">Subject Name</label>
                                <input type="text" id="subject-name" name="subject-name" required />
                            </div>
                            <div class="form-group">
                                <label for="course-id">Course</label>
                                <select id="course-id" name="course-id" required hx-get="getSemester.php"
                                hx-target="#semester" hx-trigger="change, load">
                                    <?php foreach($courseinfo as $id){?>
                                    <option value="<?php echo $id['course_id'];?>">
                                        <?php echo $id['course_id'];?>
                                    </option>
                                    <?php };?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select id="semester" name="semester" required>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn-primary">Add Subject</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="./index.js"></script>

</body>

</html>