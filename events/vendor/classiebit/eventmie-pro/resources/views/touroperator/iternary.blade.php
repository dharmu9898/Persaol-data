
@extends('voyager::tourmaster')

@section('content')

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="{{asset('js/jquery.min.js')}}"> </script>
<script src="{{asset('js/location.js')}}"> </script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<div class="row mb-5 mt-5 ml-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="row align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
            <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                        <div style="margin-top:28px;">

                                <span class="float-left">
                                    <button type="button" name="create_iternary" id="create_iternary"
                                        class="btn btn-success btn-sm" style="font-size:23px;">Add
                                Iternary</button>
                                </span>


                                <span class="float-left ml-4" style="margin-top:8px;">
                            <input id="iternarysearch" placeholder="Search by Title,Days,Etc...."
                                class="float-left py-2 px-5" name="iternarysearch" type="text">
                                </span>
                        </div>
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table id="trip_iternary_opertor" class="table table-bordered table-striped bg-light"
                        style="color:white; border:none">
                        <thead class="bg-light" style="color:black">
                            <tr>

                                <th data-column_name="iternary_iternary_title" width="20%">Title</th>
                                <th data-column_name="iternary_trip_days" width="20%">Days</th>
                                <th data-column_name="iternary_trip_location" width="20%">Location</th>
                                <th data-column_name="iternary_trip_desc" width="20%">Description</th>
                                <th width="20%">Action</th>
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

