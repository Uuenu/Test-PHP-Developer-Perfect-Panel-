<?php 

namespace api\models;

use Yii;
use yii\base\Model;


/**
 * 
 */
class ConvertRequest extends Model
{
	private const FEE = 0.02;
	
	private $currency_from;
	private $currency_to;
	private $value;
	private $url = 'https://blockchain.info/ticker';
	private $ticker = [];


	function __construct($currency_from, $currency_to, $value)
	{
		$this->currency_from = $currency_from;
		$this->currency_to = $currency_to;
		$this->value = (float) $value;
		$this->setTicker();
	}

	private function calcConvertSum()
	{
		$result = 0;
		if ($this->currency_from == 'BTC') {
			$result = ($this->value * $this->ticker[$this->currency_to]['buy']);
		}else{
			$result = ($this->value / $this->ticker[$this->currency_from]['sell']);
		}
		$result -= $result * self::FEE;
		return $result;
	}

	private function setTicker()
	{
		$this->ticker = json_decode(file_get_contents($this->url), true);
	}

	public function DataResponse() 
	{
		
        $json_error = [

            'status' => 'error',
            'code' => 400,
            'message' => '',

        ];

        if (!$this->currency_from or !$this->currency_to or !$this->value) {
        	$json_error['message'] = 'Incorrect parameters';
        	return $json_error;
        }

        if((!array_key_exists($this->currency_from,$this->ticker) and $this->currency_from != 'BTC') or (!array_key_exists($this->currency_to,$this->ticker) and $this->currency_to != 'BTC')){
			$json_error['message'] = 'Undefined currency';
			return $json_error;
        }

        if ($this->currency_from == $this->currency_to) {
        	$json_error['message'] = 'Сurrencies should be different';
        	return $json_error;
        }

        if ($this->currency_from != 'BTC' and $this->currency_to != 'BTC') {
        	$json_error['message'] = 'Unsupported exchange';
        	return $json_error;
        }

        $json_convert = [
            'status' => 'success',
            'code' => 200,
            'data' => [
                'convert_from' => $this->currency_from,
                'convert_to' => $this->currency_to,
                'value' => $this->value,
                'converted_value' => round($this->calcConvertSum(), 10),
                'rate' => round($this->calcConvertSum()/$this->value, 10),
            ],
        ];

        if ($this->value < 0.01) {
        	$json_error['message'] = 'Value should be more than 0.01';
        	$json_error['code'] = 403;
        	return $json_error;
        }else{
        	return $json_convert;
        }
	}

	public function rules()
    {
        return [
            // currency_from, currency_to, and value are required
            [['currency_from', 'currency_to', 'value'], 'required'],
        ];
    }

}


?>