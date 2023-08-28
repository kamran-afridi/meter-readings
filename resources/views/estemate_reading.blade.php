 <div class="container">
     <div class="row justify-content-center">
         <div class="col-md-12">
             <table id="example3" class="display" style="width:100%">
                 <thead>
                     <tr>
                         <th>MPXN</th>
                         <th>Meter Type</th>
                         <th>Est Readings</th>
                     </tr>
                 </thead>
                 <tbody>
                     {{-- {{ dd($data) }} --}}
                     @foreach ($data as $value)
                         <tr>
                             <td> {{ $value->meters['mpxn'] }}</td>
                             <td> {{ $value->meters['meter_type'] }}</td>
                             <td> {{ $value['estimated_reading'] }}</td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
 </div>
