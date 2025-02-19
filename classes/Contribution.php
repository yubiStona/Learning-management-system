<?php
    require_once '../configs/Database.php';
    class Contribution{
        public $conn;
        public $connection;

        public function __construct(){
            $this->connection=new Database();
            $this->conn=$this->connection->getConnection();
        }
        
        public function getContributed(){
            $result=$this->conn->prepare("select c.id, c.course_id,c.semester,c.subject_id,c.content_type,c.description,c.file_name,c.email,c.uploaded_at,c.status,s.subject_name from contribution c join subject s on c.subject_id=s.subject_id"); 
            $result->execute();
            return $result->fetchAll();   
        }

        public function updateStatus($id,$status){
            $result=$this->conn->prepare("update contribution set status=:status where id=:id");
            $result->bindParam(":id",$id);
            $result->bindParam(":status",$status);
            if($result->execute()){
                return true;
            }else{
                return false;
            }
        }

        //get approved contribution data
        public function getContributionById($id){
            $result=$this->conn->prepare("select * from contribution where id=:id");
            $result->bindParam(":id",$id);
            $result->execute();
            return $result->fetch();
        }

        //delete the contribution
        public function deleteContribution($id){
            $result=$this->conn->prepare("delete from contribution where id=:id");
            $result->bindParam(":id",$id);
            if($result->execute()){
                return true;
            }else{
                return false;
            }
        }
       
        //get the contribution for recent updates
        public function getRecent(){
            $result=$this->conn->prepare("select file_name,email,uploaded_at,status from contribution order by uploaded_at desc limit 10");
            $result->execute();
            return $result->fetchAll();
        }

        //fetch total contribution
        public function totalContribution(){
            $result=$this->conn->prepare("select count(*) as total from contribution");
            $result->execute();
            return $result->fetch();
        }
    }
?>