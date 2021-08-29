<?php

namespace App\Models\Concerns;

use App\Models\Concerns\Features;

trait Faraidh
{
    use Features;
	protected function process($data)
	{
		//Mendapatkan siapa Ahli Warisnya
			$data = $this->getAhliWaris($data);
		//Membuat angka waris
			$data = $this->getAngkaWaris($data);
		//Mengurut angka waris dari besar ke kecil
			usort($data, function($a, $b){ return ($a->angka_waris > $b->angka_waris) ? -1 : 1; });
		//Mengadukan semua angka waris
			$data = $this->getJadwalFaraidh($data);
		//Hasil mentah yang lebih dari satu jenis, diberi kelas
			$data = $this->getKelas($data);
		//Mengambil kelas dengan kelas terkecil
			foreach ($data as $key => $value) {
				asort($data[$key]->kelas);
				foreach ($data[$key]->kelas as $kelas => $where) {
					$data[$key]->hasil_matang = $data[$key]->hasil_mentah[$kelas];
					ksort($data[$key]->kelas);
					break;
				}
			}
		//Asal Mas'alah
			$data = $this->getAsalMasalah($data);
		//Dalil
			$data = $this->getDalil($data);
		return $data;
	}

	protected function getAhliWaris($param = NULL, $searchBy = 'key')
	{
		$list = array(
			(object)['key' => 'Anak Laki-Laki', 																											'value' => 'A'],
			(object)['key' => 'Cucu Laki-Laki (Anak dari Anak Laki-Laki)', 														'value' => 'B'],
			(object)['key' => 'Ayah', 																																'value' => 'C'],
			(object)['key' => 'Saudara Laki-Laki Seibu Seayah', 																			'value' => 'D'],
			(object)['key' => 'Saudara Laki-Laki Seayah', 																						'value' => 'E'],
			(object)['key' => 'Kakek (Ayah dari Ayah)', 																							'value' => 'F'],
			(object)['key' => 'Keponakan Laki-Laki (Anak dari Saudara Laki-Laki Seibu Seayah)', 			'value' => 'G'],
			(object)['key' => 'Keponakan Laki-Laki (Anak dari Saudara Laki-Laki Seayah)', 						'value' => 'H'],
			(object)['key' => 'Paman Seibu Seayah (Saudara dari Ayah)', 															'value' => 'J'],
			(object)['key' => 'Paman Seayah (Saudara dari Ayah)', 																		'value' => 'K'],
			(object)['key' => 'Saudara Laki-Laki Sepupu Seibu Seayah (Anak dari Paman Seibu Seayah)', 'value' => 'L'],
			(object)['key' => 'Saudara Laki-Laki Sepupu Seayah (Anak dari Paman Seayah)', 						'value' => 'M'],
			(object)['key' => 'Suami', 																																'value' => 'N'],
			(object)['key' => 'Saudara Laki-Laki Seibu (Anak dari Ibu)', 															'value' => 'O'],
			(object)['key' => 'Istri', 																																'value' => 'P'],
			(object)['key' => 'Anak Perempuan', 																											'value' => 'Q'],
			(object)['key' => 'Cucu Perempuan (Anak dari Anak Laki-Laki)', 														'value' => 'R'],
			(object)['key' => 'Ibu', 																																	'value' => 'S'],
			(object)['key' => 'Nenek dari Ayah', 																											'value' => 'T'],
			(object)['key' => 'Nenek dari Ibu', 																											'value' => 'U'],
			(object)['key' => 'Saudara Perempuan Seibu Seayah', 																			'value' => 'V'],
			(object)['key' => 'Saudara Perempuan Seayah', 																						'value' => 'W'],
			(object)['key' => 'Saudara Perempuan Seibu', 																							'value' => 'X'],
		);
		if ($param != NULL) {
			$search		= $searchBy;
			$searchBy = ($searchBy == 'key') ? 'value' : 'key';
			if (is_array($param)) {
				foreach ($param as $key => $value) {
					$list_key = array_search($value->waris, array_column($list, $searchBy));
					$param[$key]->ahli_waris = $list[$list_key]->$search;
				}
			} else {
				$list_key = array_search($param, array_column($list, $searchBy));
				$param		= $list[$list_key]->$search;
			}
			return $param;
		} else {
			usort($list, function($a, $b){ return strcmp($a->key, $b->key); });
			return $list;
		}

	}

