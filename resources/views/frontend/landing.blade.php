<!DOCTYPE html>
<html lang="zxx">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="keywords" content="kota bogor, bogor, asset, kota, bogor, kujang">
        <meta name="description" content="RTH (Sistem Informasi Spasial Aset Daerah) Kota Bogor">
        <meta name="author" content="Hardiyansyah - tokecang.com">

        <title>{{ Config::get('app.name') }}</title>

        <!-- favicon -->
        <link href="{{asset('img/logo.png')}}" rel="icon" type="image/png">

        <!--Font Awesome css-->
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/fontawesome-all.css')}}">

        <!--Bootstrap css-->
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/bootstrap/css/bootstrap.css')}}">

        <!--Owl Carousel css-->
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/owl.carousel.min.css')}}">
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/owl.theme.default.min.css')}}">

        <!--Magnific Popup css-->
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/magnific-popup.css')}}">
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/animate.min.css')}}">
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/progresscircle.css')}}">
        <!-- Google Fonts -->
<!--         <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:200,300,400,500,600,700,800,900%7cOpen+Sans:400,600,700,800" rel="stylesheet"> -->

        <!--Site Main Style css-->
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/style.css')}}">
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/page.css')}}">
        <link rel="stylesheet"  href="{{ asset('assets/tokecang/assets/css/login-register.css')}}">
        <link id="colors" rel="stylesheet" href="{{ asset('assets/tokecang/assets/css/defaults-color.css')}}">

        <!-- plugin dx -->
        <link href="{{ asset('plugins/dx/css/dx.common.css')}}" rel="stylesheet">
        <link href="{{ asset('plugins/dx/css/dx.greenmist.css')}}" rel="stylesheet">
        <link href="{{ asset('plugins/dx/css/dx.light.css')}}" rel="stylesheet">
        <link href="{{ asset('plugins/dx/css/dx.spa.css')}}" rel="stylesheet">

        <style>
        nav.fixed-top{
            background:#238BD1;
        }
        nav.fixed-top .logo{
            color:#FFF;
        }

        nav.fixed-top .nav-item .nav-link{
            color: #FFF !important;
        }
        </style>
</head>
<body oncontextmenu="return true;">
      <div class="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
      </div>
        <!--Navbar Start-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <!-- LOGO -->
                <a class="navbar-brand logo" href="#">
                <img src="{{asset('img/logo.png')}}"alt="RTH" style="width:33px;margin-bottom:6px;">
                    Dinas Kebersihan dan Pertamanan Kota Bogor
                </a>

                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <!--Nav Links-->
                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-scroll-nav="0" >Tentang RTH</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="1" >Manfaat RTH</a>
                        </li>
<!--                         <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="2" >Klasifikasi</a>
                        </li> -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="3">Jenis RTH</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="4">Galeri</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="5">Kontak</a>
                        </li>
