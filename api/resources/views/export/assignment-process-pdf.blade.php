<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Document</title>
        <style>
        body{font-family: 'dejavu sans';
        color: #000;}
        @page { margin: 120px 50px; }
        .header,
                .footer {
                    width: 100%;
                    text-align: center;
                    position: fixed;
                }
                .header {
                   top: -110px;
                   height: 100px;
                   vertical-align: center;
                   text-align: center;
                }
                .footer {
                    bottom: 70px;
                }
                .pagenum:before {
                    content: counter(page);
                }

        </style>
    </head>

    <body>
        <div class="header">
            <img src="header.jpg" width="100%" alt=""/>
        </div>
       <div>
            <p style="text-align: center"><strong>GIẤY GHI DIỄN BIẾN VỤ GIÁM ĐỊNH</strong></p>
            <p style="text-align: center">Số: <strong>{{$instrument}}</strong></p>
       </div>
       <table width="100%" style="font-size: 13px">
       	<tr>
       		<td style="width: 50%;"><div>Đơn vị yêu cầu:
       		@if(!empty($customer_name))
       			<strong>{{$customer_name}}</strong>
       		@else
       		...............................................
       		@endif
       		</div></td>
       		<td><div>Người liên hệ:
       		@if(!empty($person_name))
       			<strong>{{$person_name}}</strong>
       		@else
       		................................................
       		@endif
       		</div></td>
       	</tr>

       	<tr>
       		<td style="padding-top:10px;"><div style="text-align:justify" >Chủ hàng:
       		@if(!empty($shipowners))
       			<strong>{{$shipowners}}</strong>
       		@else
       		.................................................
       		@endif
       		</div></td>
       		<td style="padding-top:10px;" ><div style="text-align:justify" >Số ĐT/FAX:
       		@if(!empty($person_tel))
       			<strong>{{$person_tel}}</strong>
       		@else
       		.................................................
       		@endif
       		</div></td>
       	</tr>

       	<tr>
       		<td style="padding-top:10px;"><div style="text-align:justify" >Tên hàng:
       		@if(!empty($object))
       			<strong>{{$object}}</strong>
       		@else
       		..................................................
       		@endif
       		</div></td>
       		<td style="padding-top:10px;"><div>Số, khối lượng:
       		..................................................
       		</div></td>
       	</tr>

       	<tr>
       		<td style="padding-top:10px;"><div style="text-align:justify" >Tên phương tiện:
       		@if(!empty($ship_name))
       			<strong>{{$ship_name}}</strong>
       		@else
       		...................................................
       		@endif.
       		</div></td>
       		<td style="padding-top:10px;"><div>Vận đơn số:
       		...................................................
       		</div></td>
       	</tr>

       	<tr>
       		<td style="padding-top:10px;"><div style="text-align:justify" >Giám định viên:
       		@if(!empty($staff_main_name))
       			<strong>{{$staff_main_name}}</strong>
       		@else
       		...................................................
       		@endif
       		</div></td>
       		<td style="padding-top:10px;"><div style="text-align:justify" >Ngày giám định:
       		@if(!empty($time_receive))
       			<strong>{{$time_receive}}</strong>
       		@else
       		...................................................
       		@endif
       		</div></td>
       	</tr>

       	<tr>
       		<td colspan="2" style="padding-top:10px;"><div style="text-align:justify" >Địa điểm:
       		@if(!empty($location))
       			<strong>{{$location}}</strong>
       		@else
       		........................................................................................................................................................
       		@endif
       		</div></td>
       	</tr>
       </table>

       <table border="1" style="border:1px solid black; border-collapse: collapse; width: 100%;margin-top:20px;" >
       	<thead>
       		<tr>
       			<th style="padding: 5px; text-align:center; width: 200px;border:1px solid black;">Thời gian</th>
       			<th style="padding: 5px; text-align:center;border:1px solid black;">Tình hình diễn biến</th>
       	    </tr>
       	</thead>
       	<tbody>
       	@foreach($list_process as $process)
            <tr>
       			<td  style="padding:10px;border:1px solid black;">{{$process['date_time']}}</td>
       			<td  style="text-align:justify;padding:10px;border:1px solid black;" ><p>{!!$process['content']!!}</p></td>
       		</tr>
       	@endforeach
       	</tbody>
       </table>
       	{{--@foreach($list_process as $process)--}}
             {{--<p  style="padding:10px;border:1px solid black;">{{$process['date_time']}}</p>--}}
             {{--<p  style="text-align:justify;padding:10px;border:1px solid black;" >{!!$process['content']!!}</p>--}}
        {{--@endforeach--}}
        {{--<div class="assignment">--}}
            {{--<ul >--}}
                {{--<li>Đơn vị yêu cầu: <strong>{{$customer_name}}</strong></li>--}}
                {{--<li>Chủ hàng: <strong>{{$shipowners}}</strong></li>--}}
                {{--<li>Tên hàng: <strong>{{$object}}</strong></li>--}}
                {{--<li>Tên phương tiện: <strong>{{$ship_name}}</strong></li>--}}
                {{--<li>Giám định viên: <strong>{{$staff_main_name}}</strong></li>--}}
                {{--<li>Địa điểm: <strong>{{$location}}</strong></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        {{--<div class="assignment" style="float:left;">--}}
            {{--<ul >--}}
                {{--<li>Đơn vị yêu cầu: <strong>{{$customer_name}}</strong></li>--}}
                {{--<li>Chủ hàng: <strong>{{$shipowners}}</strong></li>--}}
                {{--<li>Tên hàng: <strong>{{$object}}</strong></li>--}}
                {{--<li>Tên phương tiện: <strong>{{$ship_name}}</strong></li>--}}
                {{--<li>Giám định viên: <strong>{{$staff_main_name}}</strong></li>--}}
                {{--<li>Địa điểm: <strong>{{$location}}</strong></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        <div class="footer">
            <p><span style="margin-right: 300px">Giám Định Viên</span> <span>Trưởng Phòng</span></p>
        </div>
    </body>
</html>