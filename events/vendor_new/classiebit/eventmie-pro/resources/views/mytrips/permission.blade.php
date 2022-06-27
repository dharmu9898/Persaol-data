
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
<div class="row mb-5" style="margin-top:4%;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 ml-auto">
        <div class="align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                
                <div class="card-body" style="margin-left:1%;">
                    <div class="card-body shadow" style="border-bottom:3px solid red;">
                        <form id="permission_form" role="form" enctype="multipart/form-data">
                        <div class="content2 ml-2" >
                    <button onclick="goBack()">Back</button>
                    <button type="submit" name="permission_action_button" id="permission_action_button"
                                    class="btn btn-primary btn-info-full mb-3 " value="Add">Approve / Reject</button>
                </div>
                        <div class="table-responsive">
                                <table id="myTable" class="table table-bordered table-striped bg-light"
                                    style="color:white; border:solid white">
                                    <thead class="bg-light" style="color:black">
                                        <tr>
                                            <th class="mob"> All<input type="checkbox" id="select-all" name="selectAll"
                                            value="all"style="width: 2.0em;height: 1.0em; border: 2px solid red;"></th>
                                            <th data-column_name="trip_permission" width="15%">Permission</th>
                                            <th data-column_name="trip_image" width="15%">Image</th>
                                            <th data-column_name="trip_title" width="25%"> Title</th>
                                            <th data-column_name="trip_country" width="15%">Country</th>
                                            <th data-column_name="trip_state" width="15%">state</th>
                                            <th data-column_name="trip_city" width="15%">City</th>
                                        </tr>
                                    </thead>
                                    <tbody style="color:black" id="myTable">
                                    </tbody>
                                </table>
                                <div class="nav-btn-container">
                                 <button class="btn btn-danger prev-btn mr-1">Prev</button>
                                    <span id="pages"></span>
                                    <button class="btn btn-success next-btn">Next</button>
                                </div>
                                <div class="form-group" align="center">
                                    <input type="hidden" name="permission_action" id="permission_action" />
                                    <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                                        value="{{Auth::user()->name}}">
                                    <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                                        value="{{Auth::user()->email}}">
                                    <input type="hidden" name="operator_auth_id" id="operator_auth_id"
                                        value="{{Auth::user()->id}}">
                                    <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                                </div>
                               
                                <br />
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-10">
        <div id="permission_model" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 style="color:green;" class="tool-modal-title" id="republishModaltouropertor">Permission Updated
                            successfully </h5>
                    </div>
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
    function goBack() {
        window.history.back();
    }
    </script>

    <script>
    $(document).ready(function() {
        email = $('#operator_auth_email').val();
        console.log('working DataTable button');

        $(function() {
            console.log('command ka function me hai');
            var curtpage = 1,
                pagelimit = 5,
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

            $('#permission_form').on('submit', function(event) {
                event.preventDefault();
                console.log('yanha tak pahucha');

                console.log("inside add");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    url: "{{eventmie_url('admins/permissiontour')}}/" + email,
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
                            $('#permission_model').modal('show');
                            $('#TripFormModal').modal('hide');
                            setTimeout(function() {
                                window.location.reload(true);

                            }, 1200);
                            var curtpage = 1;
                            pagelimit = 5,
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
            });

           

            // Edit Function Start
           
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

                    url: "{{eventmie_url('admins/toursdetail')}}/" + email,
                    type: "GET",
                    data: "page=" + page + "&query=" + query,
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                    },
                    success: function(html) {
                        $.each(html.allcat, function(i, markcat) {
                            $('#category_slug').append("<option value='" + markcat
                                .category + "'>" +
                                markcat.category + "</option>");
                        });
                        if (html.data) {
                            var dataArr = html.data.data;
                            totalrecord = html.data.total;
                            lastpage = html.data.last_page;
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

                                    "<td> <input type='checkbox' onclick='CheckAll(this)' style='width: 2.0em;height: 1.0em; border: 1px solid #a9a9a9;' value='" +
                                    dataArr[i].id +
                                    "' name='Perm[]'> </td>" +
                                    "<td> <input type='checkbox'  style='display:none;' value='" +
                                    dataArr[i].Permission +
                                    "' name='Permission[]'>" + dataArr[i].Permission +
                                    "</td>" +
                                    "<td> <img src=https://www.holidaylandmark.com/category/" +
                                    dataArr[i].image_name + " class='myimg'/ > </td>" +
                                    "<td>" + dataArr[i].TripTitle + "</td>" +
                                    "<td>" + dataArr[i].slug + "</td>" +
                                    "<td>" + dataArr[i].slug1 + "</td>" +
                                    "<td>" + dataArr[i].slug2 + "</td>" +
                                    "</tr>" +
                                    "<hr />";
                            }
                            $("#myTable tbody").html(html);
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
    @endsection