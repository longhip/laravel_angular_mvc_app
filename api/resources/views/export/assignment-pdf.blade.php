<html>
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
           <title>Document</title>
           <style>
           body{font-family: 'dejavu sans';
           color: #000;}
           </style>
       </head>
    <body>
    <div style="text-align:center; font-weight: bold; font-size: 16px;"> GIẤY YÊU CẦU GIÁM ĐỊNH </div>
    <div style="text-align:right;">Số: ... ... ... ... ... ...</div>
    <div style="text-align:center; font-weight:bold;padding-top:15px;"><u><i>Kính gửi</i></u> : Công ty TNHH Giám Định Bảo Định(BADINCO)</div>
    <div style="padding-left: 150px;padding-top:10px;">Địa chỉ &nbsp;&nbsp; :  &nbsp;&nbsp; P14 - Tầng 2 tòa nhà Thành Đạt - số 03 Lê Thánh Tông, <span style="padding-left: 70px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; P.Máy Chai, Q.Ngô Quyền - HP</span> </div>
    {{--<div style="padding-left: 242px;padding-top:10px;"></div>--}}
    <div style="padding-left: 150px;padding-top:10px;">Tel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;  (031).3765693
     <span style="padding-left: 70px">Fax &nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp; (031).3765694</span></div>
    <div style="padding-left: 150px;padding-top:10px;">Email &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; badinco@hn.vnn.vn - baodinhhp@vnn.vn </div>


    <div style="padding-top:20px;">Cơ quan yêu cầu:
        @if(!empty($customer_name))
        <strong>{{$customer_name}}</strong>
        @else
    	    ............................................................................................................................................
        @endif
    </div>
    <div style="padding-top:10px;">Địa chỉ:
        @if(!empty($customer_address))
        <strong>{{$customer_address}}</strong>
        @else
            ............................................................................................................................................
        @endif
    </div>
    <div style="padding-top:10px;">Số điện thoại:
        @if(!empty($customer_phone))
        <strong>{{$customer_phone}}</strong>
        @else
            ............................................................................................................................................
        @endif
    <div style="padding-top:10px;">Fax:
            @if(!empty($customer_fax))
               <strong>{{$customer_fax}}</strong>
            @else
                   ............................................................................................................................................
            @endif
    </div>
    <div style="padding-top:10px;">Mã số thuế:
            @if(!empty($customer_mst))
               <strong>{{$customer_mst}}</strong>
            @else
                   ............................................................................................................................................
            @endif

    </div>
    <div style="padding-top:10px;">Đối tượng yêu cầu giám định:
        @if(!empty($object))
        <strong>{{$object}}</strong>
        @else
            ............................................................................................................................................ </div>
        <div style="padding-top:10px;">............................................................................................................................................</div>
        <div style="padding-top:10px;">............................................................................................................................................</div>
        @endif
    <div style="padding-top:10px;">Hạng mục yêu cầu giám định:
        @if(!empty($assignment_item))
        <strong>{{$assignment_item}}</strong>
        @else
            ............................................................................................................................................ </div>
            <div style="padding-top:10px;">............................................................................................................................................ </div>
        @endif
    </div>
    <div style="padding-top:10px;">Ngày giờ, địa điểm hẹn giám định:
        @if(!empty($time_receive) && !empty($location))
            <strong>{{$time_receive}} - {{$location}}</strong>
        @else
            ............................................................................................................................................
        @endif
    </div>
    <div style="padding-top:10px;">Tên người liên hệ:

        @if(!empty($person_name))
            <strong>{{$person_name}}</strong>
        @else
            ............................................................................................................................................
        @endif
    </div>
    <div style="padding-top:10px;">Điện thoại:
        @if(!empty($person_tel))
            <strong>{{$person_tel}}</strong>
        @else
            ............................................................................................................................................
        @endif
    </div>
    <div style="padding-top:10px;">Giấy tờ kèm theo: ........................................................................................................................................... </div>
    <div style="padding-top:10px;">........................................................................................................................................................................ </div>
    <div style="padding-top:10px;">........................................................................................................................................................................ </div>
    <div style="padding-top:10px;">Số bản chứng thư yêu cầu cấp: &nbsp;&nbsp;&nbsp; -Tiếng việt: ................. bản </div>
    <div style="padding-top:10px; padding-left: 275px;"> -Tiếng Anh: ................. bản </div>
    <div style="padding-top:10px; padding-left: 50px;">Chúng tôi cam kết sẽ thanh toán đầy đủ phí giám định sau khi nhận được chứng thư. </div>
    <div style="padding-top:20px; text-align:right;">Ngày...........Tháng.........năm <?php echo date('Y'); ?></div>

    <div style="padding-top:10px;">
    	<table style="width:100%;">
    		<tr>
    			<td style="text-align:left;padding-left: 50px; font-weight:bold;"> KÝ CHẤP THUẬN YÊU CẦU </td>
    			<td style="text-align:right;padding-right: 50px; font-weight:bold;"> CƠ QUAN YÊU CẦU </td>
    		</tr>
    	</table>
    </div>
    </body>
</html>