@extends('voyager::tripmaster')


@section('content')


<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>


<div id="category" class="row mb-5  ml-1" style="margin-top:4%;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class=" align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="col-md-8">
                        <button type="button" name="create_category" id="create_category" style="font-size:20px;"
                            class="btn btn-success btn-sm">Add
                            Category</button>
                    </div>
                </div>
                <table id="firm_category_table"  style="width: 100%">
                    <thead>
                        <tr>
                            <th data-column_name="training_name" width="20%">Image</th>
                            <th data-column_name="company_name" width="20%">Category</th>
                            <th data-column_name="location" width="15%">Description</th>
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
<div id="CategoryFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">Add Trip category</h5>
            </div>
            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
                <form id="category_form" role="form" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <div class="row">
                             <div class="col-md-12">
                                <div class="form-group" id="category_form_name">
                                    <label class="control-label">Enter New Category</label>
                                    <div class="col-md-12">
                                        <input style="width: 100%; height: 40px;"  type="text" name="category_name" id="category_name" class="form-control"
                                            autocomplete="off" />
                                            </div>
                                    <div style="color:red;" id="tourcategory1"></div>
                                </div>
                            </div>
                        </div>
                   
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Chosen Your Category Image</label>
                                    <div class="col-md-12">
                                        <input style="width: 100%; height: 40px;" type="file" name="category_logo" id="category_logo" class="form-control"
                                            autocomplete="off" />
                                        <div class="col-3" id="image"></div>
                                        </div>
                                    <div style="color:red;" id="tourcategory2"></div>
                                </div>
                            </div>
                        </div>
                   

                    <div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                                        <label class="control-label">Description:</label>
                                        <textarea   class="form-control" id="description"
                                            name="description"></textarea>
                                    </div>
                                    <div style="color:red;" id="tourcategory3"></div>
                            </div>
                        </div>
                    
           
            <div class="form-group" align="center">
                <input type="hidden" name="category_action" id="category_action" />
                <input type="hidden" name="admin_auth_name" id="admin_auth_name" value="{{Auth::user()->name}}">
                <input type="hidden" name="admin_auth_email" id="admin_auth_email" value="{{Auth::user()->email}}">
                <input type="hidden" name="admin_auth_id" id="admin_auth_id" value="{{Auth::user()->id}}">
                <input type="hidden" name="admin_hidden_id" id="admin_hidden_id" />
            </div>
            <input type="submit" name="category_action_button" id="category_action_button"
                class="btn btn-primary btn-info-full" value="Add" />
            <br />
            </form>
            </section> 
              </div>
            </div>
        </div>
    </div>
</div>

