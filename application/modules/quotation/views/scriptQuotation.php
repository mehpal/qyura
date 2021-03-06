<link href="<?php echo base_url(); ?>assets/vendor/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendor/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/reCopy.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.min.js" type="text/javascript"></script>  
<script src="<?php echo base_url(); ?>assets/js/pages/send-quote.js"></script>
<script src="<?php echo base_url(); ?>assets/js/pages/quotation-detail.js"></script>
<script src="<?php echo base_url(); ?>assets/js/pages/quote-history.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/js/pages/quotelist.js" type="text/javascript"></script>
 <script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js" type="text/javascript"></script> 
 
 <!-- crooper css and js-->
<link href="<?php echo base_url();?>assets/cropper/cropper.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/cropper/main.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/cropper/cropper.js"></script>
<script src="<?php echo base_url(); ?>assets/cropper/main.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

<script>
               


       
    var hideKeyboard = function () {
        document.activeElement.blur();
        $(".pickDate").blur();
    };
</script>


<script>


    $(function () {
        var removeLink = '<a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false"> <i class="fa fa-minus-circle fa-2x m-t-5 label-plus"></i></a>';
        $('a.add').relCopy({append: removeLink});


    });

    function isNumberKey(evt, id) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            $("#" + id).html("Please enter number key");
            return false;
        } else {
            $("#" + id).html('');
            return true;
        }
    }
    var urls = "<?php echo base_url() ?>";
    var j = 1;

    function fetchCity(stateId) {

        $.ajax({
            url: urls + 'index.php/pharmacy/fetchCity',
            type: 'POST',
            data: {'stateId': stateId},
            success: function (datas) {
                // console.log(datas);
                $('#pharmacy_cityId').html(datas);
                $('#pharmacy_cityId').selectpicker('refresh');
                $('#StateId').val(stateId);
            }
        });

    }

    function fetchHos(type, cityId) {

        if (type != '' && type == "Hospital") {
            var method = 'index.php/healthcare/fetchHospital';
        } else if (type != '' && type == "Diagnostic") {
            var method = 'index.php/healthcare/fetchDiagno';
        }

        $.ajax({
            url: urls + method,
            type: 'POST',
            data: {'cityId': cityId},
            success: function (datas) {
                $('#miName').html(datas);
                $('#miName').selectpicker('refresh');
            }
        });

    }
    function validationHealthpkg() {
        //$("form[name='pharmacyForm']").submit();
        // alert("here");
        var check = /^[a-zA-Z\s]+$/;
        var alphacheck = /^[a-z0-9]+$/i;
        var cityId = $.trim($('#cityId').val());
        var miType = $.trim($('#miType').val());
        var miName = $.trim($('#miName').val());
        var packageId = $.trim($('#packageId').val());
        var packagetitle = $.trim($('#packagetitle').val());
        var hospitalservices = $.trim($('#hospitalServices_serviceName1').val());
        var status = 1;
        //debugger;

        if (packagetitle == '') {
            $('#packagetitle').addClass('bdr-error');
            $('#error-packagetitle1').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        } else if (!check.test(packagetitle))
        {
            $('#packagetitle').addClass('bdr-error');
            $('#error-packagetitle2').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
        }
        if (packageId == '') {
            $('#packageId').addClass('bdr-error');
            $('#error-packageId1').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        } else if (!alphacheck.test(packageId)) {
            $('#packageId').addClass('bdr-error');
            $('#error-packageId3').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_cntPrsn').focus();
        }
        if ($.trim($('#bestPrice').val()) == '') {
            $('#bestPrice').addClass('bdr-error');
            $('#error-bestPrice').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_countryId').focus();
        }
        if ($.trim($('#discountPrice').val()) == '') {
            $('#discountPrice').addClass('bdr-error');
            $('#error-discountPrice').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_address').focus();
        }
        if (!check.test(hospitalservices)) {
            $('#hospitalServices_serviceName1').addClass('bdr-error');
            $('#error-hospitalServices_serviceName1').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_cntPrsn').focus();
        }
        
        if (cityId == '') {
            $('#city').addClass('bdr-error');
            $('#error-cityId').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
        
        if (miType == '') {
            $('#miType').addClass('bdr-error');
            $('#error-miType').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
        
        if (miName == '') {
            $('#miName').addClass('bdr-error');
            $('#error-miName').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
      
                       //debugger;
        if(packageId !=''){
            $.ajax({
            url: urls + 'index.php/healthcare/check_pkgId',
            type: 'POST',
            data: {'packageId': packageId},
            success: function (datas) {
                if (datas == 1) {
                    $('#packageId').addClass('bdr-error');
                    $('#error-packageId2').fadeIn().delay(3000).fadeOut('slow');
                     status = 0;
                } 
            }
        });
     }
            
     if(status == 1){
         return true;
     }else{
         return false;
     }
        //debugger;
  }
  function editValidationHealthpkg() {
        //$("form[name='pharmacyForm']").submit();
        // alert("here");
        var check = /^[a-zA-Z\s]+$/;
        var alphacheck = /^[a-z0-9]+$/i;
        var cityId = $.trim($('#cityId').val());
        var miType = $.trim($('#miType').val());
        var miName = $.trim($('#miName').val());
        var packagetitle = $.trim($('#packagetitle').val());
        var hospitalservices = $.trim($('#hospitalServices_serviceName1').val());
        var status = 1;
        //debugger;

        if (packagetitle == '') {
            $('#packagetitle').addClass('bdr-error');
            $('#error-packagetitle1').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        } else if (!check.test(packagetitle))
        {
            $('#packagetitle').addClass('bdr-error');
            $('#error-packagetitle2').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
        }
        if ($.trim($('#bestPrice').val()) == '') {
            $('#bestPrice').addClass('bdr-error');
            $('#error-bestPrice').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_countryId').focus();
        }
        if ($.trim($('#discountPrice').val()) == '') {
            $('#discountPrice').addClass('bdr-error');
            $('#error-discountPrice').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_address').focus();
        }
        if (!check.test(hospitalservices)) {
            $('#hospitalServices_serviceName1').addClass('bdr-error');
            $('#error-hospitalServices_serviceName1').fadeIn().delay(3000).fadeOut('slow');
            status = 0;
            // $('#hospital_cntPrsn').focus();
        }
        
        if (cityId == '') {
            $('#city').addClass('bdr-error');
            $('#error-cityId').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
        
        if (miType == '') {
            $('#miType').addClass('bdr-error');
            $('#error-miType').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
        
        if (miName == '') {
            $('#miName').addClass('bdr-error');
            $('#error-miName').fadeIn().delay(3000).fadeOut('slow');
            status = 0;

        }
      
       
            
     if(status == 1){
         return true;
     }else{
         return false;
     }
        //debugger;
  }
  
  
  
   $("#refDoctor").select2({
        allowClear: true,
        placeholder: "Select Ref. Doctor"
    });
      $("#cityId").select2({
             allowClear: true,
             placeholder: "Select City Name"
        });
          $("#miType").select2({
             allowClear: true,
             placeholder: "Select MI Name"
        });
        
    $(document).ready(function () {   
        
    var oTableQuo = $('#quotationTable').DataTable({
            "processing": true,
            "serverSide": true,
            "columnDefs": [{
                    "targets": 7,
                    "searchable": false,
                    "orderable": false
                }],
            "pageLength": 10,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "dom": '<<t><"clearfix m-t-20 p-b-20" p>',
            "iDisplayStart ": 20,
            "columns": [
                {"data": "miName"},
                {"data": "uniqueId"},
                {"data": "pName"},
                {"data": "dt"},
                {"data": "docName"},
                {"data": "contact"},
                {"data": "qStatus"},
                {"data": "action", "searchable": false, "order": false},
            ],
            "ajax": {
                "url": "<?php echo site_url('quotation/getQuotationDl'); ?>",
                "type": "POST",
                "async": false,
                "data": function (d) {
                    d.searchVal = $("#search").val();
                    d.isSent = $("#isSent").val();
                    d.fromDate = $("#fromDate").val();
                    d.toDate = $("#toDate").val();
                     //d.mi = $("#mi").val();
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
                },
                 beforeSend: function () {
                   // setting a timeout
                    $('#load_consulting').show();
                },
                complete: function ()
                {
                   $('#load_consulting').hide('200');
                },
            }
        });
        
        
         $('#search').on('keyup', function() {
                        oTableQuo.draw();
         } );
         
         $('#isSent').on('change', function() {
                        oTableQuo.draw();
         } )
        
        
         $('.pickDate').datepicker().on('changeDate', function (ev) {
                $('.pickDate').datepicker('hide');
                     var sDate = $('#fromDate').val();
                     var eDate = $('#toDate').val(); 
                     var d1 = new Date($('#fromDate').val());
                     var d2 = new Date($('#toDate').val());
                     if(sDate != '' && eDate != ''){
                     if(d1.getTime() > d2.getTime()){
                        $("#date_error").html("<p>From date should be less then To date.</p>");
                        $('#fromDate').val("");
                        $('#toDate').val("");
                     }else{
                          oTableQuo.draw();
                          return false;
                         $("#date_error").html(""); 
                     }
                    } 
            });
       
    });

    function check_packageId(packageId) {
        $.ajax({
            url: urls + 'index.php/healthcare/check_pkgId',
            type: 'POST',
            data: {'packageId': packageId},
            success: function (datas) {
                if (datas == 0) {
                    //$("form[name='healthcareForm']").submit();
                    return false;
                } else {
                    $('#packageId').addClass('bdr-error');
                    $('#error-packageId2').fadeIn().delay(3000).fadeOut('slow');
                    return true;
                }
            }
        });
    }


    
    $("#savebtn").click(function () {
        $("#avatar-modal").modal('hide');
    });

    $("#picEdit").click(function () {
        $(".logo-img").hide();
        $(".logo-up").show();
        $("#picEdit").hide();
        $("#picEditClose").show();

    });

    $("#picEditClose").click(function () {
        $(".logo-up").hide();
        $(".logo-img").show();
        $("#picEdit").show();
        $("#picEditClose").hide();


    });

    var m = 1;
    function countserviceName() {

        if (m == 10)
            return false;
        m = parseInt(m) + parseInt(1);
        $('#serviceName').val(m);
        $('#multiserviceName').append('<input type=text class=form-control name=testIncluded[] placeholder="Give Test Name" maxlength="30" id=hospitalServices_serviceName' + m + ' /> <br /> ');
    }
    
      function createCSV(){
         var mi = '';
         var helathpkg_cityId = '';
         mi = $('#mi').val();
         helathpkg_cityId = $('#helathpkg_cityId').val();
         $.ajax({
              url : urls + 'index.php/healthace/createCSV',
              type: 'POST',
             data: {'mi' : mi ,'helathpkg_cityId': helathpkg_cityId },
             success:function(datas){
                console.log(datas)
             }
          });
     } 
     
     
     //    // enable disable row
    function enableFn(ena_id, status_value)
    {
        if (status_value == 1)
            var con_mess = "Desabled";
        else
            con_mess = "Enabled";
        var url = "<?php echo site_url('healthcare/status'); ?>";
        var r = window.confirm('Do you want to ' + con_mess.toLowerCase() + ' it?') 
            if (r) {
                $.ajax({
                    type: 'post',
                    data: {'enable_id': ena_id, 'status': status_value, 'table': 'qyura_healthPackage', 'id_name': 'healthPackage_id'},
                    url: url,
                    success: function (data) {
                        if (typeof data.isAlive == 'undefined') {
                            if (data)
                            {
                                location.reload(true);
                            }
                        }
                        else
                        {
                            $('#headLogin').html(data.loginMod);
                        }
                    }
                });
            }
    
    }
    
    
 function deleteFn(id)
    {
        var url = "<?php echo site_url('quotation/delete'); ?>";
       var r =  window.confirm("Do you want to delete this Test?")
            if (r)
            {
                $.ajax({
                    type: 'post',
                    data: {'delete_id': id, 'table': 'qyura_quotationDetailTests', 'id_name': 'quotationDetailTests_id'},
                    url: url,
                    success: function (data) {
                        if (typeof data.isAlive == 'undefined') {
                            if (data)
                            {
                                location.reload(true);
                            }
                        }
                        else
                        {
                            $('#headLogin').html(data.loginMod);
                        }
                    }
                });
            }
       
    }
    
    function updateFn(id){
         if(id != ''){
             
              var check = /^[a-zA-Z\s]+$/;
              var alphacheck = /^[a-z0-9\_\s]+$/i;
        
              var url = "<?php echo site_url('quotation/updateTest'); ?>";
              var diagnosticType =  $("#diagnosticType"+id).val();
              var testName =  $.trim($("#testName"+id).val());
              var date = $(".date"+id).val()
              var time =  $(".time"+id).val();
              var instruction = $("textarea#instruction"+id).val();
              var price  =  $("#price"+id).val();
              
              if(!$.isNumeric(price)){
                    $('#price'+id).addClass('bdr-error');
                    $('#error-price'+id).fadeIn().delay(3000).fadeOut('slow');
                    return false;
              }
              
              if (!alphacheck.test(testName)) {
                    $('#testName'+id).addClass('bdr-error');
                    $('#error-testName'+id).fadeIn().delay(3000).fadeOut('slow');
                    return false;
              }
                
                $.ajax({
                    type: 'post',
                    data: {'diagnosticType': diagnosticType, 'testName': testName, 'date': date, 'time' : time, 'instruction' : instruction , 'price' : price, 'id' : id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'  },
                    url: url,
                    success: function (data) {
                        if (data == true) {
                            alert('succesfully updated');
                            location.reload(true);
                        }
                        else
                        {
                            alert('some error occured');
                             location.reload(true);
                        }
                    }
                });
                
            }
    }
    
    
          // by pawan
     /**
     * @project Qyura
     * @method sendQuotation
     * @description Send Quotation
     * @access public
     * @return array
     */
    
    
    function getMI(option) {
        var city_id = $("#appointment_city").val();
        var appointment_type = $("#centerType").val();
        
        var url = '<?php echo site_url(); ?>/quotation/getMI';
        if (typeof city_id == 'string' && typeof appointment_type == 'string'){
            $.ajax({
                url: url,
                async: false,
                type: 'POST',
                data: {'city_id': city_id, 'appointment_type': appointment_type},
                beforeSend: function (xhr) {
                    $("#mi_centre").addClass('loadinggif');
                },
                success: function (data) {
                    console.log(data);
                    $('#mi_centre').html(data);
                    $('#mi_centre').selectpicker('refresh');
                    $('#timeSlot').html('');
                    $('#timeSlot').selectpicker('refresh');
                    $('#input5').prop('selectedIndex','');
                    $('#input5').selectpicker('refresh');
                    $('#speciallity').prop('selectedIndex','');
                    $('#speciallity').selectpicker('refresh');
                    $("#mi_centre").removeClass('loadinggif');
                    $('#doctorSection').hide();
                    $('#diagnosticSection').hide();
                }
            });
        }
    }
    
    function getMIDoctorList(){
        var id = $('#mi_centre').val();
            id = id.split(',');
        var h_d_id = id[0];
        var MiId = id[1];
        var type = $("#centerType").val();
        //var url = '<?php echo site_url(); ?>/quotation/appoint_timeSlot';
        if (typeof h_d_id == 'string' && typeof type == 'string'){

              $.ajax({
                url: '<?php echo site_url(); ?>/quotation/gerMIdoctorList',
                async: false,
                type: 'POST',
                data: {'MiId': MiId,'type': type},
                success: function (data) {
                    $('#refDoctor').html(data);
                }
            });
        }
        
        
    }
    
    
         function addMoreTest(){
        var total_test = parseInt($("#total_test").val());
        var newTestValue = total_test + parseInt(1);
        
        $("#total_test").val(newTestValue);
        
        var htmlData = '<div id="diagnosticClon_'+newTestValue+'"><article class="form-group m-lr-0"><label for="cname" class="control-label col-md-4 col-sm-4 cl-black">Test-'+newTestValue+' :</label></article><article class="form-group m-lr-0"><label for="cname" class="control-label col-md-4 col-sm-4">Diagnostic Type :</label><div class="col-md-8 col-sm-8"><select class="selectpicker" data-width="100%" name="input28_'+newTestValue+'" id="input28_'+newTestValue+'" required="" ><option value="">Select Diagnostic</option></option></select><div class="has-error " id="err_input28_'+newTestValue+'" ></div></div></article><article class="form-group m-lr-0"><label for="cname" class="control-label col-md-4 col-sm-4">Test Name :</label><div class="col-md-8 col-sm-8"><input type="text" required="" class="form-control" name="input29_'+newTestValue+'" id="input29_'+newTestValue+'" ><div class="has-error " id="err_input29_'+newTestValue+'" ></div></div></article><article class="form-group m-lr-0"><label for="" class="control-label col-md-4 col-sm-4">Price :</label><div class="col-md-8 col-sm-8"><input class="form-control" required="" type="text" id="input30_'+newTestValue+'" name="input30_'+newTestValue+'" placeholder="770" onkeypress="return isNumberKey(event)"><div class="has-error " id="err_input30_'+newTestValue+'" ></div></div></article><article class="form-group m-lr-0"><label for="" class="control-label col-md-4 col-sm-4">Instruction :</label><div class="col-md-8 col-sm-8"><textarea class="form-control" id="input31_'+newTestValue+'" name="input31_'+newTestValue+'" placeholder="" required="" ></textarea><div class="has-error " id="err_input31_'+newTestValue+'" ></div></div></article><article class="form-group m-lr-0"><div class="col-md-3 col-sm-3 col-md-offset-0 col-sm-offset-0"><button id="remove_'+newTestValue+'" class="btn btn-danger btn-block waves-effect waves-light" type="button" href="javascript:void(0);" onclick="removeTest(\''+newTestValue+'\');" > Remove </button></div></article></div>';
        $("#diagnosticSectionTest").append(htmlData);
        if(total_test !== 1){
            $("#remove_"+total_test).hide();
        }
        $('.selectpicker').selectpicker({
            style: 'btn-default',
            size: "auto",
            width: "100%"
        });
        changeForm();
    }
    
    
    function changeForm(){

        var id = $('#mi_centre').val();
        id = id.split(',');
        var h_d_id = id[0];
        var type = $('#centerType').val();
        
        var total_test = $("#total_test").val();

        var url = '<?php echo site_url(); ?>/quotation/find_category';
        if (typeof h_d_id == 'string' && typeof type == 'string' ){
            
            $.ajax({
                url: url,
                async: false,
                type: 'POST',
                data: {'h_d_id': h_d_id,'type': type},
                beforeSend: function (xhr) {
                    $("#input28_"+total_test).addClass('loadinggif');
                },
                success: function (data) {
                    console.log(data);

                    $("#input28_"+total_test).html(data);
                    $("#input28_"+total_test).selectpicker('refresh');
                    $("#input28_"+total_test).removeClass('loadinggif');
                }
            });
        }
      
    }
    
    
   
    
     function removeTest(div_no){
        $("#diagnosticClon_"+div_no).slideUp(function(){ });
        
        var typeVal = parseInt(div_no) - parseInt(1);
        $("#total_test").val(typeVal);
        $("#remove_"+typeVal).show();
        $("#diagnosticClon_"+div_no).remove();
    }
    
    function findDoctor(){
        var id = $('#mi_centre').val();
            id = id.split(',');
        var h_d_id = id[1];
        var type = $('#centerType').val();
        var special_id = $('#speciallity').val();
        var url = '<?php echo site_url(); ?>/quotation/find_doctor';
        if (typeof h_d_id == 'string' && typeof type == 'string' && typeof special_id == 'string'){
            $.ajax({
                url: url,
                async: false,
                type: 'POST',
                data: {'h_d_id': h_d_id,'type': type,'special_id':special_id},
                beforeSend: function (xhr) {
                    $("#input12").addClass('loadinggif');
                },
                success: function (data) {
                    console.log(data);
                    $('#input12').html(data);
                    $('#input12').selectpicker('refresh');
                    $("#input12").removeClass('loadinggif');
                }
            });
        }
    }
    
      $(document).ready(function () {
          
        $("#setData").submit(function (event) {
            event.preventDefault();
            var url = '<?php echo site_url(); ?>/quotation/sendQuotationSave/';
            var formData = new FormData(this);
            submitData(url,formData);
        });

        $('#date-3').datepicker();
        
     });
     
     
       function getpatientdetails() {
        var patient_email = $("#patient_email").val();
        var users_mobile = $("#users_mobile").val();
        var url = '<?php echo site_url(); ?>/quotation/getpatient/';
        $.ajax({
            url: url,
            async: false,
            type: 'POST',
            data: {'patient_email': patient_email,'users_mobile':users_mobile},
            beforeSend: function (xhr) {
                $("#patient_email").addClass('loadinggif');
            },
            success: function (data) {
                if(data && data != 0){
                    var data = JSON.parse(data);
                    console.log(data.mobile);
                    $("#patient_email").removeClass('loadinggif');
                    $('#users_mobile').val(data.mobile);
                    $('#stateId').val(data.stateId);
                    $('#stateId').selectpicker('refresh');
                    fetchCity(data.stateId, data.cityId, 'cityId');
                    $('#cityId').val(data.cityId);
                    $('#users_username').val(data.patientName);
                    $('#address').val(data.address);
                    $('#unqId').val(data.unqId);
                    $("#p_unqId").show();
                    $("#familyDiv").show();
                    $('#zip').val(data.pin);
                    $('#user_id').val(data.user_id);
                }else{
                    $('#users_mobile').val('');
                    $('#cityId').html('');
                    $('#cityId').selectpicker('refresh');
                    $('#stateId').prop('selectedIndex','');
                    $('#stateId').selectpicker('refresh');
                    $('#users_username').val('');
                    $('#address').val('');
                    $('#unqId').val('');
                    $('#zip').val('');
                    $('#user_id').val('');
                    
                    $('#input33').html('');
                    $('#input33').selectpicker('refresh');
                    $('#familyDiv').hide();
                    $('#familyListDiv').hide();
                    $("#p_unqId").hide();
                }
            }
        });
    }
    
       function fetchCity(stateId, cityId, id) {
        $.ajax({
            url: '<?php echo site_url('quotation/fetchCity'); ?>',
            type: 'POST',
            data: {'stateId': stateId},
            beforeSend: function (xhr) {
                $("#cityId").addClass('loadinggif');
            },
            success: function (datas) {

                $('#cityId').html(datas);
                $("#cityId").removeClass('loadinggif');
                $('#cityId').selectpicker('refresh');
                $('#StateId').val(stateId);
                console.log(cityId);
                console.log(id);
                if (typeof cityId == "string") {
                    console.log(id);
                    $('#' + id).val(cityId);
                    $('#' + id).selectpicker('refresh');
                }
            }
        });

    }
    
    function getMember(obj){
        if(obj == 1){
            var user_id = $("#user_id").val();
            if(user_id != ''){
                var url = '<?php echo site_url(); ?>/quotation/getMember/';
                $.ajax({
                    url: url,
                    async: false,
                    type: 'POST',
                    data: {'user_id': user_id},
                    beforeSend: function (xhr) {
                        $("#input33").addClass('loadinggif');
                    },
                    success: function (data) {
                        $('#familyListDiv').show();
                        $("#input33").removeClass('loadinggif');
                        $('#input33').html(data);
                        $('#input33').selectpicker('refresh');
                    }
                });
            }
        }else{
            $('#familyListDiv').hide();
            $('#input33').html('');
            $('#input33').selectpicker('refresh');
        }
    }
    
        function calculateamount(){
        //var type = $('#input5').val();
        var amount = 0;
        var con_fee = parseInt($('#input22').val());
        var oth_fee = parseInt($('#input23').val());
     
        var tax = parseInt($('#input24').val());
        var amount = con_fee + oth_fee;
        
            var total_test = $("#total_test").val();
            var a;
            for(a=1;a<=total_test;a++){
                var test_amount = parseInt($("#input30_"+a).val());
                if(test_amount)
                amount = amount+test_amount;
            }
      
        var tax_amount = (amount/100)*tax;
        var total_amount = amount + tax_amount; 
       
        if(total_amount){
            $("#paidAmount").html(total_amount);
            $("#paidamt").val(total_amount);
        }else{
            $("#paidAmount").html('00');
        }
    }
    
//     $(document).ready(function () {
//         
//        $("#QuotationForm").on('submit',function(){
//               //var formData = $("#QuotationForm").serialize();
//               var formData = new FormData(this);
//               
//               $.ajax({
//                url: '<?php //echo site_url('quotation/sendQuotationSave'); ?>',
//                type: 'POST',
//                data: formData,
//                success: function (response) {
//                    alert(response);
//                }
//            });
//       });
//    });

//  $(document).ready(function () {
//        $("#QuotationForm").submit(function (event) {
//            event.preventDefault();
//            var url = '<?php echo site_url(); ?>/quotation/sendQuotationSave/';
//            var formData = new FormData(this);
//            //submitData(url,formData);
//        });
//    });
    
   function isNumberKey(evt, id) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        $("#" + id).html("Please enter number key");
        return false;
    } else {
        $("#" + id).html('');
        return true;
    }
   }
 
      $("#appointment_city").select2({
             allowClear: true,
             placeholder: "Select a city"
      });
       $("#mi_centre").select2({
             allowClear: true,
             placeholder: "Select a MI"
      });
</script>   
<script>
//Submit Data for add and edit for all
//    function submitData(url,formData){
//        var formData = formData;
//        $.ajax({
//            type: "POST",
//            url: url,
//            data: formData, //only input
//            processData: false,
//            contentType: false,
//            success: function (response, textStatus, jqXHR) {
//                try {
//                    var data = $.parseJSON(response);
//                    alert(data.message);
//                    if (data.status == 0)
//                    {
//                        
//                    }else {
//                     
//                    }
//                } catch (e) {
//                  
//                }
//            }
//        });
//    }
    
     $(document).ready(function() {

            	$("#QuotationForm").validate({
			rules: {
				city_id:{
                                    required: true
                                },
                                zip:{
                                  required: true,
                                  number: true,
                                  maxlength:6,
                                  minlength:6
                                },
                                miType:{
                                    required: true
                                },
                                miId:{
                                    required: true
                                },
                                timeslot:{
                                    required: true
                                },
                                quotationType:{
                                    required: true
                                },
                                quotationDate:{
                                    required: true
                                },
                                quotationTime:{
                                    required: true
                                },
                                bookStatus :{
                                    required: true
                                },
                                input28_1:{
                                    required: true,
                              
                                },
                                input29_1:{
                                    required: true
                                },
                                input30_1:{
                                    required: true,
                                    number:true
                                  
                                },	
                                input31_1:{
                                    required: true,
                                
                                },
                                patient_email:{
                                   required: true,
                                   email:true
                                },
                                users_mobile:{
                                    required: true,
                                    number:true,
                                    maxlength:12,
                                    minlength:10
                                
                                },
                                users_username:{
                                    required: true,
                                
                                },
                                countryId:{
                                    required: true,
                                
                                },
                                userStateId:{
                                    required: true,
                                
                                },
                                userCityId:{
                                    required: true,
                                
                                },
                                address:{
                                    required: true,
                                
                                },
                                family_member:{
                                    required: true,
                                
                                },
                                consulationFee:{
                                    required: true,
                                    number: true
                                
                                },
                                tax:{
                                    required: true,
                                    number: true
                                
                                },
                                 existsDr:{
                                    required:true
                                },
                                refDoctor:{
                                    required: true
                                },
                                drName:{
                                    required:true
                                }
			},
			messages: {
				
                                tax: {
					required: "Please enter tax only number formate",
				},
                                 consulationFee: {
					required: "Please enter consulation fee",
                                        number: "Please enter only number formate",
				},
				family_member: {
					required: "Please select family member",
				},
                                address: {
					required: "Please enter address",
				},
                                userCityId: {
					required: "Please select city",
				},
                                 userStateId: {
					required: "Please select state",
				},
                                countryId:{
                                        required: "Please select country",
                                },
                                users_username:{
                                        required: "Please enter user full name",
                                },
                                users_mobile:{
                                        required: "Please enter user mobile number",
                                        number: "Please enter only number formate",
                                },
                                patient_email:{
                                        required: "Please enter user email",
                                        email: "Please enter a valid email address"
                                },
                                bookStatus:{
                                        required: "Please select book status",
                                },
                                input31_1:{
                                    required: "Please enter instruction",
                                },
                                input30_1:{
                                    required: "Please enter prize",
                                    number: "Please enter only number formate",
                                },
                                input29_1:{
                                    required: "Please enter test name",
                                },
                                input28_1:{
                                    required: "Please select diagnostic type category",
                                },
                                quotationTime:{
                                    required: "Please enter time",
                                },
                                quotationDate:{
                                    required: "Please enter date",
                                },
                                 miType:{
                                    required: "Please select MI type",
                                },
                                 miId:{
                                    required: "Please select MI name",
                                },
                                 timeslot:{
                                    required: "Please select time slot",
                                },
                                 city_id:{
                                    required: "Please select city",
                                },
                                zip:{
                                    required: "Please enter zip",
                                    number: "Please enter only number formate",
                                },
                                existsDr:{
                                    required:"Please select Ref. Doctor"
                                },
                                refDoctor:{
                                    required:"Please select Ref. Doctor"
                                },
                                drName:{
                                    required:"Please enter Ref. Doctor name"
                                }
			},
                        
                        submitHandler: function(form) {
                            $(form).submit();
                      }
		});
    });
    
    
   function showExistsBox(extVal){
        
        if(extVal == 1){
           $(".drList").show();
           $(".drText").hide();
        }else if(extVal == 2){
            $(".drList").hide();
           $(".drText").show();
        }
        
    }
    
    
    
    
    
    // datatable by anish for quotation history
    
$(document).ready(function () {   
    var oTableQuoHis = $('#quotationHistoryTable').DataTable({
            "processing": true,
            "serverSide": true,
            "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false
                }],
            "pageLength": 10,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "dom": '<<t><"clearfix m-t-20 p-b-20" p>',
            "iDisplayStart ": 20,
            "columns": [
                {"data": "miName"},
                {"data": "uniqueId"},
                {"data": "pName"},
                {"data": "amount"},
                {"data": "dt"},
                {"data": "qStatus"},
                {"data": "convertStatus"},
                {"data": "action"},
            ],
            "ajax": {
                "url": "<?php echo site_url('quotation/getQuotationHistoryDl'); ?>",
                "type": "POST",
                "async": false,
                "data": function (d) {
                    d.fromDate = $("#fromDate").val();
                    d.toDate = $("#toDate").val();
                    d.srch = $("#search").val();
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
                },
                 beforeSend: function () {
                   // setting a timeout
                    $('#load_consulting').show();
                },
                complete: function ()
                {
                   $('#load_consulting').hide('200');
                },
            }
        });
         $('#search').on('keyup', function() {
                        oTableQuoHis.draw();
         } );
         
        $('.pickDate').datepicker().on('changeDate', function (ev) {
                $('.pickDate').datepicker('hide');
                     var sDate = $('#fromDate').val();
                     var eDate = $('#toDate').val(); 
                     var d1 = new Date($('#fromDate').val());
                     var d2 = new Date($('#toDate').val());
                     if(sDate != '' && eDate != ''){
                     if(d1.getTime() > d2.getTime()){
                        $("#date_error").html("<p>From date should be less then To date.</p>");
                        $('#fromDate').val("");
                        $('#toDate').val("");
                     }else{
                          oTableQuoHis.draw();
                          return false;
                         $("#date_error").html(""); 
                     }
                    } 
            });
    });
    
//new function
function rejectQuotation(id){
    var url = "<?php echo site_url('quotation/rejectQuotation'); ?>";
       var r =  window.confirm("Do you really want to reject this quotation?")
            if (r)
            {
                $.ajax({
                    type: 'post',
                    data: {'bookId': id, 'table': 'qyura_quotationBooking', 'id_name': 'quotationBooking_id'},
                    url: url,
                    success: function (data) {
                        if (typeof data.isAlive == 'undefined') {
                            if (data)
                            {
                                location.reload(true);
                            }
                        }
                        else
                        {
                            alert('something wrong! pls try again');
                        }
                    }
                });
            }
}
 
</script>   