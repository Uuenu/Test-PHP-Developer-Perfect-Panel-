<?php 

namespace api\models;

use Yii;
use yii\base\Model;


/**
 *
 */
class RatesRequest extends Model
{
	private const FEE = 0.02;

	private $selectedCur;
	private $url = 'https://blockchain.info/ticker';
	private $ticker = [];


	function __construct(string $selectedCur)
	{
		$this->selectedCur = $selectedCur;
		$this->setTicker();
	}

	private function setTicker()
	{
		$this->ticker = json_decode(file_get_contents($this->url), true);
	}
	
	public function DataResponse() 
	{
		
		$json_rates = [
            'status' => 'success',
            'code' => 200,
            'data' => [],
        ];

        $json_error = [

            'status' => 'error',
            'code' => 403,
            'message' => 'Invalid token',

        ];

		if ($this->selectedCur == 'all') {
			foreach ($this->ticker as $key => $value) {
           		$json_rates['data'][$key] = round($value['last'] - $value['last'] * self::FEE, 10);
        	}
        	asort($json_rates['data']);
        	return $json_rates;
		}else{
			foreach ($this->ticker as $key => $value) {
				if ($key == $this->selectedCur) {
					$json_rates['data'][$key] = round($value['last'] - $value['last'] * self::FEE, 10);
				}
			}
			return $json_rates;
		}

	}
}

?>