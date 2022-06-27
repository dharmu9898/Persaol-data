@extends('voyager::tripmaster')

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
<link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<div class="row mb-5 mt-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class=" align-items-center">
            <div class=" container-fluid col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div style="margin-top:22px;">
                                <span class="float-left">
                                    <a href='{{eventmie_url("admins/permission")}}' style="font-size:20px;"
                                        class="btn btn-primary text-white">Trip
                                        Permission</a>
                                </span>
                                <span class="float-left ml-2">
                                    <div class="card-header">
                                        <h3 style="font-size:20px;" class="btn btn-primary ">Total
                                            Subscriber: <label id="totalsub">
                                            </label></h3>
                                    </div>
                                </span>
                                <span class="float-left ml-2">
                                    <div class="card-header">
                                        <h3 style="font-size:20px;" class="btn btn-primary ">Total Trip:
                                            <label id="totaltrip">
                                            </label>
                                        </h3>
                                    </div>
                                </span>
                                <span class="float-right ml-5" style="margin-top:08px; margin-left:10px;">
                                    <input id="tripsearch" style="width:110%; height:40px;"
                                        placeholder="Search by Title, Country, State, City, Email"
                                        class="float-right  py-2 px-4" name="tripsearch" type="text">
                                </span>
                            </div>
                        </div>
                        <br>
                        <br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <select style="height:40px;" class="form-control countries" name="manual_filter_table"
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


                <table id="trip_tour_opertor" class="table table-bordered table-striped bg-light"
                    style="color:white; border:none">
                    <thead class="bg-light" style="color:black">
                        <tr>
                            <th data-column_name="profile_name" width="15%">Image</th>
                            <th data-column_name="profile_country" width="15%">Title</th>
                            <th data-column_name="profile_country" width="10%">Country</th>
                            <th data-column_name="profile_State" width="15%">State</th>
                            <th data-column_name="profile_City" width="10%">City</th>
                            <th data-column_name="profile_email" width="10%">Subscriber</th>
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

