@extends('layouts.limitless.main')
@section('title')
<h4><span class="font-weight-semibold">Pengaturan Umum</span></h4>
@endsection
@section('breadcrumb')
<a href="{{ route('backend.dashboard.index')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
<a href="#" class="breadcrumb-item">Pengaturan</a>
<span class="breadcrumb-item active">Umum</span>
@endsection

@section('content-admin')

    
    <form role="form" method="POST" enctype="multipart/form-data" action="{{ route('backend.setting.store') }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-default">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="card-header">
                        <h4 class="card-title">Setting</h4>
                    </div>
                    
                    <div class="card-body">
                            <div class="form-group">
                                <h5 class="semibold text-primary nm">Umum.</h5>
                                <p class="text-muted nm">Pengaturan Umum</p>
                                
                            </div>
                            <div class="form-group">
                                <label>Meta Title</label>
                                <input class="form-control" name="title" value="{{ array_get($setting, 'title', '') }}"></input>
                            </div>
                            <div class="form-group">
                                <label>Meta Deskripsi</label>
                                <input class="form-control" name="deskripsi" value="{{ array_get($setting, 'deskripsi', '') }}"></input>
                            </div>
                            <div class="form-group">
                                <label>Meta Author</label>
                                <input class="form-control" name="author" value="{{ array_get($setting, 'author', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <label>Google Map Api</label>
                                <input class="form-control" name="google_map" value="{{ array_get($setting, 'google_map', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <h5 class="semibold text-primary nm">Footer.</h5>
                                <p class="text-muted nm">Pengaturan Umum</p>
                                
                            </div>
                            <div class="form-group">
                                <label>Pane Left</label>
                                <input class="form-control" name="footer_left_pane" value="{{ array_get($setting, 'footer_left_pane', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <label>Center Pane</label>
                                <input class="form-control" name="footer_center_pane" value="{{ array_get($setting, 'footer_center_pane', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <label>Right Pane</label>
                                <input class="form-control" name="footer_right_pane" value="{{ array_get($setting, 'footer_right_pane', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <h5 class="semibold text-primary nm">Lainnya.</h5>
                                <p class="text-muted nm">Pengaturan Lainnya</p>
                                
                            </div>
                            <div class="form-group">
                                <label>Base URL</label>
                                <input class="form-control" name="base_url" value="{{ array_get($setting, 'base_url', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <label>Facebook</label>
                                <input class="form-control" name="facebook_url" value="{{ array_get($setting, 'facebook_url', '') }}"></input>
                            </div>

                            <div class="form-group">
                                <label>Twitter</label>
                                <input class="form-control" name="twitter_url" value="{{ array_get($setting, 'twitter_url', '') }}"></input>
                            </div>

                        
                        <div class="form-group">
                            <button class="btn btn-primary" >Simpan</button>
                        </div>   
                    </div>
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-heading">
                        <h3 class="card-title">---</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                                <label>Google Map Api</label>
                                <input class="form-control" name="google_map_api" value="{{ array_get($setting, 'google_map_api', '') }}"></input>
                        </div>
                        <div class="form-group">
                                <label>Email 1</label>
                                <input class="form-control" name="email_one" value="{{ array_get($setting, 'email_one', '') }}"></input>
                        </div>
                        <div class="form-group">
                                <label>Latitude</label>
                                <input class="form-control" name="latitude" value="{{ array_get($setting, 'latitude', '') }}"></input>
                        </div>
                        <div class="form-group">
                                <label>Longitude</label>
                                <input class="form-control" name="longitude" value="{{ array_get($setting, 'longitude', '') }}"></input>
                        </div>
                        <div class="form-group">
                            <label>Tentang Kami</label>
                            <textarea rows="4" class="form-control tinymce_post" name="about_us">
                                {{ array_get($setting, 'about_us', '') }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label>Alamat Kami</label>
                            <textarea rows="4" class="form-control" name="our_address" id="our_address">
                                {{ array_get($setting, 'our_address', '') }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label>Email Kami</label>
                            <textarea rows="4" class="form-control tinymce_200" name="our_email">
                                {{ array_get($setting, 'our_email', '') }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label>Telepon Kami</label>
                            <input type="text" class="form-control" name="our_phone" value="{{ array_get($setting, 'our_phone', '') }}">
                        </div>

                        <div class="form-group">
                            <label>Support Kami</label>
                            <textarea rows="4" class="form-control tinymce_200" name="our_support" id="our_support">
                                {{ array_get($setting, 'our_support', '') }}
                            </textarea>
                        </div>    
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection
@section('style-head')
@parent
@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script>
    CKEDITOR.replace( 'our_address' );
    CKEDITOR.replace( 'our_support' );
</script>
@endsection
