<?php
require __DIR__ . '/classes/JwtHandler.php';

class Auth extends JwtHandler
{
    protected $db;
    protected $headers;
    protected $data;
    protected $token;

    public function __construct($db, $headers,$data=[])
    {
        parent::__construct();
        $this->db = $db;
        $this->db->exec("set names utf8mb4");
        $this->headers = $headers;
        $this->data = $data;
        //var_dump($data);
   
    }
 
    public function isValid($tp)
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);
            if ( $tp == 1 &&
                isset($data['data']->user_id) && 
                 $user = $this->fetchStd($data['data']->user_id,$this->headers['Stdid'])
            ) :
                return $user;
            elseif ( $tp == 3 &&
                isset($data['data']->user_id) && 
                 $pay = $this->fetchPay($data['data']->user_id, $this->data->VOUCHER)
            ) :
                return $pay;
            elseif( $tp == 2 &&
            isset($data['data']->user_id) && 
             $user = $this->CreatPay( $this->data,$data['data']->user_id))  :
             return $user;
            else :
                return [
                    'status' => 404,
                    "success" => 0,
                    "message" =>  $data['message'],
                ];
            endif;
        } else {
            return [
                'status' => 500,
                "success" => 0,
                "message" => "Token not found in request"
            ];
        }
    }

    protected function fetchUser($user_id)
    {
        try {
           $fetch_user_by_id = "SELECT `name`,`email`,`bank`,`university`,`ip` FROM `usersapi` WHERE `id`=:id";
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $query_stmt->execute();
            if ($query_stmt->rowCount()) :
                return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return false;
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }

    protected function fetchStd($user_id,$Stdid)
    {
       // var_dump($Stdid);
        try {
            if (
                !isset($Stdid)
                || empty(trim($Stdid))
            ) :
            $fields = ['fields' => [ 'Stdid']];
            return $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
            endif;

           
             $rs_user = $this->fetchUser($user_id);
            $bank = $rs_user['bank'];
            $user_university = $rs_user['university'];

            /* $fetch_user_by_id = "SELECT '$user_university' AS UNI,'REGISTRATION' as SYSTYPE, f.title01 AS FAC, s.fac FACID, s.universityno as STDID, s.name01 NAME, s.amt as AMT 	FROM reg.students AS s  INNER JOIN reg.faculties f ON f.code = s.fac WHERE s.active=1001 AND s.universityno =:STDIDS";
            
         $fetch_user_by_id = " SELECT '$user_university' AS UNI,'REGISTRATION' as SYSTYPE, f.name AS FAC, s.faculty_code FACID, s.university_no as STDID, s.name_ar NAME, s.reg_fees as AMT 	FROM sab2007_admin.students AS s  INNER JOIN sab2007_admin.faculties f ON f.code = s.faculty_code WHERE s.university_no=:Stdid";*/
         $fetch_user_by_id = " 
                            SELECT '$user_university' AS UNI,
                                   'REGISTRATION' as SYSTYPE, 
                                   f.name AS FAC, 
                                   s.faculty_code FACID, 
                                   s.university_no as STDID, 
                                   s.name_ar NAME, 
                                   CASE 
                                       WHEN s.pay1 > 0 THEN s.pay1
                                       WHEN s.pay1 = 0 AND s.pay2 > 0 THEN s.pay2
                                       WHEN s.pay2 = 0 AND s.pay3 > 0 THEN s.pay3
                                       WHEN s.pay3 = 0 AND s.pay4 > 0 THEN s.pay4
                                       ELSE s.reg_fees + s.fees  -- fallback if all payments are zero
                                   END AS AMT
                            FROM sab2007_admin.students AS s  
                            INNER JOIN sab2007_admin.faculties f ON f.code = s.faculty_code 
                            WHERE s.university_no = :Stdid
                        ";
                                 
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':Stdid', $Stdid, PDO::PARAM_INT);
            $query_stmt->execute();
            if ($query_stmt->rowCount()) :
            return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return $this->msg(0, 404, 'Student Not Found!');
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }
    protected function CreatPay($payData,$user_id)
    {
      //  var_dump($payData);
        try {
            if (
                !isset($payData->STUDENTSID)
                || !isset($payData->AMOUNT)
                || !isset($payData->BANKDATE)
                || !isset($payData->VOUCHERNO)
                || !isset($payData->BRANCH)
                || empty(trim($payData->STUDENTSID))
                || empty(trim($payData->AMOUNT))
                || empty(trim($payData->BANKDATE))
                || empty(trim($payData->VOUCHERNO))
                || empty(trim($payData->BRANCH))
            
            ) :
            $fields = ['fields' => ['STUDENTSID', 'AMOUNT', 'VOUCHERNO', 'BANKDATE', 'branch']];
            return $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
            endif;
            
             $rs_user = $this->fetchUser($user_id);
            $bank = $rs_user['bank'];
            $user_university = $rs_user['university'];
             if(isset($user_university)):
             //The Entry Data var_dump($rs_user); 
            //  $insert_query = "INSERT INTO `usersapi`(`name`,`email`,`password`) VALUES(:name,:email,:password)";
             $insert_query = "INSERT INTO sab2007_admin.payments 	(`university_no`, `amount`, `bankdate`, `voucher_no`,`created_at`)
             VALUES (:STUDENTSID, :AMOUNT, NOW(), :VOUCHERNO, NOW())";

             $insert_stmt = $this->db->prepare($insert_query);

             // DATA BINDING
             $insert_stmt->bindValue(':STUDENTSID',$payData->STUDENTSID, PDO::PARAM_INT);
             $insert_stmt->bindValue(':AMOUNT',$payData->AMOUNT, PDO::PARAM_INT);
             $insert_stmt->bindValue(':VOUCHERNO',$payData->VOUCHERNO, PDO::PARAM_STR);
           //  $insert_stmt->bindValue(':BRANCH',$payData->BRANCH, PDO::PARAM_STR);
           //  $insert_stmt->bindValue(':BANK',$bank, PDO::PARAM_STR);
           //  $insert_stmt->bindValue(':university',$user_university, PDO::PARAM_STR);
             $insert_stmt->execute();
             $lastId = $this->db->lastInsertId();   
            return $this->msg(1, 200,'You have successfully Payment.('.$lastId.')');
            else :
                return $this->msg(0, 404, 'Page Not Found!');
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }

    protected function fetchPay($user_id,$voucher_no)
    {
        try {
            if (
                !isset($voucher_no)
                || empty(trim($voucher_no))
            ) :
            $fields = ['fields' => [ 'VOUCHERNO']];
            return $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
            endif;

                

             $rs_user = $this->fetchUser($user_id);
            $bank = $rs_user['bank'];
            $user_university = $rs_user['university'];

             $fetch_user_by_id = "SELECT id AS ID, university_no AS STUDENTID, amount AMT, bankdate AS BANKDATE, voucher_no AS VOUCHER,BANK  FROM payments WHERE voucher_no = :VOUCHER";
         
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            //$query_stmt->bindValue(':BANK', $bank, PDO::PARAM_INT);
            $query_stmt->bindValue(':VOUCHER', $voucher_no, PDO::PARAM_INT);
            $query_stmt->execute();
            if ($query_stmt->rowCount()) :
            return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return $this->msg(0, 404, 'Payment Not Found!');
            endif;
        } catch (PDOException $e) {
            return $voucher_no;
        }
    }


}