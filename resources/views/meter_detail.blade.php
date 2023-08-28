 <div class="container">
     <div class="row justify-content-center">
         <div class="col-md-12">
             <table id="example2" class="display" style="width:100%">
                 <thead>
                     <tr>
                         <th>MPXN</th>
                         <th>Meter Type</th>
                         <th>Meter Reading</th>
                         <th>Reading Date</th>
                     </tr>
                 </thead>
                 <tbody> 
                     @foreach ($data as $value)
            
                         <tr>
                             <td> {{ $value->meters['mpxn'] }}</td>
                             <td> {{ $value->meters['meter_type'] }}</td>
                             <td> {{ $value['reading_value'] }}</td>
                             <td> {{ $value['reading_date'] }}</td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
 </div>
  
