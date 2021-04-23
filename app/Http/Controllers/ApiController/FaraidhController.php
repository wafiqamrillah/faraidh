<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Traits


class FaraidhController extends Controller
{
    public function index()
    {
        return "testing";
    }

    public function load(Request $request, $param = NULL)
    {
		$output = array();
		if ($param == 'dalil') {
			$nomor_dalil = json_decode($request->nomor_dalil);
			$output = array('result' => Faraidh::getDalil($nomor_dalil));
		}else{
			$output = array('list' => Faraidh::getAhliWaris());
		}
		return json_encode($output);
    }

	public function get(Request $request)
	{
		$datas 	= $request->result;
		$datas 	= json_decode($datas);
		$result = Faraidh::process($datas);
		$output = array(
			'result' 		=> $result,
			'explanation' 	=> $datas,
			'response'		=> (count($result) > 0) ? 'success' : 'failed',
		);
		return json_encode($output);
	}
}
