<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Map\RepositoryInterface as LayerRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Map\Informasi;
use App\Map\Layer;
use Validator;
use App\Asset\Misc\AssetHelper;

class LayerCtrl extends BackendCtrl
{

	private $layer;
	public function __construct(LayerRepository $lr, Request $r)
	{
		$this->layer = $lr;
		$this->r = $r;
	}
	public function index()
	{
		$layer = $this->layer->getlayer();

		return view('backend.layer.index')->with('layer', $layer);
	}

	public function create()
	{
		if (Gate::check('create.layer')) {
			$level = $this->GetDftrLevel();
			session()->forget('aksi');
			session(['aksi' => 'tambah']);
			$group = $this->layer->getallgroups();
			$role = $this->getRole();
			return view('backend.layer.tambah')->with('groups', $group)
				->with('level', $level)
				->with('role', $role);
		}
		return redirect()->route('backend.index')->with('flash.error', 'Anda Tidak diijinkan Mengakses Halaman ini');
	}

	public function edit($id)
	{
		// if (Gate::check('edit.layer')) {
			$level = $this->GetDftrLevel($id);
			$role = $this->getRole();
			session(['aksi' => 'edit']);
			$layer = $this->layer->find($id);
			$group = $this->layer->getallgroups();
			
			return view('backend.layer.tambah')->with('layer', $layer)->with('role', $role)->with('groups', $group)
			;
		// }
		// return redirect()->route('backend.index')->with('flash.error', 'Anda Tidak diijinkan Mengakses Halaman ini');

	}

	public function postLayer(Request $request)
	{
		\DB::beginTransaction();
		try {
			$validator = Validator::make($request->all(), Layer::$rules, Layer::$messages);

			$layer = $this->layer->postLayer($request, session('aksi'), $request->id);
			$layer->roles()->sync($request->role);
			DB::commit();
		} catch (Exception $e) {
			\DB::rollback();
			Session::flash('error', 'layer gagal disimpan!.');
		}

		return redirect()->route('backend.layer.index');
	}

	public function store($request)
	{
		$this->postLayer($request);
	}

	public function update($request)
	{
		$this->postLayer($request);
	}

	public function destroy($id)
	{
		if (Gate::check('delete.layer')) {
			$layer = $this->layer->delete($id);
			return redirect()->route('backend.layer.index')->with('flash.success', 'Layer Berhasil di Hapus..!!');
		}
		return redirect()->route('backend.index')->with('flash.error', 'Anda Tidak diijinkan Mengakses Halaman ini');
	}

