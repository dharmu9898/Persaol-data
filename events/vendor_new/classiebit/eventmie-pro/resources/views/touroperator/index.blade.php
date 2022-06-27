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


<div class="row mb-5 mt-5 ml-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="row align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-10">
                            <div style="margin-top:22px;">
                           
                                <span class="float-left ml-1">
                                    <button type="button" name="create_trip" id="create_trip"
                                        class="btn btn-success btn-sm" style="font-size:20px;">Add
                                        Trip</button>
                                </span>
                                <span class="float-left ml-1">
                                    <button type="button" name="create_trip_image" id="create_trip_image"
                                        class="btn btn-success btn-sm" style="font-size:20px;">Add
                                        Media</button>
                                </span>
                                <span class="float-left ml-1">
                                    <button type="button" name="create_iternary" id="create_iternary"
                                        class="btn btn-success btn-sm" style="font-size:20px;">Add
                                        Iternary</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2" style="margin-top:28px;">
                            <span class="float-right" style="width:300% margin-left:50em;">
                                <input id="tripsearch" 
                                    placeholder="Search by Title, Country, State, City, Email"
                                    class="float-right  py-2 px-4" name="tripsearch" type="text">
                            </span>
                        </div>
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
                <div class="table-responsive">
                    <table id="trip_tour_opertor" class="table table-bordered table-striped bg-light"
                        style="color:white; border:none">
                        <thead class="bg-light" style="color:black">
                            <tr>
                                <th data-column_name="profile_country" width="30%">Title</th>
                                <th data-column_name="profile_country" width="10%">Country</th>
                                <th data-column_name="profile_State" width="15%">State</th>
                                <th data-column_name="profile_City" width="15%">City</th>
                                <th data-column_name="profile_Action" width="30%">Action</th>
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
                <h5 class="modal-title-image" id="TripimageModaltouropertor">Add Media</h5>
            </div>
            <div class="modal-body">
                <span id="image_tour_result" aria-hidden="true"></span>
                <div class="row">
                    <section class="col-12">
                        <form id="image_add_form" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="tourimagetitle">
                                        <label class="control-label">Trip Title:</label>
                                            <select style="width: 100%; height: 40px;" name="image_title" id="image_title" class="form-control">
                                                <option value="">Select Title</option>
                                            </select>
                                        <div style="color:red;" id="imagetrip_title"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="tourimages">
                                        <label class="control-label">Chosen Your Tour Image</label>
                                            <input style="width: 100%; height: 40px;" type="file" name="image_logo[]" multiple id="image_logo"
                                                class="form-control" autocomplete="off" />
                                            <div class="col-3" id="trip_image_logo"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" id="youtube_url">
                                        <label class="control-label">YouTube Video URL:</label>
                                        <input style="width: 100%; height: 40px;" type="text" name="youtube_url" id="youtube_url"
                                            class="form-control" value="{{ old('youtube_url') }}"
                                            placeholder="youtube_video_url">
                                    </div>
                                    <div style="color:red;" id="youtube_url"></div>
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <input type="hidden" name="trip_image_action" id="trip_image_action" />
                                <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                    value="{{Auth::user()->name}}">
                                <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                    value="{{Auth::user()->email}}">
                                <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                    value="{{Auth::user()->id}}">

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
<div class="row">
    <div class="col-10">
        <div id="tripcategorymodel" class="modal fade" role="dialog">
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
<div id="TripFormModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="tripModaltouropertor">Add Trip</h5>
               
            </div>
            <div class="modal-body">
                <form id="trip_form" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Category:</label>
                                <select style="height:40px;" name="category_slug" id="category_slug"
                                    class="form-control">
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
                                    class="countries w-100 p-2" id="tourcountryId" value="{{old('country')}}" required>
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
                                <label class="control-label">Number of days:</label>
                                <input style="width: 100%; height: 40px;" type="text" name="NoOfDays" id="NoOfDays"
                                    class="form-control" pattern="[0-9]{1,2}" title="starts with digit 1,2.."
                                    value="{{ old('NoOfDays') }}" placeholder="NoOfDays(only digit)">
                                <div style="color:red;" id="tourdays"></div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group" id="tour_night">
                                <label class="control-label">Number of night:</label>
                                <input style="width: 100%; height: 40px;" type="text" name="NoOfNight" id="NoOfNight"
                                    class="form-control" pattern="[0-9]{1,2}" title="starts with digit like 1,2.."
                                    value="{{ old('NoOfNight') }}" placeholder="NoOfNight(only digit like 1,2)">
                                <div style="color:red;" id="tournight"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group" id="tour_dates">
                                <label class="control-label">Date:</label>
                                <input style="height: 40px;" type="date" name="tour_date" id="tour_date"
                                    class="form-control" autocomplete="off" />
                                <div style="color:red;" id="tripdate"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group" id="tour_times">
                                <label class="control-label">Time:</label>
                                <input style="height: 40px;" type="time" name="tour_time" id="tour_time"
                                    class="form-control" autocomplete="off" />
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
                                        <textarea class="form-control" id="trip_highlight"
                                            name="trip_highlight"></textarea>
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
                                <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                    value="{{Auth::user()->id}}">
                                    <input type="hidden" name="adm_hidden_email" id="adm_hidden_email"
                                    value="{{$admins}}">
                                <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                            </div>

                <input type="submit" name="trip_action_button" id="trip_action_button"
                    class="btn btn-primary btn-info-full" value="Add" />
                    </form>
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
                                            <select style="width: 100%; height: 40px;" name="iternary_title" id="iternary_title" class="form-control">
                                                <option value="default">Select Title</option>
                                            </select>
                                        <div style="color:red;" id="iternarytrip_title"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" id="iternary_day">
                                        <label class="control-label">Days</label>
                                        <select style="width: 100%; height: 40px;" name="iternary_days" id="iternary_days" class="form-control">
                                            <option value="">Select Days</option>

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
                                        <label style="width: 100%; height: 40px;" class="control-label">Description:</label>
                                        <textarea class="form-control" id="iternarydescription"
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
                            

                            </div>
                            <input type="submit" name="iternary_action_button" id="iternary_action_button"
                                class="btn btn-primary btn-info-full" value="Add"/>
                            <br/>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="TouropertorlModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="mymodal-title" id="mymodal">Trip Details </h5>
            </div>
            <div class="modal-body">
                <span id="trip_tour_opertor_result" aria-hidden="true"></span>
                <div class="card">
                    <div class="card-body">
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Trip Title:</strong>
                            <label id="name1"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Category:</strong>
                            <label id="category"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Country:</strong>
                            <label id="country1"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">State:</strong>
                            <label id="state1"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">City:</strong>
                            <label id="city1"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Number of days:</strong>
                            <label id="days"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Number of night:</strong>
                            <label id="night"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Date:</strong>
                            <label id="date"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Time:</strong>
                            <label id="time"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Destination:</strong>
                            <label id="destination"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Trip Highlight:</strong>
                            <label id="triphighlight1"> </label>
                        </div>
                        <div style="padding-left:10px;font-size:16px;" class="form-group">
                            <strong style="color:#d34205;">Description:</strong>
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
        <div id="listfailmodal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">email already exist
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">subscribed
                            successfully</h5>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="tripModaltouropertor">Trip
                            created
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="tripimages_success_modal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="TripimageModaltouropertor">Trip Image
                            created successfully
                        </h5>
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
        <div id="republishmodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="republishModaltouropertor">Trip republish
                            successfully</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="my_confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="republishModaltouropertor">data deleted
                            successfully </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div id="reextendmodel" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="republishModaltouropertor">select
                            different date by extending date to publish </h5>
                       
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
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Confirmation</h3>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this Trip?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="pro_ok_button" id="pro_ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="publish_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="pubmodal-title">Confirmation</h6>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to publish this Trip?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="publish_ok_button" id="publish_ok_button"
                    class="btn btn-success">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="expired_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="expmodal-title">Expired</h6>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;"><label>Your trip is expired republish by extending date</h4>
            </div>

        </div>
    </div>
