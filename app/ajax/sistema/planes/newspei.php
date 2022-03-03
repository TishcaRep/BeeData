<?php
if(empty($_POST['id']))
{
  teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
try{
  $order = \Conekta\Order::create(
    array(
      "line_items" => array(
        array(
          "name" => "Tacos",
          "unit_price" => 1000,
          "quantity" => 12
        )//first line_item
      ), //line_items
      "shipping_lines" => array(
        array(
          "amount" => 1500,
          "carrier" => "FEDEX"
        )
      ), //shipping_lines - physical goods only
      "currency" => "MXN",
      "customer_info" => array(
        "name" => "Fulanito Pérez",
        "email" => "fulanito@conekta.com",
        "phone" => "+5218181818181"
      ), //customer_info
      "shipping_contact" => array(
        "address" => array(
          "street1" => "Calle 123, int 2",
          "postal_code" => "06100",
          "country" => "MX"
        )//address
      ), //shipping_contact - required only for physical goods
      "charges" => array(
          array(
              "payment_method" => array(
                "type" => "spei"
              )//payment_method
          ) //first charge
      ) //charges
    )//order
  );
} catch (\Conekta\ParameterValidationError $error){
  echo $error->getMessage();
} catch (\Conekta\Handler $error){
  echo $error->getMessage();
}
echo "ID: ". $order->id."\n";
echo "Bank: ". $order->charges[0]->payment_method->receiving_account_bank."\n";
echo "CLABE: ". $order->charges[0]->payment_method->receiving_account_number."\n";
echo "$". $order->amount/100 . $order->currency."\n";
echo "Order";
