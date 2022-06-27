@extends('voyager::tripmaster')

@section('content')
<input type="hidden" id="a_u_a_b_t" value="{!! $a_user_api_bearer_token !!}">
<script type="text/javascript">
localStorage.setItem('a_u_a_b_t', $('#a_u_a_b_t').val());
</script>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

<div id="city1" class="row mb-5  ml-2" style="margin-top:50px;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="col-md-8">
                        <button type="button" name="create_famous_city" id="create_famous_city"style="font-size:20px;"
                            class="btn btn-success btn-sm">Add Famous City</button>
                    </div>
                    <br>
                    <br>
                </div>
                <table id="firm_city_table"  style="width: 100%">
                    <thead>
                        <tr>
                            <th data-column_name="training_name" width="15%">Image</th>
                            <th data-column_name="company_name" width="10%">Country</th>
                            <th data-column_name="State" width="10%">State</th>
                            <th data-column_name="City" width="15%">City</th>
                            <th data-column_name="description" width="10%">Description</th>
                            <th width="25%">Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="users.id"/>
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc"/>
            </div>
        </div>
    </div>
</div>
<div id="FamousCityFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalcity">Add  Famous City</h5>
            </div>

            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
            <form id="famous_city_form" role="form" enctype="multipart/form-data">
                <div class="row">   
                     <div class="col-md-12">
                     <div class="form-group" id="famous_country_form_name">
                                        <label class="control-label">Chosen Your Country</label>
                                        <select style="width: 100%; height: 40px;" name="countryId1" class="countries w-100 p-2" id="countryId1"
                                                value="{{old('country')}}" required>
                                                <option value="">Select Country </option>
                                            </select>
                                        <div style="color:red;" id="tourfamouscountry"></div>
                                    </div>
                  </div>
                </div>
           
            
            <div class="row">  
                     <div class="col-md-12">
                     <div class="form-group" id="famous_state_form_name1">
                                        <label class="control-label">Chosen Your State</label>
                                        <select style="width: 100%; height: 40px;" name="stateId" class="states w-100 p-2" id="stateId"
                                                value="{{old('state')}}" required>
                                                <option value="">Select state </option>
                                            </select>
                                        <div style="color:red;" id="tourfamousstate"></div>
                                    </div>
                  </div>
                </div>
           

            <div class="row"> 
                     <div class="col-md-12">
                     <div class="form-group" id="famous_city_form_name">
                                        <label class="control-label">Chosen Your City</label>
                                        <select style="width: 100%; height: 40px;" name="famous_city_name" class="cities w-100 p-2" id="famous_city_name" value="" required>
                                                <option value="">Select City</option>
                                            </select>
                                      
                                        <div style="color:red;" id="tourfamouscity"></div>
                                    </div>
                  </div>
                </div>
           

            <div class="row">  
                     <div class="col-md-12">
                     <div class="form-group">
                                        <label class="control-label">Chosen Your City Tour Image</label>
                                            <input style="width: 100%; height: 40px;" type="file" name="city_logo" id="city_logo"
                                                class="form-control" autocomplete="off" />
                                                <div class="col-12" id="city_image"></div>
                                        <div style="color:red;" id="tourcity"></div>
                                    </div>
                  </div>
                </div>
          

            <div class="row">  
                     <div class="col-md-12">
                     <div class="form-group">
                                        <label class="control-label">Description:</label>
                                            <textarea class="form-control" id="description1"
                                                name="description1"></textarea>
                                        <div style="color:red;" id="tourinternational2"></div>
                                    </div>
                  </div>
                </div>
          
           
                            <div class="form-group" align="center">
                                <input type="hidden" name="city_action" id="city_action" />
                                <input type="hidden" name="admin_auth_name" id="admin_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="admin_auth_email" id="admin_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="admin_auth_id" id="admin_auth_id"
                                    value="{{Auth::user()->id}}">
                                <input type="hidden" name="admin_hidden_id" id="admin_hidden_id" />
                            </div>
                            <input type="submit" name="city_action_button" id="city_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                            <br />
                        </form>
                   
                        </section>    
        </div>
    </div>
