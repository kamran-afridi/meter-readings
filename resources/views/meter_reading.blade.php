@extends('layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>MPXN</th>
                            <th>Meter Type</th>
                            <th>Reading Value</th>
                            <th>Reading Date</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Generate Meter Reading</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" id="meterReadingForm" name="meterReadingForm">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <input type="hidden" name="meter_id" id="meter_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="MPXN" class="col-form-label">MPXN:</label>
                            <select class="form-control" id="MPXN" aria-label="Default select example" name="MPXN"
                                required>
                                <option value="" selected>Please Select An Option</option>
                                @foreach ($Meters_data as $value)
                                    <option value="{{ $value['id'] }}">{{ $value['mpxn'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Meter Type" class="col-form-label">Meter Type:</label>
                            <input type="text" disabled class="form-control" id="meter_type" name="meter_type" required>
                        </div>
                        <div class="form-group">
                            <label for="reading_value" class="col-form-label">Reading Value:</label>
                            <input type="number" class="form-control" id="reading_value" name="reading_value">
                        </div>
                        <div class="form-group">
                            <label for="reading_date" class="col-form-label">Reading Date:</label>
                            <input type="date" class="form-control" id="reading_date" name="reading_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="saveBtn" class="btn btn-primary">Add Reading</button>
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
            ajax: "{{ route('meter-reading.index') }}",
            columns: [{
                    data: 'meters.mpxn',
                    name: 'meters.mpxn'
                },
                {
                    data: 'meters.meter_type',
                    name: 'meters.meter_type'
                },
                {
                    data: 'reading_value',
                    name: 'reading_value'
                },
                {
                    data: 'reading_date',
                    name: 'reading_date'
                },
            ],
            dom: 'Bfrtip',
            buttons: [{
                text: 'Add New Meter Reading',
                action: function(e, dt, node, config) {
                    $('#addmeter').modal('show');
                }
            }],
        });
        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#meterReadingForm').serialize(),
                url: "{{ route('meter-reading.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#meterReadingForm').trigger("reset");
                        // $('#ajaxModel').modal('hide');
                        alert(data.success);
                        table.draw();
                        $('#saveBtn').html('Save Changes');
                    } else {
                        printErrorMsg(data.error);
                        $('#saveBtn').html('Add Reading');
                    }

                },

            });
        });
        $("#MPXN").change(function() {
            $.get("{{ route('meter-reading.index') }}" + '/' + $('#MPXN').find(":selected").val() +
                '/edit',
                function(
                    data) {
                    $('#meter_type').val(data.meter_type);
                })
        });
    });
</script>
