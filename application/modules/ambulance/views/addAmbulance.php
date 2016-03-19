<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">


            <div class="clearfix">
                <div class="col-md-12 text-success">
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
                <div class="col-md-12">
                    <h3 class="pull-left page-title">Add New Ambulance</h3>

                </div>
            </div>
            <div class="map_canvas"></div>
            <form class="cmxform form-horizontal tasi-form avatar-form" id="submitForm" name="submitForm" method="post" action="<?php echo site_url(); ?>/ambulance/SaveAmbulance" novalidate="novalidate" enctype="multipart/form-data" >
                <input type="hidden" id="StateId" name="StateId" value="" />

                <!-- Left Section Start -->
                <section class="col-md-12 detailbox">
                    <div class="bg-white mi-form-section">
                        <article class="clearfix">
                            <aside class="col-md-8">
                                <div class="clearfix m-t-20 p-b-20">
                                    <article class="clearfix m-t-10">
                                        <label for="cemail" class="control-label col-md-4 col-sm-4">Ambulance Name :</label>
                                        <div class="col-md-8 col-sm-8">
                                            <input class="form-control" id="ambulance_name" value="<?php echo set_value('ambulance_name'); ?>" name="ambulance_name" type="text" required="">
                                            <label class="error" style="display:none;" id="error-ambulance_name"> please enter ambulance name</label>
                                            <label class="error" > <?php echo form_error("ambulance_name"); ?></label>
                                        </div>
                                    </article>

                                    <article class="clearfix m-t-10">
                                        <label for="cname" class="control-label col-md-4 col-sm-4">Registered Email Id:</label>
                                        <div class="col-md-8 col-sm-8">
                                            <input type="email" class="form-control" id="users_email" name="users_email" placeholder="abc@gmail.com" onblur="checkEmailFormat()" value="<?php echo set_value('users_email'); ?>" />
                                            <label class="error" style="display:none;" id="error-users_email"> please enter Email id Properly</label>
                                            <label class="error" style="display:none;" id="error-users_email_check"> Email Already Exits!</label>
                                            <input type="hidden" class="form-control" id="users_email_status" name="users_email_status" value="" />
                                            <label class="error" > <?php echo form_error("users_email"); ?></label>
                                        </div>
                                    </article>

                                    <article class="clearfix m-t-10">
                                        <label for="cname" class="control-label col-md-4  col-sm-4">Mobile No. :</label>
                                        <div class="col-md-8 col-sm-8">
                                            <aside class="row clone">
                                                <!--<div class="col-lg-3 col-md-4 col-sm-3 col-sm-4 col-xs-12 m-t-xs-10" id="multiPreNumber">
                                                    <select class="selectpicker" data-width="100%" name="pre_number[]" id="multiPreNumber">
                                                        <option value ='91'>+91</option>
                                                        <option value ='1'>+1</option>
                                                    </select>
                                                </div> -->
                                                <div class="col-lg-7 col-md-6 col-sm-7 col-xs-10 m-t-xs-10" >
                                                    <input type="text" class="form-control" name="users_mobile" id="users_mobile" placeholder="9837000123" maxlength="10" value="<?php echo set_value('users_mobile'); ?>" onkeypress="return isNumberKey(event)"/>
                                                    <label class="error" style="display:none;" id="error-users_mobile"> please enter a valid mobile number</label>
                                                    <label class="error" > <?php echo form_error("users_mobile"); ?></label>
                                                </div>
                                            </aside>

                                        </div>
                                    </article>



                                    <article class="clearfix m-t-10">
                                        <label for="cname" class="control-label col-md-4 col-sm-4">Ambulance Type :</label>
                                        <div class="col-md-8 col-sm-8">
                                            <select class="selectpicker form-control" data-width="100%" name="ambulanceType">
                                                <option <?php echo set_select('ambulanceType', '1', TRUE); ?> value='1'>Trauma Medicines</option>
                                                <option <?php echo set_select('ambulanceType', '2', TRUE); ?> value='2'>General Medicines</option>
                                            </select>
                                        </div>
                                    </article>
                                    <article class="clearfix m-t-10 avatar-view">
                                        <label class="control-label col-md-4 col-sm-4" for="cemail">Upload Logo :</label>
                                        <div class="col-md-8 col-sm-8 text-right">
                                            <label for="file-input"><i style="border:1px solid #777777; padding:10px;" class="fa fa-cloud-upload fa-3x avatar-view"></i></label>
                                            <input type="file" style="display:none;" class="no-display " id="file-input11" name="bloodBank_photo">
