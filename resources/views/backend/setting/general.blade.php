@extends('templates.adminlte.main')
@section('content-admin')
<div class="row">
    <div class="col-md-12">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">General Settings</h3>
        </div>
        <div class="card-body">
          
          <form method="POST" enctype="multipart/form-data" action="{{ route('backend.setting.store') }}">
            {{ csrf_field() }}
          <div class="row">
            <div class="form-group col-md-4">
              <label for="app_name" class="form-label">Website Name</label>
              <input class="form-control" required="" name="name[app_name]" type="text" value="Fleet Manager">
            </div>
  
            <div class="form-group col-md-4">
              <label for="email" class="form-label">Email Address</label>
              <input class="form-control" required="" name="name[email]" type="text" value="master@admin.com">
            </div>
  
            <div class="form-group col-md-4">
              <label for="badd1" class="form-label">Business Address 1</label>
              <input class="form-control" required="" name="name[badd1]" type="text" value="Company Address 1">
            </div>
  
            <div class="form-group col-md-4">
              <label for="badd2" class="form-label">Business Address 2</label>
              <input class="form-control" required="" name="name[badd2]" type="text" value="Company Address 2">
            </div>
  
            <div class="form-group col-md-4">
              <label for="city" class="form-label">City</label>
              <input class="form-control" required="" name="name[city]" type="text" value="Bhavnagar">
            </div>
  
            <div class="form-group col-md-4">
              <label for="state" class="form-label">State</label>
              <input class="form-control" required="" name="name[state]" type="text" value="Gujarat">
            </div>
  
            <div class="form-group col-md-3">
              <label for="country" class="form-label">Country</label>
              <input class="form-control" required="" name="name[country]" type="text" value="India">
            </div>
  
            <div class="form-group col-md-2">
              <label for="dis_format" class="form-label">Distance Format</label>
              <select class="form-control" required="" name="name[dis_format]"><option value="km" selected="selected">km</option><option value="miles">miles</option></select>
            </div>
  
            <div class="form-group col-md-3">
              <label for="language" class="form-label">Language</label>
              <select id="name[language]" name="name[language]" class="form-control" required="">
                <option value="">-</option>
                                                                                                    <option value="Albanian-al"> Albanian </option>
                                                          
                <option value="English-en" selected=""> English</option>
                <option value="French-fr"> French </option>
                <option value="German-de"> German </option>
                <option value="Italian-it"> Italian </option>
                <option value="Portuguese-pt"> Portuguese </option>
                <option value="Spanish-es"> Spanish </option>
                <option value="Traditional Chinese-zh"> Traditional Chinese </option>
              </select>
            </div>
  
            <div class="form-group col-md-4">
              <label for="time_interval" class="form-label">Default Service Item interval</label>
  
                <div class="input-group mb-3">
                  <input class="form-control" required="" min="1" name="name[time_interval]" type="number" value="30">
                  <div class="input-group-append">
                    <span class="input-group-text">day(s)</span>
                  </div>
                </div>
            </div>
  
            <div class="form-group col-md-3">
              <label for="currency" class="form-label">Currency Symbol</label>
              <input class="form-control" required="" name="name[currency]" type="text" value="₹">
            </div>
  
            <div class="form-group col-md-3">
              <label for="tax_no" class="form-label">Tax No</label>
              <input class="form-control" required="" name="name[tax_no]" type="text" value="ABCD8735XXX">
            </div>
  
            <div class="form-group col-md-3">
              <label for="icon_img"> Icon Image</label>
                          <button type="button" class="btn btn-success view1 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="fleet.image" style="margin-bottom: 5px">
              View            </button>
                          <div class="input-group input-group-sm">
              <input name="icon_img" type="file">
              </div>
            </div>
            <div class="form-group col-md-3">
              <label for="logo_img"> Logo Image</label>
                          <button type="button" class="btn btn-success view2 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="fleet.image" style="margin-bottom: 5px">
              View            </button>
                          <div class="input-group input-group-sm">
                <input name="logo_img" type="file">
              </div>
            </div>
  
            <div class="form-group col-md-12">
              <label for="invoice_text" class="form-label">Invoice Text</label>
              <textarea class="form-control" name="name[invoice_text]" cols="30" rows="3">Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.</textarea>
            </div>
          </div>
        </form></div>
        <div class="card-footer">
          <div class="col-md-2">
            <div class="form-group">
              <input type="submit" class="form-control btn btn-success" value="Save">
            </div>
          </div>
        </div>
        
        </div>
      </div>
    </div>
  
@endsection