<?php

namespace Mapparte\Stripe;

/**
 * Basic helper per chiamate HTTP 
 * utilizza le curl
 */
class HttpClient {

    /**
     * Effettua una chiamata Http POST
     *
     * @param String $url
     * @param Array  $fields
     * @param Array  $headers (optional)
     * @param String $user_password (optional)
     * 
     * @return Mixed restituisce il risultato della chiamata o un json con un messaggio di errore
     */
    public function post($url, $fields, $headers = [], $user_password = null) {

        // trasforma l'array associativo in array di stringhe "key=value"
        $fields = array_map(function($key, $value){ return $key.'='.urlencode($value); }, array_keys($fields), $fields);
        
        // mette insieme le coppie chiave valore in un'unica stringa
        $fields = implode('&', $fields );
        
        // crea un array di string nel formato chiave:valore
        $headers = array_map(function($key, $value){ return "$key: $value"; }, array_keys($headers), $headers);
        
        // aggiunge il "content type"  per impostare la modalità di invio dei dati
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';

        $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            
            if($user_password != null)
                curl_setopt($ch, CURLOPT_USERPWD, $user_password . ':' . '');

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                $result = json_encode([
                    'error' => [
                        'message' => curl_error($ch),
                        'type'    => 'request error'
                    ]
                ]);                    
            }

            curl_close($ch);

            return $result;
    }


    public function get() {

    }
    
}