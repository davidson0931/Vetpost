<?php  


    require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/vendor/autoload.php';
	use Automattic\WooCommerce\Client;
	



	require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/models/Post.php');
	require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '/models/Get.php');
	// require_once(plugin_dir_path( __FILE__ ));

    $woocommerce = new Client(
        'https://vetpost.co.nz/',
        'ck_c54695b7ff934f8ae0c294c9fa90bfd6811066e9',
        'cs_a5efc6d23d65669d9096a12025f1ef7cfea16f8e',
        [
            'version' => 'wc/v3',
        ]
        );




?>