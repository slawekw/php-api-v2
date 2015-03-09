<?
//zwraca obrót miêdzy okresem czasu w btc i fiat
require 'bitcurex.php';

$b = new Bitcurex();

$start = time() - 24 * 60 * 60 * 31;
$end = time();

$trades = $b->traders('pln', $start);

if ($trades->status == 'ok') {
    
    $volumen = 0;
    $volumenFiat = 0;
    
    foreach ($trades->data->trades as $row){
        $volumen+=$row->ts <= $end ? $row->amount : 0;
        $volumenFiat+=$row->ts <= $end ? $row->amount*$row->price : 0;
    }

    echo'Volumen between ' . $start . ' and ' . $end . ' is ' . $volumen.' BTC and '.$volumenFiat.' FIAT';
}
?>