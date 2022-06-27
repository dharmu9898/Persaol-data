@extends('voyager::tripmaster')

@section('content')
    <!-- Theme style -->
    

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
      <script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
 
<div id="country" class="row mb-5 ml-4" style="margin-top:4%;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="col-md-8">
                        <button type="button" name="create_international" id="create_international" style="font-size:20px;"
                            class="btn btn-success btn-sm">Add
                            International Tour</button>
                    </div>
                    <br>
                    <br>
                </div>
                <table id="firms_international_table"  style="width: 100%;">
                    <thead>
                        <tr class="table ">
                      
                            <th  width="15%">Image</th>
                            <th  width="15%">Country</th>
                            <th width="15%">interdescription</th>
                            <th width="20%">Action</th>
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
<div id="InternationalFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModaltour">Add International Tour</h5>
            </div>

            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
                <form id="international_form" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="international_form_name">
                                        <label class="control-label">Chosen Your Country</label>                            
                                        <select style="width: 100%; height: 40px;" name="countryId" class="countries w-100 p-2" id="countryId"
                                                    value="{{old('country')}}" required>
                                                    <option value=""> Select Country </option>
                                                </select>
                                        <div style="color:red;" id="tourinternational"></div>
                                    </div>
                            </div>
                        </div>

                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="control-label">Chosen Your International Tour Image</label>
                                            <input style="width: 100%; height: 40px;" type="file" name="international_logo" id="international_logo"
                                                class="form-control" autocomplete="off" />
                                                <div class="col-3" id="international_image"></div>
                                        <div style="color:red;" id="tourinternational1"></div>
                                    </div>
                                </div>
                            </div>

                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group" >
                                        <label class="control-label">description:</label>
                                            <textarea class="form-control" id="interdescription"
                                                name="interdescription"></textarea>
                                        <div style="color:red;" id="tourinternational2"></div>
                                    </div>
                            </div>
                            </div>

                           
                            <div class="form-group" align="center">
                                <input type="hidden" name="international_action" id="international_action" />
                                <input type="hidden" name="admin_auth_name" id="admin_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="admin_auth_email" id="admin_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="admin_auth_id" id="admin_auth_id"
                                    value="{{Auth::user()->id}}">
                                <input type="hidden" name="admin_hidden_id" id="admin_hidden_id" />
                            </div>
                            <input type="submit" name="international_action_button" id="international_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                            <br />
                        </form>
                        </section> 
                </div>
            </div>
        </div>
    </div>
</div>


<div id="InternationalModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details International Tour</h5>
               
            </div>
            <div class="modal-body">
                <span id="international_form_result" aria-hidden="true"></span>
                <div id="card" class="card" style="display: none;">
    <div class="card-body" style="margin-left:6%;">
           <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label><b>Country: </b></label>
                    <label id="countary"> </label>
                </div>
            </div>
          
            <div class="col-12">
                <div class="form-group">
                    <label><b>Description: </b></label>
                    <label id="indescriptions"> </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label><b>Image: </b></label>
                    <div class="col-12" id="inmyimage"></div>
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
        <div id="intercategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >International Tour
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
        <div id="tinternationalupdatemodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >International tour
                            updated
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="inter_tour_confirmModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h3 class="modal-title">Confirmation</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 align="center" style="margin:0;">Are you sure you want to remove this International?</h4>
            <div align="right"> 
            <button type="button" name="inok_button" id="inok_button" class="btn btn-danger">OK</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>
</div>


