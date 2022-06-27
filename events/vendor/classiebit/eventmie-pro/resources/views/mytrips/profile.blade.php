@extends('voyager::tripmaster')


@section('content')

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
      <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
 
<div class="row mb-5  ml-2" style="margin-top:2%;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class=" align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row"> 
                <div class="form-group">
                    <div class="col-md-12">
               
                         <span class="float-left ">
                        <button type="button" name="create_tour_opertor" id="create_tour_opertor"
                            class="btn btn-success btn-sm ml-4" style="font-size:20px;">Add Tour Opertor</button>
                            </span>
                            <span class="float-right" style="width:50%; margin-top:02%;">
                            <input  id="commsearch" style="width:80%; height: 40px;" placeholder="Search by Name, Country, State, City, Email"  class="float-left  py-2 px-5"
                                name="commsearch" type="text">
                                </span> 
                    </div>
                </div>
                </div>
                <br>
                <div class="row">
                            <div class="col-md-4">
                                <select  style="height:40px;" class="form-control countries" name="manual_filter_table"
                                    id="manual_filter_table">
                                    <option value="">Select Your Country</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select style="height:40px;" class="form-control states" name="manual_filter_table1"
                                    id="manual_filter_table1">
                                    <option value="">Select Your State</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select style="height:40px;" class="form-control cities" name="manual_filter_table2"
                                    id="manual_filter_table2">
                                    <option value="">Select Your City</option>
                                </select>
                            </div>
                        
                    </div>
                    <div class="table-responsive">
                                <table id="firm_tour_opertor" class="table table-bordered table-striped bg-light"
                                    style="color:white; border:none">
                                    <thead class="bg-light" style="color:black ">
                                    <tr >
                            <th data-column_name="profile_name" width="20%" >Name</th>
                            <th data-column_name="profile_country" width="15%">Country</th>
                            <th data-column_name="profile_State" width="15%">State</th>
                            <th data-column_name="profile_City" width="10%">City</th>
                            <th data-column_name="profile_email" width="10%">Email</th>
                            <th width="30%">Action</th> 
                        </tr>
                                    </thead>
                                    <tbody style="color:black" id="myTable">
                                    </tbody>
                                </table>
                               
                               
                                
                                
                              
                              
                            </div>
                <div class="nav-btn-container">
                                <button class="btn btn-danger prev-btn mr-1">Prev</button>
                                <span id="page"></span>
                                <button class="btn btn-success next-btn">Next</button>
                            </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="users.id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade" id="FamousTourOpertorFormModal" role="dialog">
                    <div class="modal-dialog modal-lg" >
                        <!-- Modal content-->
                        <div class="modal-content">
                              <div class="row">
                                 <div class="col-md-6">
                                     <center>
                                     <div class="modal-header">
                                        <h4 class="modal-title">Add  Tour Opertor</h4>
                                     </div></center>
                                     <form id="famous_tour_opertor_form" role="form" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" name="name" id="name"
                                                class="form-control" autocomplete="off"/>
                                                <div style="color:red;" id="tourname"></div>
                                            </div>
                                            
                                            <div class="form-group" id="country_form_name">
                                                <label>Chosen Your Country</label>
                                                <select style="width: 100%; height: 30px;" name="procountryId" class="countries" id="procountryId"
                                                    value="{{old('country')}}" required>
                                                    <option value=""> Select Country </option>
                                                </select>
                                         <div style="color:red;" id="tourcountry"></div>
                                            </div>
                                            <div class="form-group" id="city_form_name">
                                                <label>Chosen Your City</label>
                                                <select style="width: 100%; height: 30px;" name="procity" class="cities w-100 p-2" id="procity" value="" required>
                                                <option value="">Select City </option>
                                            </select>
                                         <div style="color:red;" id="tourcity"></div>
                                            </div>
                                            <div class="form-group" id="tour_phone">
                                                <label>Phone No:</label>
                                                <input type="mobile" name="phone" id="phone" pattern="[123456789][0-9]{9}"
                                            title="Phone number with 6-9 and remaing 9 digit with 0-9" class="form-control" autocomplete="off" />
                                            <div style="color:red;" id="tourphone"></div>
                                            </div>
                                        </div>
                                   </div>

                                <div class="col-md-6" style="margin-top:6%;">
                                    
                                        <div class="modal-body">
                                            <div class="form-group" id="tour_email">
                                                <label>Email address:</label>
                                                <input type="text" name="email" id="email" value="{{ old('email') }}"
                                                class="form-control" autocomplete="off" />
                                                <div style="color:red;" id="touremail1"></div>
                                            </div>
                                            <div class="form-group" id="state_form_name">
                                                <label>Chosen Your State</label>
                                                <select style="width: 100%; height: 30px;" name="prostate" class="states w-100 p-2" id="prostateId"
                                                value="{{old('state')}}" required>
                                                <option value="">Select state </option>
                                            </select>
                                        <div style="color:red;" id="tourstate"></div>
                                            </div>
                                            <div class="form-group" id="tour_experience">
                                        <label class="control-label">Experience:</label>
                                        <select name="tour_op_experience" class="form-control" id="tour_op_experience">
                                            <option value="all" class="form-control cnfontsize_2">Select a Experience
                                            </option>
                                            <option>0 years</option>
                                                    <option>1-5 years</option>
                                                    <option>6-10 years</option>
                                                    <option>10-15 years</option>
                                                    <option>15-20 years</option>
                                                    <option>20-25 years</option>
                                                    <option>25-30 years</option>
                                                    <option>above 30 years</option>
                                        </select>
                                        <div style="color:red;" id="tourfamousexperience"></div>
                                    </div>
                                           
                                    <div class="form-group" id="tour_password">
                                        <label class="control-label">Password:</label>
                                       
                                            <input type="text" name="password" id="password"  value="{{ old('password') }}"
                                                class="form-control" autocomplete="off"/>
                                       
                                        <div style="color:red;" id="tourpassword"></div>
                                    </div>   
                                           
                                        </div>

                                        <div class="form-group" align="center">
                                <input type="hidden" name="profile_action" id="profile_action" />
                                <input type="hidden" name="admin_auth_name" id="admin_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="admin_auth_email" id="admin_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="admin_auth_id" id="admin_auth_id"
                                    value="{{Auth::user()->id}}">
                                <input type="hidden" name="admin_hidden_id" id="admin_hidden_id" />
                             
                             <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                             <input type="submit" name="profile_action_button" id="profile_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                             <br />
                             </div>
                                        <form>
                            </div>
                           </div>
                        </div>
                    </div>
                </div>
               
               
            </div>
        </div>
    </div>

