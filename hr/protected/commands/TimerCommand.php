<?php
class TimerCommand extends CConsoleCommand {
    protected $send_list = array();//信息列表
    protected $city_list = array();//所有有信息的城市（優化查詢使用）
    protected $city = "";//

    public function run() {
        echo "start:";
        $command = Yii::app()->db->createCommand();
        $firstday = date("Y/m/d");
        $lastday = date("Y/m/d",strtotime("$firstday + 1 month"));
        $this->longTimeContract();//合同過期提示（郵件)
        $aaa = $command->update('hr_employee', array("z_index"=>2),"staff_status=0 and test_type=1 and replace(test_start_time,'-', '/') <= '$firstday' and replace(test_end_time,'-', '/') >='$firstday'");//試用期
        $command->reset();
        echo "試用期:$aaa<br>";
        $aaa = $command->update('hr_employee', array("z_index"=>1),"staff_status=0 and test_type=1 and replace(test_start_time,'-', '/') >= '$firstday'");//未入職
        $command->reset();
        echo "未入職:$aaa<br>";
        $aaa = $command->update('hr_employee', array("z_index"=>5),"staff_status=0 and (test_type=0 or replace(test_end_time,'-', '/') <='$firstday')");//正式員工
        $command->reset();
        echo "正式員工:$aaa<br>";
        $aaa = $command->update('hr_employee', array("z_index"=>4),"staff_status=0 and fix_time='fixation' and replace(end_time,'-', '/') >='$firstday' and replace(end_time,'-', '/') <='$lastday'");//合同即將過期
        $command->reset();
        echo "合同即將過期:$aaa<br>";
        $aaa = $command->update('hr_employee', array("z_index"=>3),"staff_status=0 and fix_time='fixation' and replace(end_time,'-', '/') <'$firstday'");//合同過期
        echo "合同過期:$aaa<br>";


        $this->signedContract();//是否簽署合同
        $this->contractCitySendEmail();//員工合同7天將過期(合同未過期)
        $this->contractAgoSendEmail();//合同過期10天后（合同已過期）給饒總發送郵件

        //加班、請假批准后的郵件提示（開始)
        $this->leaveThreeSendEmail();
        $this->leaveSevenSendEmail();
        $this->leaveMoreSendEmail();
        $this->workThreeSendEmail();
        $this->workSevenSendEmail();
        $this->workMoreSendEmail();
        //加班、請假批准后的郵件提示（結束)

        $this->sendEmail();//統一發送郵件
        echo "end";
    }

    private function sendEmail(){
        $email = new Email("人事系統待處理事項","","人事系統待處理事項");
        $userlist = $email->getEmailUserList($this->city_list);
        if($userlist){
            foreach ($userlist as $user){
                if($this->city != $user["city"]){
                    $this->city = $user["city"];
                    $this->city_list = $email->getAllCityToMaxCity($user["city"]);
                }
                $message="";
                foreach ($this->send_list as $send){
                    $html = "";
                    $bool = array_intersect($this->city_list,$send["city_list"]);
                    if(empty($bool)){
                        continue;//該城市沒有提示信息
                    }
                    if(!empty($send["auth_list"])){
                        if(!$this->arrSearchStr($send["auth_list"],$user["a_read_write"])){
                            continue;//用戶權限不一致
                        }
                    }
                    if(!empty($send["incharge"])){//incharge：1 需要boss身份  0：不需要驗證
                        if(empty($user["incharge"])){
                            continue;//該用戶不是boss
                        }
                    }
                    $city_bool = true;
                    $city_list = array($user["city"]);
                    if(key_exists("joeEmail",$send)){//驗證是否只給繞生發郵件
                        if($send["joeEmail"]){
                            $joeEmail = $email->getJoeEmail();
                            if($user["email"]!=$joeEmail){//用戶不是繞生跳出循環
                                continue;
                            }
                            $city_bool = false;
                            $city_list = $send["city_list"];
                        }
                    }
                    if($send["city_allow"]&&$city_bool){ //需要查詢下級城市
                        $city_list = $this->city_list;
                    }
                    $html.=$send["title"];
                    $html.="<table border='1'>".$send["table_head"]."<tbody>";
                    foreach ($city_list as $city){//城市循環
                        if(in_array($city,$send["city_list"])){
                            $html .= implode("",$send[$city]["table_body"]);
                        }
                    }
                    if(!empty($html)){
                        $html.="</tbody></table><p>&nbsp;</p><br/>";
                    }
                    $message.=$html;
                }

                if(!empty($message)){ //如果有內容則發送郵件
                    $email->setMessage($message);
                    $email->addToAddrEmail($user["email"]);
                    $email->sent("系统生成");
                    $email->resetToAddr();
                }
            }
        }
    }

