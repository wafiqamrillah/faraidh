<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concerns\Faraidh;

class FaraidhController extends Controller
{
    use Faraidh;

    public function index()
    {
		return redirect('/');
        // return view('api.index');
    }

    public function load(Request $request, $param = NULL)
    {
		$output = array();
		if ($param == 'dalil') {
			$nomor_dalil = json_decode($request->nomor_dalil);
			$output = array('result' => $this->getDalil($nomor_dalil));
		}else{
			$output = array('list' => $this->getAhliWaris());
		}
		return json_encode($output);
    }

	public function get(Request $request)
	{
		$datas 	= $request->result;
		$datas 	= json_decode($datas);
		$result = $this->process($datas);
		$output = array(
			'result' 		=> $result,
			'explanation' 	=> $datas,
			'response'		=> (count($result) > 0) ? 'success' : 'failed',
		);
		return json_encode($output);
	}
}