<!--                                                   <input type="file" style="display:none;" class="no-display" id="file-input" name="ambulance_img">-->     

                                            <img src="<?php echo base_url() . 'assets/default-images/ambulance_logo.png' ?>" width="70" height="65" class="image-preview-show"/>
                                            <label class="error" > <?php echo form_error("ambulance_img"); ?></label>
                                            <label class="error" > <?php echo $this->session->flashdata('valid_upload'); ?></label>
                                        </div>
                                    </article>


                                    <article class="clearfix m-t-10">
                                        <label for="cname" class="control-label col-md-4 col-sm-4">Address:</label>
                                        <div class="col-md-8 col-sm-8">
                                            <select class="selectpicker form-control" data-width="100%" name="ambulance_countryId" id="ambulance_countryId">
                                                <option <?php echo set_select('ambulance_countryId', '', TRUE); ?> value=''>Select Country</option>
                                                <option <?php echo set_select('ambulance_countryId', '1'); ?> value="1">INDIA</option>

                                            </select>
                                            <label class="error" style="display:none;" id="error-ambulance_countryId"> please select a country</label>
                                            <label class="error" > <?php echo form_error("ambulance_countryId"); ?></label>
                                        </div>
                                    </article>
                                    <article class="clearfix">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <select class="selectpicker form-control" data-width="100%" name="ambulance_stateId" Id="ambulance_stateId" data-size="4" onchange ="fetchCity(this.value)">
                                                <option value="">Select State</option>
                                                <?php foreach ($allStates as $key => $val) { ?>
                                                    <option value="<?php echo $val->state_id; ?>"><?php echo $val->state_statename; ?></option>
                                                <?php } ?>
                                            </select>
                                            <label class="error" style="display:none;" id="error-ambulance_stateId"> please select a state</label>
                                            <label class="error" > <?php echo form_error("ambulance_stateId"); ?></label>
                                        </div>
                                    </article>

                                    <article class="clearfix">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <select class="form-control selectpicker" data-width="100%" name="ambulance_cityId" id="ambulance_cityId" data-size="4">
                                                <!--<option>Select City</option>
                                                <option>Kolkata</option>
                                                <option>Delhi</option>-->
                                            </select>
                                            <label class="error" style="display:none;" id="error-ambulance_cityId"> please select a city</label>
                                            <label class="error" > <?php echo form_error("ambulance_cityId"); ?></label>
                                        </div>
                                    </article>
                                    <article class="clearfix m-t-10">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="text" class="form-control" id="ambulance_zip" name="ambulance_zip" placeholder="700001" maxlength="6" value="<?php echo set_value('ambulance_zip'); ?>"  onkeypress="return isNumberKey(event)"/>
                                            <label class="error" style="display:none;" id="error-ambulance_zip"> please enter a zip code</label>   
                                            <label class="error" > <?php echo form_error("ambulance_zip"); ?></label>
                                        </div>
                                    </article>


                                    <article class="clearfix m-t-10">
                                        <label class="control-label col-md-4" for="cname">Manual</label>
                                        <div class="col-md-8">
                                            <aside class="radio radio-info radio-inline">
                                                <input <?php echo set_radio('isManual', '1'); ?> type="radio"  name="isManual" value="1" id="isManual" onclick="IsAdrManual(this.value)">
                                                <label for="inlineRadio1"> Yes</label>
                                            </aside>
                                            <aside class="radio radio-info radio-inline">
                                                <input <?php echo set_radio('isManual', '0'); ?> type="radio" checked="" name="isManual" value="0" id="isManual" onclick="IsAdrManual(this.value)">
                                                <label for="inlineRadio2"> No</label>
                                            </aside>
                                            <label class="error" > <?php echo form_error("isManual"); ?></label> 
                                        </div>
                                    </article>

                                    <article class="clearfix m-t-10">

                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="text" class="form-control" value="<?php echo set_value('ambulance_address'); ?>" name="ambulance_address" id="geocomplete" placeholder="209, ABC Road, near XYZ Building " />
                                            <label class="error" style="display:none;" id="error-ambulance_address"> please enter an address</label>
                                            <label class="error" > <?php echo form_error("ambulance_address"); ?></label> 
                                        </div>
                                    </article>
                                    <article class="clearfix m-t-10">
                                        <label class="control-label col-sm-4" for="cname">Latitude & Longitude</label>
                                        <div class="col-sm-8">
                                            <aside class="row m-b-10">
                                                <div class="col-sm-6">
                                                    <input class="form-control" name="lat" required="" type="text" value="<?php echo set_value('lat'); ?>"  id="lat" readonly="" placeholder="Latitude" />
                                                    <label class="error" style="display:none;" id="error-lat">Please enter the correct format for latitude</label>
                                                    <label class="error" > <?php echo form_error("lat"); ?></label>
                                                </div>
                                                <div class="col-sm-6 m-t-xs-10">
                                                    <input name="lng" class="form-control" required="" type="text" value="<?php echo set_value('lng'); ?>"  id="lng" readonly="" placeholder="Longitude"/>
                                                    <label class="error" style="display:none;" id="error-lng"> Please enter the correct format for longitude</label>
                                                    <label class="error" > <?php echo form_error("lng"); ?></label>
                                                </div>
                                            </aside>
                                        </div>
                                    </article>


                                    <article class="clearfix m-t-10">
                                        <label for="cname" class="control-label col-md-4  col-sm-4">Phone:</label>
                                        <div class="col-md-8 col-sm-8">
                                           <!-- <a href="javascript:void(0)" onclick="countPhoneNumber()" class="add pull-right" rel=".clone"><i class="fa fa-plus-circle fa-2x m-t-5 label-plus"></i></a>-->
                                            <a href="javascript:void(0)" class="add pull-right" rel=".clone"><i class="fa fa-plus-circle fa-2x m-t-5 label-plus"></i></a>
                                            <aside class="row clone">
                                                <div class="col-lg-3 col-md-4 col-sm-3 col-sm-4 col-xs-12 m-t-xs-10" id="multiPreNumber">
                                                    <select class="selectpicker" data-width="100%" name="pre_number[]" id="multiPreNumber">
                                                        <option value ='91'>+91</option>
                                                        <option value ='1'>+1</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-7 col-md-6 col-sm-7 col-xs-10 m-t-xs-10" id="multiPhoneNumber">
                                                    <input type="text" class="form-control" name="ambulance_phn[]" id="ambulance_phn1" placeholder="9837000123" maxlength="10"  onkeypress="return isNumberKey(event)"/>
                                                    <label class="error" style="display:none;" id="error-ambulance_phn1"> please enter a valid phone number</label>
                                                    <label class="error" > <?php echo form_error("ambulance_phn"); ?></label>
                                                </div>
                                            </aside>
                                            <p class="m-t-0">* If it is landline, include Std code with number </p>
                                        </div>
                                    </article>



                                    <article class="form-group m-lr-0 ">
                                        <label for="cemail" class="control-label col-md-4 col-sm-4">Contact Person :</label>
                                        <div class="col-md-8 col-sm-8">
                                            <input class="form-control" name="ambulance_cntPrsn" value="<?php echo set_value('ambulance_cntPrsn'); ?>" type="text" required="" id="ambulance_cntPrsn">
                                            <label class="error" style="display:none;" id="error-ambulance_cntPrsn">please enter name properly!</label>
                                            <label class="error" > <?php echo form_error("ambulance_cntPrsn"); ?></label>
                                        </div>
                                    </article>

                                    <article class="form-group m-lr-0">
                                        <label for="cname" class="control-label col-md-4 col-sm-4">Membership Type :</label>
                                        <div class="col-md-8 col-sm-8">
                                            <select class="selectpicker" data-width="100%" name="ambulance_mmbrTyp" id="ambulance_mmbrTyp">
                                                <option <?php echo set_select('ambulance_mmbrTyp', '1', TRUE); ?> value="1">Life Time</option>
                                                <option <?php echo set_select('ambulance_mmbrTyp', '2'); ?> value="2">Health Club</option>
                                            </select>
                                            <label class="error" style="display:none;" id="error-ambulance_mmbrTyp">please enter only charcters!</label>
                                            <label class="error" > <?php echo form_error("ambulance_mmbrTyp"); ?></label>
                                        </div>
                                    </article>
                                    <article class="form-group m-lr-0">
                                        <label for="cname" class="control-label col-md-4">24/7 Services ? </label>
                                        <div class="col-md-8">
                                            <aside class="radio radio-info radio-inline">
                                                <input <?php echo set_radio('isEmergency', '1'); ?> type="radio" id="isEmergency_yes" value="1" name="isEmergency" checked>
                                                <label for="inlineRadio1"> Yes</label>
                                            </aside>
                                            <aside class="radio radio-info radio-inline">
                                                <input <?php echo set_radio('isEmergency', '0'); ?> type="radio" id="isEmergency_no" value="0" name="isEmergency">
                                                <label for="inlineRadio2"> No</label>
                                            </aside>
                                        </div>
                                    </article>

                            </aside>
                        </article>
                    </div>
                    <!-- .form -->


                </section>
                <!-- Left Section End -->


                <section class="clearfix ">
                    <div class="col-md-12 m-t-20 m-b-20">
                        
                        <input type="hidden" value="1" name="isValid" value="<?php echo set_value('isValid'); ?>" id="isValid">
                            <input type="hidden" value="<?php echo set_value('userId'); ?>" name="userId" id="userId">
                            <label class="error" style="display:none;" id="error-userexist">Ambulance already exist!</label>
                        
                        <button class="btn btn-danger waves-effect pull-right" type="button">Reset</button>
                        <div>
                            <input class="btn btn-success waves-effect waves-light pull-right m-r-20" type="submit" onclick="return validationAmbulance()" value="Submit" />
                        </div>
                    </div>

                </section>

                <fieldset>

                    <input name="lat" type="hidden" value="22.725473" />

                    <!-- <label>Longitude</label> -->
                    <input name="lng" type="hidden" value="75.893852" />

                </fieldset>
                <div id="upload_modal_form">
                    <?php $this->load->view('upload_crop_modal'); ?>
                </div>
            </form>


            <!-- consultation -->

        </div>

        <!-- container -->
    </div>

    <!-- content -->