    private function arrSearchStr($arr,$str){
        foreach ($arr as $item){
            if (strpos($str,$item)!==false)
                return true;
        }
        return false;
    }

    //合同即将到期
    private function longTimeContract(){
        $command = Yii::app()->db->createCommand();
        $firstday = date("Y/m/d");
        $lastday = date("Y/m/d",strtotime("$firstday + 1 month"));
        $sql = "staff_status=0 and fix_time='fixation' and replace(end_time,'-', '/') >='$firstday' and replace(end_time,'-', '/') <='$lastday'";
        $rows = $command->select("*")->from("hr_employee")->where($sql)->queryAll();
        if($rows){
            $description = "<p>下列员工的合同即将到期：</p>";
            $arr = $this->getListToStaffList($description,$rows);
            $arr["auth_list"] = array("ZG02","ZE04");
            $arr["city_allow"] = true;
            $arr["incharge"] = 0;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //員工錄入后2周提示是否簽署合同
    private function signedContract(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $firstDay = date("Y/m/d");
        $firstDay = date("Y/m/d",strtotime("$firstDay - 14 day"));
        $sql = "staff_status=4 and replace(entry_time,'-', '/') <='$firstDay'";
        $rows = $command->select("*")->from("hr_employee")->where($sql)->queryAll();
        if($rows){
            $description = "<p>請檢查下列员工的是否簽署合同：</p>";
            $arr = $this->getListToStaffList($description,$rows,true);
            $arr["auth_list"] = array("ZE01");
            $arr["city_allow"] = true;
            $arr["incharge"] = 0;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //員工合同7天將過期時給地區總監發送郵件
    private function contractCitySendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $firstday = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$firstday + 7 day"));
        $sql = "staff_status=0 and fix_time='fixation' and replace(end_time,'-', '/') ='$firstday'";
        $rows = $command->select("*")->from("hr_employee")->where($sql)->queryAll();
        if($rows){
            $description="<p>【紧急】下列員工的合同将于".date("Y年m月d日",strtotime($firstday))."到期,请记得安排续约</p>";
            $arr = $this->getListToStaffList($description,$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = false;
            $arr["incharge"] = 1;//只給boss發郵件
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }
    //員工合同過期10天給饒總發送郵件
    private function contractAgoSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $firstday = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$firstday - 10 day"));
        $sql = "staff_status=0 and fix_time='fixation' and replace(end_time,'-', '/') ='$firstday'";
        $rows = $command->select("*")->from("hr_employee")->where($sql)->queryAll();
        if($rows){
            $description="<p>【紧急】下列員工的合同于".date("Y年m月d日",strtotime($firstday))."已到期</p>";
            $arr = $this->getListToStaffList($description,$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = true;
            $arr["incharge"] = 1;
            $arr["joeEmail"] = true;//僅限繞生收到郵件
            if(count($arr)>7){
                $this->send_list[] = $arr;
            }
        }
    }
    private function getListToStaffList($description,$rows,$bool=false){

        $arr = array();
        $arr["city_list"] = array();
        //$description="【紧急】下列員工的合同于".date("Y年m月d日",$firstday)."已到期";
        //$arr["auth_list"] = array("ZE01");
        $arr["title"] = $description;
        $arr["table_head"] = "<thead><th>员工编号</th><th>员工姓名</th><th>员工所在城市</th><th>员工入职日期</th><th>合同日期</th></thead>";
        foreach ($rows as $row){
            if($bool){
                if(!$this->docmanSearch("EMPLOY",$row["id"],$row["lcd"])){
                    continue;
                }
            }
            $city = "'".$row["city"]."'";
            if(!in_array($city,$this->city_list)){
                $this->city_list[] = $city;
            }
            if(!key_exists($row["city"],$arr)){
                $arr["city_list"][]=$row["city"];
                $arr[$row["city"]]=array();
                $arr[$row["city"]]["city_name"]=CGeneral::getCityName($row["city"]);
            }
            if("nofixed"==$row["fix_time"]){
                $con_date = date("Y-m-d",strtotime($row["start_time"]))."(无期限合同)";
            }else{
                $con_date = date("Y-m-d",strtotime($row["start_time"]))." - ".$row["end_time"];
            }
            $arr[$row["city"]]["table_body"][]="<tr><td>".$row["code"]."</td>"."<td>".$row["name"]."</td>"."<td>".$arr[$row["city"]]["city_name"]."</td>"."<td>".$row["entry_time"]."</td>"."<td>".$con_date."</td></tr>";
        }
        return $arr;
    }

    private function getJobListToStaffList($description,$str,$rows){

        $arr = array();
        $arr["city_list"] = array();
        $arr["title"] = $description;
        $arr["table_head"] = "<thead><th>员工编号</th><th>员工姓名</th><th>员工所在城市</th><th>".$str."编号</th></thead>";
        $str = $str=="加班"?"WORKEM":"LEAVE";
        foreach ($rows as $row){
            if ($this->docmanSearch($str, $row["id"], $row["lud"])) {
                $city = "'".$row["city"]."'";
                if(!in_array($city,$this->city_list)){
                    $this->city_list[] = $city;
                }
                if(!key_exists($row["city"],$arr)){
                    $arr["city_list"][]=$row["city"];
                    $arr[$row["city"]]=array();
                    $arr[$row["city"]]["city_name"]=CGeneral::getCityName($row["city"]);
                }
                $arr[$row["city"]]["table_body"][]="<tr><td>".$row["code"]."</td>"."<td>".$row["name"]."</td>"."<td>".$arr[$row["city"]]["city_name"]."</td>"."<td>".$row["job_code"]."</td></tr>";
            }
        }
        return $arr;
    }

    //加班附件提示(3天)
    private function workThreeSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $date = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$date - 3 day"));
        $end = date("Y/m/d",strtotime("$date - 7 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday' and date_format(a.lud,'%Y/%m/%d') > '$end'";
        $rows = $command->select("a.work_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的加班單“批准”3天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"加班",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = false;
            $arr["incharge"] = 1;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //加班附件提示(7天)
    private function workSevenSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $date = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$date - 7 day"));
        $endday = date("Y/m/d",strtotime("$date - 15 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday' and date_format(a.lud,'%Y/%m/%d') > '$endday'";
        $rows = $command->select("a.work_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的加班單“批准”7天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"加班",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = true;
            $arr["incharge"] = 1;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //加班附件提示(15天)
    private function workMoreSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $date = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$date - 15 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday'";
        $rows = $command->select("a.work_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的加班單“批准”15天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"加班",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = true;
            $arr["incharge"] = 1;
            $arr["joeEmail"] = true;//僅限繞生收到郵件
            if(count($arr)>7){
                $this->send_list[] = $arr;
            }
        }
    }

    //請假附件提示(3天)
    private function leaveThreeSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $date = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$date - 3 day"));
        $endday = date("Y/m/d",strtotime("$date - 7 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday' and date_format(a.lud,'%Y/%m/%d') > '$endday'";
        $rows = $command->select("a.leave_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的请假單“批准”3天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"请假",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = false;
            $arr["incharge"] = 1;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //請假附件提示(7天)
    private function leaveSevenSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $date = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$date - 7 day"));
        $endday = date("Y/m/d",strtotime("$date - 15 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday' and date_format(a.lud,'%Y/%m/%d') > '$endday'";
        $rows = $command->select("a.leave_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的请假單“批准”7天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"请假",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = true;
            $arr["incharge"] = 1;
            if(count($arr)>6){
                $this->send_list[] = $arr;
            }
        }
    }

    //請假附件提示(15天)
    private function leaveMoreSendEmail(){
        $command = Yii::app()->db->createCommand();
        $command->reset();
        $firstday = date("Y/m/d");
        $firstday = date("Y/m/d",strtotime("$firstday - 15 day"));
        $sql = "a.status=4 and date_format(a.lud,'%Y/%m/%d') <= '$firstday'";
        $rows = $command->select("a.leave_code as job_code,b.code,b.name,b.city,a.id,a.lud")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where($sql)->queryAll();
        if($rows){
            $description="<p>下列員工的请假單“批准”15天后,还未上传附件</p>";
            $arr = $this->getJobListToStaffList($description,"请假",$rows);
            $arr["auth_list"] = '';
            $arr["city_allow"] = true;
            $arr["incharge"] = 1;
            $arr["joeEmail"] = true;//僅限繞生收到郵件
            if(count($arr)>7){
                $this->send_list[] = $arr;
            }
        }
    }

    //請假、加班附件變更查詢
    private function docmanSearch($docType,$id,$date){
        $date = date("Y/m/d H:i:s",strtotime($date));
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("b.lcd")->from("docman$suffix.dm_master a")
            ->leftJoin("docman$suffix.dm_file b","b.mast_id = a.id")
            ->where("a.doc_type_code='$docType' and a.doc_id = '$id' and date_format(b.lcd,'%Y/%m/%d %H:%i:%s') > '$date'")->queryRow();
        if($rows){
            return false;//不需要發送郵件
        }else{
            return true;//需要發送郵件
        }

    }
}
?>