<div id="ListModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Rvsp</h5>
            </div>
            <div class="modal-body">
                <span id="trip_list_result" aria-hidden="true"></span>


                <div class="card-body">
                    <div class="card-body shadow" style="border-bottom:3px solid red;">
                        <form id="list_form" role="form" enctype="multipart/form-data">
                            <div class="table-responsive">
                                <table id="listTable" class="table table-bordered table-striped bg-light"
                                    style="color:white; border:none">
                                    <thead class="bg-light" style="color:black">
                                        <tr>
                                            <th class="mob"> All<input type="checkbox" id="select-all" name="selectAll"
                                                    value="all"
                                                    style="width: 2.0em;height: 1.0em; border: 2px solid red;"></th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody style="color:black" id="myTable">
                                    </tbody>
                                </table>

                                </h2>
                                <div class="form-group" align="center">
                                    <input type="hidden" name="list_action" id="list_action" />
                                    <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                        value="{{Auth::user()->name}}">
                                    <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                        value="{{Auth::user()->email}}">
                                    <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                        value="{{Auth::user()->id}}">
                                    <input type="hidden" name="operators_hidden_id" id="operators_hidden_id" />
                                </div>
                                <input type="submit" name="list_action_button" id="list_action_button"
                                    class="btn btn-primary btn-info-full" value="Add" />
                                <br />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="TripFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="">Add Trip</h5>
            </div>
            <div class="modal-body">
            <form id="trip_form" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                        <div class="form-group">
                                    <label class="control-label">Category Name:</label>
                                    <select style="height:40px;" name="category_slug" id="category_slug" class="form-control">
                                        <option value="">Select Category</option>
                                    </select>
                                    <div style="color:red;" id="tourcategory"></div>
                                </div>
                        </div>

                        <div class="col-6">
                        <div class="form-group" id="trip_title">
                                    <label class="control-label">Trip Title</label>
                                    <input style="height:40px;" type="text" name="my_trip" id="my_trip" class="form-control"
                                        value="{{ old('my_trip') }}" placeholder="my_trip">
                                    <div style="color:red;" id="tourtitle"></div>
                                </div>
                        </div>
                    </div>

                   
                    <div class="row">
                        <div class="col-6">
                        <div class="form-group" id="country_form_name">
                                    <label class="control-label">Chosen Your Country</label>
                                    <select style="width: 100%; height: 40px;" name="tourcountryId"
                                        class="countries w-100 p-2" id="tourcountryId" value="{{old('country')}}"
                                        required>
                                        <option value=""> Select Country </option>
                                    </select>
                                    <div style="color:red;" id="tripcountry"></div>
                                </div>
                        </div>

                        <div class="col-6">
                        <div class="form-group" id="state_form_name">
                                    <label class="control-label">Chosen Your State</label>
                                    <select style="width: 100%; height: 40px;" name="tourstate" class="states w-100 p-2"
                                        id="tourstate" value="{{old('state')}}" required>
                                        <option value="">Select state </option>
                                    </select>
                                    <div style="color:red;" id="tripstate"></div>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                        <div class="form-group" id="city_form_name">
                                    <label class="control-label">Chosen Your City</label>
                                    <select style="width: 100%; height: 40px;" name="tourcity" class="cities w-100 p-2"
                                        id="tourcity" value="" required>
                                        <option value="">Select City </option>
                                    </select>
                                    <div style="color:red;" id="tripcity"></div>
                                </div>
                        </div>

                        <div class="col-3">
                        <div class="form-group" id="tour_days">
                                    <label class="control-label">Num of days:</label>
                                    <input style="width: 100%; height: 40px;" type="text" name="NoOfDays" id="NoOfDays" class="form-control"
                                        pattern="[0-9]{1,2}" title="starts with digit 1,2.."
                                        value="{{ old('NoOfDays') }}" placeholder="NoOfDays(only digit)">
                                    <div style="color:red;" id="tourdays"></div>
                                </div>
                        </div>
                        <div class="col-3">
                        <div class="form-group" id="tour_night">
                                    <label class="control-label">Num of night:</label>
                                    <input style="width: 100%; height: 40px;" type="text" name="NoOfNight" id="NoOfNight" class="form-control"
                                        pattern="[0-9]{1,2}" title="starts with digit like 1,2.."
                                        value="{{ old('NoOfNight') }}" placeholder="NoOfNight(only digit like 1,2)">
                                    <div style="color:red;" id="tournight"></div>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                        <div class="form-group" id="tour_dates">
                                    <label class="control-label">Date:</label>
                                    <input style="height: 40px;" type="date" name="tour_date" id="tour_date" class="form-control"
                                        autocomplete="off" />
                                    <div style="color:red;" id="tripdate"></div>
                                </div>
                        </div>

                        <div class="col-6">
                        <div class="form-group" id="tour_times">
                                    <label class="control-label">Time:</label>
                                    <input style="height: 40px;" type="time" name="tour_time" id="tour_time" class="form-control"
                                        autocomplete="off" />
                                    <div style="color:red;" id="triptime"></div>
                                </div>
                        </div>
                    </div>
             
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group" id="tour_destination">
                                    <label class="control-label">Destination:</label>
                                    <input type="text" name="Destination" id="Destination" class="form-control"
                                        value="{{ old('Destination') }}" placeholder="Destination">
                                </div>
                                <div style="color:red;" id="tripdestination"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group" id="tour_highlight">
                                    <label class="control-label">Trip Highlight:</label>
                                    <textarea class="form-control" id="trip_highlight" name="trip_highlight"></textarea>
                                </div>
                                <div style="color:red;" id="triphighlight"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group" id="tour_description">
                                    <label class="control-label">Description:</label>
                                    <textarea class="form-control" id="tripdescription"
                                        name="tripdescription"></textarea>
                                </div>
                                <div style="color:red;" id="tripdesc"></div>
                                </div>
                            </div>
                            <div class="form-group" align="center">
                        <input type="hidden" name="trip_action" id="trip_action" />
                        <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                            value="{{Auth::user()->name}}">
                        <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                            value="{{Auth::user()->email}}">
                        <input type="hidden" name="operator_auth_id" id="operator_auth_id" value="{{Auth::user()->id}}">
                        <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                    </div>
                    <input type="submit" name="trip_action_button" id="trip_action_button"
                        class="btn btn-primary btn-info-full" value="Add" />
                    
                </form>
        </div>
    </div>