<div id="TouropertorlModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mymodal-title" id="mymodal">Details Tour Opertor</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <span id="famous_tour_opertor_result" aria-hidden="true"></span>
              
              
            <div class="card">
               
                <div class="card-body">
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>Nane:</strong>
                        <label id="name1"> </label>
                    </div>
                   
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>Country:</strong>
                        <label id="country1"> </label>
                    </div>
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>State:</strong>
                        <label id="state1"> </label>
                    </div>
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>City:</strong>
                        <label id="city1"> </label>
                    </div>
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>Phone:</strong>
                        <label id="phone1"> </label>
                    </div>
                   <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>Experience:</strong>
                        <label id="experience1"> </label>
                    </div>
                    <div style="padding-left:10px;font-size:16px;" class="form-group">
                        <strong>Email:</strong>
                        <label id="email1"> </label>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div id="tcategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="exampleModaltouropertor">Tour Opertor
                            created
                            successfully</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="tinternationalupdatemodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="exampleModaltouropertor">Tour Opertor updated
                            successfully</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="profile_confirmModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h6 class="modal-title">Confirmation</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <br>
            
         </div>
         <div class="modal-body">
            <h4 align="center" style="margin:0;">Are you sure you want to remove this Tour Opertor?</h4>
         </div>
         <div class="modal-footer">
            <button type="button" name="pro_ok_button" id="pro_ok_button" class="btn btn-danger">OK</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
         </div>
      </div>
   </div>
</div>

