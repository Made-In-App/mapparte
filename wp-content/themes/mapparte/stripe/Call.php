<?php

namespace Mapparte\Stripe;

require('HttpClient.php');

/**
 * Classe base con elementi comuni a 
 * tutte le chiamate all'API Stripe
 * 
 * Fondamentalmente prende in ingresso
 * la chiave segreta di stripe e istanzia
 * un semplice HttpClient per le chiamate. 
 * 
 */
class Call {

    protected $sk;    // secret key
    protected $http;  // HttpClient
    
    /**
     * Costruttore
     * 
     * @param string $sk  È il parametro secrect key della piattaforma
     * 
     */
    function __construct($sk) {

        $this->sk = $sk;
        $this->http = new HttpClient(); // dipendenza da HttpClient
    }
}

