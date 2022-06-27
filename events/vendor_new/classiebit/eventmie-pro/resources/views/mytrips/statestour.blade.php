@extends('voyager::tripmaster')

@section('content')


<div id="country1" class="row mb-5  ml-2" style="margin-top:4%;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class=" align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="col-md-8">
                        <button type="button" name="create_famous_state" id="create_famous_state"style="font-size:20px;"
                            class="btn btn-success btn-sm">Add Famous State</button>
                    </div>
                    <br>
                    <br>
                </div>
                <table id="firm_state_table" style="width: 100%">
                    <thead>
                        <tr>
                            <th data-column_name="training_name" width="10%">Image</th>
                            <th data-column_name="company_name" width="15%">Country</th>
                            <th data-column_name="State" width="15%">State</th>
                            <th data-column_name="explain" width="15%">Description</th>
                            <th width="25%">Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="users.id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
            </div>
        </div>
    </div>
</div>
<div id="FamousStateFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title1" id="exampleModalstate">Add  Famous State</h5>
          </div>
                        
          <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">

                        <form id="famous_state_form" role="form" enctype="multipart/form-data">
                            <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group" id="famous_country1_form_name">
                                        <label class="control-label">Chosen Your Country</label>
                                        <select style="width: 100%; height: 40px;" name="scountryId" class="countries w-100 p-2" id="scountryId"
                                                value="{{old('country')}}" required>
                                                <option value=""> Select Country </option>
                                            </select>
                                        <div style="color:red;" id="tourfamouscountry1"></div>
                                    </div>
                                    </div>
                                </div>
                           

                            <div class="row">
                                    <div class="col-sm-12">
                                    <div class="form-group" id="famous_state_form_name1">
                                        <label class="control-label">Chosen Your State</label>
                                        <select style="width: 100%; height: 40px;" name="sstateId" class="states" id="sstateId"
                                                value="{{old('state')}}" required>
                                                <option value=""> Select state </option>
                                        </select>
                                        <div style="color:red;" id="tourfamousstate1"></div>
                                      </div>
                                    </div>
                                </div>
                          

                            <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group" >
                                        <label class="control-label">Chosen Your State Tour Image</label>
                                            <input style="width: 100%; height: 40px;"  type="file" name="state_logo" id="state_logo"
                                                class="form-control" autocomplete="off" />
                                                <div class="col-12" id="state_image"></div>
                                        <div style="color:red;" id="tourstate"></div>
                                    </div>
                                    </div>
                                </div>
                          

                            <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Description:</label>
                                            <textarea class="form-control" id="explain"
                                                name="explain"></textarea>
                                        <div style="color:red;" id="tourinternational2"></div>
                                    </div>
                                    </div>
                                </div>
                                                   
                            <div class="form-group" align="center">
                                <input type="hidden" name="states_action" id="states_action" />
                                <input type="hidden" name="admin_auth_name" id="admin_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="admin_auth_email" id="admin_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="admin_auth_id" id="admin_auth_id"
                                    value="{{Auth::user()->id}}">
                                <input type="hidden" name="admin_hidden_id" id="admin_hidden_id" />
                            </div>
                            <input type="submit" name="states_action_button" id="states_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                            <br />
                       
                </form> 
                </section> 
        </div>
    </div>
</div>
</div>
</div>

<div id="StateModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details State Tour</h5>
            </div>
            <div class="modal-body">
                <span id="famous_state_form_result" aria-hidden="true"></span>
                <div id="card2" class="card2" style="display: none;">
    <div class="card-body" style="margin-left:6%;">
           <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label><b>Country: </b></label>
                    <label id="countary1"> </label>
                </div>
            </div>
          
            <div class="col-12">
                <div class="form-group">
                    <label><b>State: </b></label>
                    <label id="states"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>Description: </b></label>
                    <label id="stadescriptions"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>Image: </b></label>
                    <div class="col-12" id="stamyimage"></div>
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
        <div id="statecategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >Famous State Tour
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
        <div id="stateupdatemodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >Famous State tour updated
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="state_tour_confirmModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h3 class="modal-title">Confirmation</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 align="center" style="margin:0;">Are you sure you want to remove this State tour?</h4>
            <div align="right"> 
            <button type="button" name="stateok_button" id="stateok_button" class="btn btn-danger">OK</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
         </div>
         </div>
      </div>
   </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
      <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
