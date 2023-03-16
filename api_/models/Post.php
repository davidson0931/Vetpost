<?php

class postFunctions
{


    public function delete_order($data)
    {
        global $woocommerce;

        $response = $woocommerce->delete('orders/'.$data->order_number, ['force' => true]) ? array("code" => 200, "message" => "success") : array("message" => "failed");
        return $response;

    }


    public function update_order($data)
    {
        // return $data['orderId'];

        global $woocommerce;
		print_r($data);
        // $response = $woocommerce->put('orders/'.$data->orderId, $data->data) ? array("code" => 200, "message" => "success") : array("message" => "failed");
        // return $response;
        print_r($woocommerce->put('orders/'.$data->orderId, (array)$data->data));
    }



    public function create_order($data)
    {


        global $woocommerce;

        // STATIC POST ORDER (For testing Purposes)
        // $data = [
        //     'payment_method' => 'bacs',
        //     'payment_method_title' => 'Direct Bank Transfer',
        //     'set_paid' => true,
        //     'billing' => [
        //         'first_name' => 'John',
        //         'last_name' => 'Doe',
        //         'address_1' => '969 Market',
        //         'address_2' => '',
        //         'city' => 'San Francisco',
        //         'state' => 'CA',
        //         'postcode' => '94103',
        //         'country' => 'US',
        //         'email' => 'john.doe@example.com',
        //         'phone' => '(555) 555-5555'
        //     ],
        //     'shipping' => [
        //         'first_name' => 'John',
        //         'last_name' => 'Doe',
        //         'address_1' => '969 Market',
        //         'address_2' => '',
        //         'city' => 'San Francisco',
        //         'state' => 'CA',
        //         'postcode' => '94103',
        //         'country' => 'US'
        //     ],
        //     'line_items' => [
        //         [
        //             'product_id' => 93,
        //             'quantity' => 2
        //         ],
        //         [
        //             'product_id' => 22,
        //             'variation_id' => 23,
        //             'quantity' => 1
        //         ]
        //     ],
        //     'shipping_lines' => [
        //         [
        //             'method_id' => 'flat_rate',
        //             'method_title' => 'Flat Rate',
        //             'total' => '10.00'
        //         ]
        //     ]
        // ];

        $response = $woocommerce->post('orders', $data) ? array("code" => 200, "message" => "success") : array("message" => "failed");
        return $response;
    }



}

?>