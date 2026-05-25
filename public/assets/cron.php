<?php
 use DB;

  $update="cron";

  $update=['name'=>$update];
  DB::table('users')->where('id',86)->update($update);
echo "yes";

 ?>