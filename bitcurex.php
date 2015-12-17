<?

function cmpDESC($a, $b) {
    if ($a[0] == $b[0])
        return 0;
    return ($a[0] < $b[0]) ? -1 : 1;
}

function cmpASC($a, $b) {
    if ($a[0] == $b[0])
        return 0;
    return ($a[0] > $b[0]) ? -1 : 1;
}

Class Bitcurex {

    private $bitcurexUrl = 'https://api.bitcurex.com/v2/';
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey = '', $secretKey = '') {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    private function _getData($url = '', $type = 'GET', $array = null) {
        $ch = curl_init($this->bitcurexUrl . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        if ($type != 'GET') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'content-type:application/json',
                'accept:application/json'
            ));
            $string = str_replace('\\', '', json_encode($array));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);
        return $data;
    }

    private function hmac($data) {
        $string = str_replace('%2F', '/', http_build_query($data));
        return hash_hmac('sha512', $string, $this->secretKey);
    }

    public function getUserBalance($nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $hash = $this->hmac(array('nonce' => $nonce));
        $url = 'balance/' . $this->apiKey . '/' . $hash . '/?nonce=' . $nonce;
        return $this->_getData($url);
    }

    public function getUserOffers($market = 'pln', $nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $hash = $this->hmac(array('market' => $market, 'nonce' => $nonce));
        $url = 'offers/' . $market . '/' . $this->apiKey . '/' . $hash . '/?nonce=' . $nonce;
        return $this->_getData($url);
    }

    public function getAllOffers($market = 'pln', $nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $hash = $this->hmac(array('market' => $market, 'nonce' => $nonce));
        $url = 'all/offers/' . $market . '/' . $this->apiKey . '/' . $hash . '/?nonce=' . $nonce;
        return $this->_getData($url);
    }

    public function createOffer($market = 'pln', $limit, $volume, $offerType, $nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $array = array('limit' => $limit, 'market' => $market, 'nonce' => $nonce, 'offer_type' => $offerType, 'volume' => $volume);
        $array2 = array('limit' => $limit, 'nonce' => $nonce, 'offer_type' => $offerType, 'volume' => $volume);
        $hash = $this->hmac($array);
        $url = 'offer/' . $market . '/' . $this->apiKey . '/' . $hash;
        return $this->_getData($url, 'POST', $array2);
    }

    public function deleteOffer($offerId, $nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $array = array('offer_id' => "$offerId", 'nonce' => "$nonce");
        $array2 = array('nonce' => "$nonce", 'offer_id' => "$offerId");
        $hash = $this->hmac($array2);
        $url = 'offer/del/' . $this->apiKey . '/' . $hash;
        return $this->_getData($url, 'DELETE', $array);
    }

    public function getUserTransaction($market = 'pln', $fromts = 0, $nonce = null) {
        $nonce = $nonce == null ? $this->nonce() : $nonce;
        $hash = $this->hmac(array('market' => $market, 'nonce' => $nonce, 'txid' => $fromts));
        $url = 'trades/' . $market . '/' . $fromts . '/' . $this->apiKey . '/' . $hash . '/?nonce=' . $nonce;

        return $this->_getData($url);
    }

    public function orderBook($market = 'pln', $depth = 100) {
        $url = $market . '/' . $depth . '/orderbook';
        $data = $this->_getData($url);
        usort($data->bids, "cmpASC");
        usort($data->asks, "cmpDESC");
        return $data;
    }

    public function traders($market = 'pln', $fromts = 0) {
        $url = $market . '/' . $fromts . '/trades';
        return $this->_getData($url);
    }

    public function ticker($market = 'pln') {
        $url = $market . '/ticker';
        return $this->_getData($url);
    }

    protected function nonce() {
        return round(microtime(true) * 1000);
    }
}

?>