</div>
</div>
</div>
<div id="CityModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details City Tour</h5>
            </div>
            <div class="modal-body">
                <span id="famous_city_form_result" aria-hidden="true"></span>
                <div id="card3" class="card3" style="display: none;">
    <div class="card-body" style="margin-left:6%;">
           <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label><b>Country: </b></label>
                    <label id="countary2"> </label>
                </div>
            </div>
          
            <div class="col-12">
                <div class="form-group">
                    <label><b>State: </b></label>
                    <label id="states1"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>City: </b></label>
                    <label id="city"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>Description: </b></label>
                    <label id="citydescriptions"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>Image: </b></label>
                    <div class="col-12" id="citymyimage"></div>
                </div>
            </div>
         
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-10">
        <div id="citycategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >Famous City Tour
                            created
                            successfully</h5>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-10">
        <div id="cityupdatemodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="exampleModalLabel">Famous City tour updated
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div id="city_tour_confirmModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h3 class="modal-title">Confirmation</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 align="center" style="margin:0;">Are you sure you want to remove this City Tour?</h4>
            <div align="right">
            <button type="button" name="cityok_button" id="cityok_button" class="btn btn-danger">OK</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            
         </div>
         </div>
      </div>
   </div>
</div>

<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
      <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
 
<script>
$(document).ready(function() {

   
    email = $('#admin_auth_email').val();
    console.log('working DataTable button');
    $('#famous_country_name').on('change', function() {
        if ($('#famous_country_name').val() != '') {
            $('#tourfamouscountry').hide();
        }
    });famous_city_name
    $('#famous_state_name').on('change', function() {
        if ($('#famous_state_name').val() != '') {
            $('#tourfamousstate').hide();

        }

    });
    $('#famous_city_name').on('change', function() {
        if ($('#famous_city_name').val() != '') {
            $('#tourfamouscity').hide();

        }

    });
    $('#description1').on('change', function() {
        if ($('#description1').val() != '') {
            $('#tourinternational2').hide();

        }

    });
    var dt = $('#firm_city_table').DataTable(

        {

            "ajax": {
                "paging": true,
                "scrollX": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                "url": "{{eventmie_url('admins/tourcity')}}/" + email,
                "dataSrc": 'data',
                "type": "GET",
                "datatype": "json",
                "crossDomain": true,
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem(
                    'a_u_a_b_t'));
                }
            },
            processing: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true,
            rowReorder: false,
            columnDefs: [{
                    "title": "Image",
                    "targets": 0,
                  
                },
                {
                    "title": "Country",
                    "targets": 1,
                 
                },
                {
                    "title": "State",
                    "targets": 2,
                   
                },
                {
                    "title": "City",
                    "targets": 3,
                  
                },
                {
                    "title": "Description",
                    "targets": 4,
                   
                    render: function(data, type, row, meta) {
                        let desc = data.length > 6 ? data.substring(0, 6) + ' ...' : data;
                        data =
                            desc;
                        return data;
                    }
                },

            ],

            columns: [{
                data: null,
                render:function(data, type, row)
                {
                    return '<img src="https://www.holidaylandmark.com/category/'+data['Image']+'" width="100px" height="50px"/>'
                },
         },
                {
                    data: 'country_name'
                },
                {
                    data: 'state_name'
                },
                {
                    data: 'city_name'
                },
                {
                    data: 'Describes'
                },
                {
                data: null,
                render: function(data, type, row) {
                    return '<button type="button"  id="' + data['id'] +
                        '" class="edittripcity btn btn-warning btn-sm ">Edit</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="deleteCity btn btn-danger btn-sm ">Delete</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="detailCity btn btn-success btn-sm ">Detail</button>'
                },


            },

            ],
        });

    CKEDITOR.replace('description1');
    $('#create_famous_city').click(function() {
        console.log('working create_famous_city button');
        $('#tourcity').html('(please attach image)');
        $('#famous_country_form_name1').show();
        $('#famous_state_form_name').show();
        $('#famous_city_form_name').show();
        $('#famous_city_form')[0].reset();
        $('#famous_city_form_result').html('');
        $('.modal-title').text("Add Famous Places");
        $('#FamousCityFormModal').modal('show');
        $('#city_action_button').val("Add");
        $('#city_action').val("Add");
        CKEDITOR.instances['description1'].setData('');
    });


    $(document).on('click', '.edittripcity', function() {
        console.log('working edit button');
      
        var id = $(this).attr('id');
        $('#famous_city_form')[0].reset();
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/editcity/')}}/" + id,
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log(html);
                $('#city_logo').show();
               

                $('#countryId1').prepend("<option value='"+html.data.slug1+"' selected='selected'>"+html.data.slug1+"</option>").attr('disabled', false);
                $('#stateId').prepend("<option value='"+html.data.slug+"' selected='selected'>"+html.data.slug+"</option>").attr('disabled', false);
                $('#famous_city_name').prepend("<option value='"+html.data.slug2+"' selected='selected'>"+html.data.slug2+"</option>").attr('disabled', false);

                CKEDITOR.instances['description1'].setData(html.data.Describes);
                
                if (html.data.Image == "default.png") {
                $('#city_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" width="10px" height="80px">');
            } else {
                $('#city_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" width="10px" height="80px">');
            }
                $('.modal-title').text("Edit City Tour");
                $('#city_action_button').val("Update");
                $('#city_action').val("Update");
                $('#FamousCityFormModal').modal('show');
                $('#admin_hidden_id').val(id);
            }
        })
    });  

    $('#famous_city_form').on('submit', function(event) {
        event.preventDefault();
        CKEDITOR.instances['description1'].updateElement();
        if ($('#city_action').val() == 'Add') {
            console.log("inside add");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admins/addcitytour')}}/" + email,
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

                    $('#famous_country_name').has('option').length > 0 
                           {
                            $('#tourfamouscountry').html("Please  select country field");    
                           }
                           
                           $('#famous_state_name').has('option').length > 0 
                           {
                            $('#tourfamousstate').html("Please  select state field");    
                           }

                           $('#famous_city_name').has('option').length > 0 
                           {
                            $('#tourfamouscity').html("Please  select city field");    
                           }

                    if (!$('#description1').val()) {


                        $('#tourinternational2').html("Please  describe  field");
                    }

                },
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#citycategorymodel').modal('show');
                        $('#FamousCityFormModal').modal('hide');
                        $('#famous_city_form')[0].reset();
                        $('#firm_city_table').DataTable().ajax.reload()
                    } else {

                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })
        }
       
        if ($('#city_action').val() == "Update") {
            console.log("update me aa raha");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
     });
            $.ajax({

                    url: "{{ url('admins/updatecity') }}",
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
                       
                            $('#cityupdatemodel').modal('show'); 
                            $('#FamousCityFormModal').modal('hide');
                        $('#famous_city_form')[0].reset();
                        $('#firm_city_table').DataTable().ajax.reload()
                        
                         setTimeout(function() {
                            window.location.reload(true);
                           
                         }, 1500);
                        
                       
                        
                      
                       
                    } else {

                    }
                },

               error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            });
        }
    });
   
   
    $(document).on('click', '.detailCity', function() {
        id = $(this).attr('id');
        console.log('working details button');
        console.log(id);
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/detailcity')}}/" + id,

            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log('get quote by firm id :' + html.data.id);
                console.log("yanha aa raha hai");
                var countary = html.data.slug1;
                var state = html.data.slug;
                var city = html.data.slug2;
                var description1 = html.data.Describes;
                $('#CityModal').modal('show');
                $(".card3").show();
                $('#countary2').html(countary);
                $('#states1').html(state);
                $('#city').html(city);
               $('#citydescriptions').html(description1);
               if (html.data.Image == "default.png") {
                $('#citymyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" width="200px" height="200px">');
            } else {
                $('#citymyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" width="200px" height="200px">');
            }
            },
               error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });

    var user_id;
   $(document).on('click', '.deleteCity', function() {
      user_id = $(this).attr('id');

      $('#city_tour_confirmModal').modal('show');
   });

   $('#cityok_button').click(function() {
      console.log('working delete button');
     console.log(user_id);
     $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
     });
      $.ajax({
         type: "get",
         data: {},
         url: "{{url('admins/deletecitytour/')}}/" + user_id,
         headers: {
            "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
         },
         beforeSend: function() {
            $('#cityok_button').text('Deleting');
         },
         success: function (data) {
             $('#city_tour_confirmModal').modal('hide');
            $('#firm_city_table').DataTable().ajax.reload()
              },
              error: function (data) {
                  console.log('Error:', data);
              }
      })
   });
    

});
</script>
@endsection