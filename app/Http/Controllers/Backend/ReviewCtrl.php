<?php

namespace App\Http\Controllers\Backend;
use App\Rental\Models\Review;
use Illuminate\Http\Request;
class ReviewCtrl extends BackendCtrl{
    public function index(){
        $review = Review::orderBy('date','DESC')->get();
        return view('backend.reviews.index')->with(['review'=>$review]);
    }

    public function show($id){
        return view('backend.reviews.show');
    }
}
