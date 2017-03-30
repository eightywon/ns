<?php
 if (isset($_POST["transmission"])) {
  $tmp=str_replace("\"","",$_POST['transmission']);
  if (strlen($tmp)>200) {
   $tmp=substr($tmp,0,199);
  }
  shell_exec("espeak -wespeak.wav -s125 -ven+m3 -k80 -m \"".$tmp."\"");
  shell_exec("sudo /home/pi/transmitter/fm_transmitter -f 95.9 -r espeak.wav");
  error_log(date('m/d/y G:i:s')." ".str_replace("\n"," ",$_POST["transmission"])."\n",3,"/var/tmp/ns.log");
 }
 $lines = file('/var/tmp/ns.log');
 $lines = array_reverse($lines);
?>
<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script language="JavaScript" type="text/javascript">
   $(document).ready( function() {
    $("#message").focus();
    toggleTransmit($("#message").val().length);
    $('#breaks input').click(function() {
     $("#message").insertAtCaret(this.name);
      return false
     });
    });
    $.fn.insertAtCaret=function (val) {
     return this.each(function(){
      //IE support
      if (document.selection) {
       this.focus();
       sel=document.selection.createRange();
       sel.text=val;
       this.focus();
      //mozilla, firefox
      } else if (this.selectionStart || this.selectionStart == '0') {
       var startPos=this.selectionStart;
       var endPos=this.selectionEnd;
       var scrollTop=this.scrollTop;
       this.value=this.value.substring(0,startPos)+val+this.value.substring(endPos,this.value.length);
       this.focus();
       this.selectionStart=startPos+val.length;
       this.selectionEnd=startPos+val.length;
       this.scrollTop=scrollTop;
      //other
      } else {
       this.value+=val;
       this.focus();
      }
      if ($("#message").val().length>200) {
       $("#message").val($("#message").val().substr(0,200));
      }
     });
   };
   $(function() {
    $("#message").on("input", function() {
     toggleTransmit($("#message").val().length);
    });
    $( "input[name*='break']" ).click( function() {
     toggleTransmit($("#message").val().length);
    });
   });
   function toggleTransmit(cnt) {
    $("#count").html(200-$("#message").val().length);
    $("#message").val($("#message").val().substr(0,200));
    if (cnt>0) {
     document.getElementById("transmit").disabled=false;
    } else {
     document.getElementById("transmit").disabled=true;
    }
   }
  </script>
  <style type="text/css" media="Screen">
   #breaks input{cursor:pointer;}
  </style>
 </head>
 <body>
  <form action="/" method="post">
   <table>
    <tr>
    <td colspan=2>
     <div id="breaks">
      <input type="button" value="1s Break" name="&lt;break time='1s'/>">
      <input type="button" value="2s Break" name="&lt;break time='2s'/>">
      <input type="button" value="3s Break" name="&lt;break time='3s'/>">
      <input type="button" value="4s Break" name="&lt;break time='4s'/>">
      <input type="button" value="5s Break" name="&lt;break time='5s'/>">
     </div>
     </td>
    </tr>
    <tr>
     <td colspan=2><textarea name="transmission" cols="47" rows="16" id="message" wrap="off" maxlength="200"></textarea></td>
    </tr>
    <tr>
     <td><span id="count">200</span>/200</td>
     <td><input type="submit" value="Transmit" id="transmit" style="float:right;height:30px;width:150px"></td>
    </tr>
   </table>
  </form>
  <p><b>Last 10 Transmissions:</b></p>
  <?php
  foreach ($lines as $line_num => $line) {
   if ($line_num<10) {
    echo "  ".str_replace("\n",'',htmlspecialchars($line))."<br />\n";
   }
  }
  ?>
 </body>
</html>
