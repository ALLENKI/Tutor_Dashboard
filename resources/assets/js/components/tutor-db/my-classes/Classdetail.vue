<template>

    <div>
        <div class="row">
            <div class="col-lg-8">
				<div class="m-portlet m-portlet--mobile">


   
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h3 class="m-portlet__head-text">

									Course Information
								</h3>
							</div>
						</div>		
								    
					    <div class="m-portlet__head-tools">
							<ul class="m-portlet__nav">
								<li class="m-portlet__nav-item">
									<a href="#" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
										Change Topic
									</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="m-portlet__body">
						<div class="m-widget13">
						    <div class="m-widget13__item">
								<span class="m-widget13__text m-widget13__item-bolder">
									{{classDet.topic_name}}
								</span>
							</div>
                                   
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
													
												 
									<li><span style="font-weight:bold">Sub Category:</span> {{classDet.topic.subCategory}} </li>
								</span>	     												
							</div>

							<div class="m-widget13__item">

								<span class="m-widget13__desc m-widget13__item-bolder">

									<li><span style="font-weight:bold">Subject:</span> 
										{{classDet.topic.subject}}
									</li>
								</span>																							 											
							</div>



							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									<li><span style="font-weight:bold">Tags:</span></li>
								</span>				
							</div>
						</div>
					</div>


							
				</div>
				<div class="m-portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Class Timings
												</h3>
							</div>
						</div>		
					</div>
					<div class="m-portlet__body">
						<div class="m-section">
							<div class="m-section__content">
								<table class="table table-bordered m-table">
									<thead>
														<th>Units</th>
														<th>Timings</th>
														<th>Classroom</th>
									</thead>
									<tbody style="font-weight:200;font-size:14px">
			                                        	<tr v-for="item in classDet.timings">
															<td>{{item.unit}}</td>
															<td>{{item.date}}/{{item.start_time}}/{{item.end_time}}</td>
															<td>{{item.classroom}}</td>						
														</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>	
            <div class="col-lg-4">
                                <div class="m-portlet m-portlet--tab">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
												<h3 class="m-portlet__head-text">
													Location Information
												</h3>
											</div>
										</div>
									</div>
									<div class="m-portlet__body" style="height:700px">
									    <div class="m-widget13">
										    <div style="height:300px;">

										        <div class="m-widget13__text">


												<span class="m-widget13__desc">

												

													  {{classDet.locationf.name}}													
												</span>												
											    </div>
											    <br />
											    <div style="height:100%;overflow:hidden;display:block;background: url(http://maps.googleapis.com/maps/api/staticmap?center=48.858271,2.294264&size=640x300&zoom=5&key=AIzaSyBMlTEcPR5QULmk9QUaS7lwUK7qtabunEI) no-repeat 50% 50%;">
												<img src="http://maps.googleapis.com/maps/api/staticmap?center=48.858271,2.294264&size=640x300&zoom=16&key=AIzaSyBMlTEcPR5QULmk9QUaS7lwUK7qtabunEI" style="" alt=""/>
											    </div><br />
											    <div class="m-widget13__item">
												<span class="m-widget13__desc">

													<li><span style="font-weight:bold">Street:</span>  {{classDet.locationf.street_address}}</li>

											

												</span>
												
											    </div>
											    <div class="m-widget13__item">
												<span class="m-widget13__desc">
													<li><span style="font-weight:bold">Landmark:</span> {{classDet.locationf.landmark}}</li>
												</span>
												
											    </div>
											    <div class="m-widget13__item">
												<span class="m-widget13__desc">
													<li><span style="font-weight:bold">Locality:</span> {{classDet.locationf.locality.slug}}</li>
												</span>
												
											    </div>	
											     <div class="m-widget13__item">
												<span class="m-widget13__desc">
													<li><span style="font-weight:bold">City:</span> {{classDet.locationf.city.slug}}</li>
												</span>
												
											    </div>	
											    <div class="m-widget13__item">
												<span class="m-widget13__desc m-widget13__text-bolder">
													<li><span style="font-weight:bold">Pincode:</span> {{classDet.locationf.pincode}}</li>
												</span>
												
											    </div>	

										    </div>
									    </div>
								    </div>	

                                </div>
				            </div>

                        


                            <div class="col-lg-11">

                                <div class="m-portlet">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Uploaded Files
												</h3>
											</div>
										</div>		
								    </div>
									<div class="m-portlet__body">
										<div class="m-widget13">
										    <div class="m-widget13__item">
												
											</div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                            

                            <div class="col-lg-11">
                                <div class="m-portlet">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Class Chat
												</h3>
											</div>
										</div>		
								    </div>
									<div class="m-portlet__body">


										<div class="m-widget13">
										    <div class="m-widget13__item">
												<label for="exampleTextarea">
													Enter Your Message
												</label>
												<textarea class="form-control m-input" id="exampleTextarea" rows="5"></textarea>
												<br />
                                                
                                              
												<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
													
													<div class="m-demo__preview m-demo__preview--btn">


														
															<!--<router-link :to="{path: '/my-classes/uploadfile' }" exact class="m-menu__item">
															<div class="btn btn-primary">Choose File</div>
															</router-link>-->												
														
														<Uploadfile></Uploadfile>

														
														
													<!--	<a href="#" class="btn btn-secondary m-btn m-btn--icon">
															<span>
																<i class="la la-archive"></i>
																<span>
																	Send File
																</span>
															</span>														
														</a> 
														
														<a href="#" class="btn btn-secondary m-btn m-btn--icon">
															<span>
																<i class="la la-archive"></i>
																<span>
																	Send
																</span>
															</span>														
														</a> -->
														
													</div>
												</div>

												
											</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                             </div>                              
                             	
                    <Notes :ahamclass="classDet"></Notes>					

						</div>
                     </div>
		        </div>
			</div>
	    </div>
	</div>													
</template>

<script>
import Uploadfile from './Uploadfile';
import Notes from './Notes';
export default {
  props:['classDet'],
  components:{
  	Uploadfile,
  	Notes
  },
}
</script>

<style>
</style>


 
    
       


