@extends('templates.adminlte.main')

@section('content-admin')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Detil User {{$item->name}}</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active" href="#tab_user" data-toggle="tab">User</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_disbursment" data-toggle="tab">Disbursment</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_user">
                          <div id="griduser"></div>
                        </div>
                        
                        <div class="tab-pane" id="tab_disbursment">
                          The European languages are members of the same family. Their separate existence is a myth.
                          For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                          in their grammar, their pronunciation and their most common words. Everyone realizes why a
                          new common language would be desirable: one could refuse to pay expensive translators. To
                          achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                          words. If several languages coalesce, the grammar of the resulting language is more simple
                          and regular than that of the individual languages.
                        </div>
                        
                    </div>
                </div>
            </div>    
        </div>    
    </div>    
@endsection

@section('style-head')
@parent
<link rel="stylesheet" href="{{ asset('plugins/dx/css/dx.common.css')}}" />
<link rel="stylesheet" href="{{ asset('plugins/dx/css/dx.light.css')}}" />
@endsection

@section('script-end')
@parent
<script src="{{ asset('plugins/dx/js/dx.all.js') }}"></script>

<script>
    $(function(){
        var customers = [{
            "ID" : 1,
            "CompanyName" : "Premier Buy",
            "Address" : "7601 Penn Avenue South",
            "City" : "Richfield",
            "State" : "Minnesota",
            "Zipcode" : 55423,
            "Phone" : "(612) 291-1000",
            "Fax" : "(612) 291-2001",
            "Website" : "http =//www.nowebsitepremierbuy.com"
        }, {
            "ID" : 2,
            "CompanyName" : "ElectrixMax",
            "Address" : "263 Shuman Blvd",
            "City" : "Naperville",
            "State" : "Illinois",
            "Zipcode" : 60563,
            "Phone" : "(630) 438-7800",
            "Fax" : "(630) 438-7801",
            "Website" : "http =//www.nowebsiteelectrixmax.com"
        }, {
            "ID" : 3,
            "CompanyName" : "Video Emporium",
            "Address" : "1201 Elm Street",
            "City" : "Dallas",
            "State" : "Texas",
            "Zipcode" : 75270,
            "Phone" : "(214) 854-3000",
            "Fax" : "(214) 854-3001",
            "Website" : "http =//www.nowebsitevideoemporium.com"
        }, {
            "ID" : 4,
            "CompanyName" : "Screen Shop",
            "Address" : "1000 Lowes Blvd",
            "City" : "Mooresville",
            "State" : "North Carolina",
            "Zipcode" : 28117,
            "Phone" : "(800) 445-6937",
            "Fax" : "(800) 445-6938",
            "Website" : "http =//www.nowebsitescreenshop.com"
        }, {
            "ID" : 5,
            "CompanyName" : "Braeburn",
            "Address" : "1 Infinite Loop",
            "City" : "Cupertino",
            "State" : "California",
            "Zipcode" : 95014,
            "Phone" : "(408) 996-1010",
            "Fax" : "(408) 996-1012",
            "Website" : "http =//www.nowebsitebraeburn.com"
        }, {
            "ID" : 6,
            "CompanyName" : "PriceCo",
            "Address" : "30 Hunter Lane",
            "City" : "Camp Hill",
            "State" : "Pennsylvania",
            "Zipcode" : 17011,
            "Phone" : "(717) 761-2633",
            "Fax" : "(717) 761-2334",
            "Website" : "http =//www.nowebsitepriceco.com"
        }, {
            "ID" : 7,
            "CompanyName" : "Ultimate Gadget",
            "Address" : "1557 Watson Blvd",
            "City" : "Warner Robbins",
            "State" : "Georgia",
            "Zipcode" : 31093,
            "Phone" : "(995) 623-6785",
            "Fax" : "(995) 623-6786",
            "Website" : "http =//www.nowebsiteultimategadget.com"
        }, {
            "ID" : 8,
            "CompanyName" : "EZ Stop",
            "Address" : "618 Michillinda Ave.",
            "City" : "Arcadia",
            "State" : "California",
            "Zipcode" : 91007,
            "Phone" : "(626) 265-8632",
            "Fax" : "(626) 265-8633",
            "Website" : "http =//www.nowebsiteezstop.com"
        }, {
            "ID" : 9,
            "CompanyName" : "Clicker",
            "Address" : "1100 W. Artesia Blvd.",
            "City" : "Compton",
            "State" : "California",
            "Zipcode" : 90220,
            "Phone" : "(310) 884-9000",
            "Fax" : "(310) 884-9001",
            "Website" : "http =//www.nowebsiteclicker.com"
        }, {
            "ID" : 10,
            "CompanyName" : "Store of America",
            "Address" : "2401 Utah Ave. South",
            "City" : "Seattle",
            "State" : "Washington",
            "Zipcode" : 98134,
            "Phone" : "(206) 447-1575",
            "Fax" : "(206) 447-1576",
            "Website" : "http =//www.nowebsiteamerica.com"
        }, {
            "ID" : 11,
            "CompanyName" : "Zone Toys",
            "Address" : "1945 S Cienega Boulevard",
            "City" : "Los Angeles",
            "State" : "California",
            "Zipcode" : 90034,
            "Phone" : "(310) 237-5642",
            "Fax" : "(310) 237-5643",
            "Website" : "http =//www.nowebsitezonetoys.com"
        }, {
            "ID" : 12,
            "CompanyName" : "ACME",
            "Address" : "2525 E El Segundo Blvd",
            "City" : "El Segundo",
            "State" : "California",
            "Zipcode" : 90245,
            "Phone" : "(310) 536-0611",
            "Fax" : "(310) 536-0612",
            "Website" : "http =//www.nowebsiteacme.com"
        }, {
            "ID" : 13,
            "CompanyName" : "Super Mart of the West",
            "Address" : "702 SW 8th Street",
            "City" : "Bentonville",
            "State" : "Arkansas",
            "Zipcode" : 72716,
            "Phone" : "(800) 555-2797",
            "Fax" : "(800) 555-2171",
            "Website" : "http://www.nowebsitesupermart.com"
        }, {
            "ID" : 14,
            "CompanyName" : "Electronics Depot",
            "Address" : "2455 Paces Ferry Road NW",
            "City" : "Atlanta",
            "State" : "Georgia",
            "Zipcode" : 30339,
            "Phone" : "(800) 595-3232",
            "Fax" : "(800) 595-3231",
            "Website" : "http =//www.nowebsitedepot.com"
        }, {
            "ID" : 15,
            "CompanyName" : "K&S Music",
            "Address" : "1000 Nicllet Mall",
            "City" : "Minneapolis",
            "State" : "Minnesota",
            "Zipcode" : 55403,
            "Phone" : "(612) 304-6073",
            "Fax" : "(612) 304-6074",
            "Website" : "http =//www.nowebsitemusic.com"
        }, {
            "ID" : 16,
            "CompanyName" : "Tom's Club",
            "Address" : "999 Lake Drive",
            "City" : "Issaquah",
            "State" : "Washington",
            "Zipcode" : 98027,
            "Phone" : "(800) 955-2292",
            "Fax" : "(800) 955-2293",
            "Website" : "http =//www.nowebsitetomsclub.com"
        }, {
            "ID" : 17,
            "CompanyName" : "E-Mart",
            "Address" : "3333 Beverly Rd",
            "City" : "Hoffman Estates",
            "State" : "Illinois",
            "Zipcode" : 60179,
            "Phone" : "(847) 286-2500",
            "Fax" : "(847) 286-2501",
            "Website" : "http =//www.nowebsiteemart.com"
        }, {
            "ID" : 18,
            "CompanyName" : "Walters",
            "Address" : "200 Wilmot Rd",
            "City" : "Deerfield",
            "State" : "Illinois",
            "Zipcode" : 60015,
            "Phone" : "(847) 940-2500",
            "Fax" : "(847) 940-2501",
            "Website" : "http =//www.nowebsitewalters.com"
        }, {
            "ID" : 19,
            "CompanyName" : "StereoShack",
            "Address" : "400 Commerce S",
            "City" : "Fort Worth",
            "State" : "Texas",
            "Zipcode" : 76102,
            "Phone" : "(817) 820-0741",
            "Fax" : "(817) 820-0742",
            "Website" : "http =//www.nowebsiteshack.com"
        }, {
            "ID" : 20,
            "CompanyName" : "Circuit Town",
            "Address" : "2200 Kensington Court",
            "City" : "Oak Brook",
            "State" : "Illinois",
            "Zipcode" : 60523,
            "Phone" : "(800) 955-2929",
            "Fax" : "(800) 955-9392",
            "Website" : "http =//www.nowebsitecircuittown.com"
        }];
        $("#griduser").dxDataGrid({
            dataSource: customers,
            showBorders: true,
            paging: {
                pageSize: 10
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [5, 10, 20],
                showInfo: true
            },
            columns: ["CompanyName", "City", "State", "Phone", "Fax"]
        });
    })
</script>
@endsection