<script>
$(document).ready(function() {
    email = $('#admin_auth_email').val();
    console.log('working DataTable button');
    $('#countryId').on('change', function() {
        if ($('#countryId').val() != '') {
            $('#tourinternational').hide();
        }
    });
    $('#interdescription').on('change', function() {
        if ($('#interdescription').val() != '') {
            $('#tourinternational2').hide();

        }

    });
    var dt = $('#firms_international_table').DataTable(

        {
            "ajax": {
                "paging": true,
                "scrollX": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                "url": "{{eventmie_url('admins/tourinternational')}}/" + email,
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
            columnDefs:  [
             
             {
               "title": "Image",
               "targets": 0,
              
            },
            {
               "title": "Country",
               "targets": 1,
              
            },
            {
               "title": "interdescription",
               "targets": 2,
              
               render: function(data, type, row, meta) {
                  let desc = data.length > 6 ? data.substring(0, 6) + ' ...' : data;
                  data =
                     desc;
                  return data;
               }
            },
           
         ],

         columns: [
            {
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
               data: 'desc'
            },
            {
                data: null,
                render: function(data, type, row) {
                    return '<button type="button"  id="' + data['id'] +
                        '" class="edittripInter btn btn-warning btn-sm ">Edit</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="deleteInter btn btn-danger btn-sm ">Delete</button>&nbsp;&nbsp;<button type="button"  id="' +
                        data['id'] +
                        '" class="detailInternal btn btn-success btn-sm ">Detail</button>'
                },
            },
         ],
      });

    CKEDITOR.replace('interdescription');
    $('#create_international').click(function() {
        console.log('working create_international button');
        $('#tourinternational1').html('(please attach image)');
        $('#international_form_name').show();
        $('#international_form')[0].reset();
        $('#international_form_result').html('');
        $('.modal-title').text("Add International Tour");
        $('#InternationalFormModal').modal('show');
        $('#international_action_button').val("Add");
        $('#international_action').val("Add");
        CKEDITOR.instances['interdescription'].setData('');
    });


    $(document).on('click', '.edittripInter', function() {
        console.log('working edit button');

        var id = $(this).attr('id');

        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/editinter/')}}/" + id,
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log(html);
                $('#international_logo').show();
               

                $('#countryId').prepend("<option value='"+html.data.slug+"' selected='selected'>"+html.data.slug+"</option>").attr('disabled', false);

                CKEDITOR.instances['interdescription'].setData(html.data.desc);
                
                if (html.data.Image == "default.png") {
                $('#international_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" width="20px" height="20px">');
            } else {
                $('#international_image').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" width="20px" height="20px">');
            }
                $('.modal-title').text("Edit International Tour");
                $('#international_action_button').val("Update");
                $('#international_action').val("Update");
                $('#InternationalFormModal').modal('show');
                $('#admin_hidden_id').val(id);
            }
        })
    });
     $('#international_form').on('submit', function(event) {
        event.preventDefault();
        CKEDITOR.instances['interdescription'].updateElement();
        if ($('#international_action').val() == 'Add') {
            console.log("inside add");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
     });
        
            $.ajax({
                url: "{{url('admins/addinternational')}}/" + email,
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

                    $('#countryId').has('option').length > 0 
                           {
                            $('#tourinternational').html("Please  select country field");    
                           }
                    if (!$('#interdescription').val()) {
                        $('#tourinternational2').html("Please  describe  field");
                    }

                },
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#intercategorymodel').modal('show');
                        $('#InternationalFormModal').modal('hide');
                        $('#international_form')[0].reset();
                        $('#firms_international_table').DataTable().ajax.reload()
                    } else {

                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })
        }
        if ($('#international_action').val() == "Update") {
            console.log("update me aa raha");
            $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
        }
     });
            $.ajax({

                    url: "{{ url('admins/updateinternal') }}",
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
                    
                        $('#tinternationalupdatemodel').modal('show');
                        $('#InternationalFormModal').modal('hide');
                        $('#international_form')[0].reset();
                        $('#firms_international_table').DataTable().ajax.reload()

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


    $(document).on('click', '.detailInternal', function() {
        id = $(this).attr('id');
        console.log('working details button');
        console.log(id);
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/detailinternal')}}/" + id,

            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log('get quote by firm id :' + html.data.id);
                console.log("yanha aa raha hai");
                var countary = html.data.slug;
                var describe1 = html.data.desc;
                $('#InternationalModal').modal('show');
              
               
                $(".card").show();
                $('#countary').html(countary);
               $('#indescriptions').html(describe1);
               if (html.data.Image == "default.png") {
                $('#inmyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image +
                    '" height="180em" Width="1000em">');
            } else {
                $('#inmyimage').html('  <img src="https://www.holidaylandmark.com/category/' + html.data.Image + '" height="180em" Width="1000em">');
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
   $(document).on('click', '.deleteInter', function() {
      user_id = $(this).attr('id');
      $('#inter_tour_confirmModal').modal('show');
   });

   $('#inok_button').click(function() {
      console.log('working delete button');
     
      $.ajax({
         type: "get",
         data: {},
         url: "{{url('admins/deleteinternaltour/')}}/" + user_id,
         headers: {
            "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
         },
         beforeSend: function() {
            $('#inok_button').text('Deleting');
         },
         success: function (data) {
             $('#inter_tour_confirmModal').modal('hide');
            $('#firms_international_table').DataTable().ajax.reload()
              },
              error: function (data) {
                  console.log('Error:', data);
              }
      })
   });
});
</script>
@endsection