<!--                         <li class="nav-item">
                            <a href="#" class="nav-link" data-scroll-nav="5">Kontak</a>
                        </li> -->
                        {{-- <li class="nav-item">
                            <a href="javascript:void(0)" onclick="{{ (auth()->check()===true) ? 'openBackend()':'openLoginModal()'  }}" class="nav-btn">Masuk / Daftar</a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </nav>
        <!--Navbar End-->
        <!--Home Section Start-->
        <section id="home" class="slider-area" data-stellar-background-ratio=".7" data-scroll-index="0">
            <div id="owl-slider" class="owl-carousel owl-theme">
            <div class="sl-item" style="background-image: url('img/rth_background_v2.png')" >
            <!-- <div class="overlay"> -->
                <div class="container">
                    <!--Banner Content-->
                    <div class="banner-div">
                       <div class="section-title">
                            <div class="main-title">
                                <div class="title-main-page-head">
                                    <h4>RTH KOTA BOGOR</h4>
                                    <br>
                                    <p>Pengembangan informasi distribusi perencanaan, penataan dan pengembangan RTH Publik Kota Bogor. </p>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            <!-- </div> -->
            </div>
            </div>
        </section>
        <!--About Section Start-->
        <section class="pt-100 pb-100" data-scroll-index="1">
            <div class="container">
                <div class="row">
                     <div class="col-md-8 offset-md-2">
                       <div class="section-title">
                            <div class="main-title">
                                <div class="title-main-page">
                                    <p>Pengertian RTH Kota dan RTH Publik</p>
                                    <p>Ruang terbuka hijau (RTH) merupakan bagian dari penataan ruang perkotaan yang berfungsi sebagai kawasan lindung. RTH sendiri dapat didefinisikan sebagai area memanjang/jalur dan atau mengelompok, yang penggunaannya lebih bersifat terbuka, tempat tumbuh tanaman, baik yang tumbuh tanaman secara alamiah maupun yang sengaja ditanam.
                                        RTH Publik adalah RTH yang dimiliki dan dikelola oleh pemerintah daerah kota/kabupaten yang digunakan untuk kepentingan masyarakat secara umum.
                                        Kawasan RTH kota terdiri atas Hutan Kota, Kawasan perlindungan plasma nutfah eks-situ, Taman Kota, Taman Lingkungan, Green Belt Jalan inner ring road selatan, Pulau Jalan dan median jalan, Jalur Pejalan Kaki, Ruang di bawah Jalan Layang, Sempadan rel kereta api,  Jalur Hijau di bawah tegangan tinggi, Sempadan Sungai, Sempadan Situ, Sempadan Mata Air, Tempat Pemakaman Umum (TPU), Lapangan Olahraga dan Kebun Penelitian.
                                        </p>
                                </div>
                            </div>
                       </div>
                     </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                          <div class="porgress-skill">
                             <div class="row">
                             @foreach($kec as $r)
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                  <a href="#1" onclick="return view_survei('{{$r->id}}','{{$r->nama}}') "return false><div class="circlechart" data-percentage={{$r->count}}>{{$r->nama}}</div></a>
                                </div>
                                @endforeach
                             </div>
                          </div>
                    </div>
                </div>
            </div>
        </section>
        <!--About Section End-->

        <!--Service Section Start-->
        {{-- <section class="pt-100 pb-100 bg-gray" data-scroll-index="2">
            <div class="container">
                <div class="row">
                     <div class="col-md-8 offset-md-2">
                       <div class="section-title">
                            <div class="main-title">
                                <div class="title-main-page">
                                    <p>Aset Pemerintah Kota Bogor Per Jenis Aset</p>
                                </div>
                            </div>
                       </div>
                     </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="porgress-skill">
                            <div class="row">
                                @foreach($jenis as $r)
                                <div class="col-md-2 col-sm-6 col-xs-6" style="word-wrap: break-word;">
                                    <a href="#1" onclick="return view_jenis('{{$r->id}}','{{$r->nama}}')" return false><div class="circlechart" data-percentage={{$r->count}} data-max="2000">{{$r->nama}}</div></a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="display:none;" id="myDIV">
                    <div class="col-lg-12 col-md-12">
                          <div class="porgress-skill">
                             <div class="row">
                             @foreach($jenis2 as $r)
                                <div class="col-md-2 col-sm-6 col-xs-6" style="word-wrap: break-word;">
                                  <a href="#1" onclick="return view_jenis('{{$r->id}}','{{$r->nama}}')" return false><div class="circlechart" data-percentage={{$r->count}} data-max="2000">{{$r->nama}}</div></a>
                                </div>
                             @endforeach
                          </div>
                    </div>
                </div>
            </div>
            <div class="view_detail">
              <a href="#1" class="blog-btn" onclick="myFunction()" id="more">Lainnya</a>
            </div>
        </section> --}}
        <!--Service Section End-->

        <!--map Section Start-->
        <section class="pt-100 pb-100" data-scroll-index="3">

            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="section-title">
                                <div class="main-title">
                                    <div class="title-main-page">
                                        <p>Jenis RTH Kawasan Perkotaan (RTHKP)</p>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="section-title">
                                <p>Menurut Peraturan</p>
                                    
                            </div>
                        </div>
                    </div>
                <div class="row">
                    
                    <div class="col-md-6">
                       <div class="project-details-title">
                          <p style="font-size:30px;">Taman Kota</p>
                       </div>
                       <div class="project-description">
                          <p>Taman kota merupakan ruang di dalam kota yang ditata untuk menciptakan keindahan, kenyamanan, keamanan dan kesehatan bagi penggunanya. Taman kota dilengkapi dengan beberapa fasilitas untuk kebutuhan masyarakat kota sebagai tempat rekreasi. Selain itu, taman kota difungsikan sebagai paru-paru kota, pengendali iklim mikro, konservasi tanah dan air serta habitat berbagai flora dan fauna. Apabila terjadi suatu bencana, maka taman kota dapat difungsikan sebagai tempat posko pengungsian.
                            Pepohonan yang ada dalam taman kota dapat memberikan manfaat keindahan, penangkal angin dan penyaring cahaya matahari. Taman kota berperan sebagai sarana pengembangan budaya kota, pendidikan dan pusat kegiatan masyarakat.
                            .</p>
                       </div>
                       <div class="project-features">
                            <div class="row">
                              <div class="">
                              <a href="javascript:void(0)" onclick="openLoginModal();" class="blog-btn">Cari Tahu</a>
                              </div>
                            </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                        <div class="project-img">
                           <img src="{{ asset('img/taman_kota.png') }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                        
                        <div class="col-md-6">
                            <div class="project-img">
                               <img src="{{ asset('img/taman_wisata.png') }}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-details-title">
                                <p style="font-size:30px;">Taman Wisata</p>
                            </div>
                            <div class="project-description">
                                <p>.</p>
                            </div>
                            <div class="project-features">
                                    <div class="row">
                                    <div class="">
                                    <a href="javascript:void(0)" onclick="openLoginModal();" class="blog-btn">Cari Tahu</a>
                                    </div>
                                    </div>
                            </div>
                        </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="project-details-title">
                            <p style="font-size:30px;">Taman Rekreasi</p>
                        </div>
                        <div class="project-description">
                            <p>.</p>
                        </div>
                        <div class="project-features">
                            <div class="row">
                                <div class="">
                                <a href="javascript:void(0)" onclick="openLoginModal();" class="blog-btn">Cari Tahu</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="project-img">
                            <img src="{{ asset('img/taman_rekreasi.png') }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                        
                        <div class="col-md-6">
                            <div class="project-img">
                               <img src="{{ asset('img/taman_lingkungan.png') }}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-details-title">
                                <p style="font-size:30px;">Taman Lingkungan</p>
                            </div>
                            <div class="project-description">
                                <p>Taman lingkungan merupakan lahan terbuka yang berfungsi sosial dan estetik sebagai sarana kegiatan rekreatif, edukasi atau kegiatan lain pada tingkat lingkungan (RT, RW, Kelurahan atau Kecamatan)</p>
                            </div>
                            <div class="project-features">
                                    <div class="row">
                                    <div class="">
                                    <a href="javascript:void(0)" onclick="openLoginModal();" class="blog-btn">Cari Tahu</a>
                                    </div>
                                    </div>
                            </div>
                        </div>
                </div>
           </div>
        </section>

        <section class="pt-100 pb-100" data-scroll-index="5">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="section-title">
                                <div class="main-title">
                                    <div class="title-main-page">
                                        <p>Video</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="embed-responsive embed-responsive-21by9" align="center">
                        {{-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/H06MZK0HedE?rel=0" width="350" height="350" frameborder="0" >
                        </iframe> --}}
                    </div>
                    
                </div>
        </section>

        <!--Portfolio Section Start-->
        <section class="portfolio bg-gray" data-scroll-index="4">
            <div class="container">
                <div class="row">
                    <div class="col">
                       <div class="section-title">
                            <div class="main-title">
                                <div class="title-main-page">
                                    <p>Galeri</p>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
                <div id="owl-demo" class="owl-carousel owl-loaded owl-drag">
                <div class="row portfolio-section sl-item">
                    <!--Portfolio Item-->
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-1.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-1.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-2.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-2.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-3.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-3.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-4.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-4.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                   </div>

                   <div class="row portfolio-section sl-item">
                    <!--Portfolio Item-->
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-5.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-5.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-6.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-6.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-7.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-7.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 item application">
                        <div class="portfolio-item">
                            <img src="{{ asset('assets/tokecang/assets/images/portfolio/img-8.jpg')}}" alt="">
                            <div class="item-overlay">
                                <div class="icons">
                                    <span class="icon">
                                        <span class="port-link">
                                        <a href="{{ asset('assets/tokecang/assets/images/portfolio/img-8.jpg')}}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   
                </div>
            </div>
        </section>
        <!--Portfolio Section End-->
        <!-- Info Video -->
        

        <!--Contact Info Section Start-->
        <section class="info pt-50 pb-50" data-scroll-index="5">
            <div class="container">
                 {{-- <div class="row">
                     <div class="col-lg-4">
                         <div class="contact-info d-flex">
                             <div class="w-25">
                                 <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                                 </div>
                             </div>
                             <div class="contact-text w-75">
                                 <h2>Kontak</h2>
                                 <p>Tlp : (0251) 8321075 ext. 219</p>
                                 <p>Fax : (0251) 8323099</p>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-4">
                         <div class="contact-info d-flex">
                             <div class="w-25">
                                 <div class="contact-icon">
                               <i class="far fa-envelope-open"></i>
                                 </div>
                             </div>
                             <div class="contact-text w-75">
                                 <h2>Email</h2>
                                 <p>bpkad@kotabogor.go.id</p>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-4">
                         <div class="contact-info d-flex mb-0">
                             <div class="w-25">
                                 <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                                 </div>
                             </div>
                             <div class="contact-text w-75">
                                 <h2>Alamat</h2>
                                 <p>Jl. Ir. H Juanda No. 10<br>Bogor 16121</p>
                             </div>
                         </div>
    
                 </div> --}}
            </div>                 
          </div>
        </section>
        <!--Contact Info Section End-->

        <!--Footer Section Start-->
        <footer class="">
            <div class="container">
                <div class="row">
<!--                     <div class="col-lg-8 offset-lg-2">
                         <div class="footer-social">
                             <ul class="">
                                 <li><i class="fab fa-facebook-f"></i></li>
                                 <li><i class="fab fa-google-plus-g"></i></li>
                                 <li><i class="fab fa-twitter"></i></li>
                                 <li><i class="fab fa-vimeo-v"></i></li>
                                 <li><i class="fab fa-linkedin-in"></i></li>                            
                             </ul>
                         </div>
                     </div> -->
                     <div class="col-lg-12">
                         <div class="copy-right">
                         <br>
                            <p>&copy; RTH Kota Bogor, {{date('Y')}}. All rights reserved</p>
                         </div>
                     </div>
                </div>
            </div>
        </footer>
        <div class="container">
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <p class="modal-title">Informasi !</p>
                  </div>
                  <div class="modal-body">
                    <p>.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="container">
          <!-- Modal -->
          <div class="modal fade" id="modaldetailasset" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="gridasset"></div>
                </div>
                <div class="modal-footer">
                  <a href="javascript:void(0)" onclick="openLoginModal();" class="btn btn-default"><span>Detail</span></a>
                  <a href="{{'map'}}" class="btn btn-default"><span>Lihat Peta</span></a>
                </div>
              </div>
            </div>
          </div>
          </div>
          <div class="container">
  <!-- Modal -->
        <div class="modal fade" id="modaldetailjenis" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div id="gridjenis"></div>
              </div>
              <div class="modal-footer">
                <a href="javascript:void(0)" onclick="openLoginModal();" class="btn btn-default"><span>Detail</span></a>
                <a href="{{'map'}}" class="btn btn-default"><span>Lihat Peta</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- </div> -->
        <div class="modal fade login" id="loginModal">
              <div class="modal-dialog login animated">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h4 class="modal-title">Masuk</h4>
                    </div>
                    <div class="modal-body">  
                        <div class="box">
                             <div class="content">
                                <div class="social">
                                    <img src="{{asset('images/kotabogor.png')}}"alt="RTH" style="width:33px;margin-bottom:6px;">
                                </div>
                                <div class="division">
                                    <div class="line l"></div>
                                      <span>RTH</span>
                                    <div class="line r"></div>
                                </div>
                                @include('layouts.elements.alert')
                                <div class="form loginBox">
                                    <form method="post" action="{{ route('login') }}" accept-charset="UTF-8">
                                    {{ csrf_field() }}
                                    <input class="form-control" type="text" placeholder="Username" name="username">
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                    <input class="btn btn-default btn-login" type="submit" value="Masuk">
                                    </form>
                                </div>
                             </div>
                        </div>
                        <div class="box">
                            <div class="content registerBox" style="display:none;">
                             @include('layouts.elements.alert')
                             <div class="form">
                                <form method="post" action="{{ route('register') }}">
                                {{ csrf_field() }}
                                <input class="form-control" type="text" placeholder="Nama" name="name">
                                <input class="form-control" type="text" placeholder="Username" name="username">
                                <input class="form-control" type="text" placeholder="Email" name="email">
                                <input id="password" class="form-control" type="password" placeholder="Password" name="password">
                                <input class="btn btn-default btn-register" type="submit" value="Daftar">
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="forgot login-footer">
                            <span>Belum Punya Akun ?
                                 <a href="javascript: showRegisterForm();"> Daftar Disini</a></span>
                        </div>
                        <div class="forgot register-footer" style="display:none">
                             <span>Sudah Punya Akun ?</span>
                             <a href="javascript: showLoginForm();">Silahkan Masuk</a>
                        </div>
                    </div>        
                  </div>
              </div>
        <!--Footer Section Endssss-->

        <!--Jquery js-->
        <script src="{{ asset('assets/tokecang/assets/js/jquery-3.0.0.min.js')}}"></script>
        <!--Bootstrap js-->
        <script src="{{ asset('assets/tokecang/assets/bootstrap/js/bootstrap.min.js')}}"></script>
        <!--Stellar js-->
        <script src="{{ asset('assets/tokecang/assets/js/jquery.stellar.js')}}"></script>
        <!--Animated Headline js-->
        <script src="{{ asset('assets/tokecang/assets/js/animated.headline.js')}}"></script>
        <!--Owl Carousel js-->
        <script src="{{ asset('assets/tokecang/assets/js/owl.carousel.min.js')}}"></script>
        <!--ScrollIt js-->
        <script src="{{ asset('assets/tokecang/assets/js/scrollIt.min.js')}}"></script>
        <!--Isotope js-->
        <script src="{{ asset('assets/tokecang/assets/js/isotope.pkgd.min.js')}}"></script>
        <!--Magnific Popup js-->
        <script src="{{ asset('assets/tokecang/assets/js/jquery.magnific-popup.min.js')}}"></script>
        <!--Particle js-->
        <script src="{{ asset('assets/tokecang/assets/js/progresscircle.js')}}"></script>
        <!--Site Main js-->
        <script src="{{ asset('assets/tokecang/assets/js/contact.js')}}"></script>
        <script src="{{ asset('assets/tokecang/assets/js/main.js')}}"></script>

        <script src="{{ asset('plugins/dx/js/dx.all.js')}}"></script>
        <script src="{{ asset('plugins/dx/js/jszip.min.js')}}"></script>
        <script type="text/javascript">

        function view_survei(id,kec) {
              $.ajax({
                  type: 'GET',
                  url: "{{route('datakec')}}/"+id,
                  dataType: "json",
                  success: function (data) {
                      $('#modaldetailasset').modal('show');
                      loadTableAsset(data,kec);
                  }
              });
          }
        function loadTableAsset(data,kec) {
        $("#gridasset").dxPieChart({
            type: "pie",
            palette: "bright",
            title: "Aset di Kecamatan <br>"+ kec,
            dataSource: data,
            legend: {
                horizontalAlignment: "center",
                verticalAlignment: "bottom"
            },
            "export": {
                enabled: true
            },
            series: [{
                smallValuesGrouping: {
                    mode: "topN",
                    topCount: 3
                },
                argumentField: "nama",
                valueField: "count",
                label: {
                    visible: true,
                    format: "fixedPoint",
                    customizeText: function (point) {
                        return point.valueText;
                    },
                    connector: {
                        visible: true,
                        width: 1
                    }
                }
            }]
        });
    }
    function view_jenis(id,jen) {
        $.ajax({
            type: 'GET',
            url: "{{route('datajen')}}/"+id,
            dataType: "json",
            success: function (data) {
                $('#modaldetailjenis').modal('show');
                loadTableAssetJenis(data,jen);
            }
        });
    }
        function loadTableAssetJenis(data,jen) {
        $("#gridjenis").dxPieChart({
            type: "pie",
            palette: "bright",
            title: "Jenis Aset <br>"+ jen,
            dataSource: data,
            legend: {
                horizontalAlignment: "center",
                verticalAlignment: "bottom"
            },
            "export": {
                enabled: true
            },
            series: [{
                smallValuesGrouping: {
                    mode: "topN",
                    topCount: 3
                },
                argumentField: "nama",
                valueField: "count",
                label: {
                    visible: true,
                    format: "fixedPoint",
                    customizeText: function (point) {
                        return point.valueText;
                    },
                    connector: {
                        visible: true,
                        width: 1
                    }
                }
            }]
        });
    }
    function myFunction() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
        x.style.display = "block";
        $('#more').html('Sembunyikan');
    } else {
        x.style.display = "none";
        $('#more').html('Lihat');
    }
}
function openLoginModal(){
    showLoginForm();
    setTimeout(function(){
        $('#loginModal').modal('show');    
    }, 230);
    
}
function openBackend(){
    document.location.href = '/backend';
}
function showLoginForm(){
    $('#loginModal .registerBox').fadeOut('fast',function(){
        $('.loginBox').fadeIn('fast');
        $('.register-footer').fadeOut('fast',function(){
            $('.login-footer').fadeIn('fast');    
        });
        
        $('.modal-title').html('Login with');
    });       
     $('.error').removeClass('alert alert-danger').html(''); 
}
function showRegisterForm(){
    $('.loginBox').fadeOut('fast',function(){
        $('.registerBox').fadeIn('fast');
        $('.login-footer').fadeOut('fast',function(){
            $('.register-footer').fadeIn('fast');
        });
        $('.modal-title').html('Register with');
    }); 
    $('.error').removeClass('alert alert-danger').html('');
       
}
        </script>
</html>