<?
//wyœwietla stan konta
require 'bitcurex.php';

$apiKey='';
$secretKey='';

$b=new Bitcurex($apiKey,$secretKey);


$data=$b->getUserBalance();
if($data->status=='ok'){
    echo 'PLN '.$data->data->pln.'<br/>';
    echo 'EUR '.$data->data->eur.'<br/>';
    echo 'USD '.$data->data->usd.'<br/>';
    echo 'BTC '.$data->data->btc.'<br/>';
}

?>