	protected function getAngkaWaris($param=NULL, $total=1)
	{
		$list = (object)[
			'A' =>	(object)['waris' => 'A', 'satu' => '31', 'lebih' => '31'	, 'golongan' => 'ashobah', 'hak' => '',	],
			'B' =>	(object)['waris' => 'B', 'satu' => '30', 'lebih' => '30'	, 'golongan' => 'ashobah', 'hak' => '',	],
			'C' =>	(object)['waris' => 'C', 'satu' => '24', 'lebih' => ''		, 'golongan' => 'ashobah', 'hak' => 'dzawil furudh',	],
			'D' =>	(object)['waris' => 'D', 'satu' => '20', 'lebih' => '21'	, 'golongan' => 'ashobah', 'hak' => '',	],
			'E' =>	(object)['waris' => 'E', 'satu' => '16', 'lebih' => '17'	, 'golongan' => 'ashobah', 'hak' => '',	],
			'F' =>	(object)['waris' => 'F', 'satu' => '22', 'lebih' => ''		, 'golongan' => 'ashobah', 'hak' => 'dzawil furudh',	],
			'G' =>	(object)['waris' => 'G', 'satu' => '6', 'lebih' => '6'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'H' =>	(object)['waris' => 'H', 'satu' => '5', 'lebih' => '5'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'J' =>	(object)['waris' => 'J', 'satu' => '4', 'lebih' => '4'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'K' =>	(object)['waris' => 'K', 'satu' => '3', 'lebih' => '3'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'L' =>	(object)['waris' => 'L', 'satu' => '2', 'lebih' => '2'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'M' =>	(object)['waris' => 'M', 'satu' => '1', 'lebih' => '1'		, 'golongan' => 'ashobah', 'hak' => '',	],
			'N' =>	(object)['waris' => 'N', 'satu' => '25', 'lebih' => ''		, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'O' =>	(object)['waris' => 'O', 'satu' => '11', 'lebih' => '13'	, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'P' =>	(object)['waris' => 'P', 'satu' => '23', 'lebih' => '23'	, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'Q' =>	(object)['waris' => 'Q', 'satu' => '28', 'lebih' => '29'	, 'golongan' => 'dzawil furudh', 'hak' => '',	],
			'R' =>	(object)['waris' => 'R', 'satu' => '26', 'lebih' => '27'	, 'golongan' => 'dzawil furudh', 'hak' => '',	],
			'S' =>	(object)['waris' => 'S', 'satu' => '9', 'lebih' => ''		, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'T' =>	(object)['waris' => 'T', 'satu' => '7', 'lebih' => ''		, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'U' =>	(object)['waris' => 'U', 'satu' => '8', 'lebih' => ''		, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
			'V' =>	(object)['waris' => 'V', 'satu' => '19', 'lebih' => '18'		, 'golongan' => 'dzawil furudh', 'hak' => '',	],
			'W' =>	(object)['waris' => 'W', 'satu' => '15', 'lebih' => '14'		, 'golongan' => 'dzawil furudh', 'hak' => '',	],
			'X' =>	(object)['waris' => 'X', 'satu' => '10', 'lebih' => '12'	, 'golongan' => 'dzawil furudh', 'hak' => array("ashobah ma'al ghoir", "ashobah bil ghoir"),	],
		];
		if ($param != NULL) {
			if(is_array($param)){
				foreach ($param as $key => $value) {
					$waris = $value->waris;
					$param[$key]->angka_waris = ($value->jumlah > 1) ? $list->$waris->lebih : $list->$waris->satu;
					$param[$key]->golongan = $list->$waris->golongan;
					$param[$key]->golongan = $list->$waris->hak;
				}
				$result = $param;
			}else{
				$result = (object)['angka_waris' => ($total > 1) ? $list->$param->lebih : $list->$param->satu, 'golongan' => $list->$param->golongan, 'hak' => $list->$param->hak];
			}
			return $result;
		} else {
			return $list;
		}

	}

	protected function getJadwalFaraidh($array=array())
	{
		$list = (object)[
			'1'  => (object)['angka_waris' => 1, 	'hasil_mentah' => array('A'			, ''		, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'2'  => (object)['angka_waris' => 2, 	'hasil_mentah' => array('A'			, 'Mj'	, ''		, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'3'  => (object)['angka_waris' => 3, 	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, ''		, 'Mj'	, 'Mj'	, 'Mj'	, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'4'  => (object)['angka_waris' => 4, 	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, ''		, 'Mj'	, 'Mj'	, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'5'  => (object)['angka_waris' => 5, 	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, ''		, 'Mj'	, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'6'  => (object)['angka_waris' => 6, 	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, ''		, 'A'		, 'A'		, 'A'			, 'A'		, 'A'		, 'A'			, 'A'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'Mj'	, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'7'  => (object)['angka_waris' => 7, 	'hasil_mentah' => array('1/6'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, ''		, 'S<-'	, 'Mj'		, '1/6'	, '1/6'	, '1/6'		, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, '1/6'	, '1/6'	, 'Mj'		, '1/6'	, '1/6'		, '1/6'		, '1/6'		, '1/6'		, '1/6'	, '1/6'	)],
			'8'  => (object)['angka_waris' => 8, 	'hasil_mentah' => array('1/6'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'S<-'	, ''		, 'Mj'		, '1/6'	, '1/6'	, '1/6'		, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, '1/6'	, '1/6'	, '1/6'		, '1/6'	, '1/6'		, '1/6'		, '1/6'		, '1/6'		, '1/6'	, '1/6'	)],
			'9'  => (object)['angka_waris' => 9, 	'hasil_mentah' => array('1/3'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'Mj'	, 'Mj'	, ''			, '1/3d', '1/3d', '1/6Ar'	, '1/6Ar'	, '1/6Ar'	, '1/3d', '1/3d', '1/6Ar'	, '1/6Ar'	, '1/3d', '1/3d', '1/6Ar'	, '1/3'	, '1/3a', '1/3b'	, '1/3c', '1/6'		, '1/6'		, '1/6'		, '1/6'		, '1/6'	, '1/6'	)],
			'10' => (object)['angka_waris' => 10,	'hasil_mentah' => array('1/6'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/3d'	, ''		, '1/6'	, '-'			, 'S<-'		, '1/6'		,  '1/6', '1/6'	, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, '1/6'	, 'Mj'		, '1/6'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	)],
			'11' => (object)['angka_waris' => 11,	'hasil_mentah' => array('1/6'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/3d'	, '1/6'	, ''		, 'S<-'		, '-'			, '1/6'		,  '1/6', '1/6'	, '1/6'		, '1/6'		, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, '1/6'	, 'Mj'		, '1/6'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	)],
			'12' => (object)['angka_waris' => 12,	'hasil_mentah' => array('1/3'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/6Ar'	, '-'		, 'S<-'	, ''			, 'S<-'		, '1/3'		,  '1/3', '1/3'	, '1/3'		, '1/3'		, '1/3'	, '1/3'	, '1/3'		, 'Mj'	, '1/3'	, 'Mj'		, '1/3'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	)],
			'13' => (object)['angka_waris' => 13,	'hasil_mentah' => array('1/3'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/6Ar'	, 'S<-'	, '-'		, 'S<-'		, ''			, '1/3'		,  '1/3', '1/3'	, '1/3'		, '1/3'		, '1/3'	, '1/3'	, '1/3'		, 'Mj'	, '1/3'	, 'Mj'		, '1/3'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	)],
			'14' => (object)['angka_waris' => 14,	'hasil_mentah' => array('2/3'		, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, '1/6'	, '1/6'	, '1/6Ar'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, ''			, '-'		, '1<-2', '1<-2'	, 'Mj'		, '1/6M', 'Mj'	, 'Mj'		, 'AII'	, '2/3'	, 'Mj'		, '2/3'	, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'15' => (object)['angka_waris' => 15,	'hasil_mentah' => array('1/2'		, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, '1/6'	, '1/6'	, '1/3d'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '-'			, ''		, '1<-2', '1<-2'	, 'Mj'		, '1/6M', 'Mj'	, 'Mj'		, 'AII'	, '1/2'	, 'Mj'		, '1/2'	, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'16' => (object)['angka_waris' => 16,	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/3d'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '1<-2'	, '1<-2', ''		, '-'			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A'		, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'17' => (object)['angka_waris' => 17,	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/6Ar'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '1<-2'	, '1<-2', '-'		, ''			, 'A.M'		, 'A.M'	, 'Mj'	, 'Mj'		, 'A'		, 'A'		, 'Mj'		, 'A'		, 'A.j'		, 'A.j'		, 'A.j'		, 'A.j'		, 'Mj'	, 'Mj'	)],
			'18' => (object)['angka_waris' => 18,	'hasil_mentah' => array('2/3'		, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, '1/6'	, '1/6'	, '1/6Ar'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, 'Mj'		, 'Mj'	, 'A.M'	, 'A.M'		, ''			, '-'		, '1<-2', '1<-2'	, 'A'		, '2/3'	, 'Mj'		, '2/3'	, 'A'			, 'A'			, 'A'			, 'A'			, 'Mj'	, 'Mj'	)],
			'19' => (object)['angka_waris' => 19,	'hasil_mentah' => array('1/2'		, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, 'A.M'	, '1/6'	, '1/6'	, '1/3d'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '1/6M'	, '1/6M', 'A.M'	, 'A.M'		, '-'			, ''		, '1<-2', '1<-2'	, 'AII'	, '1/2'	, 'Mj'		, '1/2'	, 'A'			, 'A'			, 'A'			, 'A'			, 'Mj'	, 'Mj'	)],
			'20' => (object)['angka_waris' => 20,	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/3d'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, '1<-2'	, '1<-2',	''		,	'-'			, 'A'		, 'A'		, 'Mj'		, 'AI'	, 'A'			, 'A'			, 'A'			, 'A'			, 'Mj'	, 'Mj'	)],
			'21' => (object)['angka_waris' => 21,	'hasil_mentah' => array('AI'		, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/6Ar'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, '1<-2'	, '1<-2',	'-'		,	''			, 'A'		, 'A'		, 'Mj'		, 'AI'	, 'A'			, 'A'			, 'A'			, 'A'			, 'Mj'	, 'Mj'	)],
			'22' => (object)['angka_waris' => 22,	'hasil_mentah' => array('1/6A'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/3'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'AII'		, 'AII'	, 'A'		, 'A'			, 'A'			, 'AII'	, 'A'		, 'A'			, ''		, '1/6A', 'Mj'		, '1/6A', '1/6A'	, '1/6A'	, '1/6A'	, '1/6A'	, '1/6'	, '1/6'	)],
			'23' => (object)['angka_waris' => 23,	'hasil_mentah' => array('1/4'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/3a'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '2/3'		, '1/2'	, 'A'		, 'A'			, '2/3'		, '1/2'	, 'A'		, 'A'			, '1/6A', ''		, '1/4'		, '-'		, '1/8'		, '1/8'		, '1/8'		, '1/8'		, '1/8'	, '1/8'	)],
			'24' => (object)['angka_waris' => 24,	'hasil_mentah' => array('As'		, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/3b'	, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'	, '1/4'	, ''			, 'As'	, '1/6As'	, '1/6As'	, '1/6As'	, '1/6As'	, '1/6'	, '1/6'	)],
			'25' => (object)['angka_waris' => 25,	'hasil_mentah' => array('1/2'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, 'A'		, '1/6'	, '1/6'	, '1/3c'	, '1/6'	, '1/6'	, '1/3'		, '1/3'		, '2/3'		, '1/2'	, 'A'		, 'A'			, '2/3'		, '1/2'	, 'AI'	, 'AI'		, '1/6A', '-'		, 'As'		, ''		, '1/4'		, '1/4'		, '1/4'		, '1/4'		, '1/4'	, '1/4'	)],
			'26' => (object)['angka_waris' => 26,	'hasil_mentah' => array('1/2'		, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'A.j'		, 'A.j'	, 'A.j'	, 'A.j'		, 'A'			, 'A'		, 'A'		, 'A'			, '1/6A', '1/8'	, '1/6As'	, '1/4'	, ''			, '-'			, '1/6'		, 'Mj'		, '1<-2', 'Mj'	)],
			'27' => (object)['angka_waris' => 27,	'hasil_mentah' => array('2/3'		, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'A.j'		, 'A.j'	, 'A.j'	, 'A.j'		, 'A'			, 'A'		, 'A'		, 'A'			, '1/6A', '1/8'	, '1/6As'	, '1/4'	, '-'			, ''			, '1/6'		, 'Mj'		, '1<-2', 'Mj'	)],
			'28' => (object)['angka_waris' => 28,	'hasil_mentah' => array('1/2'		, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'A.j'		, 'A.j'	, 'A.j'	, 'A.j'		, 'A'			, 'A'		, 'A'		, 'A'			, '1/6A', '1/8'	, '1/6As'	, '1/4'	, '1/6'		, '1/6'		, ''			, '-'			, '1/2'	, '1<-2')],
			'29' => (object)['angka_waris' => 29,	'hasil_mentah' => array('2/3'		, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, 'A.j'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'A.j'		, 'A.j'	, 'A.j'	, 'A.j'		, 'A'			, 'A'		, 'A'		, 'A'			, '1/6A', '1/8'	, '1/6As'	, '1/4'	, 'Mj'		, 'Mj'		,	'-'			, ''			, '2/3'	, '1<-2')],
			'30' => (object)['angka_waris' => 30,	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, '1/6'	, '1/8'	, '1/6'		, '1/4'	, '1<-2'	, '1<-2'	, '1/2'		, '2/3'		, ''		, 'Mj'	)],
			'31' => (object)['angka_waris' => 31,	'hasil_mentah' => array('A'			, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, 'Mj'	, '1/6'	, '1/6'	, '1/6'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, 'Mj'		, 'Mj'	, 'Mj'	, 'Mj'		, '1/6'	, '1/8'	, '1/6'		, '1/4'	, 'Mj'		, 'Mj'		, '1<-2'	, '1<-2'	, 'Mj'	, ''		)],
		];
		if (count($array)>0) {
			foreach ($array as $key => $value) {
				$angka_waris = $value->angka_waris;
				if ($key == 0) {
					$array[$key]->hasil_mentah[] = $list->$angka_waris->hasil_mentah[$key];
				} else {
					$totaldata_before = count($array) + ($key - count($array));
					for ($i=($totaldata_before - 1); $i >= 0; $i--) {
						$array[$key]->hasil_mentah[] = $list->$angka_waris->hasil_mentah[$array[$i]->angka_waris];
						if (count($array[$key]->hasil_mentah) > 1) {
							foreach ($array[$key]->hasil_mentah as $hasil_mentah => $nilai) {
								if(isset($array[$key]->hasil_mentah[$hasil_mentah+1])){
									if ($nilai == '1/3d' && $array[$key]->hasil_mentah[$hasil_mentah+1] == '1/3d') {
										$array[$key]->hasil_mentah = array('1/6Ar');
									}elseif (($nilai == '1/3a' && $array[$key]->hasil_mentah[$hasil_mentah+1] == '1/3b') || ($nilai == '1/3b' && $array[$key]->hasil_mentah[$hasil_mentah+1] == '1/3a')) {
										$array[$key]->hasil_mentah = array('1/IV');
									}elseif (($nilai == '1/3b' && $array[$key]->hasil_mentah[$hasil_mentah+1] == '1/3c') || ($nilai == '1/3c' && $array[$key]->hasil_mentah[$hasil_mentah+1] == '1/3b')) {
										$array[$key]->hasil_mentah = array('1/VI');
									}elseif (($nilai == 'A.M' && $array[$key]->hasil_mentah[$hasil_mentah+1] == 'A.j') || ($nilai == 'A.j' && $array[$key]->hasil_mentah[$hasil_mentah+1] == 'A.M')) {
										$array[$key]->hasil_mentah = array('Mj');
									}
								}
							}
						}
					}
				}
			}
			return $array;
		} else {
			return $list;
		}

	}

	protected function getKelas($param=NULL)
	{
		$list = (object)[
			'2/3' 				=> '18',
			'1/2' 				=> '17',
			'1/3' 				=> '16',
			'1/3a' 				=> '16',
			'1/3b' 				=> '16',
			'1/3c' 				=> '16',
			'1/3d' 				=> '16',
			'1/IV' 				=> '15',
			'1/4' 				=> '14',
			'As' 				=> '13',
			'1/6A'				=> '12',
			'1/6As'				=> '11',
			'1/6Ar'				=> '10',
			'1/VI'				=> '9',
			'1/6'				=> '8',
			'1/8'				=> '7',
			'S<-'				=> '6',
			'AI'				=> '5',
			'AII'				=> '4',
			'A'					=> '3',
			'Mj'				=> '2',
			'A.M'				=> '2',
			'A.j'				=> '2',
			'1<-2'				=> '1',
			'1/6IV'				=> '1',
			'-'				=> '',
		];
		if ($param != NULL) {
			if (is_array($param)) {
				foreach ($param as $key => $value) {
					foreach ($value->hasil_mentah as $hasil_mentah) {
						$param[$key]->kelas[] = $list->$hasil_mentah;
					}
				}
				$result = $param;
			}else{
				$result = $list->$param;
			}
			return $result;
		} else {
			return $list;
		}

	}

	protected function getAsalMasalah($param=NULL)
	{
		$base = array();
		$kpt = array();
		$i = 0;
		foreach ($param as $value) {
			if (!(in_array(substr($value->hasil_matang, 0, 1), array('M', 'A', '-', '<', 'S'))) && !($value->hasil_matang == '1<-2')) {
				$base[$i] = explode('/', $value->hasil_matang);
				if (is_numeric(substr($base[$i][1], 0, 1))) {
					$kpt[] = substr($base[$i][1], 0, 1);
				} else {
					$base[$i][1] = Features::RomanConversion($base[$i][1]);
					$kpt[] = $base[$i][1];
				}
			} else {
				$base[] = $value->hasil_matang;
			}
			$i++;
		}
		$kpt = Features::lcm($kpt);
		$sisa_bagian = $kpt;
		$total = array();
		for ($i=0; $i < count($base) ; $i++) {
			$countable[$i] = (is_array($base[$i])) ? (int)$kpt / (int)$base[$i][1] * (int)$base[$i][0] : 0;
			if($i == (count($base) - 1)){
				$sisa_bagian = $sisa_bagian - array_sum($countable);
				for ($j=0; $j < count($base) ; $j++) {
					$param[$j]->asalmasalah = $kpt;
					if(is_array($base[$j])){
						$param[$j]->bagian = (int)$kpt / (int)$base[$j][1] * (int)$base[$j][0];
					}else{
						if ($base[$j] == 'A' || $base[$j] == 'As') {
							$param[$j]->bagian = $sisa_bagian;
						}elseif ($base[$j] == 'Mj') {
							$param[$j]->bagian = 0;
						}else {
							if (in_array($param[$j]->hasil_matang, array('S<-', '1<-2'))) {
								if(isset($param[($j - 1)])){
									$bagian_sebelumnya = $param[($j-1)]->bagian;
									if ($param[$j]->hasil_matang == 'S<-') {
										$param[$j]->bagian = $bagian_sebelumnya;
									}elseif($param[$j]->hasil_matang == '1<-2'){
										$param[$j]->bagian = $bagian_sebelumnya/2;
									}
								}else{
									$param[$j]->bagian = 0;
								}
							}else{
								$param[$j]->bagian = $base[$j];
							}
						}
					}
					$total[] = $param[$j]->bagian;
				}
			}
		}
		$asalmasalah = $kpt;
		// Check Asal Masalah
		if ($asalmasalah <> array_sum($total)) {
			$max = max($total);
			for ($i=0; $i <= $max; $i++) {
				if($asalmasalah % $max == 0){
					break;
				}
				$max = $max + 1;
			}
			$asalmasalah = $asalmasalah * $max;
			foreach ($param as $key => $value) {
				$value->asalmasalah = $asalmasalah;
				if (in_array($value->hasil_matang, array('S<-', '1<-2'))) {
					if(isset($param[($key - 1)])){
						$jumlah_orang = $param[($key - 1)]->jumlah + $value->jumlah;
						$value->bagian = number_format(($value->hasil_matang == 'S<-') ? $param[($key-1)]->bagian/$jumlah_orang : $param[($key-1)]->bagian/3, 0, '.', ',');
						$param[($key-1)]->bagian = $param[($key-1)]->bagian - $value->bagian;
					}else{
						$value->bagian = '';
					}
				}else{
					$value->bagian = $value->bagian*$max;
				}
			}
		}
		return $param;
	}

	protected function getDalil($param=NULL)
	{
		if($param != NULL){
			if (is_array($param)) {
				$table = array(
					'1' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'2' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'3' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'4' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'5' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'6' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'7' => (object)['S<-' => '3', '1<-2' => '3', '1/8' => '4', 'Mj' => '4', '1/4' => '2', '1/6' => '2'],
					'8' => (object)['S<-' => '3', '1<-2' => '3', '1/8' => '4', 'Mj' => '4', '1/4' => '2', '1/6' => '2'],
					'9' => (object)['1/3' => '5', '1/2' => '5', '2/3' => '24', '1/6Ar' => '24', '1/4' => '6', '1/6' => '6', '1/IV' => '7', '1/VI' => '7'],
					'10' => (object)['1/3' => '9', '1/2' => '9', 'S<-' => '9', '1<-2' => '9', '1/8' => '4', 'Mj' => '4', '1/4' => '8', '1/6' => '8'],
					'11' => (object)['1/3' => '9', '1/2' => '9', 'S<-' => '9', '1<-2' => '9', '1/8' => '4', 'Mj' => '4', '1/4' => '8', '1/6' => '8'],
					'12' => (object)['1/3' => '9', '1/2' => '9', 'S<-' => '9', '1<-2' => '9', '1/8' => '4', 'Mj' => '4', '1/4' => '8', '1/6' => '8'],
					'13' => (object)['1/3' => '9', '1/2' => '9', 'S<-' => '9', '1<-2' => '9', '1/8' => '4', 'Mj' => '4', '1/4' => '8', '1/6' => '8'],
					'14' => (object)['A' => '12', 'As' => '12', '1/3' => '14', '1/2' => '14', '2/3' => '10', '1/6Ar' => '10', 'S<-' => '11', '1<-2' => '11', '1/8' => '4', 'Mj' => '4', '1/4' => '13', '1/6' => '13', 'AI' => '26', 'AII' => '26'],
					'15' => (object)['A' => '12', 'As' => '12', '1/3' => '14', '1/2' => '14', '2/3' => '10', '1/6Ar' => '10', 'S<-' => '11', '1<-2' => '11', '1/8' => '4', 'Mj' => '4', '1/4' => '13', '1/6' => '13', 'AI' => '26', 'AII' => '26'],
					'18' => (object)['A' => '12', 'As' => '12', '1/3' => '14', '1/2' => '14', '2/3' => '10', '1/6Ar' => '10', 'S<-' => '11', '1<-2' => '11', '1/8' => '4', 'Mj' => '4', '1/4' => '13', '1/6' => '13', 'AI' => '26', 'AII' => '26'],
					'19' => (object)['A' => '12', 'As' => '12', '1/3' => '14', '1/2' => '14', '2/3' => '10', '1/6Ar' => '10', 'S<-' => '11', '1<-2' => '11', '1/8' => '4', 'Mj' => '4', '1/4' => '13', '1/6' => '13', 'AI' => '26', 'AII' => '26'],
					'16' => (object)['A' => '15', 'As' => '15', '1/8' => '4', 'Mj' => '4', 'AI' => '25', 'AII' => '25'],
					'17' => (object)['A' => '15', 'As' => '15', '1/8' => '4', 'Mj' => '4', 'AI' => '25', 'AII' => '25'],
					'20' => (object)['A' => '15', 'As' => '15', '1/8' => '4', 'Mj' => '4', 'AI' => '25', 'AII' => '25'],
					'21' => (object)['A' => '15', 'As' => '15', '1/8' => '4', 'Mj' => '4', 'AI' => '25', 'AII' => '25'],
					'22' => (object)['1/6A' => '23', '1/6As' => '23', 'A' => '29', 'As' => '29', '1/8' => '4', 'Mj' => '4', '1/4' => '27', '1/6' => '27'],
					'23' => (object)['1/8' => '17', 'Mj' => '17', '1/4' => '16', '1/6' => '16'],
					'24' => (object)['1/6A' => '28', '1/6As' => '28', 'A' => '29', 'As' => '29', '1/8' => '4', 'Mj' => '4', '1/4' => '27', '1/6' => '27'],
					'25' => (object)['1/3' => '18', '1/2' => '18', '1/4' => '19', '1/6' => '19'],
					'26' => (object)['1/3' => '22', '1/2' => '22', '2/3' => '21', '1/6Ar' => '21', 'S<-' => '20', '1<-2' => '20', '1/8' => '4', 'Mj' => '4', '1/4' => '12', '1/6' => '12'],
					'27' => (object)['1/3' => '22', '1/2' => '22', '2/3' => '21', '1/6Ar' => '21', 'S<-' => '20', '1<-2' => '20', '1/8' => '4', 'Mj' => '4', '1/4' => '12', '1/6' => '12'],
					'29' => (object)['1/3' => '22', '1/2' => '22', '2/3' => '21', '1/6Ar' => '21', 'S<-' => '20', '1<-2' => '20', '1/8' => '4', 'Mj' => '4', '1/4' => '12', '1/6' => '12'],
					'28' => (object)['1/3' => '12', '1/2' => '12', '2/3' => '21', '1/6Ar' => '21', 'S<-' => '20', '1<-2' => '20', '1/8' => '4', 'Mj' => '4', '1/4' => '12', '1/6' => '12'],
					'30' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
					'31' => (object)['A' => '1', 'As' => '1', '1/8' => '4', 'Mj' => '4'],
				);
				foreach ($param as $key => $value) {
					if (isset($table[$value->angka_waris])) {
						$hasil_matang = $value->hasil_matang;
						$nomor_dalil = (isset($table[$value->angka_waris]->$hasil_matang) ? $table[$value->angka_waris]->$hasil_matang : "");
					} else {
						$nomor_dalil = "";
					}
					$param[$key]->nomor_dalil = $nomor_dalil;
				}
				$result = $param;
			}else{
				$table = array(
					'1' => (object)[
						'arab' => '',
						'penjelasan' => array('Hadits dari Ibni Abas R.A., dari Nabi SAW, beliau bersabda: "Berikanlah bahagian tertentu pada mereka yang berhak menerimanya, adapun sisanya untuk ahli waris laki-laki yang terdekat hubungannya kepada si mayat".'),
						'sumber' => 'Riwayat Bukhori-Muslim. Al-Khotib Juz II Halaman 99',
					],
					'2' => (object)[
						'arab' => '',
						'penjelasan' => array('Dari sahabat Al-Mughiroh berkata : "Bahwa Nabi pernah memberi harta pusaka kepada seorang nenek 1/6 (seperenam)".'),
						'sumber' => 'Hadits Riwayat Imam Abu Dawud. Al-Khotib Juz 11 Halaman 107',
					],
					'3' => (object)[
						'arab' => '',
						'penjelasan' => array('Dari Ubaidah bin Shomit R.A. berkata : "Bahwasanya Nabi SAW. pernah memberikan benda pusaka untuk 2 orang nenek 1/6 (seperenam) baginya bersama-sama".'),
						'sumber' => 'Hadits Shohih menurut syarah Bukhori, Muslim, riwayat Al-Hakimi - Al-Khotib Juz II Halaman 107',
					],
					'4' => (object)[
						'arab' => '',
						'penjelasan' => array('Ia terhalang oleh ahli waris yang terdekat hubungannya dengan si mayat daripadanya, yaitu ahli waris yang angka warisannya lebih besar daripadanya pada waktu mengadu angka waris di tabel III (tabel Jadwal Faraidh).'),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'5' => (object)[
						'arab' => '',
						'penjelasan' => array("Jika yang meninggal itu tidak mempunyai anak (cucu, dengan Ijma'), pewarisnya hanya kedua orang tuanya saja. Maka ibunya mendapat 1/3 (sepertiga)."),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'6' => (object)[
						'arab' => '',
						'penjelasan' => array("Bagian 2 (dua) orang ibu dan bapak, masing-masing mendapatkan 1/6 (seperenam) dari peninggalannya, jika si mayat punya anak laki-laki (cucu laki-laki dari anak laki-laki. Kakek dalam masalah ini disamakan dengan bapak, dengan Ijma')."),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'7' => (object)[
						'arab' => '',
						'penjelasan' => array("Karena shohabat Umar bin Al-Khotob dalam dua masalah (mas'alah ghorowain) dengan begitu rupa, ibu diberi 1/3 (sepertiga) dari sisa, yang dalam kenyataannya (1/3 sisa itu) adalah 1/4, apabila mereka bersamaan dengan istri dan 1/6 apabila bersamaan dengan suami."),
						'sumber' => 'Kitab Syarkowi Juz II Halaman 172',
					],
					'8' => (object)[
						'arab' => '',
						'penjelasan' => array("Apabila ada seorang laki-laki atau perempuan Kalalah (tiada mempunyai anak dan ayah) meninggal, tetapi mempunyai saudara laki-laki atau dan perempuan seibu saja, maka masing-masingnya mendapat 1/6 (seperenam)."),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'9' => (object)[
						'arab' => '',
						'penjelasan' => array("Apabila saudara laki-laki atau dan perempuan seibu itu jumlahnya lebih dari 2 (dua) orangm maka mereka bersekutu dalam memperoleh 1/3 (sepertiga)."),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'10' => (object)[
						'arab' => '',
						'penjelasan' => array("Apabila saudara perempuan (sekandung/seayah saja) itu jumlahnya 2 (dua) orang (atau lebih), maka mereka mendapat 2/3 (dua pertiga) bagian dari barang peninggalannya."),
						'sumber' => 'Surat An-Nisa : 176',
					],
					'11' => (object)[
						'arab' => '',
						'penjelasan' => array("Jika mereka ahli waris itu beberapa saudara laki-laki dan saudara perempuan (sekandung/seayah saja), maka tiap seorang saudara laki-laki mendapat bagian sebanyak dua kali bagian seorang saudara perempuan (segendong-sepikul Jawa)."),
						'sumber' => 'Surat An-Nisa : 176',
					],
					'12' => (object)[
						'arab' => '',
						'penjelasan' => array('Dari Abi Musa R.A. berkata : "Bahwa Ibnu'." Mas'ud ".'benar-benar telah ditanya tentang masalah ahli waris seorang anak perempuan, seorang cucu perempuan dan seorang saudara perempuan. Maka jawabnya : tentu aku akan memberi pada 2 (dua) orang (anak perempuan dan cucu perempuan) itu menurut apa yang Rasulullah SAW telah pernah memberinya, ialah : untuk seorang anak perempuan mendapat 1/2 (separuh) untuk cucu perempuan mendapat 1/6 (seperenam) sebagai penyempurnaan 2/3 (dua pertiga). Maka sisanya untuk saudara perempuan".'),
						'sumber' => "Al-Hadits (Riwayat Imam Al-Bukhori). Diqiyaskan pada hadits ini, hukum beberapa orang cucu perempuan sama dengan seorang cucu perempuan, hukum beberapa orang saudara perempuan sama dengan seorang saudara perempuan, dengan Ijma' (Al-Qulyubi Juz III Halaman 153)",
					],
					'13' => (object)[
						'arab' => '',
						'penjelasan' => array('Maka seorang atau lebih saudara perempuan seayah saja bersamaan dengan seorang saudara perempuan sekandung mendapat 1/6 (seperenam).'),
						'sumber' => 'Diqiyaskan pada Al-Hadits dari: Abi Musa No.12 (Kitab Rohabiyah)',
					],
					'14' => (object)[
						'arab' => '',
						'penjelasan' => array("Jika ada seorang mayat tidak mempunyai anak (cucu dan ayah dengan Ijma'), tetapi mempunyai seorang saudara perempuan (sekandung/seayah), maka ia mendapat 1/2 (separuh) barang pusaka."),
						'sumber' => 'Surat An-Nisa : 176',
					],
					'15' => (object)[
						'arab' => '',
						'penjelasan' => array('Allah berfirman tentang keterangan ahli waris seorang saudara laki-laki sekandung/seayah, ia juga pewaris saudaranya perempuan, jika saudara perempuan yang meninggal itu tidak mempunyai anak (artinya anak laki-laki/cucu laki-laki dari anak laki-laki dan tidak mempunyai ayah).'),
						'sumber' => 'Surat An-Nisa : 176, hukum beberapa orang saudara laki-laki itu diqiyaskan (disamakan) dengan hukum seorang saudara laki-laki pada ayat tersebut tadi.',
					],
					'16' => (object)[
						'arab' => '',
						'penjelasan' => array("Dan seorang atau beberapa orang istri mendapat 1/4 (seperempat) dari peninggalanmu, jika kamu tidak mempunyai anak (atau cucu dari anak laki-laki dengan Ijma')."),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'17' => (object)[
						'arab' => '',
						'penjelasan' => array("Tetapi jika kamu (suami) mempunyai anak (atau cucu dari anak laki-laki dengan Ijma'), maka seorang atau beberapa orang istri-istrimu mendapat 1/8 (seperdelapan) dari peninggalanmu."),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'18' => (object)[
						'arab' => '',
						'penjelasan' => array('Dan kamu (suami) mendapat 1/2 (seperdua) harta peninggalan istri-istrimu jika mereka tidak mempunyai anak (atau cucu dari anak laki-laki dengan Ijma'."')."),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'19' => (object)[
						'arab' => '',
						'penjelasan' => array('Dan jika mereka (istri-istrimu) mempunyai anak (atau cucu dari anak laki-laki dengan Ijma'."')".' maka kamu (suami) mendapat 1/4 (seperempat) dari harta peninggalan mereka.'),
						'sumber' => 'Surat An-Nisa : 12',
					],
					'20' => (object)[
						'arab' => '',
						'penjelasan' => array("Allah telah menentukan tentang bagian pusaka untuk anak-anakmu, yaitu :",
										"Bagian seorang anak laki-laki mendapat sama dengan bagian 2 (dua) orang anak perempuan (segendong-sepikul, Jawa) hukum cucu dari anak laki-laki seperti hukum anak, begitulah pendapat Ijma'."),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'21' => (object)[
						'arab' => '',
						'penjelasan' => array("Maka jika anak perempuan saja itu ada 2 orang atau lebih, maka mereka itu mendapat 2/3 (dua pertiga) dari barang pusaka (hukum cucu perempuan dari anak laki-laki seperti hukum anak perempuan, begitulah pendapat Ijma')."),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'22' => (object)[
						'arab' => '',
						'penjelasan' => array('Dan jika anak perempuan itu hanya seorang saja, maka ia mendapat 1/2 (seperdua) dari peninggalan (seorang cucu perempuan dari anak laki-laki hukumnya seperti anak perempuan; mendapat 1/2; begitulah pendapat Ijma'."')."),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'23' => (object)[
						'arab' => '',
						'penjelasan' => array("Kakek mendapat 1/6 (seperenam) bagian sebagai bahagian tetap dan sisanya sebagai bahagian lunak dan jika bersamaan dengan saudara, maka kakek menghitung saudara yang terhalang bukan olehnya, kake sekurang-kurangnya mendapat 1/6 bagian, sehingga terulur jika bersisa 1/6 ke bawah, dan mendapat 1/6 bagian atau bagi rata jika bersisa 1/2 ke bawah, dan mendapat 1/3 sisa atau bagi rata jika bersisa 1/2 ke atas, dan mendapat 1/3 jumlah pusaka atau bagi rata jika tiada (sepi) ahli waris lain. Lihat tabel IX."),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'24' => (object)[
						'arab' => '',
						'penjelasan' => array('Jika yang meninggal itu mempunyai beberapa saudara, maka ibu mendapat 1/6 (seperenam)'),
						'sumber' => 'Surat An-Nisa : 11',
					],
					'25' => (object)[
						'arab' => '',
						'penjelasan' => array('Masalah Musytarikah adalah kumpulnya antara saudara seibu-seayah dengan saudara seibu saja, baik disertai seorang saudara perempuan seibu-seayah atau lebih banyak, atau tanpa saudara perempuan seibu-seayah, baik dalam masalah itu disertai ahli waris lainnya atau tidak.',
										'Dan jika jumlah bilangan dari saudara seibu-seayah itu lebih banyak daripada jumlah bagian saudara seibu saja sehingga kurang bahagian tiap seorang saudara se-ibu seayah daripada bahagian tiap seorang saudara seibu saja atau terjadi ahli waris menghabiskan pusaka, maka jadikanlah semua saudara itu anak seibu saja, agar supaya tidak gagal mendapat bahagian pusaka untuk saudara seibu-seayah.',
										'Karena saudara seibu-seayah itu lebih hak baginya, daripada saudara seibu saja, dengan alasan, saudara kandung tidak terhalang oleh anak perempuan atau oleh kakek, dan seorang saudara seibu-seayah itu berkumpul dengan anak perempuan, menjadi '."'".'ashobah, dan berkumpul dengan kakek dianggap saudaranya, mendapat separuh daripada bagian kakek, dan baginya separuh sebagai bagian tetap. Tidak ada saudara-seibu saja seperti saudara seibu-seayah malah mereka terhalang oleh anak perempuan atau oleh kakek, setengah dari umpama:',
										'I. (Pertama) : Jika ada mayat meninggalkan suami, ibu, seorang saudara seibu-seayah dan dua orang saudara seibu saja, maka tidak dapat apa-apa bagi seorang saudara seibu-seayah karena ahli waris telah menghabiskan pusaka, maka jadikanlah semua saudara itu anak seibu, dan bagian sepertiga pusaka yang jadi milik dua orang saudara seibu saja itu untuk tiga orang saudara seibu, jadi terbagi dengan bagian tertentu tidak dengan bagian lunak.',
										'II. (Kedua) : Jika ada mayat meninggalkan suami, ibu, dua orang saudara seibu-seayah, dan seorang saudara seibu saja, maka ada sisa unutk dua orang saudara seibu-seayah tadi, yaitu 1/6 bagian dan untuk seorang saudara seibu saja juga 1/6 bagian, jadi bagian seorang saudara seibu-seayah kurang daripada bagian seorang saudara seibu saja. Maka jadikanlah semua saudara itu anak seibu, dan kumpulkanlah 1/4 bagian yang untuk dua orang saudara seibu seayah dan 1/6 bagian untuk seorang saudara seibu saja; jadi 1/3 bagian pusaka dan hasil kumpulannya itu untuk tifa orang saudara seibu juga. Artinya terbagi dengan jalan bagian tertentu tidak dengan jalan bagian lunak, berdasarkan Firman Allah SWT : "Jika saudara seibu itu lebih dari dua orang, maka mereka bersekutu untuk mendapatkan 1/3 bagian dari peninggalan harta pusaka, jika simayat tidak meninggalkan anak atau cucu atau ayah". (Surat An-Nisa : 12).',
										'Masalah pertama dinamakan masalah Musytarikah Qubro dan yang kedua dinamakan masalah Musytarikah Shugro. Begitu juga dinamakan Musytarika Shugro, jika ada mayat meninggalkan : suami, 2 (dua) orang saudara seibu saja, dan 2 (dua) orang saudara seibu-seayah.'),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'26' => (object)[
						'arab' => '',
						'penjelasan' => array('Seorang saudara perempuan sekandung/seayah bersamaan dengan kakek tidak mempunyai bahagian tertentu, kecuali dalam masalah Akdariyah, yaitu : suami, ibu, kakek dan seorang saudara perempuan.'),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'27' => (object)[
						'arab' => '',
						'penjelasan' => array('(I). Ayah atau kakek baginya 1/6 bagian saja, jika si mayat mempunyai anak laki-laki atau cucu laki-laki.'),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'28' => (object)[
						'arab' => '',
						'penjelasan' => array('(II). Ayah atau kakek baginya 1/6 bagian sebagai tetap dan sisanya sebagai lunak, jika si mayat mempunyai : anak perempuan atau cucu perempuan. Berdasarkan Firman Allah SWT dalam surat An-Nisa : 11 yang artinya: "Bagi ayah dan ibu masing-masing 1/6 bagian, jika si mayat mempunyai anak".'),
						'sumber' => 'Risalah Jadwal Faraidh Praktis oleh Kyai Rochmadi',
					],
					'29' => (object)[
						'arab' => '',
						'penjelasan' => array('Ayah atau kakek mendapat sisa dari ibu saja, sebagai bagian lunak jika si mayat tidak mempunyai anak/cucu (baik laki-laki atau perempuan). Berdasarkan Firman Allah SWT : "Jika si mayat tidak mempunyai anak, maka pewarisnya hanya kedua orang : ibu dan ayah, bagi ibu 1/3 (sepertiga) pusaka (sisanya untuk ayah)."'),
						'sumber' => 'Surat An-Nisa : 11',
					],
				);
				$result = $table[$param];
			}
		}else{
			$result = (object)[
				'arab' => '',
				'penjelasan' => '',
				'sumber' => ''
			];
		}
		return $result;
	}
}
