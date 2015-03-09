<?
//zlicza ile btc jest w bid a ile w ask, orderbook jest ograniczony lepiej u¿ywaæ getAllOffers
require 'bitcurex.php';

$b=new Bitcurex();
$orderBook=$b->orderBook();

$sumBidVolumen=0;
$sumAskVolumen=0;

foreach($orderBook->bids as $row)
    $sumBidVolumen+=$row[1];

foreach($orderBook->asks as $row)
    $sumAskVolumen+=$row[1];

echo'Sum bid:'.$sumBidVolumen.' <br/> Sum ask:'.$sumAskVolumen;
?>