<div id="CategoryModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details Category Tour</h5>
            </div>
            <div class="modal-body">
                <span id="category_form_result" aria-hidden="true"></span>
                <div id="card1" class="card1" style="display: none;">
                    <div class="card-body" style="margin-left:6%;">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label><b>Category Name: </b></label>
                                    <label id="name"> </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label><b>Description: </b></label>
                                    <label id="descriptions"> </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label><b>Image: </b></label>
                                    <div class="col-12" id="myimage"></div>
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
        <div id="tcategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light" >
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" >tour category
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
        <div id="tcategoryupdatemodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="exampleModalLabel">tour category
                            updated
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="trainer_class_confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirmation</h3>
                <h4 align="center" style="margin:0;">Are you sure you want to remove this Category?</h4>
                <div align="right">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    email = $('#admin_auth_email').val();
    console.log('working DataTable button');
    $('#category_name').on('change', function() {
        if ($('#category_name').val() != '') {
            $('#tourcategory1').hide();
        }
    });
    $('#description').on('change', function() {
        if ($('#description').val() != '') {
            $('#tourcategory3').hide();

        }
    });
    var dt = $('#firm_category_table').DataTable(
        {
            "ajax": {
                "paging": true,
                "scrollX": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                "url": "{{eventmie_url('admins/tourcategories')}}/" + email,
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
            columnDefs: [

                {
                    "title": "Image",
                    "targets": 0,

                },
                {
                    "title": "Category",
                    "targets": 1,

                },
                {
                    "title": "Description",
                    "targets": 2,

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
                    render: function(data, type, row) {
                        return '<img src="https://www.holidaylandmark.com/category/' + data[
                            'Image'] + '" width="100px" height="50px"/>'
                    },
                },
                {
                    data: 'category'
                },
                {
                    data: 'Description'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<button type="button"  id="' + data['id'] +
                            '" class="edittripCategory btn btn-warning btn-sm ">Edit</button>&nbsp;&nbsp;<button type="button"  id="' +
                            data['id'] +
                            '" class="deleteClass btn btn-danger btn-sm ">Delete</button>&nbsp;&nbsp;<button type="button"  id="' +
                            data['id'] +
                            '" class="detailCategory btn btn-success btn-sm ">Detail</button>'
                    },


                },

            ],
        });

    CKEDITOR.replace('description');
    $('#create_category').click(function() {
        console.log('working create_category button');
        $('#tourcategory2').html('(please attach image(jpeg,png,jpg,gif,svg,webp)');
        $('#image').hide();
        $('#category_form_name').show();
        $('#category_form')[0].reset();
        $('#category_form_result').html('');
        $('.modal-title').text("Add Category");
        $('#CategoryFormModal').modal('show');
        $('#category_action_button').val("Add");
        $('#category_action').val("Add");
        CKEDITOR.instances['description'].setData('');
    });

    $('#category_form').on('submit', function(event) {
        event.preventDefault();
        CKEDITOR.instances['description'].updateElement();
        if ($('#category_action').val() == 'Add') {
            console.log("inside add");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admins/addcategories')}}/" + email,
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
                    if (!$('#category_name').val()) {


                        $('#tourcategory1').html("Please  select category field");
                    }
                    if (!$('#description').val()) {


                        $('#tourcategory3').html("Please  describe  field");
                    }

                },
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#tcategorymodel').modal('show');
                        $('#CategoryFormModal').modal('hide');
                        $('#category_form')[0].reset();
                        $('#firm_category_table').DataTable().ajax.reload()
                    } else {

                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })
        }
        if ($('#category_action').val() == "Update") {
            console.log("update me aa raha");
            $.ajax({

                url: "{{ url('admins/updatecategories') }}",
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
                        $('#tcategoryupdatemodel').modal('show');
                        $('#CategoryFormModal').modal('hide');
                        $('#category_form')[0].reset();
                        $('#firm_category_table').DataTable().ajax.reload()

                    } else {

                    }
                },

                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

    });



    $(document).on('click', '.detailCategory', function() {
        id = $(this).attr('id');
        console.log('working details button');
        console.log(id);
        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/detailcategories')}}/" + id,

            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log('get quote by firm id :' + html.data.id);
                console.log("yanha aa raha hai");
                var categories = html.data.category;
                var describe = html.data.Description;
                $('#CategoryModal').modal('show');

                $(".card1").show();
                $('#name').html(categories);
                $('#descriptions').html(describe);
                if (html.data.Image == "default.png") {
                    $('#myimage').html(
                        '  <img src="https://www.holidaylandmark.com/category/' +
                        html.data.Image +
                        '"  height="180em" Width="1000em">');
                } else {
                    $('#myimage').html(
                        '  <img src="https://www.holidaylandmark.com/category/' +
                        html.data.Image + '"  height="180em" width="1000em">');
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
    $(document).on('click', '.deleteClass', function() {
        user_id = $(this).attr('id');
        $('#trainer_class_confirmModal').modal('show');
    });

    $('#ok_button').click(function() {
        console.log('working delete button');

        $.ajax({
            type: "get",
            data: {},
            url: "{{url('admins/deletecategories/')}}/" + user_id,
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            beforeSend: function() {
                $('#ok_button').text('Deleting');
            },
            success: function(data) {
                $('#trainer_class_confirmModal').modal('hide');
                $('#firm_category_table').DataTable().ajax.reload()
            },
            error: function(data) {
                console.log('Error:', data);
            }
        })
    });

    CKEDITOR.replace('description');
    $(document).on('click', '.edittripCategory', function() {
        console.log('fgvbhfghcfghworking edit button');
        var id = $(this).attr('id');
        console.log(email);
        $.ajax({
            type: "GET",
            data: {},
            url: "{{url('admins/editcat/')}}/" + id,
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
            },
            success: function(html) {
                console.log(html);
                console.log(html.data.category);
                $('#category_logo').show();
                $('#category_name').val(html.data.category);

                CKEDITOR.instances['description'].setData(html.data.Description);

                if (html.data.Image == "default.png") {
                    $('#category_logo').html(
                        '  <img src="https://www.holidaylandmark.com/category/' +
                        html.data.Image +
                        '" width="20px" height="20px">');
                } else {
                    $('#category_logo').html(
                        '  <img src="https://www.holidaylandmark.com/category/' +
                        html.data.Image + '" width="20px" height="20px">');
                }

                $('#CategoryFormModal').modal('show');
                $('.modal-title').text("Edit category");
                $('#category_action_button').val("Update");
                $('#category_action').val("Update");
                $('#admin_hidden_id').val(id);

            }
        })
    });
});
</script>
@endsection