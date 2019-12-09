<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Jalan;
use App\JalanKondisi;
use Laracasts\Flash\Flash;
use PDF;
class LaporanCtrl extends BackendCtrl
{
    public function __construct(){
       
    }

    public function index(Request $request){
		if($request->isMethod('post')){
			$this->post($request);
		}
        return view('backend.setting.laporan');
	}
	
	public function tahunanIndex(){
        return view('backend.setting.laporantahunan');
    }

    public function post(Request $request){
        $timeawal = strtotime($request->daritgl);
        $timeakhir = strtotime($request->sampaitgl);
        if($timeakhir < $timeawal){
            Flash::error('Akhir Tanggal Melebihi awal tanggal '.$timeawal.' || '.$timeakhir);
            return redirect()->route('backend.laporan.index');
		}
		if($request->kecamatan == 0){
			$whereRaw = '1=1';
			$whereRawArr = array();
		}else{
			$whereRaw = 'jalan.kode_kec = ?';
			$whereRawArr = array($request->kecamatan);
		}
		$data = Jalan::join('wilayah_kecamatan','jalan.kode_kec','=','wilayah_kecamatan.kode_kec')
		//->whereBetween('jalan.created_at', [date('Y-m-d',$timeawal) , date('Y-m-d',$timeakhir)])
		//->where('jalan.kode_kec',$request->kecamatan)
		->whereRaw($whereRaw,$whereRawArr)
		->orderBy('jalan.no_ruas','ASC')
		->select('jalan.*','wilayah_kecamatan.nama_kecamatan')

		->get();
		
		session(['data'=>$data]);
        //$this->exportExcel();
        return view('backend.setting.laporan')->with('data',$data);
	}
	
	public function posttahunan(Request $request){
		if($request->kecamatan == 0){
			$whereRaw = '1=1';
			$whereRawArr = array();
		}else{
			$whereRaw = 'jalan_kondisi.kecamatan_dilalui LIKE ?';
			$whereRawArr = array('%'.$request->kecamatan.'%');
		}
		$data = JalanKondisi::whereRaw($whereRaw,$whereRawArr)
		->orderBy('jalan_kondisi.no_ruas','ASC')
		->select('jalan_kondisi.*')

		->get();
		
		session(['data'=>$data]);
        //$this->exportExcel();
        return view('backend.setting.laporantahunan')->with('data',$data);
    }

    public function exportExcel(Request $request){
		$excel2 = \PHPExcel_IOFactory::createReader('Excel2007');
		if($request->tipe == 'tahunan'){
			$excel2 = $excel2->load(public_path('assets').'/sample_jalan_tahunan.xlsx'); // Empty Sheet
			$excel2->setActiveSheetIndex(0);
			$column = 11;
			$no = 1;
			foreach(session('data') as $k => $v){
				$excel2->getActiveSheet()
				->setCellValue('A'.$column, $no)
				->setCellValue('B'.$column, $v->no_ruas)
				->setCellValue('C'.$column, $v->nama_ruas)
				->setCellValue('D'.$column, $v->kecamatan_dilalui)
				->setCellValue('E'.$column, $v->panjang)
				->setCellValue('F'.$column, $v->lebar)
				->setCellValue('G'.$column, $v->tahun)
				->setCellValue('H'.$column, $v->pembiayaan)
				->setCellValue('I'.$column, $v->biaya)
				->setCellValue('J'.$column, $v->jenis)
				->setCellValue('K'.$column, strip_tags($v->ket));
				$no++;
				$column++;
			}

			// Add column headers
			
			$this->Excel2007('Laporan Jalan');
			$objWriter = \PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
			//$objWriter->save('Laporan Jalan.xlsx');
			$objWriter->save('php://output');
			exit();
		}else{
			$excel2 = $excel2->load(public_path('assets').'/sample_jalan.xlsx'); // Empty Sheet
			$excel2->setActiveSheetIndex(0);
			$column = 11;
			$no = 1;
			foreach(session('data') as $k => $v){
				$excel2->getActiveSheet()
				->setCellValue('A'.$column, $no)
				->setCellValue('B'.$column, $v->no_ruas)
				->setCellValue('C'.$column, $v->nama_ruas)
				->setCellValue('D'.$column, $v->nama_kecamatan)
				->setCellValue('E'.$column, $v->panjang)
				->setCellValue('F'.$column, $v->lebar)
				->setCellValue('G'.$column, $v->ptjp_aspal)
				->setCellValue('H'.$column, $v->ptjp_beton)
				->setCellValue('I'.$column, $v->ptjp_kerikil)
				->setCellValue('J'.$column, $v->ptjp_tanah)
				->setCellValue('K'.$column, $v->ptk_baik_persentase)
				->setCellValue('L'.$column, $v->ptk_baik_km)
				->setCellValue('M'.$column, $v->ptk_sedang_persentase)
				->setCellValue('N'.$column, $v->ptk_sedang_km)
				->setCellValue('O'.$column, $v->ptk_rusakringan_persentase)
				->setCellValue('P'.$column, $v->ptk_rusakringan_km)
				->setCellValue('Q'.$column, $v->ptk_rusakberat_persentase)
				->setCellValue('R'.$column, $v->ptk_rusakberat_km)
				->setCellValue('S'.$column, $v->lhr_rata)
				->setCellValue('T'.$column, $v->akses_jalan)
				->setCellValue('U'.$column, $v->pangkal_latitude)
				->setCellValue('V'.$column, $v->pangkal_longitude)
				->setCellValue('W'.$column, $v->ujung_latitude)
				->setCellValue('X'.$column, $v->ujung_longitude)
				->setCellValue('Y'.$column, $v->no_ruas_pangkal)
				->setCellValue('Z'.$column, $v->no_ruas_ujung)
				->setCellValue('AA'.$column, $v->pembiayaan)
				->setCellValue('AB'.$column, $v->ket);
				$no++;
				$column++;
			}

			// Add column headers
			
			$this->Excel2007('Laporan Jalan');
			$objWriter = \PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
			//$objWriter->save('Laporan Jalan.xlsx');
			$objWriter->save('php://output');
			exit();
		}
        
	}
	
	public function importExcel(){
		$objPHPExcel = \PHPExcel_IOFactory::load(public_path('assets').'/data_jalan.xlsx');
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		//extract to a PHP readable array format
		$arr_data = [];
		$header = [];
		/*foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			echo "<br>The worksheet ".$worksheetTitle." has ";
			echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
			echo ' and ' . $highestRow . ' row.';
			echo '<br>Data: <table border="1"><tr>';
			for ($row = 1; $row <= $highestRow; ++ $row) {
				echo '<tr>';
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					$dataType = \PHPExcel_Cell_DataType::dataTypeForValue($val);
					echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}*/
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getCalculatedValue(); /*getValue()*/
			
			if ($row == 1) {
				$header[$row][$column] = $data_value;
			} else {
				$arr_data[$row][$column] = $data_value;
			}
			

		}
		return dd($arr_data);
		
		
	}

    public function Excel2003($namafile){
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
	}

	public function Excel2007($namafile){
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
	}

	public function getPdf(){
		$data = session('data');
 
		$pdf = PDF::loadView('layouts.elements.pdf',compact('data'))
			->setPaper('a4','landscape');
		return $pdf->stream('Laporan Jalan '.date('dmYHis'));
	}
}
