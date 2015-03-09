<?
//wyœwietla wszystkie otwarte pozycje u¿ytkownika
require 'bitcurex.php';

$apiKey='';
$secretKey='';

$b=new Bitcurex($apiKey,$secretKey);

$data=$b->getUserOffers();

if($data->status=='ok'){
    foreach($data->data as $row)echo'Type:'.$row->type.' currency:'.$row->currency.' volume:'.$row->volume.' limit:'.$row->limit.'<br/>';
}

?>