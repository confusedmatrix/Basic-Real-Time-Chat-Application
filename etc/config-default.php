<?php

$config = array(

    'base'     => '/',
    'baseurl'  => '/',
    'database' => array(
        'name' => 'chat',
        'host' => '127.0.0.1',
        'port' => '27017'
    )

);

$config['defaults'] = array(
    
    'page' => array(
        'title'               => 'Real-time chat system',
        'meta-description'    => 'An example real-time chat application making use of long polling and built on Blueprint PHP Framework and MongoDB',
        'h1'                  => 'Real-time chat system <small>Built on Blueprint PHP Framework and MongoDB</small>',
        'css'                 => array(
            $config['base'] . 'css/bootstrap.min.css',
            $config['base'] . 'css/chat.css'
        ),
        'js'                  => array(
            $config['base'] . 'js/bootstrap.min.js',
            $config['base'] . 'js/chat.js'
        )
    )
    
);

/* 
    Return the $config becuase this file will be loaded as an array
    and used in the config class
*/
return $config;