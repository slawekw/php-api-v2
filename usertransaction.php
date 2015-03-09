<?
//wyœwietla historie tranzakcji
require 'bitcurex.php';

$apiKey='';
$secretKey='';

$b=new Bitcurex($apiKey,$secretKey);

$data=$b->getUserTransaction();
if($data->status=='ok'){
    foreach($data->data->trades as $row)echo'tid:'.$row->tid.' type:'.$row->type.'  amount:'.$row->amount.' price:'.$row->price.'<br/>';
}

?>