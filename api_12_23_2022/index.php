<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');


require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "api/config/config.php");

// die();

$get = new getFunctions();
$post = new postFunctions();

if (isset($_REQUEST['request'])) {
    $req = explode('/', rtrim($_REQUEST['request'], '/'));
} else {
    $req = array("errorcatcher");
}


switch($_SERVER['REQUEST_METHOD']) {    
    case 'POST':

        switch($req[0]) 
        {   
            
            case 'create_order':
                $d = json_decode(file_get_contents("php://input"));
                echo json_encode($post->create_order($d),  JSON_PRETTY_PRINT);
            break;

            case 'update_order':
                $d = json_decode(file_get_contents("php://input"));
				
                echo json_encode($post->update_order($d),  JSON_PRETTY_PRINT);
            break;

            case 'delete_order':
			
			
                $d = json_decode(file_get_contents("php://input"));
                echo json_encode($post->delete_order($d),  JSON_PRETTY_PRINT);
            break;

        }
        
        break;
        
        
    case 'GET':
            
        switch($req[0]) 
        {
            case 'all_orders':
                $d = $_REQUEST;
				
                echo json_encode($get->all_orders($d),  JSON_PRETTY_PRINT);
			 break;
			case 'item_statuses_count':
                 $d = $_REQUEST;
				
                echo json_encode($get->item_statuses_count($d),  JSON_PRETTY_PRINT); 
            break;
            
            case 'all_products':
                $d = json_decode(file_get_contents("php://input"));
                echo json_encode($get->all_products($d),  JSON_PRETTY_PRINT);
            break;
			
			 case 'get_products':
               if(isset($_REQUEST['id'])){
					$d = json_decode(file_get_contents("php://input"));
					echo json_encode($get->get_product_byId($d , $_REQUEST['id']),  JSON_PRETTY_PRINT);
				}
            break;
			
            case 'get_tax_rates':
               
                $d = json_decode(file_get_contents("php://input"));
                echo json_encode($get->get_tax_rates($d),  JSON_PRETTY_PRINT);
            break;
			
			case 'get_order':
			
				
				
			
			
				if(isset($_REQUEST['id'])){
					$d = json_decode(file_get_contents("php://input"));
					echo json_encode($get->get_order_byId($d , $_REQUEST['id']),  JSON_PRETTY_PRINT);
				}
                
            break;
        }

     

}


?>
   


