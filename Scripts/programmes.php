<script type="text/javascript">
$(document).ready(function(){
	$("#d-re").hide();
	$("#d-soirée").hide();
	$("#d-sei").hide();
	$("#d-web").hide();
	$("#d-rits").hide();
	$("#d-voyage").hide();
	$("#d-pougnes").hide();
	$("#d-wed").hide();
	$("#d-wei").hide();
  $("#hide").click(function(){
    $("#calque1").hide();
  });
  
//   Affiche le programme bureau
  
  $("#bureau").click(function(){
    $("#d-re").hide();
    $("#d-bureau").show();
    $("#d-soirée").hide();
    $("#d-sei").hide();
    $("#d-web").hide();
    $("#d-rits").hide();
    $("#d-voyage").hide();
    $("#d-pougnes").hide();
    $("#d-wed").hide();
    $("#d-wei").hide();
  });
  
  $("#re").click(function(){
    $("#d-re").show();
    $("#d-bureau").hide();
	$("#d-soirée").hide();
	$("#d-sei").hide();
	$("#d-web").hide();
	$("#d-rits").hide();
	$("#d-voyage").hide();
	$("#d-pougnes").hide();
	$("#d-wed").hide();
	$("#d-wei").hide();
  });
  

 $("#soirée").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").show();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
  
 
 $("#sei").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").show();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
  
 
 $("#web").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").show();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
  
 
 $("#rits").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").show();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
  
 $("#voyage").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").show();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
  
 $("#pougnes").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").show();
 	$("#d-wed").hide();
 	$("#d-wei").hide();
 });
 
 $("#wed").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").show();
 	$("#d-wei").hide();
 });
  
 $("#wei").click(function(){
   $("#d-re").hide();
   $("#d-bureau").hide();
 	$("#d-soirée").hide();
 	$("#d-sei").hide();
 	$("#d-web").hide();
 	$("#d-rits").hide();
 	$("#d-voyage").hide();
 	$("#d-pougnes").hide();
 	$("#d-wed").hide();
 	$("#d-wei").show();
 });

});
</script>