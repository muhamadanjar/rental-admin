<script id="details-mobil-template" type="text/x-handlebars-template">
    <div class="label label-info">Mobil {{ name }}'s</div>
        <table class="table details-table" id="mobil-{{id}}">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Deposit</th>
            </tr>
            </thead>
    </table>
</script>

<script id="details-transaksi-template" type="text/x-handlebars-template">
    <div class="label label-info">Transaksi <b>{{ no_transaksi }}</b></div>
        <div class="map_transaksi" id="map_transaksi"></div>
        <table class="table details-table" id="transaksi-{{id}}">
            <thead>
            <tr>
                <th>Driver</th>
                <th>Mobil</th>
                <th>Warna</th>
                <th>No Plat</th>
                <th>No Telp</th>
            </tr>
            </thead>
    </table>
</script>

<script id="form-bahasa-template" type="text/x-handlebars-template">
    {{#bahasa}}
    <div id="array_bahasa_{{ id }}" >
        <input type="hidden" class="form-control" name="jumlah_bahasa[]" value="1"/>
        <fieldset style="border-top:1px;">
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Bahasa yang dikuasai</label>
                <div class="col-xs-7 col-lg-5 inputGroupContainer">
                    <select class="form-control" name="bahasa_pencaker[]">
                        <option value="">Pilih Bahasa</option>
                        <option value="99" {{#if_eq bahasa_pencaker '99'}} selected {{else}}  {{/if_eq}}>Lainnya</option>
                        <option value="2" {{#if_eq bahasa_pencaker '2'}} selected {{else}}  {{/if_eq}} >Inggris</option>
                        <option value="1" {{#if_eq bahasa_pencaker '1'}} selected {{else}}  {{/if_eq}} >Perancis</option>
                        <option value="3" {{#if_eq bahasa_pencaker '3'}} selected {{else}}  {{/if_eq}} >Arab</option>
                        <option value="4" {{#if_eq bahasa_pencaker '4'}} selected {{else}}  {{/if_eq}} >Mandarin</option>
                        <option value="5" {{#if_eq bahasa_pencaker '5'}} selected {{else}}  {{/if_eq}} >Jerman</option>
                        <option value="7" {{#if_eq bahasa_pencaker '7'}} selected {{else}}  {{/if_eq}} >Korea</option>
                        <option value="6" {{#if_eq bahasa_pencaker '6'}} selected {{else}}  {{/if_eq}} >Jepang</option>
                    </select>
                </div>
                <div class="col-xs-2 col-lg-1 inputGroupContainer">
                    <a href="javascript:hapus_bahasa({{id}});" class="btn btn-primary"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Tingkat Kemampuan</label>
                <div class="col-xs-9 col-lg-6 selectContainer">
                    <select class="form-control" name="tingkat_kemampuan[]">
                        <option value="1" {{#if_eq tingkat_kemampuan '1'}} selected {{else}}  {{/if_eq}}>Aktif</option>
                        <option value="0" {{#if_eq tingkat_kemampuan '0'}} selected {{else}}  {{/if_eq}}>Pasif</option>
                    </select>
                </div>
            </div>
        </fieldset>
        
    </div>
    <hr/>
    {{/bahasa}}
    
</script>
<script id="form-tambahbahasa-template" type="text/x-handlebars-template">
    
    <div id="array_bahasa_{{ id }}" >
        <input type="hidden" class="form-control" name="jumlah_bahasa[]" value="1"/>
        <fieldset style="border-top:1px;">
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Bahasa yang dikuasai</label>
                <div class="col-xs-7 col-lg-5 inputGroupContainer">
                    <select class="form-control" name="bahasa_pencaker[]">
                        <option value="">Pilih Bahasa</option>
                        <option value="99" {{#if_eq bahasa_pencaker '99'}} selected {{else}}  {{/if_eq}}>Lainnya</option>
                        <option value="2" {{#if_eq bahasa_pencaker '2'}} selected {{else}}  {{/if_eq}} >Inggris</option>
                        <option value="1" {{#if_eq bahasa_pencaker '1'}} selected {{else}}  {{/if_eq}} >Perancis</option>
                        <option value="3" {{#if_eq bahasa_pencaker '3'}} selected {{else}}  {{/if_eq}} >Arab</option>
                        <option value="4" {{#if_eq bahasa_pencaker '4'}} selected {{else}}  {{/if_eq}} >Mandarin</option>
                        <option value="5" {{#if_eq bahasa_pencaker '5'}} selected {{else}}  {{/if_eq}} >Jerman</option>
                        <option value="7" {{#if_eq bahasa_pencaker '7'}} selected {{else}}  {{/if_eq}} >Korea</option>
                        <option value="6" {{#if_eq bahasa_pencaker '6'}} selected {{else}}  {{/if_eq}} >Jepang</option>
                    </select>
                </div>
                <div class="col-xs-2 col-lg-1 inputGroupContainer">
                    <a href="javascript:hapus_bahasa({{id}});" class="btn btn-primary"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Tingkat Kemampuan</label>
                <div class="col-xs-9 col-lg-6 selectContainer">
                    <select class="form-control" name="tingkat_kemampuan[]">
                        <option value="1" {{#if_eq tingkat_kemampuan '1'}} selected {{else}}  {{/if_eq}}>Aktif</option>
                        <option value="0" {{#if_eq tingkat_kemampuan '0'}} selected {{else}}  {{/if_eq}}>Pasif</option>
                    </select>
                </div>
            </div>
        </fieldset>
        
    </div>
</script>

<script id="form-keterampilan-template" type="text/x-handlebars-template">
    {{#keterampilan}}
    <div id="array_keterampilan_{{id}}" >
        <input type="hidden" class="form-control" name="jumlah_keterampilan[]" value=""/>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label-right">Nama Pelatihan / Keterampilan</label>
                <div class="col-xs-7 col-lg-5 inputGroupContainer">
                    <input type="text" class="form-control" name="nama_pelatihan_pencaker[]" value="{{ nama_pelatihan_pencaker }}" />
                </div>
                <div class="col-xs-2 col-lg-1 inputGroupContainer">
                    <a href="javascript:hapus_keterampilan({{id}});" class="btn btn-primary"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label-right">Nama Lembaga Pelatihan / Keterampilan</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <input type="text" class="form-control" name="nama_lem_pelatihan_pencaker[]" value="{{ nama_lem_pelatihan_pencaker }}" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label-right">Tahun Lulus</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <input type="text" class="form-control" name="tahun_lulus_pelatihan_pencaker[]" value="{{ tahun_pelatihan_pencaker }}" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label-right">Nilai Sertifikat</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <input type="text" class="form-control" name="nilai_ketrampilan_pelatihan_pencaker[]" value="{{ nilai_pelatihan_pencaker }}" />
                </div>
            </div>
        </fieldset>
    </div>
    <hr/>
    {{/keterampilan}}
    
</script>

<script id="form-pengalaman-template" type="text/x-handlebars-template">
    {{#pengalaman}}
    <div id="array_pengalaman_{{id}}" >
        <input type="hidden" class="form-control" name="jumlah_pengalaman[]" value=""/>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Nama Perusahaan</label>
                <div class="col-xs-7 col-lg-5 inputGroupContainer">
                    <input type="text" class="form-control" name="perusahaan_pengalaman[]" value="{{perusahaan_pengalaman}}" />
                </div>
                <div class="col-xs-2 col-lg-1 inputGroupContainer">
                    <a href="javascript:hapus_pengalaman({{id}});" class="btn btn-primary"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Jabatan</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <input type="text" class="form-control" name="jabatan_pengalaman[]" value="{{jabatan_pengalaman}}" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Deskripsi Pekerjaan	</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <textarea class="form-control" name="deskripsi_pengalaman[]" rows="4">{{deskripsi_pengalaman}}</textarea>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Lama Kerja</label>
                <div class="col-xs-4 col-lg-3 inputGroupContainer">
                    <input readonly='true' type="text" class="form-control lama_kerja" name="tahun_mulai_pengalaman[]" value="{{tahun_mulai_pengalaman}}"/>
                </div>
                <div class="col-xs-4 col-lg-3 inputGroupContainer">
                    <input readonly='true' type="text" class="form-control lama_kerja" name="tahun_akhir_pengalaman[]" value="{{tahun_akhir_pengalaman}}" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-xs-2 col-lg-3 control-label">Gaji Per Bulan</label>
                <div class="col-xs-9 col-lg-6 inputGroupContainer">
                    <select class="form-control" name="range_gaji_pengalaman[]">
                        <option value="">Pilih Gaji Per Bulan</option>
                        <option {{#if_eq bahasa_pencaker '1'}} selected {{else}}  {{/if_eq}} value="1">&lt; 1 jt</option>
                        <option {{#if_eq bahasa_pencaker '2'}} selected {{else}}  {{/if_eq}} value="2">1 jt - 2 jt</option>
                        <option {{#if_eq bahasa_pencaker '3'}} selected {{else}}  {{/if_eq}} value="3">2 jt - 3 jt</option>
                        <option {{#if_eq bahasa_pencaker '4'}} selected {{else}}  {{/if_eq}} value="4">3 jt - 4 jt</option>
                        <option {{#if_eq bahasa_pencaker '5'}} selected {{else}}  {{/if_eq}} value="5">4jt - 5 jt</option>
                        <option {{#if_eq bahasa_pencaker '6'}} selected {{else}}  {{/if_eq}} value="6">5 jt ke atas</option>
                    </select>
                </div>
            </div>
        </fieldset>
        
    </div>
    <hr/>
    {{/pengalaman}}
        <script>
                        $('.lama_kerja').datepicker({
                            dateFormat: 'yy-mm-dd',
                            prevText: '<i class="fa fa-chevron-left"></i>',
                            nextText: '<i class="fa fa-chevron-right"></i>',
                            changeMonth:true,
                            changeYear:true,
                            yearRange:"1970:"+new Date().getFullYear()
                        });
        </script>
    
</script>

<script id="details-infomap-template" type="text/x-handlebars-template">
          <div class="card card-primary">
            <div class="box-header with-border">
              <div class="box-tools pull-right">
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm carousel-control" href="#carousel-popup" role="button" data-slide="prev"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm carousel-control" href="#carousel-popup" role="button" data-slide="next"><i class="fa fa-chevron-right"></i></button>
                    
                  </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div style="min-height: 150px;overflow-y: scroll;height: 150px;width:100%">
              
                <div id="carousel-popup" class="carousel slide">
                  <div class="carousel-inner carousel-infotable">
                    <div class="item active">
                      <table class="table table-hover table-striped">
                        <tbody>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...</td>
                          
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                        
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                    
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                        
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
      
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                      
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                  
                        </tr>
                        <tr>
                          <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false"><input style="position: absolute; opacity: 0;" type="checkbox"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                    
                        </tr>
                        
                        </tbody>
                      </table>
                    </div>
                    <div class="item">
                      
                    </div>
                    
                  
                  </div>
                  
                </div>
              </div>
              
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">

                <div class="pull-right">
                  
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
</script>