    //Setting Layer Info
	public function getLayerinfo($id)
	{
		$admin = \Auth::user();
		$layer = $this->layer->find($id);
		$layer_ = $layer->kodelayer;
		$title = 'Pengaturan Layer';
		$field = json_decode($layer->jsonfield);

		return view('backend.layer.layerSettinglyr')
			->with('title', $title)
			->with('layers', $layer)
			->with('field', $field)
			->with('id', $id);
	}
	public function getLayerinfopopup($id, $idx, $layern)
	{
		$admin = \Auth::user();
		$layer = $this->layer->find($id);
		$identify = Informasi::where('layerid', '=', $idx)->where('namalayer', '=', $layern, 'AND')->first();
		$identify2 = $identify == null ? new Informasi : $identify;

		$field = json_decode($this->getFields($id, $idx));
		$title = 'Pengaturan Layer ' . $layern;

		return view('backend.layer.layerSettinglyrFtr')
			->with('judul', $title)
			->with('layers', $layer)
			->with('admin', $admin)
			->with('idx', $idx)
			->with('identify', $identify2)
			->with('url', url('/'))
			->with('field', $field);
	}
	public function postLayerinfopopup($id, $idx, $layern)
	{

		$fieldinfo = $this->getFieldinfos($this->r);
		// $medias = $this->getMedias();

		$desc = $this->r->display == 'keyvalue' ? $this->getDesc() : $this->r->description;
		$rules = array(
			'namalayer' => 'required',

		);
		$validator = Validator::make($this->r->all(), $rules);
		if ($validator->fails()) {
	    	// get the error messages from the validator
			$messages = $validator->messages();

	        // redirect our user back to the form with the errors from the validator
			return redirect()->route('backend.layer.index')
				->withErrors($validator);
		} else {
			$check = Informasi::where('layerid', '=', $idx)->where('namalayer', '=', $layern, 'AND')->first();
			if ($check === null) {
				$identify = new Informasi;
				$identify->title = $this->r->title;
				$identify->display = $this->r->display;
				$identify->description = $desc;
				$identify->namalayer = $this->r->namalayer;
				$identify->layerid = $this->r->layerid;
				$identify->keydata = $fieldinfo;
				// $identify->media = $medias;
				$identify->showattachments = $this->r->showattachments;

				$identify->save();
				$msg = 'tambah';
			} else {

				$identify = $check;
				$identify->title = $this->r->title;
				$identify->description = $desc;
				$identify->namalayer = $this->r->namalayer;
				$identify->layerid = $this->r->layerid;
				$identify->display = $this->r->display;
				$identify->keydata = $fieldinfo;
				// $identify->media = $medias;
				$identify->showattachments = $this->r->showattachments;

				$identify->save();
				$identify->touch();
				$msg = 'edit';
			}

			return redirect('backend/layer');
		}
	}
	public function getFields($id, $idx)
	{
		$layer = $this->layer->find($id);

		$layers = json_decode($layer->jsonfield);
		foreach ($layers as $key => $value) {
			if ($key == $idx) {
				$field = $value;
			}
		}

		return json_encode($field);
	}
	public function getFieldinfos($r)
	{
		$visible = $r->visible;
		$nf = $r->nf;
		$nalias = $r->nalias;
		$label = $r->label_field;
		$name = $r->name_field;
		$type = $r->type_field;
		$array = array();
		$array2 = array();
		if ($visible != null) {

			foreach ($name as $i => $value) {
				$v = 0;
				if (!isset($visible[$i])) {
					//array_push($visible, null);
					$visible[$i] = null;
				}
				$v = ($visible[$i] == null ? 0 : 1);

				$array['fieldName'] = $name[$i];
				$array['label'] = $label[$i];
				$array['visible'] = (bool)$v;
				$array['fieldType'] = $type[$i];
				array_push($array2, $array);
			}
		}

		return json_encode($array2);
	}
	public function getMedias()
	{
		$title = $this->r->title_m;
		$caption = $this->r->caption_m;
		$url = $this->r->url_m;
		$link = $this->r->link_m;
		$type = $this->r->type_m;

		$fields = $this->r->field;

		$array = array();
		$array2 = array();
		$value = array();
		$array['title'] = $title;
		$array['caption'] = $caption;
		$array['type'] = $type;
		if ($type == 'image') {
			$value['sourceURL'] = $url;
			$value['linkURL'] = $link;
		} else {
			$comma_separated = implode(",", $fields);
			$comma_separated = explode(",", $comma_separated);
			$value['fields_'] = $fields;
			$value['fields'] = $comma_separated;
		}


		$array['value'] = $value;
		array_push($array2, $array);
		return json_encode($array2);
	}
	public function getDesc()
	{
		$visible = $this->r->visible;
		$nf = $this->r->nf;
		$nalias = $this->r->nalias;
		$label = $this->r->label_field;
		$name = $this->r->name_field;
		$html = '<table>';

		foreach ($visible as $i => $value) {
			$html .= '<tr><td><b>' . $label[$i] . '</b></td><td>{' . $value . '}</td></tr>';
		}

		$html .= '</table>';

		return $html;
	}
	public function getLayeresrihapus($id)
	{
		$layer = Layer::find($id);
		$layer->jsonfield = null;
		$layer->save();
		return redirect('layers');
	}
	//================================
	public function GetDftrLevel($lvl = '')
	{
		$level = \App\Role::orderBy('id', 'asc')->get();
		$a = '';
		foreach ($level as $key => $value) {
			$ck = (strpos($lvl, "." . $value->id) === false) ? '' : 'checked';
			$a .= "<div class='checkbox'>";
			$a .= "<label class='checkbox-info'><input type='checkbox' name='role[]' class='styled' value='$value->name' $ck><span class='fa fa-check'></span> $value->id - $value->name</label>";
			$a .= "</div>";
		}
		return $a;
	}

	public function getVallevelmodul($layerid)
	{
		$detil = \App\RoleLayer::whereRaw('layer_id = ?', array($layerid))->get();
		$a = '';
		foreach ($detil as $key => $value) {
			$a .= '.' . $value->role_id;
		}
		return $a;
	}

	public function getLevel($layerid = '')
	{
		$levelform = \Input::get('level');
		$array = array();
		$array2 = array();
		if (empty($layerid)) {
			return false;
		}
		if ($levelform != null) {
			foreach ($levelform as $key => $value) {
				$array['layer_id'] = $layerid;
				$array['role_id'] = $value;

				array_push($array2, $array);
			}
			return $array2;
		}
	}

	public function getSettingUrl()
	{
		return view('backend.layer.layerSettingGantiUrlEsri');
	}

	public function postSettingUrl(Request $request)
	{
		$search = $request->search;
		$replace = $request->replace;
		$layers = \App\Map\Layer::orderBy('urutanlayer', 'asc')->where('tipelayer', 'ol')->get();
		$array = array();
		//dd($layers);
		foreach ($layers as $key => $l) {
			$array[$key] = str_replace($search, $replace, $l->urllayer);
			\DB::table('layer')->where('id', $l->id)->update(['urllayer' => $array[$key]]);
		}
		return redirect()->route('backend.layer.settingurl');

	}

	public function getRole()
	{
		return Role::where('name', '<>', 'superadmin')->get();
	}


}