<script>
    $(document).ready(function () {
        email = $('#admin_auth_email').val();
    console.log('working DataTable button');
    $('#name').on('change', function() {
        if ($('#name').val() != '') {
            $('#tourname').hide();
        }
    });
    $('#phone').on('change', function() {
        if ($('#phone').val() != '') {
            $('#tourphone').hide();
        }
    });
    $('#email').on('change', function() {
        if ($('#email').val() != '') {
            $('#touremail1').hide();

        }

    });
    
    $('#tour_op_experience').on('change', function() {
        if ($('#tour_op_experience').val() != '') {
            $('#tourfamousexperience').hide();

        }

    });
        $(function () {
            console.log('command ka function me hai');
            var curtpage = 1,
                pagelimit = 10,
                totalrecord = 0;
            query = '';
            fetchData(curtpage, query);
            // handling the prev-btn
            $(".prev-btn").on("click", function () {
                if (curtpage > 1) {
                    curtpage--;
                }
                console.log("Prev Page: " + curtpage);
                fetchData(curtpage, query);
            });
            $(document).on('keyup', '#commsearch', function () {
                console.log('search input click hua');
                var query = $('#commsearch').val();
                console.log(query);
                var curtpage = 1;
                pagelimit = 10,
                    totalrecord = 0;
                fetchData(curtpage, query);
            });
            $("#manual_filter_table").change(function() {
                var query = $('#manual_filter_table').val();
                    console.log(query);
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchData(curtpage, query);
            })
            $("#manual_filter_table1").change(function() {
                var query = $('#manual_filter_table1').val();
                    console.log(query);
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchData(curtpage, query);
            })
            $("#manual_filter_table2").change(function() {
                var query = $('#manual_filter_table2').val();
                    console.log(query);
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchData(curtpage, query);
            })


            
            $('#create_tour_opertor').click(function() {
        console.log('working create_tour_opertor button');
        $('#tourname').show();
        $('#country_form_name').show();
        $('#state_form_name').show();
        $('#city_form_name').show();
        $('#tour_experience').show();
        $('#tour_phone').show();
        $('#tour_password').show();
        $('#tour_email').show();
        $('#famous_tour_opertor_form')[0].reset();
        $('#famous_tour_opertor_result').html('');
        $('.modal-title').text("Add Tour Opertor");
        $('#FamousTourOpertorFormModal').modal('show');
        $('#profile_action_button').val("Add");
        $('#profile_action').val("Add");
        
        
    });

    $('#famous_tour_opertor_form').on('submit', function(event) {
        event.preventDefault();
        
        if ($('#profile_action').val() == 'Add') {
            console.log("inside add");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
    });
            $.ajax({
              
                url: "{{url('admins/addprofile')}}/" + email,
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {

$('#name').has('option').length > 0 
       {
        $('#tourname').html("Please  select name field");    
       }


       $('#phone').has('option').length > 0 
       {
        $('#tourphone').html("Please  select phone field");  
       }
       $('#tour_op_experience').has('option').length > 0 
       {
        $('#tourfamousexperience').html("Please  select experience field");    
       }
       $('#email').has('option').length > 0 
       {
        $('#touremail1').html("Please  select email field");    
       }
       $('#password').has('option').length > 0 
       {
        $('#tourpassword').html("Please  select password field");    
       }


},
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#tcategorymodel').modal('show');
                         $('#FamousTourOpertorFormModal').modal('hide');
                         var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchData(curtpage, query);
                    } else {

                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })
        }
        if ($('#profile_action').val() == "Update") {
            console.log("update me aa raha");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
    });
            $.ajax({

                url: "{{ url('admins/updateprofile') }}",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },

                success: function(data) {
                    if (data.success) {
                        $('#FamousTourOpertorFormModal').modal('hide');
                        $('#tinternationalupdatemodel').modal('show');
                        setTimeout(function() {
                            window.location.reload(true);
                         }, 1200);
                        var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchData(curtpage, query);

                    } else {

                    }
                },

                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

    });

    var user_id;
   $(document).on('click', '.deleteProfile', function() {
      user_id = $(this).attr('id');
      $('#profile_confirmModal').modal('show');
   });

   $('#pro_ok_button').click(function() {
      console.log('working delete button');
     
      $.ajax({
         type: "get",
         data: {},
         url: "{{url('admins/deleteprofile/')}}/" + user_id,
         headers: {
            "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
         },
         beforeSend: function() {
            $('#pro_ok_button').text('Deleting');
         },
         success: function (data) {
             $('#profile_confirmModal').modal('hide');
             var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchData(curtpage, query);
              },
              error: function (data) {
                  console.log('Error:', data);
              }
      })
   });

   $(document).on('click', '.detailProfile', function() {
        id = $(this).attr('id');
        console.log('working details button');
        console.log(id);
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/detailprofile')}}/" + id,

            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log('get quote by firm id :' + html.data.id);
                console.log("yanha aa raha hai");
                var name = html.data.name;
                var country = html.data.country;
                var state = html.data.state;
                var city = html.data.city;
                var phone = html.data.phone;
                var experience = html.data.Experience;
                var emails = html.data.email;
               
                $('#TouropertorlModal').modal('show');
              
               
                $(".cardop").show();
                $('#name1').html(name);
                $('#country1').html(country);
               $('#state1').html(state);
               $('#city1').html(city);
               $('#phone1').html(phone);
               $('#experience1').html(experience);
               $('#email1').html(emails);
                     
            
        },
               error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
    

            // Edit Function Start
            $(document).on('click', '.editProfile', function () {
                console.log('working editProfile button');
                // $('#command_sample_form').show();
                var id = $(this).attr('id');
                // $('#form_command').html('');
                $.ajax({
                    type: "get",
                    data: {},
                    url: "{{url('admins/profileedit')}}/" + id,
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                    },
                    success: function (html) {
                        $('#procountryId').prepend("<option value='"+html.data.country+"' selected='selected'>"+html.data.country+"</option>").attr('disabled', false);
                $('#prostateId').prepend("<option value='"+html.data.state+"' selected='selected'>"+html.data.state+"</option>").attr('disabled', false);
                $('#procity').prepend("<option value='"+html.data.city+"' selected='selected'>"+html.data.city+"</option>").attr('disabled', false);

                        $('#name').val(html.data.name);
                        $('#phone').val(html.data.phone);
                        $('#tour_op_experience').val(html.data.Experience);
                        $('#email').val(html.data.email);
                        $('#password').val(html.data.password);
                        $('.modal-title').text("Edit Tour Opertor Data");
                        $('#profile_action_button').val("Update");
                        $('#profile_action').val("Update");
                        $('#FamousTourOpertorFormModal').modal('show');
                        $('#admin_hidden_id').val(id);

                    }
                })
            });

            // handling the next-btn
            $(".next-btn").on("click", function () {
                if (curtpage * pagelimit < totalrecord) {
                    curtpage++;
                }
                console.log("Next Page: " + curtpage);
                fetchData(curtpage, query);
            });
            $(document).on('click', '.link-click', function (event) {
                curtpage = $(this).attr('page');
                $(this).addClass('active');
                console.log(this);
                fetchData(curtpage, query);
            });
            function fetchData(page, query) {
                var  email = $('#admin_auth_email').val();
                console.log('command ka fetch function');
                // ajax() method to make api calls
                $.ajax({

                   
                    url: "{{eventmie_url('admins/toursprofile')}}/" +  email,
                    type: "GET",
                    data: "page=" + page + "&query=" + query,
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                    },
                    success: function (html) {
                        console.log(html.data);

                        if (html.data) {
                            var dataArr = html.data.data;

                            totalrecord = html.data.total;
                            console.log(totalrecord + ' total record aa rha hai');
                            lastpage = html.data.last_page;
                            console.log(lastpage + ' last page record aa rha hai');
                            var link = "";
                            for (var j = 1; j <= lastpage; j++) {
                                if (html.data.current_page == j) {
                                    link +=
                                        "<button class='btn btn-primary link-click active' page='" +
                                        j + "'>" + j + "</button>&nbsp;";
                                } else {
                                    link +=
                                        "<button class='btn btn-primary link-click' page='" +
                                        j + "'>" + j + "</button>&nbsp;";
                                }
                            }
                            $("#page").html(link);
                            var html = "";
                            for (var i = 0; i < dataArr.length; i++) {
                                html += "<tr>" +
                            
                                    "<td>" + dataArr[i].name + "</td>" +
                                    "<td>" + dataArr[i].country + "</td>" +
                                    "<td>" + dataArr[i].state + "</td>" +
                                    "<td>" + dataArr[i].city + "</td>" +
                                    "<td>" + dataArr[i].email + "</td>" +
                                    "<td> <button type='button'  id='" + dataArr[i].id +
                                    "'class='editProfile btn btn-warning btn-sm'>Edit</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='deleteProfile btn btn-danger btn-sm'>Delete</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='detailProfile btn btn-success btn-sm'>Detail</button></td>" +
                                    "</tr>" +
                                    "<hr />";
                            }
                            $("#firm_tour_opertor tbody").html(html);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }
        });
    });
    function check()
{

    var pass1 = document.getElementById('phone');


    var message = document.getElementById('message');

   var goodColor = "#0C6";
    var badColor = "#FF9B37";

    if(Phoneno.value.length!=10){

      Phoneno.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "required 10 digits, match requested format!"
    }}

</script>
<script src="{{asset('js/location.js')}}"> </script>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
      <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
      <script>
    $(document).ready(function() {
    $("[href]").each(function() {
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    });
});

</script>

@endsection