</div>
<div id="unpublish_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="unpubmodal-title">Confirmation</h6>
          

            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to unpublish this Trip?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="unpublish_ok_button" id="unpublish_ok_button"
                    class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>

</script>

<script type="text/javascript">
function CheckAll(obj) {
    var row = obj.parentNode.parentNode;
    var inputs = row.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == "checkbox") {
            inputs[i].checked = obj.checked;
        }
    }
}
</script>
<script>
$(document).ready(function() {
    $('#select-all').click(function() {
        $('input[type="checkbox"]').prop('checked', this.checked);
    })
});
</script>
<script>
$(document).ready(function() {
    email = $('#operator_auth_email').val();
    console.log('working DataTable button');
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
    $('#NoOfNight').on('change', function() {
        if ($('#NoOfNight').val() != '') {
            $('#tournight').hide();

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
    $('#list_form').on('submit', function(event) {
        event.preventDefault();
        console.log('yanha tak pahucha');

        console.log("inside add");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
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
                "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
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
    $('#create_trip_image').click(function() {
        console.log('working create_trip_image button');
        $('#tourimagetitle').show();
        $('#tourimages').show();
        $('#image_add_form')[0].reset();
        $('#image_tour_result').html('');
        $('.modal-title-image').text("Add Media");
        $('#ImageFormModal').modal('show');
        $('#trip_image_action_button').val("Add");
        $('#trip_image_action').val("Add");
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
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 1200);
                        var curtpage = 1;
                        pagelimit = 10,
                            totalrecord = 0;
                        fetchData(curtpage, query);
                    } else {
                        $('#iternary_success_modal').modal('show');
                        $('#IternaryFormModal').modal('hide');
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
        }
    });

    $('#image_add_form').on('submit', function(event) {
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
                        $('#imagetrip_title').html("Please  fill trip title field");
                        
                    }
                    if (!$('#image_logo').val()) {
                        $('#trip_image_logo').html("Please  choose image");
                    }
                    if (!$('#youtube_url').val()) {
                            $('#youtube_url').html("Please  fill url  field");
                        }
                },
                success: function(data) {
                    console.log("after before");
                    if (data.success) {
                        console.log("after data success");
                        $('#tripimages_success_modal').modal('show');
                        $('#ImageFormModal').modal('hide');
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 1200);
                        var curtpage = 1;
                        pagelimit = 10,
                            totalrecord = 0;
                        fetchData(curtpage, query);
                    } else {
                        $('#tripimages_success_modal').modal('show');
                        $('#ImageFormModal').modal('hide');
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
            $('#tour_night').show();
            $('#tour_dates').show();
            $('#tour_times').show();
            $('#tour_destination').show();
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

        $('#trip_form').on('submit', function(event) {
            event.preventDefault();
            CKEDITOR.instances['trip_highlight'].updateElement();
            CKEDITOR.instances['tripdescription'].updateElement();
            if ($('#trip_action').val() == 'Add') {
                console.log("inside add");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
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
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                    },
                    beforeSend: function() {

                        if ($('#category_slug').has('option').length > 0) {
                            $('#tourcategory').html("Please  fill category field");
                        }

                        if (!$('#my_trip').val()) {
                            $('#tourtitle').html("Please  fill my_trip  field");
                        }


                        if (!$('#NoOfDays').val()) {
                            $('#tourdays').html("Please  fill no of days  field");
                        }
                        if (!$('#NoOfNight').val()) {
                            $('#tournight').html("Please  fill no of night  field");
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
                            $('#triphighlight').html("Please  highlight  field");
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
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
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
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
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

            if ($('#trip_action').val() == "Republish") {
                console.log("repub me aa raha");


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });


                $.ajax({

                    url: "{{ eventmie_url('touroperator/replishubtrip') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                    },
                    beforeSend: function() {

                        if (!$('#tour_date').val()) {
                            $('#tripdate').html("Please extend date");
                        }

                    },
                    success: function(data) {
                        if (data.success) {
                            $('#TripFormModal').modal('hide');
                            $('#republishmodel').modal('show');
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1200);
                            var curtpage = 1;
                            pagelimit = 10,
                                totalrecord = 0;
                            fetchData(curtpage, query);

                        } else {
                            $('#TripFormModal').modal('hide');
                            $('#reextendmodel').modal('show');
                        }
                    },

                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }


        });

        var id;
        $(document).on('click', '.pubTrip', function() {
            id = $(this).attr('id');
            date = $(this).attr('date');
            time = $(this).attr('time');
            var dateObj = new Date(date + ' ' + time);
            console.log(dateObj);
            var today = new Date();
            console.log(today);
            const diffTime = Math.abs(today - dateObj);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            console.log(diffDays);
            console.log(diffTime);

            if (diffDays > 2) {
                $('#expired_Modal').modal('show');
            } else {
                $('#publish_Modal').modal('show');
            }


        });
        $(document).on('click', '.unpubTrip', function() {
            id = $(this).attr('id');
            $('#unpublish_Modal').modal('show');
        });
        var id;
        $(document).on('click', '.deleteTrip', function() {
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
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#pro_ok_button').text('Deleting');
                },
                success: function(data) {
                    $('#profile_confirmModal').modal('hide');
                    $('#my_confirmModal').modal('show');

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

        $('#publish_ok_button').click(function() {
            console.log('working publish button');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/publishtrip')}}/" + id,


                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#publish_ok_button').text('publishing');
                },
                success: function(data) {
                    $('#publish_Modal').modal('hide');

                    var curtpage = 1;
                    pagelimit = 10,
                        totalrecord = 0;
                    fetchData(curtpage, query);
                    $('#publish_action').val("Unpublish");
                    $('#publish_action_button').val("Unpublish");


                },
                error: function(data) {
                    console.log('Error:', data);
                }
            })
        });
        $('#unpublish_ok_button').click(function() {
            console.log('working unpublish button');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/unpublishtrip')}}/" + id,


                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                beforeSend: function() {
                    $('#unpublish_ok_button').text('unpublishing');
                },
                success: function(data) {
                    $('#unpublish_Modal').modal('hide');
                    setTimeout(function() {
                        window.location.reload(true);

                    }, 1000);
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


        $(document).on('click', '.detailTrip', function() {
            id = $(this).attr('id');
            console.log('working details button');
            console.log(id);
            $.ajax({
                type: "get",
                data: {},

                url: "{{eventmie_url('touroperator/detailtrip')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
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
                    var night = html.data.night;
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
                    $('#night').html(night);
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
        $(document).on('click', '.editTrip', function() {
            console.log('working editTrip button');
            // $('#command_sample_form').show();
            var id = $(this).attr('id');
            // $('#form_command').html('');
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/edittrip/')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    $('#tourcountryId').prepend("<option value='" + html.data
                        .slug + "' selected='selected'>" + html.data
                        .slug + "</option>").attr('disabled', false);
                    $('#tourstate').prepend("<option value='" + html.data.slug1 +
                        "' selected='selected'>" + html.data.slug1 + "</option>"
                    ).attr('disabled', false);
                    $('#tourcity').prepend("<option value='" + html.data.slug2 +
                            "' selected='selected'>" + html.data.slug2 + "</option>"
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
                    CKEDITOR.instances['trip_highlight'].setData(html.data.Keyword);
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


        $(document).on('click', '.repubTrip', function() {
            console.log('working repubTrip button');
            // $('#command_sample_form').show();
            var id = $(this).attr('id');
            // $('#form_command').html('');

            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/edittrip/')}}/" + id,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    $('#tourcountryId').prepend("<option value='" + html.data
                        .slug + "' selected='selected'>" + html.data
                        .slug + "</option>").attr('disabled', false);
                    $('#tourstate').prepend("<option value='" + html.data.slug1 +
                        "' selected='selected'>" + html.data.slug1 + "</option>"
                    ).attr('disabled', false);
                    $('#tourcity').prepend("<option value='" + html.data.slug2 +
                        "' selected='selected'>" + html.data.slug2 + "</option>"
                    ).attr('disabled', false);



                    var mydate = html.data.datetime;
                    console.log(mydate);
                    var today = new Date(new Date(mydate).getTime() + 60 * 60 * 48 *
                        1000);
                    console.log(today);

                    var curr_date = today.getDate();
                    var curr_month = today.getMonth() + 1;
                    var curr_year = today.getFullYear();
                    var newdate = curr_date + "-" + curr_month + "-" + curr_year;
                    console.log(newdate);
                    $('#category_slug').val(html.data.Category);
                    $('#my_trip').val(html.data.TripTitle);
                    $('#tourcountryId').val(html.data.slug);
                    $('#tourstate').val(html.data.slug1);
                    $('#tourcity').val(html.data.slug2);
                    $('#NoOfDays').val(html.data.NoOfDays);
                    $('#NoOfNight').val(html.data.night);
                    $('#tour_date').val(newdate);
                    $('#tour_time').val(html.data.time);
                    $('#Destination').val(html.data.Destination);
                    CKEDITOR.instances['trip_highlight'].setData(html.data.Keyword);
                    CKEDITOR.instances['tripdescription'].setData(html.data
                        .Description);
                    $('.modal-title').text("Republish Trip");
                    $('#trip_action_button').val("Republish");
                    $('#trip_action').val("Republish");
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

        function fetchData(page, query) {
            var email = $('#operator_auth_email').val();
            console.log('command ka fetch function');
            // ajax() method to make api calls
            $.ajax({

                url: "{{eventmie_url('touroperator/toursdetail')}}/" + email,
                type: "GET",
                data: "page=" + page + "&query=" + query,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html.data);
                    var subdata = html.sub;
                    var myuser = html.myusers;
                    console.log(subdata);
                    console.log(myuser);
                    $('#totalsub').html(subdata);
                    $.each(html.allcat, function(i, markcat) {
                        console.log('all category: ' + markcat.category);
                        $('#category_slug').append("<option value='" + markcat
                            .category + "'>" +
                            markcat.category + "</option>");
                    });
                    $.each(html.alltrip, function(i, markiternary) {
                        console.log('all category here: ' + markiternary.TripTitle);
                        $('#image_title').append("<option value='" + markiternary
                            .TripTitle + "' >" +
                            markiternary.TripTitle + "</option>");
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
                            if (dataArr[i].publish == 0) {
                                html += "<tr>" +

                                    "<td><span type='button'style='border:none'  id='" +
                                    dataArr[i].id +
                                    "'class='detailTrip '>" + dataArr[i].TripTitle +
                                    "</span></td>" +
                                    "<td>" + dataArr[i].slug + "</td>" +
                                    "<td>" + dataArr[i].slug1 + "</td>" +
                                    "<td>" + dataArr[i].slug2 + "</td>"

                                    +

                                    "<td> <button type='button' style='width:80px;font-size:13px;'  id='" +
                                    dataArr[i].id + "' date='" + dataArr[i].datetime +
                                    "' time='" + dataArr[i].time +
                                    "'class='pubTrip btn btn-warning btn-sm'> publish</button>" +



                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='repubTrip btn btn-success btn-sm'>Repub</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='editTrip btn btn-warning btn-sm'>Edit</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='deleteTrip btn btn-danger btn-sm'>Delete</button>" +
                                    " </td>" +
                                    "</tr>" +
                                    "<hr />";
                            } else if (dataArr[i].publish == 1) {

                                html += "<tr>" +
                                    "<td><span type='button'style='border:none'  id='" +
                                    dataArr[i].id +
                                    "'class='detailTrip '>" + dataArr[i].TripTitle +
                                    "</span></td>" +
                                    "<td>" + dataArr[i].slug + "</td>" +
                                    "<td>" + dataArr[i].slug1 + "</td>" +
                                    "<td>" + dataArr[i].slug2 + "</td>"

                                    +

                                    "<td> <button type='button' style='width:80px;font-size:13px;'  id='" +
                                    dataArr[i].id +
                                    "'class='unpubTrip btn btn-danger btn-sm'>Unpublish</button>" +



                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='repubTrip btn btn-success btn-sm'>Repub</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='editTrip btn btn-warning btn-sm'>Edit</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='deleteTrip btn btn-danger btn-sm'>Delete</button>" +
                                    " </td>" +
                                    "</tr>" +
                                    "<hr />";



                            } else if (dataArr[i].publish == -1) {

                                html += "<tr>" +
                                    "<td><span type='button'style='border:none'  id='" +
                                    dataArr[i].id +
                                    "'class='detailTrip '>" + dataArr[i].TripTitle +
                                    "</span></td>" +
                                    "<td>" + dataArr[i].slug + "</td>" +
                                    "<td>" + dataArr[i].slug1 + "</td>" +
                                    "<td>" + dataArr[i].slug2 + "</td>"

                                    +

                                    "<td> <button type='button'  style='width:80px;font-size:13px;'  id='" +
                                    dataArr[i].id + "' date='" + dataArr[i].datetime +
                                    "' time='" + dataArr[i].time +
                                    "'class='pubTrip btn btn-warning btn-sm'> publish</button>" +



                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='repubTrip btn btn-success btn-sm'>Repub</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='editTrip btn btn-warning btn-sm'>Edit</button>" +
                                    " <button type='button'  id='" + dataArr[i].id +
                                    "'class='deleteTrip btn btn-danger btn-sm'>Delete</button>" +
                                    " </td>" +
                                    "</tr>" +
                                    "<hr />";



                            }

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