@extends('voyager::tourmaster')

@section('content')
<style>
.myimg {

    border-radius: 50%;
    padding: 5px;
    width: 80px;
    height: 80px;
}
</style>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<div class="row mb-5 ml-2 mt-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="row align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div style="margin-top:28px;">

                                <span class="float-left">
                                    <button type="button" name="create_trip_image" id="create_trip_image"
                                        class="btn btn-success btn-sm" style="font-size:20px;">Add
                                        Media</button>
                                </span>
                                <span class="float-right ml-4" style="margin-top:8px;">
                                    <input id="trip_image_search" placeholder="Search by Trip"
                                        class="float-left  py-1 px-5" name="trip_image_search" type="text">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="trip_image_table" class="table table-bordered table-striped bg-light"
                        style="color:white; border:none">
                        <thead class="bg-light" style="color:black">
                            <tr>
                                <th data-column_name="trip_image_title" width="15%">Image</th>
                                <th data-column_name="trip_video_title" width="25%">Video</th>
                                <th data-column_name="image_trip_title" width="35%">Trip</th>

                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody style="color:black" id="myTable">
                        </tbody>
                    </table>
                </div>

                <div class="nav-btn-container">
                    <button class="btn btn-danger prev-btn mr-1">Prev</button>
                    <span id="pages"></span>
                    <button class="btn btn-success next-btn">Next</button>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="users.id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
            </div>
        </div>
    </div>
</div>
<div id="ImageFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="TripimageModaltouropertor">Add Image</h5>
              
            </div>
            <div class="modal-body">
                <span id="image_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
                        <form id="image_form" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="tourimagetitle">
                                        <label class="control-label">Trip Title:</label>
                                        <div class="col-md-12">
                                            <select style="width: 100%; height: 40px;" name="image_title" id="image_title" class="form-control">
                                                <option value="">Select Title</option>
                                            </select>
                                        </div>
                                        <div style="color:red;" id="imagetrip_title"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="tourimages">
                                        <label class="control-label">Chosen Your Tour Image</label>
                                        <div class="col-md-12">

                                            <input style="width: 100%; height: 40px;" type="file" name="image_logo[]" multiple id="image_logo"
                                                class="form-control" autocomplete="off" />
                                            <div class="col-3" id="trip_image_logo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="youtube_link">
                                        <label class="control-label">YouTube Video URL:</label>
                                        <input style="width: 100%; height: 40px;" type="text" name="youtube_url" id="youtube_url"
                                            class="form-control" value="{{ old('youtube_url') }}"
                                            placeholder="youtube_video_url">
                                    </div>
                                    <div style="color:red;" id="youtube_url"></div>
                                </div>
                            </div>

                            <div class="form-group" align="center">
                                <input type="hidden" name="trip_image_action" id="trip_image_action"/>
                                <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                    value="{{Auth::user()->id}}">
                                <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                            </div>
                            <input type="submit" name="trip_image_action_button" id="trip_image_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="TouropertorlModalImage" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details Tour Images</h5>
                
            </div>
            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="card">
                    <div class="card-body">
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Trip Title:</strong>
                            <label id="trip_title"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Image:</strong>
                            <label id="trip_Image"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Video:</strong>
                            <label id="trip_Video"> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div id="tripimage_success_modal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="TripimageModaltouropertor">Trip Image
                            created successfully
                        </h5>
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
                        <h5 style="color:green;" class="tool-modal-title" id="TripimageModaltouropertor">Trip Image
                            updated
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

