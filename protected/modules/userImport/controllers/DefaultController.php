<?php

class DefaultController extends Controller {

    public function actionIndex() {
        
        $model = new CFormModel;
        
        $not_imported = '';

        if (!empty($_POST)) {

            $error_rows = array();

            $raw = $_POST['csvimport'];

            $csvimport = $this->strtocsv($raw);
            $header = array_shift($csvimport);
            $i = 0;

            foreach ($csvimport as $row)
            {
                
                $i++;
                
                $data = array();
                foreach ($header as $k => $head)
                {
                    // get the data field from Model.field
                    //produces $data['User']['username'] = 'csvusername';
                    //and $data['Profile']['phone'] = '555-555-5555';
                    if (strpos($head, '.') !== false) {
                        $h = explode('.', $head);
                        $data[$h[0]][$h[1]] = (isset($row[$k])) ? $row[$k] : '';
                    }
                   
                    // get the data field from field
                    else {
                        $data['User'][$head] = (isset($row[$k])) ? $row[$k] : '';
                    }
                }

                $user = new User;
                $profile = new Profile;

                $user->attributes = $data['User'];
                $user->activkey = Yii::app()->getModule('user')->encrypting(microtime() . $user->password);
                $user->createtime = time();
                $user->lastvisit = time();

                //adding profile data from loop above if exists
                if ($data['Profile'])
                {
                    $profile->attributes = $data['Profile'];
                    $profile->user_id = 0;
                }

                if ($user->validate() && $profile->validate())
                {
                    
                    $user->password = Yii::app()->getModule('user')->encrypting($user->password);

                    if ($user->save())
                    {
                        //Send notification email upon create if set
                        if (Yii::app()->getModule('user')->sendActivationMail)
                        {
                            $activation_url = Yii::app()->createAbsoluteUrl('/user/activation/activation', array("activkey" => $user->activkey, "email" => $user->email));
                            UserModule::sendMail(
                                    $user->email,
                                    UserModule::t("You registered from {site_name}", array('{site_name}' => Yii::app()->name)), 
                                    UserModule::t("Please activate you account go to {activation_url}",
                                    array('{activation_url}' => $activation_url))
                            );
                        }

                        //do provfile save
                        $profile->user_id = $user->id;
                        $profile->save();
                    }
                    else
                    {
                        //save errors
                        Yii::app()->user->setFlash('error', "Row $i, User failed to save.");
                        
                        array_push($error_rows, $row);

                    }//end save
                    
                }else{

                        array_push($error_rows, $row);
                }

                 /*
                 * TODO
                 * Store error returned by PROFILE model
                 */
                //flash and store validation errors
                if($user->getErrors())
                {
                    foreach ($user->getErrors() as $k=>$val)
                    {
                        Yii::app()->user->setFlash('error', "Row $i failed to validate.".$val[0]);
                    }

                }
                    
            }//end foreach

            //convert non imported to csv for easy editing
            if(!empty($error_rows))
            {
                $cr = "\n";
                //var for csv storage of not imported items...
                $not_imported = $this->arrayToCsv($header).$cr;
                foreach ($error_rows as $k=>$v){
                    $not_imported .= $this->arrayToCsv($v).$cr;
                }
            }
            else
            {
                Yii::app()->user->setFlash('success', "Finished importing $i rows");
            }
        }// end post


        $this->render('index', array('model' => $model,'not_imported'=>$not_imported));
    }


     //replaces str_getcsv for php installations less than 5.3
    public function strtocsv($input, $delimiter=',', $enclosure='"', $escape=null, $eol=null) {
        $temp = fopen("php://memory", "rw");
        fwrite($temp, $input);
        fseek($temp, 0);
        $r = array();
        while (($data = fgetcsv($temp, 4096, $delimiter, $enclosure)) !== false) {
            $r[] = $data;
        }
        fclose($temp);
        return $r;
    }


    public function arrayToCsv( array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ( $fields as $field ) {
            if ($field === null && $nullToMysqlNull) {
                $output[] = 'NULL';
                continue;
            }

            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            }
            else {
                $output[] = $field;
            }
        }

        return implode( $delimiter, $output );
    }




}