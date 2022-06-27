@extends('voyager::tripmaster')

@section('content')

<style>
.button {
    height: 60px;
    width: 240px;
    border-radius: 10px;
    background: linear-gradient(to bottom, #33ccff 0%, #0066ff 100%);
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin-left: 30px;
}
</style>
<div class="row "style="margin-top:22px;">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <div class=" align-items-center">
            <div class="col-xl-12 col-12 mb-4 mb-xl-0">
                <div class="container-fluid card border-primary border-primary-5">
                    <div class="row card-body">
                        <table >
                            <tr>
                                <td style="width:25%">
                                    <div class="col-xl-4 ">
                                        <button class="button py-2 "><i class="fas fa-users text-center"></i> Total
                                            User:<label id="totaluser">
                                            </label></button >
                                    </div>
                                </td>
                                
                                <td style="width:25%;">
                                    <div class="col-xl-4">
                                        <button class="button py-2"><i class="fas fa-user-check"></i> Total Subscribes:<label
                                                id="totalsub">
                                            </label></button>
                                    </div>
                                </td>
                                
                                <td style="width:25%">
                                    <div class="col-xl-4">
                                        <button class="button py-2"><i class="fas fa-suitcase-rolling "></i> Total
                                            Trip:<label id="totaltrip">
                                            </label></button>
                                    </div>
                                </td>
                                <tr>
                                <table>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table id="trip_image_table" class="table table-bordered table-striped bg-light"
                                    style="color:white; border:none">
                                    <thead class="bg-light" style="color:black">
                                        <tr>
                                            <th data-column_name="trip_users_title" width="35%">Trip</th>

                                            <th data-column_name="trip_subscribers" width="35%">No of Users</th>

                                            <th width="30%">Action</th>
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
                        </div>
                    </div>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="users.id" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
                <div class="form-group" align="center">
                    <input type="hidden" name="trip_image_action" id="trip_image_action" />
                    <input type="hidden" name="operator_auth_name" id="operator_auth_name"
                        value="{{Auth::user()->name}}">
                    <input type="hidden" name="operator_auth_email" id="operator_auth_email"
                        value="{{Auth::user()->email}}">
                    <input type="hidden" name="operator_auth_id" id="operator_auth_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="operator_auth_roleid" id="operator_auth_roleid"
                        value="{{Auth::user()->role_id}}">
                    <input type="hidden" name="operator_hidden_id" id="operator_hidden_id" />
                </div>
            </div>
        </div>
    </div>
</div>



<div id="Subscriberdetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="myusermodal-title" id="myusermodal">Details</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">
                        <span id="subscriber_tour_result" aria-hidden="true"></span>

                        <div class="card-body" >
                            <div class="card-body shadow" style="border-bottom:3px solid red; margin: top -5px;%;">
                                <form id="list_form" role="form" enctype="multipart/form-data">
                                    <div class="table-responsive">
                                        <table id="listTable" class="table table-bordered table-striped bg-light"
                                            style="color:white; border:none">
                                            <thead class="bg-light" style="color:black">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color:black" id="myTable">
                                            </tbody>
                                        </table>
                                        </h2>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
$(document).ready(function() {
    $("[href]").each(function() {
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    });
});
</script>
<script>
$(document).ready(function() {
    email = $('#operator_auth_email').val();
    console.log('working DataTable button');
    $(function() {
        console.log('command ka function me hai');
        var curtpage = 1,
            pagelimit = 10,
            totalrecord = 0;
        query = '';
        fetchusersData(curtpage, query);
        // handling the prev-btn
        $(".prev-btn").on("click", function() {
            if (curtpage > 1) {
                curtpage--;
            }
            console.log("Prev Page: " + curtpage);
            fetchusersData(curtpage, query);
        });
        $(document).on('keyup', '#trip_users_search', function() {
            console.log('search input click hua');
            var query = $('#trip_users_search').val();
            console.log(query);
            var curtpage = 1;
            pagelimit = 10,
                totalrecord = 0;
            fetchusersData(curtpage, query);
        });
        $(document).on('click', '.alldetailUsers', function() {
            var id = $(this).attr('id');
            console.log('working details button');
            console.log(id);
            $.ajax({
                type: "get",
                data: {},
                url: "{{eventmie_url('touroperator/alldetailUsers')}}/" + id,

                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    $('#Subscriberdetails').modal('show');
                    if (html.data) {
                        console.log('inside success');
                        var dataArr = html.data;
                        console.log(dataArr.length);


                        var html = "";
                        for (var i = 0; i < dataArr.length; i++) {
                            html += "<tr>" +
                                "<td>" + dataArr[i].Name + "</td>" +
                                "<td>" + dataArr[i].emailid + "</td>" +
                                "<td>" + dataArr[i].Phoneno + "</td>" +
                                "<td>" + dataArr[i].Address + "</td>" +
                                "<td>" + dataArr[i].created_at + "</td>" +
                                "</tr>" +
                                "<hr />";
                            $("#listTable tbody").html(html);
                        }
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
        // handling the next-btn
        $(".next-btn").on("click", function() {
            if (curtpage * pagelimit < totalrecord) {
                curtpage++;
            }
            console.log("Next Page: " + curtpage);
            fetchusersData(curtpage, query);
        });
        $(document).on('click', '.link-click', function(event) {
            curtpage = $(this).attr('page');
            $(this).addClass('active');
            console.log(this);
            fetchusersData(curtpage, query);
        });

        function fetchusersData(page, query) {
            var emails = $('#operator_auth_email').val();

            var role_id = $('#operator_auth_roleid').val();
            var email = emails + role_id;
            console.log('command ka fetchiternary function');
            $.ajax({
                url: "{{eventmie_url('touroperator/usersdetail')}}/" + email,
                type: "GET",
                data: "page=" + page + "&query=" + query,
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('a_u_a_b_t')
                },
                success: function(html) {
                    console.log(html.data);

                    $('#totaltrip').html(html.galleries.length);
                    var subarr = html.sub;
                    var allsum = html.allsub;
                    var allsums = eval(allsum.join('+'))
                    console.log(allsums);
                    $('#totaluser').html(subarr);
                    $('#totalsub').html(allsums);
                    console.log('yeh sub hai');
                    console.log(html.sub);
                    if (html.data) {
                        var dataArr = html.data.data;

                        console.log(dataArr);
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
                            console.log('inside for loop');

                            html += "<tr>" +

                                "<td><input type='hidden'  value='" +
                                dataArr[i].TripHeading + "' name='heading' id='heading'>" +
                                dataArr[i].TripHeading +
                                " </td>" +
                                "<td>" + dataArr[i].user_count + "</td>" +
                                "<td> <button type='button'  id='" + dataArr[i].id +
                                "'class='alldetailUsers btn btn-success btn-sm'>Detail</button>" +
                                "</td>" +
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