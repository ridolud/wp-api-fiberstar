<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
* Class API Service
* Version: 0.0.1
* Author: ridolud
*/
class ApiFiberstar
{
	protected $url = "http://fiberstar.co.id/api/coverages.php/coverage";
	public $kota;
	public $kecamatan;
	public $kelurahan;
	public $jalan;
	public $limit = 8;

	function __construct()
	{
		//
	}

	protected function build($url)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return [];
		} else {
		  return $response;
		}
	}

	public function generateData($action = '', $search = null)
	{
		$url = $this->url . $action;
		$data = json_decode($this->build($url));
		$ristict_value = [ 'Not Listed', 'Pilih Kota', 'Pilih Kecamatan', 'Pilih Kelurahan', 'Pilih Jalan' ];
		$new_data = [];
		foreach ($data as $key => $value) {
			// if( strpos( $value->name, $search ) !== false ){
			// 	array_push($new_data, $value);
			// }
			if(in_array($value->id, $ristict_value)){
				unset($data[$key]);
			}
		}
		if(is_null($search)) { $new_data = $data; }
		// return array_slice($new_data, 0, $this->limit);
		return $new_data;
		
	}

	public function getKota($search = null)
	{
		return $this->generateData('', $search);
	}

	public function getKecamatan($id_kota, $search = null)
	{
		return $this->generateData('/'. rawurlencode($id_kota), $search);
	}

	public function getKelurahan($id_kota, $id_kecamatan, $search = null)
	{
		return $this->generateData('/'. rawurlencode($id_kota) . '/' . rawurlencode($id_kecamatan), $search);
	}

	public function getJalan($id_kota, $id_kecamatan, $id_kelurahan, $search = null)
	{
		return $this->generateData('/'. rawurlencode($id_kota) . '/' . rawurlencode($id_kecamatan) . '/' .rawurlencode($id_kelurahan), $search);
	}

}