<div id="IternaryFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="iternaryModaltouropertor">Add Iternary</h5>
               
            </div>
            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
                        <form id="iternary_form" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">Trip Title:</label>
                                        <div class="col-md-12">
                                            <select style="width: 100%; height: 40px;" name="iternary_title" id="iternary_title" class="form-control">
                                                <option value="default">Select Title</option>
                                            </select>
                                        </div>
                                        <div style="color:red;" id="iternarytrip_title"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" id="iternary_day">
                                        <label class="control-label">Days</label>
                                        <select style="width: 100%; height: 40px;" placeholder="iternarylocation" name="iternary_days" id="iternary_days" class="form-control">
                                           

                                        </select>
                                        <div style="color:red;" id="iternaryday"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="iternary_location">
                                        <label class="control-label">location:</label>
                                        <input style="width: 100%; height: 40px;" type="text" name="iternarylocation" id="iternarylocation"
                                            class="form-control" value="{{ old('iternarylocation') }}"
                                            placeholder="iternarylocation">
                                    </div>
                                    <div style="color:red;" id="iternaryloc"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="iternary_description">
                                        <label class="control-label">Description:</label>
                                        <textarea  class="form-control" id="iternarydescription"
                                            name="iternarydescription"></textarea>
                                    </div>
                                    <div style="color:red;" id="iternarydesc"></div>
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <input type="hidden" name="iternary_action" id="iternary_action" />
                                <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                    value="{{Auth::user()->id}}">
                                    <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                            </div>
                            <input type="submit" name="iternary_action_button" id="iternary_action_button"
                                class="btn btn-primary btn-info-full" value="Add" />
                            <br />
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="IterTouropertorlModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Details Iternary</h5>
               
            </div>
            <div class="modal-body">
                <span id="iternary_tour_result" aria-hidden="true"></span>

                <div class="card">

                    <div class="card-body">
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Title:</strong>
                            <label id="title"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Days:</strong>
                            <label id="days"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Location:</strong>
                            <label id="location"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Description:</strong>
                            <label id="description"> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div id="iternary_success_modal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="iternaryModaltouropertor">Iternary
                            From created
                            successfully</h5>
                      
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="iternaryModaltouropertor">Iternary
                            From updated
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="iternary_confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirmation</h3>

            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this Iternary?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="iter_ok_button" id="iter_ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    email = $('#operator_auth_email').val();
    console.log('working DataTable button');
    $('#iternary_title').on('change', function() {
        if ($('#iternary_title').val() != '') {
            $('#iternarytrip_title').hide();
        }
    });
    $('#iternary_days').on('change', function() {
        if ($('#iternary_days').val() != '') {
            $('#iternaryday').hide();
        }
    });

    $('#iternarylocation').on('change', function() {
        if ($('#iternarylocation').val() != '') {
            $('#iternaryloc').hide();

        }

    });





    $(function() {
        console.log('command ka function me hai');
        var curtpage = 1,
            pagelimit = 10,
            totalrecord = 0;
        query = '';
        fetchiternaryData(curtpage, query);
        // handling the prev-btn
        $(".prev-btn").on("click", function() {
            if (curtpage > 1) {
                curtpage--;
            }
            console.log("Prev Page: " + curtpage);
            fetchiternaryData(curtpage, query);
        });
        $(document).on('keyup', '#iternarysearch', function() {
            console.log('search input click hua');
            var query = $('#iternarysearch').val();
            console.log(query);
            var curtpage = 1;
            pagelimit = 10,
                totalrecord = 0;
            fetchiternaryData(curtpage, query);
        });


        CKEDITOR.replace('iternarydescription');
    $('#create_iternary').click(function() {
        console.log('working create_iternary button');
        $('#iternary_title').show();
        $('#iternary_days').show();
        $('#iternary_location').show();
        $('#iternary_description').show();
        $('#iternary_form')[0].reset();
        $('#iternary_tour_result').html('');
        $('.modal-title').text("Add Iternary");
        $('#IternaryFormModal').modal('show');
        $('#iternary_action_button').val("Add");
        $('#iternary_action').val("Add");
        CKEDITOR.instances['iternarydescription'].setData('');
    });

        $('#iternary_form').on('submit', function(event) {
            event.preventDefault();
            CKEDITOR.instances['iternarydescription'].updateElement();

            if ($('#iternary_action').val() == 'Add') {
                console.log("inside add");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    url: "{{eventmie_url('touroperator/additernary')}}/" + email,
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

                        if ($('#iternary_title').has('option').length > 0) {
                            $('#iternarytrip_title').html(
                                "Please  fill iternary trip field");
                        }

                        if (!$('#iternary_days').val()) {
                            $('#iternaryday').html(
                                "Please  fill iternary days  field");
                        }


                        if (!$('#iternarylocation').val()) {
                            $('#iternaryloc').html(
                                "Please  fill iternary location  field");
                        }



                    },
                    success: function(data) {
                        console.log("after before");
                        if (data.success) {
                            console.log("after data success");
                            $('#iternary_success_modal').modal('show');
                            $('#IternaryFormModal').modal('hide');
                            var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchiternaryData(curtpage, query);
                        } else {

                        }
                    },
                    error: function(data) {
                        console.log("error coming");
                        console.log('Error:', data);
                    }
                })
            }
            if ($('#iternary_action').val() == "Update") {
                console.log("update me aa raha");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    url: "{{ eventmie_url('touroperator/updateiternary') }}",
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
                            $('#IternaryFormModal').modal('hide');
                            $('#tinternationalupdatemodel').modal('show');
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1200);
                            var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchiternaryData(curtpage, query);
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
        $(document).on('click', '.deleteIter', function() {
            id = $(this).attr('id');
            $('#iternary_confirmModal').modal('show');
        });

        $('#iter_ok_button').click(function() {
            console.log('working delete button');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/destroyiter')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#iter_ok_button').text('Deleting');
                },
                success: function(data) {
                    $('#iternary_confirmModal').modal('hide');
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchiternaryData(curtpage, query);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            })
        });

        $(document).on('click', '.detailIternary', function() {
            id = $(this).attr('id');
            console.log('working details button');
            console.log(id);
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/detailiter')}}/" + id,

                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log('get quote by firm id :' + html.data.id);
                    console.log("yanha aa raha hai");
                    var title = html.data.trips;
                    var days = html.data.Days;
                    var location = html.data.location;
                    var description = html.data.explanation;


                    $('#IterTouropertorlModal').modal('show');


                    $(".cardop").show();
                    $('#title').html(title);
                    $('#days').html(days);
                    $('#location').html(location);
                    $('#description').html(description);




                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });


        // Edit Function Start
        $(document).on('click', '.editIter', function() {
            console.log('working editIter button');
            // $('#command_sample_form').show();
            var id = $(this).attr('id');
            // $('#form_command').html('');
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/edititer/')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {

                   
                   
                    var day= html.day;
                   var days =html.data.Days;
                   console.log(day);
                   console.log(days);
                    $('#iternary_days').prepend("<option value>  " +
                    days + "</option>");
                    for (var i = 1; i <= day; i++) {
                            $('#iternary_days').append("<option value=  '" + i +
                                "'> day " +
                                i + "</option>");
                        }

                        $('#iternary_title').val(html.data.trips);
                    $('#iternarylocation').val(html.data.location);
                    CKEDITOR.instances['iternarydescription'].setData(html.data
                        .explanation);

                    $('.modal-title').text("Edit Iternary Data");
                    $('#iternary_action_button').val("Update");
                    $('#iternary_action').val("Update");
                    $('#IternaryFormModal').modal('show');
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
            fetchiternaryData(curtpage, query);
        });
        $(document).on('click', '.link-click', function(event) {
            curtpage = $(this).attr('page');
            $(this).addClass('active');
            console.log(this);
            fetchiternaryData(curtpage, query);
        });

        function fetchiternaryData(page, query) {
            var email = $('#operator_auth_email').val();
            console.log('command ka fetchiternary function');
            $.ajax({
                url: "{{eventmie_url('touroperator/iternarydetail')}}/" + email,
                type: "GET",
                data: "page=" + page + "&query=" + query,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html.data);
                    $.each(html.alltrip, function(i, markiternary) {
                        console.log('all category here: ' + markiternary.TripTitle);
                        $('#iternary_title').append("<option value='" + markiternary
                            .TripTitle + "'>" +
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


                                "<td>" + dataArr[i].trips + "</td>" +
                                "<td>" + dataArr[i].Days + "</td>" +
                                "<td>" + dataArr[i].location + "</td>" +
                                "<td>" + dataArr[i].explanation + "</td>" +
                                "<td> <button type='button'  id='" + dataArr[i].id +
                                "'class='editIter btn btn-warning btn-sm'>Edit</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='deleteIter btn btn-danger btn-sm'>Delete</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='detailIternary btn btn-success btn-sm'>Detail</button></td>" +
                                "</tr>" +
                                "<hr />";
                        }
                        $("#trip_iternary_opertor tbody").html(html);
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

$('#iternary_title').on('change', function() {

console.log('slug name change');
if (this.value != 'default') {
    ajaxCall();

} else {
    $('#iternary_days').empty();
    $('#iternary_days').append('<option> Select iternary_days </option>');
}


function ajaxCall() {
    $('#iternary_days option:selected').remove();
    console.log('in ajaxCall');
    var iternary_title = $("#iternary_title option:selected").val();
    console.log(iternary_title);
    $.ajax({

        type: "get",
        data: {},
        url: "{{eventmie_url('touroperator/iternary_day/')}}/" + iternary_title,
        headers: {
            "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
        },

        success: function(html) {


            $('#iternary_days').html('');

            $.each(html.data, function(i, myiternaryday) {
                console.log('inside iternary days');
                var day = myiternaryday.NoOfDays;
                console.log(day);

                for (var i = 1; i <= day; i++) {
                    $('#iternary_days').append("<option value=  '" + i +
                        "'> day " +
                        i + "</option>");


                }


            });

        },
        error: function(errorResponse) {
            console.log(errorResponse);
        }
    });
}
});
</script>


@endsection