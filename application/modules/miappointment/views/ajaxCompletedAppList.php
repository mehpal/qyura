


                        <!-- Table Section Start -->
                        <article class="clearfix m-top-40 p-b-20">
                            <aside class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr class="border-a-dull">
                                            <th>Appt Id</th>
                                            <th>MI Name</th>
                                            <th>Patient Name</th>
                                            <th>Phone</th>
                                            <th>Email Id</th>
                                            <th>Action</th>
                                        </tr>
                                         <?php  if(isset($reports) && !empty($reports)){
                               foreach($reports as $key=>$val){ ?>
                                        <tr>
                                            <td>
                                                <h6><?php echo  isset($val['orderId'])?$val['orderId']:'' ?></h6>
                                            </td>
                                            <td>
                                                <h6><?php echo  isset($val['miName'])?$val['miName']:'' ?></h6>
                                                <p><?php echo  isset($val['city_name'])?$val['city_name']:'' ?></p>
                                            </td>
                                            <td>
                                                <h6><?php echo  isset($val['userName'])?$val['userName']:'' ?></h6>
                                                <p><?php echo  isset($val['userGender'])?$val['userGender']:'' ?> | <?php echo  isset($val['userAge'])?$val['userAge']:'' ?></p>
                                            </td>
                                            <td>
                                                <h6><?php echo  isset($val['usersMobile'])?$val['usersMobile']:'' ?></h6>
                                            </td>
                                            <td>
                                                <h6><?php echo  isset($val['email'])?$val['email']:'' ?></h6>
                                            </td>
                                            <td>
                                                
                                                <a href="<?php echo detailRouter($val['type'],$val['id'],$val['orderId']) ?>" class="btn btn-warning waves-effect waves-light m-b-5" type="button">View</a>
                                            </td>
                                        </tr>
                               <?php }
                                         }?>

                                    </tbody>
                                </table>
                            </aside>

                        </article>
                        <!-- Table Section End -->
                        <?php
                        
                        ?>
                        <article class="clearfix m-t-20 p-b-20">
                            <ul class="list-inline list-unstyled pull-right call-pagination">
                               <?php echo $this->ajax_pagination->create_links(); ?>
                            </ul>
                            
                           
                            
                        </article>
                    
