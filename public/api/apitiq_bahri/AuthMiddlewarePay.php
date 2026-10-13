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
		
		var_dump($headers);
   
    }
 
    public function isValid($tp)
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);
            if ( $tp == 1 &&
                isset($data['data']->user_id) && 
                 $user = $this->fetchStd($data['data']->user_id,$this->headers['stdid'])
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
           $fetch_user_by_id = "SELECT `name`,`email`,`bank`,`university`,`ip` FROM `users` WHERE `id`=:id";
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

    protected function fetchStd($user_id,$stdid)
    {
        try {
            if (
                !isset($stdid)
                || empty(trim($stdid))
            ) :
            $fields = ['fields' => [ 'Stdid']];
            return $this->msg(0, 422, 'Please Fill in all Required Fields!GET', $fields);
            endif;

           
             $rs_user = $this->fetchUser($user_id);
            $bank = $rs_user['bank'];
            $user_university = $rs_user['university'];

             $fetch_user_by_id = "SELECT ' . $user_university . ' AS UNI,'REGISTRATION' as SYSTYPE, f.title01 AS FAC, s.fac FACID, s.universityno as STDID, s.name01 NAME, s.amt as AMT 	FROM reg.students AS s  INNER JOIN reg.faculties f ON f.code = s.fac WHERE s.active=1001 AND s.universityno =:STDIDS ";
         
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':STDIDS', $stdid, PDO::PARAM_INT);
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
            //  $insert_query = "INSERT INTO `users`(`name`,`email`,`password`) VALUES(:name,:email,:password)";
             $insert_query = "INSERT INTO reg.paymentsv3	(universityno, amount, bankdate, voucher_no, branch, bank_short_code, university)
             VALUES (:STUDENTSID, :AMOUNT, NOW(), :VOUCHERNO, :BRANCH, :BANK, :university)";

             $insert_stmt = $this->db->prepare($insert_query);

             // DATA BINDING
             $insert_stmt->bindValue(':STUDENTSID',$payData->STUDENTSID, PDO::PARAM_INT);
             $insert_stmt->bindValue(':AMOUNT',$payData->AMOUNT, PDO::PARAM_INT);
             $insert_stmt->bindValue(':VOUCHERNO',$payData->VOUCHERNO, PDO::PARAM_STR);
             $insert_stmt->bindValue(':BRANCH',$payData->BRANCH, PDO::PARAM_STR);
             $insert_stmt->bindValue(':BANK',$bank, PDO::PARAM_STR);
             $insert_stmt->bindValue(':university',$user_university, PDO::PARAM_STR);
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

             $fetch_user_by_id = "SELECT id AS ID, universityno STUDENTID, amount AMT, bankdate BANKDATE, voucher_no VOUCHER, branch BRANCH,1 DONE, bank_short_code BANK, university UNIVERSITY  FROM reg.paymentsv3 WHERE bank_short_code= :BANK && voucher_no = :VOUCHER";
         
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':BANK', $bank, PDO::PARAM_INT);
            $query_stmt->bindValue(':VOUCHER', $voucher_no, PDO::PARAM_INT);
            $query_stmt->execute();
            if ($query_stmt->rowCount()) :
            return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return $this->msg(0, 404, 'Payment Not Found!');
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }


}