<?php

    /*
    *  Copyright (c) Codiad & RustyGumbo, distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */
	    
    require_once('file_db.php');
    $dataBase = new file_db(BASE_PATH . '/data/messaging');

    class Message {

        //////////////////////////////////////////////////////////////////
        // PROPERTIES
        //////////////////////////////////////////////////////////////////

        public $sender		= '';
        public $recipient	= '';
        public $message         = '';
        public $is_read		= 0;

        //////////////////////////////////////////////////////////////////
        // Initialize Data Base
        //////////////////////////////////////////////////////////////////

        function getDB() {
            global $dataBase;
            return $dataBase;
        }

        //////////////////////////////////////////////////////////////////
        // METHODS
        //////////////////////////////////////////////////////////////////

        // -----------------------------||----------------------------- //

        //////////////////////////////////////////////////////////////////
        // Create a message.
        //////////////////////////////////////////////////////////////////

        public function Create(){
            /* Array */ $query = array('sender' => $this->sender, 'recipient' => $this->recipient, 'message' => $this->message, 'is_read' => 0);
            /* Array */ $results = $this->getDB()->create($query, 'message');

            if ($results != null) {
                echo formatJSEND("success");
            } else {
                echo formatJSEND("error", "Error: Your message could not be sent.");
            }
        }

        //////////////////////////////////////////////////////////////////
        // Create a message.
        //////////////////////////////////////////////////////////////////

        public function CheckNew(){
            /* Array */ $query = array('recipient' => $this->recipient, 'is_read' => 0, 'message' => "*", 'sender' => "*");
            /* Array */ $results = $this->getDB()->select($query, 'message');
            /* Array */ $data = array();

            if ($results != null) {
                /* Object */ $message = $results[0];

                //Update the message.
                /* Array */ $query = array('sender' => $message->get_field('sender'), 'recipient' => $message->get_field('recipient'), 'message' => $message->get_field('message'), 'is_read' => 1);

                //Workaround: file_db does not provide an update method, the entry must be deleted and re-inserted.
                $message->remove();
                $new_message = $this->getDB()->create($query, 'message');

                //Prepare the return data.
                $data['sender'] = $new_message->get_field('sender');
                $data['message'] = $new_message->get_field('message');
            }

            echo formatJSEND("success", $data);
        }

        //////////////////////////////////////////////////////////////////
        // Get users other than the user in session.
        //////////////////////////////////////////////////////////////////

        public function GetOtherUsers(){
            /* Array */ $users = getJSON('users.php');

            //Remove the user in session.
            foreach($users as $key => $user) {
                if($user['username'] == $_SESSION['user']){
                    unset($users[$key]);
                    break;
                }
            }

            return $users;
        }
    }
?>