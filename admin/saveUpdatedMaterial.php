<?php
    require_once('../classes/materialCRUD.php');
    require_once('../configs/config.php');
     // Get the ID from the query parameter
     session_start();
     // Check if the user is logged in and is an admin
     if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin'])) {
         http_response_code(403); // Forbidden
         echo "Unauthorized access.";
         exit();
     }

     $materialCRUD= new materialCRUD();

    //initialize the error message
    $errors=[];

     if($_SERVER["REQUEST_METHOD"]=="POST"){
        $subId=$_POST['subject-id'];
        $course_id=$_POST['course-id'];
        $types=$_POST['material-type'];
        $subject_name=$materialCRUD->getOneSubject($course_id,$subId);
        //validate subject dropdown
        if(empty($_POST['subject-id'])){
            $errors[]="Subject is required";
        }

        $file_path=null;
        $file_name=null;
        //handle file upload
        if(
            isset($_FILES['file']) && $_FILES['file']['error']==UPLOAD_ERR_OK
        ){
            //add name of the upload directory and temp name to the file
            $uploadDir="../uploads/";//directory to store uploaded files
            $originalName = $_FILES['file']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

             // 3. Validate MIME type (not just extension)
             $finfo = new finfo(FILEINFO_MIME_TYPE);
             $mime = $finfo->file($_FILES['file']['tmp_name']);

             if ($mime !== 'application/pdf') {
                $errors[]="Invalid file type -PDF required";
                // die("<script>alert('Invalid file type - PDF required'); window.history.back();</script>");
            }

            // 4. Generate final filename
            $newFileName = $subject_name['course_id']."_".$subject_name['semester']."_".$subject_name['subject_name']."_".$types. ".pdf";
            $newFileName=str_replace(" ","_",$newFileName);
            $newFilePath = $uploadDir . $newFileName;
   
            //handle errors
            if(!empty($errors)){
                $_SESSION['form_errors']=$errors;
                header("Location: updateMaterial.php?id=" . $_POST['material_id'] . "&courseId=" . $_POST['course-id']);
                exit();
            }   

            // Proceed if no errors

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFilePath)) {
               $file_name=$newFileName;
            } else {
                $_SESSION['form_errors'] = ["Failed to upload file."];
                header("Location: updateMaterial.php?id=" . $_POST['material_id']."&courseId=".$_POST['course_id']);
                exit();
            }
            }


         // Update data in the database
        }
        $result = $materialCRUD->updateMaterial($_POST, $file_name);
        if ($result) {
        header("Location: " . BASE_PATH . "/admin/studyMaterial.php");
        exit();
        } else {
        $_SESSION['form_errors'] = ["Failed to update study materials."];
        header("Location: updateMaterial.php?id=" . $_POST['material_id']."&courseId=".$_POST['course_id']);
        exit();
        }
    }
?>