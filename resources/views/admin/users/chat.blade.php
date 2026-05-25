@extends('admin.layouts.master')
@section('content')

@section('page_title')
    Single Chat 
@endsection
	<style>
		<style>
body {
  margin: 0 auto;
  max-width: 800px;
  padding: 0 20px;
}
form#chatticket .container.darker button.btn-primary {
    position: absolute;
    right: 145px;
    color: #212529;
    background: #ffde59;
   
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
img.yoyopics {
    width: 4%;
}

.profil {
  /* text-align: center; */
  margin: 20px 0;
}

.profil {
    position: relative;
    min-height: 68px;
    margin-bottom: 25px;
    background: #212529;
    padding-left: 80px;
}
.profil .img img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #212529;
}
.profil .img {
    width: 80px;
    height: 80px;
    position: absolute;
    left: 20px;
    border-radius: 50%;
    overflow: unset;
    top: 15px;
}
.profil h4 {
  margin-top: 10px;
  font-weight: 600;
  color: #fff;
}

.chats {
  max-height: 500px;
  overflow-y: auto;
  padding: 10px;
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.container {
  border: 1px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 12px;
  padding: 15px;
  margin: 10px 0;
  max-width: 75%;
  position: relative;
  word-break: break-word;
}

.container img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  float: left;
  margin-right: 10px;
  object-fit: cover;
}

.container img.right {
  float: right;
  margin-left: 10px;
  margin-right: 0;
}

.container.darker {
    background-color: #ffde594f;
    border-color: #ffde597a;
    margin-left: auto;
}

.container p {
  margin: 10px 0 5px;
}

.time-right {
  float: right;
  font-size: 12px;
  color: #888;
}

.time-left {
  float: left;
  font-size: 12px;
  color: #888;
}

form#chatticket {
  margin-top: 20px;
}

form#chatticket .container.darker  button.btn, form#chatticket .container.darker button.btn.btn-primary.form-control.sendmessage {
    position: absolute;
    right: 12px;
    width: 120px;
    top: 11px;
    height: 57px;
    color: #fff;
    background: #000;
    font-size: 18px;
    border-radius: 4px;
}

form#chatticket textarea.message {
  width: 100%;
  border-radius: 8px;
  margin-bottom: 10px;
  resize: none;
}

form#chatticket .btn-primary {
  margin-top: 5px;
  margin-right: 5px;
}

img.userimg, img.mayimg {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  margin-top: 5px;
}
</style>
	</style>
  <!-- <img src="{{asset(@$mata['image'])}}" class="yoyopics"> -->
<!-- <h2>{{@$mata['name']}} {{@$mata['lname']}} </h2> -->
<hr>
<div class="profil mb-2">
    <div class="img"><img  src="{{asset(@$userdata['image'])}}" alt="No image" /></div>
    <h4>{{@$userdata['name']}} {{@$userdata['lname']}}</h4>
</div>
<input type="hidden" class="user_id" name="id" value={{@$id}} />
<input type="hidden" class="user_image"  value="{{@$mata['image']}}" />
<input type="hidden" class="user_name"  value="{{@$mata['name']}} {{@$mata['lname']}}"/>
<div class="chats">
</div>

<input type="hidden"  class="user_imagebane" name="" value="">
<form id="chatticket" method="post" enctype="multipart/form-data">
<div class="container darker" style="background: #ffff;border: unset;">
  <!-- <input type="hidden" name=""> -->
  <textarea class="form-control message" placeholder="send"></textarea>
  <img id="pic"  />
  <input type="file" name="file"  style="display: none;"  class="filedata"  accept="image/png, image/gif, image/jpeg" oninput="pic.src=window.URL.createObjectURL(this.files[0])">
  <button typw="button" class="btn-primary" onclick="$('.filedata').trigger('click');"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
<button  class=" btn btn-primary form-control  sendmessage"  type="submit" >Send </button>
 <!-- <p><?php echo date('d-M, h:i A'); ?></p> -->
  
</div>
</form>
<input type="hidden" name="" value="" class="chatcount">
@endsection

@push('scripts')
<script>
	$("#checkPermissionAll").click(function(){
		if($(this).is(':checked'))
		{
			$('input[type=checkbox]').prop('checked', true)
		}else
		{
			$('input[type=checkbox]').prop('checked', false)
		}
	})
</script>
<!-- <script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>
 -->

<script>
  
</script>
<script>