<script>
$(document).ready(function() {
    email = $('#admin_auth_email').val();
    console.log('working DataTable button');
    $('#famous_country_name1').on('change', function() {
        if ($('#famous_country_name1').val() != '') {
            $('#tourfamouscountry1').hide();
        }
    });
    
    $('#famous_state_name1').on('change', function() {
        if ($('#famous_state_name1').val() != '') {
            $('#tourfamousstate1').hide();

        }
    });
    $('#explain').on('change', function() {
        if ($('#explain').val() != '') {
            $('#tourinternational2').hide();

        }

    });
    var dt = $('#firm_state_table').DataTable(

        {

            "ajax": {
                "paging": true,
                "scrollX": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                "url": "{{eventmie_url('admins/tourstates')}}/" + email,
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
                    "title": "Description",
                    "targets": 3,
                   
                    render: function(data, type, row, meta) {
                        let desc = data.length > 6 ? data.substring(0, 6) + ' ...' : data;
                        data =
                            desc;
                        return data;
                    }
                },

            ],

            columns: [ {
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
                    data: 'Explain'
                },
                {
                data: null,
                render: function(data, type, row) {
                    return '<button type="button"  id="' + data['id'] +
                        '" class="edittripstate btn btn-warning btn-sm ">Edit</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="deleteState btn btn-danger btn-sm ">Delete</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="detailStates btn btn-success btn-sm ">Detail</button>'
                },


            },

            ],
        });

    CKEDITOR.replace('explain');
    $('#create_famous_state').click(function() {
        console.log('working create_famous_state button');
        $('#tourstate').html('(please attach image)');
        $('#famous_country1_form_name').show();
        $('#famous_state_form_name1').show();
    
        $('#famous_state_form')[0].reset();
        $('#famous_state_form_result').html('');
        $('.modal-title1').text("Add Famous Places");
        $('#FamousStateFormModal').modal('show');
        $('#states_action_button').val("Add");
        $('#states_action').val("Add");
        CKEDITOR.instances['explain'].setData('');
    });

    $(document).on('click', '.edittripstate', function() {
        console.log('working edit button');

        var id = $(this).attr('id');

        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/editstate/')}}/" + id,
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log(html);
                $('#state_logo').show();
               

                $('#scountryId').prepend("<option value='"+html.data.slug1+"' selected='selected'>"+html.data.slug1+"</option>").attr('disabled', false);
                $('#sstateId').prepend("<option value='"+html.data.slug+"' selected='selected'>"+html.data.slug+"</option>").attr('disabled', false);

                CKEDITOR.instances['explain'].setData(html.data.Explain);
                
                if (html.data.Image == "default.png") {
                $('#state_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" width="20px" height="20px">');
            } else {
                $('#state_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" width="20px" height="20px">');
            }
                $('.modal-title1').text("Edit State Tour");
                $('#states_action_button').val("Update");
                $('#states_action').val("Update");
                $('#FamousStateFormModal').modal('show');
                $('#admin_hidden_id').val(id);
            }
        })
    });

    
    $('#famous_state_form').on('submit', function(event) {
        event.preventDefault();
        CKEDITOR.instances['explain'].updateElement();
        if ($('#states_action').val() == 'Add') {
            console.log("inside add");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admins/addstatestour')}}/" + email,
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

                    $('#famous_country_name1').has('option').length > 0 
                           {
                            $('#tourfamouscountry1').html("Please  select country field");    
                           }
                           
                           $('#famous_state_name1').has('option').length > 0 
                           {
                            $('#tourfamousstate1').html("Please  select state field");    
                           }

                         

                    if (!$('#explain').val()) {


                        $('#tourinternational2').html("Please  describe  field");
                    }

                },
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#statecategorymodel').modal('show');
                        $('#FamousStateFormModal').modal('hide');
                        $('#famous_state_form')[0].reset();
                        $('#firm_state_table').DataTable().ajax.reload()
                    } else {

                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })
        }
     
        if ($('#states_action').val() == "Update") {
            console.log("update me aa raha");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
     });
            $.ajax({

                    url: "{{ url('admins/updatestate') }}",
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
                        
                            $('#stateupdatemodel').modal('show');
                        $('#FamousStateFormModal').modal('hide');
                        $('#famous_state_form')[0].reset();
                        $('#firm_state_table').DataTable().ajax.reload()
                       
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
    })
  
  
    $(document).on('click', '.detailStates', function() {
        id = $(this).attr('id');
        console.log('working details button');
        console.log(id);
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/detailstates')}}/" + id,

            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log('get quote by firm id :' + html.data.id);
                console.log("yanha aa raha hai");
                var countary = html.data.slug1;
                var state = html.data.slug;
                var describe2 = html.data.Explain;
                $('#StateModal').modal('show');
                 $(".card2").show();
                $('#countary1').html(countary);
                $('#states').html(state);
               $('#stadescriptions').html(describe2);
               if (html.data.Image == "default.png") {
                $('#stamyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" width="200px" height="200px">');
            } else {
                $('#stamyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" width="200px" height="200px">');
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
   $(document).on('click', '.deleteState', function() {
      user_id = $(this).attr('id');
      $('#state_tour_confirmModal').modal('show');
   });

   $('#stateok_button').click(function() {
      console.log('working delete button');
     
      $.ajax({
         type: "get",
         data: {},
         url: "{{url('admins/deletestatetour/')}}/" + user_id,
         headers: {
            "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
         },
         beforeSend: function() {
            $('#stateok_button').text('Deleting');
         },
         success: function (data) {
             $('#state_tour_confirmModal').modal('hide');
            $('#firm_state_table').DataTable().ajax.reload()
              },
              error: function (data) {
                  console.log('Error:', data);
              }
      })
   });


});
</script>
@endsection