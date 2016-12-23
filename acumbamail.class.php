<?php
/* PHP Class for Acumbamail API */
/* ONLY FOR WORDPRESS*/

class AcumbamailAPI{
    private $auth_token;
    private $customer_id;
    private $http_method;

    function __construct($customer_id, $auth_token){
        $this->auth_token = $auth_token;
        $this->customer_id = $customer_id;
        $this->http_method = get_option('acumba_http',"https");
    }

    // createList($sender_email,$name,$company,$country,$city,$address,$phone)
    // Crea una nueva lista de suscriptores
    public function createList($sender_email,$name,$company,$country,$city,$address,$phone){
        $request = "createList";
        $data = array(
            'sender_email' => $sender_email,
            'name'         => $name,
            'company'      => $company,
            'country'      => $country,
            'city'         => $city,
            'address'      => $address,
            'phone'        => $phone,
        );
        return $this->callAPI($request, $data);
    }

    // deleteList($list_id)
    // Borra una lista de suscriptores
    public function deleteList($list_id){
        $request = "deleteList";
        $data = array('list_id' => $list_id);
        return $this->callAPI($request, $data);
    }

    // deleteSubscriber($list_id, $email)
    // Elimina un suscriptor de una lista
    public function deleteSubscriber($list_id, $email){
        $request = "deleteSubscriber";
        $data = array(
            'list_id' => $list_id,
            'email' => $email,
        );
        return $this->callAPI($request, $data);
    }

    // getSubscribers($list_id, $status)
    // Obtiene los suscriptores de una lista
    public function getSubscribers($list_id, $status){
        $request = "getSubscribers";
        $data = array(
            'list_id' => $list_id,
            'status' => $status,
        );
        return $this->callAPI($request, $data);
    }

    // batchDeleteSubscribers($list_id,$email_list)
    // Elimina un grupo de suscriptores de una lista
    public function batchDeleteSubscribers($list_id,$email_list){
        $request = "batchDeleteSubscribers";
        $data = array(
            'list_id' => $list_id,
            'email_list' => $email_list,
        );
        return $this->callAPI($request, $data);
    }

    // addMergeTag($list_id,$field_name)
    // Elimina un suscriptor de una lista
    public function addMergeTag($list_id,$field_name){
        $request = "addMergeTag";
        $data = array(
            'list_id' => $list_id,
            'field_name' => $field_name,
        );
        return $this->callAPI($request, $data);
    }

    // addSubscriber($list_id,$merge_fields)
    // Agrega un suscriptor a una lista
    public function addSubscriber($list_id,$merge_fields,$double_optin,$welcome_email){
        $request = "addSubscriber";
        $merge_fields_send=array();

        foreach (array_keys($merge_fields) as $merge_field) {
            $merge_fields_send['merge_fields['.$merge_field.']']=$merge_fields[$merge_field];
        }

        $data = array(
            'list_id' => $list_id,
            'double_optin' => $double_optin,
	    'welcome_email' => $welcome_email,
        );

        $data=array_merge($data,$merge_fields_send);

        return $this->callAPI($request, $data);
    }

    // getSubscriberDetails($list_id,$subscriber)
    // Obtiene datos avanzados de un suscriptor
    public function getSubscriberDetails($list_id,$subscriber){
        $request = "getSubscriberDetails";
        $data = array(
            'list_id' => $list_id,
            'subscriber' => $subscriber,
        );
        return $this->callAPI($request, $data);
    }

    // getListStats($list_id)
    // Obtiene datos generales sobre la lista
    public function getListStats($list_id){
        $request = "getListStats";
        $data = array('list_id' => $list_id);
        return $this->callAPI($request, $data);
    }

    // getLists()
    // Obtiene las listas de suscriptores disponibles
    public function getLists(){
        $request = "getLists";
        return $this->callAPI($request);
    }

    // getFields($list_id)
    // Obtiene los campos de la lista y el tipo que tienen
    public function getFields($list_id){
        $request = "getFields";
        $data = array('list_id' => $list_id);
        return $this->callAPI($request, $data);
    }

    // getMergeFieldsWordPress($list_id)
    // Obtiene los merge fields de la lista y el tipo que tienen
    public function getMergeFieldsWordPress($list_id){
        $request = "getMergeFieldsWordPress";
        $data = array('list_id' => $list_id);
        return $this->callAPI($request, $data);
    }

    // callAPI($request, $data = array())
    // Realiza la llamada a la API de Acumbamail con los datos proporcionados
    function callAPI($request, $data = array()){
        $url = "https://acumbamail.com/api/1/".$request.'/';

        $fields = array(
            'customer_id' => $this->customer_id,
            'auth_token'=> $this->auth_token,
            'response_type' => 'json',
        );

        if(count($data)!=0){
            $fields=array_merge($fields,$data);
        }

        $postvars = '';
        foreach($fields as $key=>$value) {
            $postvars .= $key.'='.$value.'&';
        }

        if(function_exists("curl_init")){
            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            if (strpos($url, 'https') !== false){
                curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/assets/cacert.pem");
                //curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch,CURLOPT_POSTREDIR, 3);
            }

            $response = curl_exec($ch);

            if(curl_errno($ch)){
                $response = "CURL ERROR: ".curl_error($ch);
            }else{
                $response=json_decode($response,true);
            }
            curl_close($ch);
        }else{
            $response = "Para utilizar el plugin necesitas cURL instalado en tu servidor";
        }
        return $response;
    }
}
?>
