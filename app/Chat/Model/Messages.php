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
        if (!empty($message) && $message['timestamp'] < (time() - 86400)) {

            $this->messages->drop();
            return false;

        }

    }

    /**
     * buildQuery function.
     * 
     * @access public
     * @param $last_id
     * @return mixed
     */
    private function buildQuery($last_id) {

        $query = array();
        if ($last_id != 0) {
            
            $id = new \MongoID($last_id);
            $query = array('_id' => array('$gt' => $id));

        }

        return $query;
    }

    /**
     * setContainer function.
     * 
     * @access public
     * @return void
     */
    public function getMessagesAsJSON() {

        $last_id = $_GET['last'];
        $t = time();

        // do not return unless new message is available
    	while(!$this->checkForNewMessages($last_id) && ($t + 35) > time()) usleep(10000); // sleep 1/100 second

        $query = $this->buildQuery($last_id);
        $cursor = $this->messages->find($query);
        
        $data = array();
        while ($cursor->hasNext())
            $data['messages'][] = $cursor->getNext();

        $c = count($data['messages']);
        $data['last'] = $data['messages'][($c - 1)]['_id']->{'$id'};

        $this->database->closeConnection();
        return json_encode($data);

    }

    /**
     * checkForNewMessages function.
     * 
     * @access private
     * @param mixed $last_id
     * @return boolean
     */
	private function checkForNewMessages($last_id) {

        $query = $this->buildQuery($last_id);
        $count = $this->messages->find($query)->count();
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