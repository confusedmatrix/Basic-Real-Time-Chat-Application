<?php

namespace Chat\Model;

use Blueprint\Model\Model;

/**
 * Messages class.
 * 
 * @extends Model
 */
class Messages extends Model {

    /**
     * setContainer function.
     * 
     * @access public
     * @param mixed $container
     * @return void
     */
    public function setContainer($container) {
    
        parent::setContainer($container);
        
        $this->database = $this->container->get('database');
    	
        // create collection if not already exists
        $this->messages = !empty($this->database->messages) ? $this->database->messages : $this->database->createCollection('messages');

        // drop collection if last message is older than 24 hours old.
        $message = $this->messages->find()->sort(array('_id' => -1))->limit(1)->getNext();
        if ($message['timestamp'] < (time() - 86400)) {

            $this->messages->drop();
            return false;

        }

    }

    /**
     * setContainer function.
     * 
     * @access public
     * @return void
     */
    public function getMessagesAsJSON() {

        $time = intval($_GET['time']);  

        // do not return unless new message is available
    	while(!$this->checkNewMessages($time) && time() > ($_GET['time'] + 35)) usleep(10000); // sleep 1/100 second

    	$cursor = $this->messages->find(array('timestamp' => array('$gt' => $time)));
        
        $data = array();
        while ($cursor->hasNext())
            $data[] = $cursor->getNext();

        $this->database->closeConnection();
        return json_encode($data);

    }

    /**
     * checkNewMessages function.
     * 
     * @access private
     * @param mixed $time
     * @return boolean
     */
	private function checkNewMessages($time) {

        $count = $this->messages->find(array('timestamp' => array('$gt' => $time)))->count();
		if ($count >= 1) return true;

		return false;

	}

    /**
     * addMessage function.
     * 
     * @access public
     * @return void
     */
	public function addMessage() {

        $username = htmlentities(strip_tags($_POST['username']));
        $message = htmlentities(strip_tags($_POST['message']));

		$this->messages->insert(array('username' => $username, 'message' => $message, 'timestamp' => time()));

	}

    /**
     * clearMessages function.
     * 
     * @access public
     * @return void
     */
    public function clearMessages() {

        $this->messages->drop();

    }

}