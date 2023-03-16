<?php  


    require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/vendor/autoload.php';
	use Automattic\WooCommerce\Client;
	



	require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/models/Post.php');
	require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/models/Get.php');
	// require_once(plugin_dir_path( __FILE__ ));

    $woocommerce = new Client(
        'https://ndomain.ml/vetpost/',
        'ck_4c4f25c9f968df2732510305541b1d48ead13062',
        'cs_830b4920c6403d5f6e6d70cddb5a01d0e4680268',
        [
            'version' => 'wc/v3',
        ]
        );


?>