</div>
</div>


<div id="TouropertorlModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mymodal-title" id="mymodal">Trip Details
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </h5>
            </div>
            <div class="modal-body">
                <span id="trip_tour_opertor_result" aria-hidden="true"></span>


                <div class="card" style="margin: top -5px;">

                    <div class="card-body">
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Trip Title:</strong>
                            <label id="name1"> </label>
                        </div>

                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Category:</strong>
                            <label id="category"> </label>
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
                            <strong>Number of days:</strong>
                            <label id="days"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Date:</strong>
                            <label id="date"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Time:</strong>
                            <label id="time"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Destination:</strong>
                            <label id="destination"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong>Trip Highlight:</strong>
                            <label id="triphighlight1"> </label>
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
        <div id="tcategorymodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">Trip
                            created successfully</h5>
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
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">Trip updated
                            successfully</h5>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="listfailmodal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">email
                            already exist
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="listsuccessmodal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">subscribed
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
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirmation</h3>
                <h4 align="center" style="margin:0;">Are you sure you want to remove this Tourdetail?</h4>
                <div align="right">
                    <button type="button" name="pro_ok_button" id="pro_ok_button" class="btn btn-danger">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    email = $('#operator_auth_email').val();
    console.log('working DataTable button');
    $('#category_slug').on('change', function() {
        if ($('#category_slug').val() != '') {
            $('#tourcategory').hide();
        }
    });
    $('#my_trip').on('change', function() {
        if ($('#my_trip').val() != '') {
            $('#tourtitle').hide();
        }
    });

    $('#NoOfDays').on('change', function() {
        if ($('#NoOfDays').val() != '') {
            $('#tourdays').hide();

        }

    });
    $('#tour_date').on('change', function() {
        if ($('#tour_date').val() != '') {
            $('#tripdate').hide();

        }

    });
    $('#tour_time').on('change', function() {
        if ($('#tour_time').val() != '') {
            $('#triptime').hide();

        }

    });
    $('#NoOfNight').on('change', function() {
        if ($('#NoOfNight').val() != '') {
            $('#tournight').hide();

        }

    });
    $('#Destination').on('change', function() {
        if ($('#Destination').val() != '') {
            $('#tripdestination').hide();

        }

    });
    $('#trip_highlight').on('change', function() {
        if ($('#trip_highlight').val() != '') {
            $('#triphighlight').hide();

        }

    });
    $('#tripdescription').on('change', function() {
        if ($('#tripdescription').val() != '') {
            $('#tripdesc').hide();

        }

    });

    $(function() {
        console.log('command ka function me hai');
        var curtpage = 1,
            pagelimit = 10,
            totalrecord = 0;
        query = '';
        fetchData(curtpage, query);
        // handling the prev-btn
        $(".prev-btn").on("click", function() {
            if (curtpage > 1) {
                curtpage--;
            }
            console.log("Prev Page: " + curtpage);
            fetchData(curtpage, query);
        });
        $(document).on('keyup', '#tripsearch', function() {
            console.log('search input click hua');
            var query = $('#tripsearch').val();
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


        CKEDITOR.replace('trip_highlight');
        CKEDITOR.replace('tripdescription');
        $('#create_trip').click(function() {
            console.log('working create_trip button');

            $('#trip_title').show();
            $('#country_form_name').show();
            $('#state_form_name').show();
            $('#city_form_name').show();
            $('#tour_days').show();
            $('#tour_dates').show();
            $('#tour_times').show();
            $('#tour_destination').show();
            $('#tour_night').show();
            $('#tour_highlight').show();
            $('#tour_description').show();
            $('#trip_form')[0].reset();
            $('#trip_tour_opertor_result').html('');
            $('.modal-title').text("Add Trip");
            $('#TripFormModal').modal('show');
            $('#trip_action_button').val("Add");
            $('#trip_action').val("Add");
            CKEDITOR.instances['trip_highlight'].setData('');
            CKEDITOR.instances['tripdescription'].setData('');


        });

        $('#list_form').on('submit', function(event) {
            event.preventDefault();
            console.log('yanha tak pahucha');

            console.log("inside add");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr(
                        'content')
                }
            });
            $.ajax({

                url: "{{eventmie_url('touroperator/listtour')}}/" + email,
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem(
                        'a_u_a_b_t')
                },

                success: function(data) {
                    console.log("after before");
                    if (data.success) {


                        console.log("after data success");
                        $('#listsuccessmodal').modal('show');
                        $('#ListModal').modal('hide');
                        setTimeout(function() {
                            window.location.reload(true);

                        }, 1200);
                        var curtpage = 1;
                        pagelimit = 10,
                            totalrecord = 0;
                        fetchData(curtpage, query);
                    } else {
                        $('#listfailmodal').modal('show');
                        $('#ListModal').modal('hide');
                        setTimeout(function() {
                            window.location.reload(true);

                        }, 1200);



                    }
                },
                error: function(data) {
                    console.log("error coming");
                    console.log('Error:', data);
                }
            })


        });
        $('#trip_form').on('submit', function(event) {
            event.preventDefault();
            CKEDITOR.instances['trip_highlight'].updateElement();
            CKEDITOR.instances['tripdescription'].updateElement();
            if ($('#trip_action').val() == 'Add') {
                console.log("inside add");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr(
                            'content')
                    }
                });
                $.ajax({

                    url: "{{eventmie_url('touroperator/addtour')}}/" + email,
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem(
                            'a_u_a_b_t')
                    },
                    beforeSend: function() {

                        if ($('#category_slug').has('option').length > 0) {
                            $('#tourcategory').html(
                                "Please  fill category field");
                        }
                        if (!$('#my_trip').val()) {
                            $('#tourtitle').html(
                                "Please  fill my_trip  field");
                        }

                        if (!$('#NoOfDays').val()) {
                            $('#tourdays').html(
                                "Please  fill no of days  field");
                        }
                        if (!$('#NoOfNight').val()) {
                            $('#tournight').html(
                                "Please  fill no of night  field");
                        }
                        if (!$('#tour_date').val()) {
                            $('#tripdate').html("Please  fill date  field");
                        }
                        if (!$('#tour_time').val()) {
                            $('#triptime').html("Please  fill time  field");
                        }
                        if (!$('#Destination').val()) {
                            $('#tripdestination').html(
                                "Please  fill destination  field");
                        }
                        if (!$('#trip_highlight').val()) {
                            $('#triphighlight').html(
                                "Please  highlight  field");
                        }
                        if (!$('#tripdescription').val()) {
                            $('#tripdesc').html("Please  describe  field");
                        }

                    },
                    success: function(data) {
                        console.log("after before");
                        if (data.success) {
                            console.log("after data success");
                            $('#tcategorymodel').modal('show');
                            $('#TripFormModal').modal('hide');
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
                        console.log("error coming");
                        console.log('Error:', data);
                    }
                })
            }
            if ($('#trip_action').val() == "Update") {
                console.log("update me aa raha");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr(
                            'content')
                    }
                });
                $.ajax({

                    url: "{{ eventmie_url('touroperator/updatetrip') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem(
                            'a_u_a_b_t')
                    },

                    success: function(data) {
                        if (data.success) {
                            $('#TripFormModal').modal('hide');
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

        var id;
        $(document).on('click', '.deleteProfile', function() {
            id = $(this).attr('id');
            $('#profile_confirmModal').modal('show');
        });

        $('#pro_ok_button').click(function() {
            console.log('working delete button');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/destroytrip')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem(
                        'a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#pro_ok_button').text('Deleting');
                },
                success: function(data) {
                    $('#profile_confirmModal').modal('hide');
                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchData(curtpage, query);
                },
                error: function(data) {
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
                url: "{{eventmie_url('touroperator/detailtrip')}}/" + id,

                headers: {
                    "Authorization": "Bearer " + localStorage.getItem(
                        'a_u_a_b_t')
                },
                success: function(html) {
                    console.log('get quote by firm id :' + html.data.id);
                    console.log("yanha aa raha hai");
                    var name = html.data.TripTitle;
                    var category = html.data.Category;
                    var country = html.data.slug;
                    var state = html.data.slug1;
                    var city = html.data.slug2;
                    var days = html.data.NoOfDays;
                    var date = html.data.datetime;
                    var time = html.data.time;
                    var destination = html.data.Destination;
                    var triphighlight = html.data.Keyword;
                    var description = html.data.Description;

                    $('#TouropertorlModal').modal('show');
                    $(".cardop").show();
                    $('#name1').html(name);
                    $('#category').html(category);
                    $('#country1').html(country);
                    $('#state1').html(state);
                    $('#city1').html(city);
                    $('#days').html(days);
                    $('#date').html(date);
                    $('#time').html(time);
                    $('#destination').html(destination);
                    $('#triphighlight1').html(triphighlight);
                    $('#description').html(description);
                    if (html.data.image_name == "default.png") {
                        $('#myimage').html(
                            '  <img src="https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="100px" height="100px">');
                    } else {
                        $('#myimage').html(
                            '  <img src="https://www.holidaylandmark.com/category/' +
                            html.data.image_name +
                            '" width="100px" height="100px">');
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
        $(document).on('click', '.editProfile', function() {
            console.log('working editProfile button');
            // $('#command_sample_form').show();
            var id = $(this).attr('id');
            // $('#form_command').html('');
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/edittrip/')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem(
                        'a_u_a_b_t')
                },
                success: function(html) {
                    $('#tourcountryId').prepend("<option value='" + html
                        .data
                        .slug + "' selected='selected'>" + html.data
                        .slug + "</option>").attr('disabled', false);
                    $('#tourstate').prepend("<option value='" + html.data
                        .slug1 +
                        "' selected='selected'>" + html.data.slug1 +
                        "</option>"
                    ).attr('disabled', false);
                    $('#tourcity').prepend("<option value='" + html.data
                            .slug2 +
                            "' selected='selected'>" + html.data.slug2 +
                            "</option>"
                        )
                        .attr('disabled', false);

                    $('#category_slug').val(html.data.Category);
                    $('#my_trip').val(html.data.TripTitle);
                    $('#tourcountryId').val(html.data.slug);
                    $('#tourstate').val(html.data.slug1);
                    $('#tourcity').val(html.data.slug2);
                    $('#NoOfDays').val(html.data.NoOfDays);
                    $('#NoOfNight').val(html.data.night);
                    $('#tour_date').val(html.data.datetime);
                    $('#tour_time').val(html.data.time);
                    $('#Destination').val(html.data.Destination);
                    CKEDITOR.instances['trip_highlight'].setData(html.data
                        .Keyword);
                    CKEDITOR.instances['tripdescription'].setData(html.data
                        .Description);
                    $('.modal-title').text("Edit Trip Data");
                    $('#trip_action_button').val("Update");
                    $('#trip_action').val("Update");
                    $('#TripFormModal').modal('show');
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
            fetchData(curtpage, query);
        });
        $(document).on('click', '.link-click', function(event) {
            curtpage = $(this).attr('page');
            $(this).addClass('active');
            console.log(this);
            fetchData(curtpage, query);
        });


        $(document).on('click', '.listTrip', function() {
            var id = $(this).attr('trip_title');
            console.log('working list button');
            console.log(id);
            $.ajax({
                type: "get",
                data: {},

                url: "{{eventmie_url('touroperator/subscriberdetail')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem(
                        'a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html.data);
                    $('#ListModal').modal('show');

                    if (html.data) {
                        var dataArr = html.data;

                        totalrecord = html.data.total;
                        console.log(totalrecord +
                            ' total record aa rha hai');
                        lastpage = html.data.last_page;
                        console.log(lastpage +
                            ' last page record aa rha hai');

                        var html = "";
                        for (var i = 0; i < dataArr.length; i++) {
                            html += "<tr>" +
                                "<td> <input type='checkbox' onclick='CheckAll(this)' style='width: 2.0em;height: 1.0em; border: 1px solid #a9a9a9;' value='" +
                                dataArr[i].id +
                                "' name='Sub[]'> </td>" +
                                "<td> <input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].Name +
                                "' name='name[]'>" + dataArr[i].Name +
                                "</td>" +

                                "<td><input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].emailid +
                                "' name='email[]'>" + dataArr[i].emailid +
                                "</td>" +
                                "<td><input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].Phoneno +
                                "' name='phonno[]'>" + dataArr[i].Phoneno +
                                "</td>" +
                                "<td><input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].Address +
                                "' name='address[]'><input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].TripHeading +
                                "' name='tripss[]'><input type='checkbox'  style='display:none;' value='" +
                                dataArr[i].password +
                                "' name='password[]'>" + dataArr[i]
                                .Address +
                                "</td>" +

                                "</tr>" +
                                "<hr />";
                        }
                        $("#listTable tbody").html(html);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });

        function fetchData(page, query) {
            var email = $('#operator_auth_email').val();
            console.log('command ka fetch function');
            // ajax() method to make api calls
            $.ajax({

                url: "{{eventmie_url('admins/toursdetail')}}/" + email,
                type: "GET",
                data: "page=" + page + "&query=" + query,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log('yanha tak pahucha');
                    console.log(html.data);
                    var subdata = html.sub;
                    var tripdata = html.galleries;
                    console.log(subdata);
                    $('#totalsub').html(subdata);

                    $('#totaltrip').html(html.galleries.length);
                    $.each(html.allcat, function(i, markcat) {
                        console.log('yanha tak markcat');
                        console.log('all category: ' + markcat.category);
                        $('#category_slug').append("<option value='" +
                            markcat
                            .category + "'>" +
                            markcat.category + "</option>");
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
                                "<td>" + dataArr[i].TripTitle + "</td>" +
                                "<td>" + dataArr[i].slug + "</td>" +
                                "<td>" + dataArr[i].slug1 + "</td>" +
                                "<td>" + dataArr[i].slug2 + "</td>" +

                                "<td> " + dataArr[i].Subscriber +
                                " <button type='button'  id='" + dataArr[i].id +
                                "' trip_title='" + dataArr[i].TripTitle +
                                "'   class='listTrip btn btn-success btn-sm'>List</button> </td>" +
                                "<td> <button type='button'  id='" + dataArr[i].id +
                                "'class='editProfile btn btn-warning btn-sm'>Edit</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='deleteProfile btn btn-danger btn-sm'>Delete</button>" +
                                " <button type='button'  id='" + dataArr[i].id +
                                "'class='detailProfile btn btn-success btn-sm'>Detail</button></td>" +
                                "</tr>" +
                                "<hr />";
                        }
                        $("#trip_tour_opertor tbody").html(html);
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