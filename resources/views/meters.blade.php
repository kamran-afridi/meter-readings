@extends('layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <table id="example" class="display tex-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>MPXN</th>
                            <th>Meter Type</th>
                            <th>Installation Date</th>
                            <th>Est ann consumption</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addmeter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Meter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" id="meterForm" name="meterForm">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="MPXN" class="col-form-label">MPXN:</label>
                            <input type="text" class="form-control" id="MPXN" name="MPXN" required>
                        </div>
                        <div class="form-group">
                            <label for="meter_type" class="col-form-label">Meter Type:</label>
                            <select class="form-control" id="meter_type" aria-label="Default select example" name="meter_type">
                                <option value="GAS" selected>GAS</option>
                                <option value="ELECTRIC">ELECTRIC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Installation_Date" class="col-form-label">Installation Date:</label>
                            <input type="date" class="form-control" id="Installation_Date" name="Installation_Date"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="estimated_annual_consumption " class="col-form-label">EST Annual
                                Consumption:</label>
                            <input type="number" class="form-control" id="estimated_annual_consumption" min="2000"
                                max="8000" placeholder="The expected values are between 2,000 and 8,000"
                                name="estimated_annual_consumption">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="saveBtn" class="btn btn-primary">Add Meter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- displaymeterinfo section --}}
    <div class="modal fade" id="displaymeterinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Meter Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="meterinfo_content">

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- display estemate meterinfo section --}}
    <div class="modal fade" id="estimated_reading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Estimated Reading Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="estimated_reading_content">

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- calculate estimated annual consumption  --}}
    <div class="modal fade" id="cal_annu_meter_reading" tabindex="-1" role="dialog"
        aria-labelledby="cal_annu_meter_reading" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cal_annu_meter_reading">Add Estimated Reading </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="estimated_reading_form" name="estimated_reading_form">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <input type="hidden" name="meter_id" id="meter_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="estimated_reading_date" class="col-form-label">Estimated Reading Date:</label>
                            <input type="date" class="form-control" id="estimated_reading_date"
                                name="estimated_reading_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="save_estimated_readingBtn" class="btn btn-primary">Generate Estimated
                            Reading</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#example').DataTable({
            processing: true,
            serverSide: true,

            ajax: "{{ route('meters.index') }}",
            columns: [{
                    data: 'mpxn',
                    name: 'mpxn'
                },
                {
                    data: 'meter_type',
                    name: 'meter_type'
                },
                {
                    data: 'installation_date',
                    name: 'installation_date'
                },
                {
                    data: 'estimated_annual_consumption',
                    name: 'estimated_annual_consumption'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: 'Add New Meter',
                action: function(e, dt, node, config) {
                    $('#addmeter').modal('show');
                }
            }],
        });
        /* Add meter info*/
        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#meterForm').serialize(),
                url: "{{ route('meters.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#meterForm').trigger("reset");
                        alert(data.success);
                        table.draw();
                    } else {
                        printErrorMsg(data.error);
                        $('#saveBtn').html('Save Changes');
                    }

                }
            });
        });
        /* Add Estimated Reading*/
        $('#save_estimated_readingBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#estimated_reading_form').serialize(),
                url: "{{ route('create.estimated.reading') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // alert(data);
                    if ($.isEmptyObject(data.error)) {
                        $('#estimated_reading_form').trigger("reset");
                        alert(data.success);
                        table.draw();
                        $('#save_estimated_readingBtn').html('Add Estimated Reading');
                    } else {
                        printErrorMsg(data.error);
                        $('#save_estimated_readingBtn').html('Add Estimated Reading');
                    }
                }
            });
        });

    });
    /* displaymeterinfo logic */
    function displaymeterinfo(id) {
        var id = id;
        var url = "{{ route('meters.show', ':id') }}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'HTML',
            success: function(data) {
                // console.log(data);
                $('.meterinfo_content').html('')
                $('.meterinfo_content').html(data)
                $('#displaymeterinfo').modal('show');
                var table = $('#example2').DataTable({

                });

            }
        });
    }
    /* display estimated reading logic */
    function view_est_reading(id) {
        var id = id;
        var url = "{{ route('meters.view_est_reading', ':id') }}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'HTML',
            success: function(data) {
                // console.log(data);
                $('.estimated_reading_content').html('')
                $('.estimated_reading_content').html(data)
                $('#estimated_reading').modal('show');
                var table = $('#example3').DataTable({

                });

            }
        });
    }
    /* cal_annu_meter_reading display model form */
    function cal_annu_meter_reading(id) {
        var id = id;
        $('#meter_id').val(id);
        $('#cal_annu_meter_reading').modal('show');
    }
</script>
