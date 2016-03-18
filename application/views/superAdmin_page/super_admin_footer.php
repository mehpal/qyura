<footer class="footer text-right">
      2015 © Qyura.
</footer>
</div>
<!-- End Right content here -->
    </div>
    <!-- END wrapper -->
<script>
var resizefunc = [];

</script>
     <script src="<?php echo base_url();?>assets/jquery-1.8.2.min.js"> </script>
     <script src="<?php echo base_url();?>assets/js/framework.js"></script>

<!--     <script type= 'text/javascript' src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>-->
     <script src="<?php echo base_url(); ?>assets/js/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/datatables/dataTables.bootstrap.js"></script>
     

<!--     <script type= 'text/javascript' src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>-->
        
 <script>
     
     $('#search').bind('keypress', function(e)
    {
       if(e.keyCode == 13)
       {
          return false;
       }
    });
</script>       
<script>
    //Submit Data for add and edit for all
    function removeError(obj)
    {
        var id =obj.id;
        $('#'+id).removeClass('error');
    }
    function submitData(url,formData){
        var formData = formData;
        
        $.ajax({
            type: "POST",
            url: url,
            data: formData, //only input
            processData: false,
            contentType: false,
            xhr: function ()
            {
                $(".loader").show();
                var xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('#addLoad .progress-bar').css('width', percentComplete + '%');
                    }
                }, false);
                //Download progress
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                    }
                }, false);
                return xhr;
            },
            success: function (response, textStatus, jqXHR) {
                try {
                    $(".loader").hide();

                    var data = $.parseJSON(response);
                    if (data.status == 0)
                    {
                        if (data.isAlive)
                        {
                            $('#addLoad .progress-bar').css('width', '00%');
                            console.log(data.errors);
                            $.each(data.errors, function (index, value) {
                                if(typeof data.custom == 'undefined'){
                                $('#err_' + index).html(value);
                                }
                                else
                                {
                                    $('#err_' + index).addClass('error');
                                    
                                    if(index == 'TopError')
                                    {
                                        $('#er_' + index).html(value);
                                    }
                                    else{
                                        $('#er_TopError').append('<p>'+value+'</p>');
                                    }
                                }
                                
                            });
                            $('#er_TopError').show();
                            setTimeout(function () {
                                $('#er_TopError').hide(5000);
                                $('#er_TopError').html('');
                            }, 5000);
                        }
                        else
                        {
                            $('#headLogin').html(data.loginMod);
                        }
                    }else {
//                        document.getElementById("setData").reset();
                        $('#myModal').modal('toggle');
                        $('#successTop').show();
                        $('#successTop').html(data.msg);
                        
                        setTimeout(function () {
                            $('#successTop').hide();
                            $('#successTop').html('');
                            if(data.url){
                                window.location.href = '<?php echo site_url() ?>'+'/'+data.url;
                            }else{
                                location.reload(true);
                            }                    
                        },3000);
                    }
                } catch (e) {
                    $('#er_TopError').show();
                    $('#er_TopError').html(e);
                    setTimeout(function () {
                        $('#er_TopError').hide(5000);
                        $('#er_TopError').html('');
                    }, 5000);
                }
            }
        });
    }
</script>