<div id="images_confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirmation</h3>
            
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this Image?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="imag_ok_button" id="imag_ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    email = $('#operator_auth_email').val();
    console.log('working DataTable button');
    $('#image_title').on('change', function() {
        if ($('#image_title').val() != '') {
            $('#imagetrip_title').hide();
        }
    });
    $('#image_logo').on('change', function() {
        if ($('#image_logo').val() != '') {
            $('#trip_image_logo').hide();
        }
    });

    $('#youtube_url').on('change', function() {
        if ($('#youtube_url').val() != '') {
            $('#youtube_video_url').hide();
        }
    });

    $(function() {
        console.log('command ka function me hai');
        var curtpage = 1,
            pagelimit = 10,
            totalrecord = 0;
        query = '';
        fetchimageData(curtpage, query);
        // handling the prev-btn
        $(".prev-btn").on("click", function() {
            if (curtpage > 1) {
                curtpage--;
            }
            console.log("Prev Page: " + curtpage);
            fetchimageData(curtpage, query);
        });
        $(document).on('keyup', '#trip_image_search', function() {
            console.log('search input click hua');
            var query = $('#trip_image_search').val();
            console.log(query);
            var curtpage = 1;
            pagelimit = 10,
                totalrecord = 0;
            fetchimageData(curtpage, query);
        });

        $('#create_trip_image').click(function() {
            console.log('working create_trip_image button');
            $('#tourimagetitle').show();
            $('#tourimages').show();
            $('#youtube_link').show();
            $('#image_form')[0].reset();
            $('#image_tour_result').html('');
            $('.modal-title').text("Add Media");
            $('#ImageFormModal').modal('show');
            $('#trip_image_action_button').val("Add");
            $('#trip_image_action').val("Add");
        });

        $('#image_form').on('submit', function(event) {
            event.preventDefault();

            if ($('#trip_image_action').val() == 'Add') {
                console.log("inside add");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    url: "{{eventmie_url('touroperator/addimage')}}/" + email,
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

                        if ($('#image_title').has('option').length > 0) {
                            $('#imagetrip_title').html(
                                "Please  fill trip title field");
                        }

                        if (!$('#image_logo').val()) {
                            $('#trip_image_logo').html("Please  choose image");
                        }
                        if (!$('#youtube_url').val()) {
                            $('#youtube_video_url').html("Please  fill url  field");
                        }

                    },
                    success: function(data) {
                        console.log("after before");

                        if (data.success) {
                            console.log("after data success");
                            $('#tripimage_success_modal').modal('show');
                            $('#ImageFormModal').modal('hide');
                            setTimeout(function() {
                                window.location.reload(true);

                            }, 1200);
                            var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchimageData(curtpage, query);
                        } else {

                        }
                    },
                    error: function(data) {
                        console.log("error coming");
                        console.log('Error:', data);
                    }
                })
            }
            if ($('#trip_image_action').val() == "Update") {
                console.log("update me aa raha");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    url: "{{ eventmie_url('touroperator/updateimage') }}",
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
                            $('#ImageFormModal').modal('hide');
                            $('#tinternationalupdatemodel').modal('show');
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1200);
                            var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchimageData(curtpage, query);

                        } else {

                        }
                    },

                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

        });

        var id;
        $(document).on('click', '.deleteImage', function() {
            id = $(this).attr('id');
            $('#images_confirmModal').modal('show');
        });

        $('#imag_ok_button').click(function() {
            console.log('working delete button');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/destroyimage')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#imag_ok_button').text('Deleting');
                },
                success: function(data) {
                    $('#images_confirmModal').modal('hide');
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchimageData(curtpage, query);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            })
        });

        $(document).on('click', '.detailImage', function() {
            id = $(this).attr('id');
            console.log('working details button');
            console.log(id);
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/detailimage')}}/" + id,

                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log('get quote by firm id :' + html.data.id);
                    console.log("yanha aa raha hai");
                    var name = html.data.trips;


                    $('#TouropertorlModalImage').modal('show');


                    $(".cardop").show();
                    $('#trip_title').html(name);
                    if (html.data.image_name == "default.png") {
                        $('#trip_Image').html(
                            '  <img src="https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="220px" height="150px">');
                    } else {
                        $('#trip_Image').html(
                            '  <img src="https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="220px" height="150px">');
                    }
                   
                    if (html.data.video =="default.png" ) {
                        $('#trip_Video').html(
                            '  <iframe width="250px" height="100px"  src="https://www.youtube.com/embed/' +
                            html.data.video +'" ></iframe>');
                    } else {
                        $('#trip_Video').html(
                            '  <iframe width="250px" height="100px"  src="https://www.youtube.com/embed/' +
                            html.data.video +'" ></iframe>');
                    }




                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });


        // Edit Function Start
        $(document).on('click', '.editImage', function() {
            console.log('working editImage button');
            // $('#command_sample_form').show();
            var id = $(this).attr('id');
            // $('#form_command').html('');
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/editimage/')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html);
                    $('#image_logo').show();
                    $('#tourimagetitle').show();
                    $('#tourimages').show();
                    $('#youtube_link').show();
                    $('#image_title').val(html.data.trips);
                    $('#youtube_url').val(html.data.video_link);
                    if (html.data.image_name == "default.png") {
                        $('#trip_image_logo').html(
                            '  <img src="https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="20px" height="20px">');
                    } else {
                        $('#trip_image_logo').html(
                            '  <img src=https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="20px" height="20px">');
                    }
                    $('.modal-title').text("Edit Image Data");
                    $('#trip_image_action_button').val("Update");
                    $('#trip_image_action').val("Update");
                    $('#ImageFormModal').modal('show');
                    $('#operator_hidden_id').val(id);
                }
            })
        });
        // handling the next-btn
        $(".next-btn").on("click", function() {
            if (curtpage * pagelimit < totalrecord) {
                curtpage++;
            }
            console.log("Next Page: " + curtpage);
            fetchimageData(curtpage, query);
        });
        $(document).on('click', '.link-click', function(event) {
            curtpage = $(this).attr('page');
            $(this).addClass('active');
            console.log(this);
            fetchimageData(curtpage, query);
        });

        function fetchimageData(page, query) {
            var email = $('#operator_auth_email').val();
            console.log('command ka fetchiternary function');
            $.ajax({
                url: "{{eventmie_url('touroperator/imagedetail')}}/" + email,
                type: "GET",
                data: "page=" + page + "&query=" + query,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html.data);
                    $.each(html.alltrip, function(i, markiternary) {
                        console.log('all category here: ' + markiternary.TripTitle);

                        $('#image_title').append("<option value='" + markiternary
                            .TripTitle + "' >" +
                            markiternary.TripTitle + "</option>");

                    });



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
                        $("#pages").html(link);
                        var html = "";
                        for (var i = 0; i < dataArr.length; i++) {
                            html += "<tr>" +
                                "<td> <img src=https://www.holidaylandmark.com/category/" +
                                dataArr[i].image_name + " class='myimg'/ > </td>" +
                                "<td> <iframe width='250' height='100' src=https://www.youtube.com/embed/" +
                                dataArr[i].video + " frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'allowfullscreen></iframe></td>" +
                                "<td>" + dataArr[i].trips + "</td>" +
                                "<td> <button type='button'  id='" + dataArr[i].id +
                                "'class='editImage btn btn-warning btn-sm'>Edit</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='deleteImage btn btn-danger btn-sm'>Delete</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='detailImage btn btn-success btn-sm'>Detail</button></td>" +
                                "</tr>" +
                                "<hr />";
                        }
                        $("#trip_image_table tbody").html(html);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }
    });
});
</script>
@endsection