let adminId='{{ Auth::user()->id }}';
let baseUrl="{{ url('/') }}";

    $('#chatticket').on('submit',async function(event)
    {          
          var countval=0; 
            var sum=0;
             countval=$('.chatcount').val();
            sum=parseInt(countval)+1;
            // console.log('sum',sum);
            var uidddd=$('.user_id').val();
            event.preventDefault();
            var val =$('.filedata').val();
            var sms=$('.message').val();
            if(val)
            {

            call_ajax();
            }
      var type='';
     var user_image=$('.user_image').val();
     var user_name=$('.user_name').val();

     var user_imagebane=$('.user_imagebane').val();
     var uid=$('.user_id').val();
     var main_id=adminId+'_'+uid;
     var main_id1=uid+'_'+adminId;
     if(sms!=='' || user_imagebane!=='')
     {
        
        sms=  sms.trim();
          if(user_imagebane!=='')
          {
           
               sms=user_imagebane;
                 type='image';
                  sendnotification(uidddd,type,'image');
              $('.user_imagebane').val('');
             $('.filedata').val('');
             $('#pic').hide();
          }
          else
          {
             sms=sms;
            type='text';
                 sendnotification(uidddd,type,sms,adminId);

          }
          // console.log('sms',sms);
          $('.message').val('');
        var date="<?php echo date('d-M, h:i A'); ?>";
        const db= await firebase.database();
         var rec1 = await db.ref("chat/"+main_id).push({
              datetime:date,
              message: sms,
              senderId:adminId,
              type:type
          });
           var rec1 = await db.ref("chat/"+main_id1).push({
              datetime:date,
              message: sms,
              senderId:adminId,
              type:type
          });


            var rec1 = await db.ref("chatList/"+adminId+'/'+uid).set({
              lastMessage:sms,
              type: type,
              dateTime:date,
              userId:uid,
              userImage:user_image,
              userName:user_name,
              messageCount:'0'

          });

         var rec1 = await db.ref("chatList/"+uid+'/'+adminId).set({
          lastMessage:sms,
          type:type,
          dateTime:date,
          userId:adminId,
          userImage:'',
          userName:'SmoothRide',
          messageCount:sum.toString()==='NaN' ? '1' : sum.toString()


          });
          if(rec1)
          {
             $('.message').val('');
             // mysms();
          }
          else
          {
            return false;
          }
     }
   
     else
        {  
            return false;
           
     }
      
    }); 



</script>

<script>
  
// function call_ajax(argument) {

// console.log(formData);
            
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            function call_ajax(file_data)
            {
              var form = $('form')[0]; 
            var formData = new FormData(form);     
           formData.append('file', $('input[type=file]')[0].files[0]);
            $.ajax({
            url:"{{route('chatimg')}}",
            type:"post",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,

            success:function(res)
            {
               // console.log('res',res);
               $('.user_imagebane').val(res);
            },
            error:function(res)
            {
            console.log(res);
            }

            }); 
            }
          // }
</script>
<script>
                   

    function  sendnotification(uidddd,type,sms,adminId)
    {
            $.ajax({
            url:" {{route('sendnotification')}} ",
            type:"post",
            data: {'uid':uidddd,'type':type,'sms':sms,'adminId':adminId},
            success:function(res)
            {
               console.log('res',res);
            },
            error:function(res)
            {
            console.log(res);
            }

            }); 
    }


</script>

<script>
      let db=firebase.database();
     var count=0;
      var uid=$('.user_id').val();
     

     db.ref("/chatList/"+ uid +'/'+adminId).on("value", (snap) => {
        if(count==undefined)
                 {
                    count=0;
                 }
                 else
                 {
                    count=snap.val().messageCount;
                 }
                 // console.log('count',count);
                 $('.chatcount').val(count);

               });
</script>
<script>

var uid=$('.user_id').val();
     // const db=firebase.database();
     db.ref("chat/"+adminId+'_'+uid).on("child_added", (snap) => {
              var sms='';
              var sms1='';
              if(snap.val().senderId==adminId)  
                          {
                           

                           if(snap.val().type=='image')
                           {
                               var img='<img  class="userimg" src="'+baseUrl+'/'+snap.val().message+'" />';
                           }
                           else
                           {
                              var img=snap.val().message;
                           }
                     sms ="<div class='container darker'>"+
                      "<img src='"+baseUrl+"/assets/admin/img/default-user.png' alt='Avatar' class='right' style='width:100%;'>"+
                      "<p>"+ img +"</p>"+
                      "<span class='time-left'>"+ snap.val().datetime +"</span>"+
                      "</div>" ;   
                          
                          $('.chats').append(sms);
                          }

                          else
                          {
                              if(snap.val().type=='image')
                           {
                               var img1='<img class="mayimg" src="'+baseUrl+'/'+snap.val().message+'" />';
                             // console.log('fghf',snap.val().message);
                           }
                           else
                           {
                              var img1=snap.val().message;
                           }
                                                         // console.log(snap.val());
                          sms1 ="<div class='container'>"+
                          "<img src='"+baseUrl+"/assets/admin/img/default-user.png' alt='Avatar'  style='width:100%;'>"+
                          "<p>"+ img1 +"</p>"+
                          "<span class='time-right'>"+ snap.val().datetime +"</span>"+
                          "</div>" ;

                            
                           $('.chats').append(sms1);      

                          }
                     //     
                     //       
                        
                           
                        // })
                   
                    // console.log(sms+sms1);
                })
</script>
<script >
  $(document).ready(function() {
  // $(".chats").stop().animate({ scrollTop: $(".chats")[0].scrollHeight}, 1000);
//     $(document).ready(function () {
//     // Handler for .ready() called.
//     $('html, body').animate({
//         scrollTop: $('.chats').offset().top
//     }, 'fast');
// });
    setTimeout(function () { 
$(".chats").animate({scrollTop:  $('.chats').prop('scrollHeight')});
 }, 2000); 
          // $(".chats").animate({scrollTop:  $('.chats').prop('scrollHeight')});

     // $("html, body").animate({
     //                scrollTop: $(
     //                  'html, body').get(0).scrollHeight
     //            }, 2000);
});


</script>
@endpush