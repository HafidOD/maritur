@extends('layouts.front')

@section('content')
	<section class="gg-intback">
	  <div class="gg-datadetail container">
	    <h1>{{$hotel->name}}</h1>
	    	<div id="gg-address">
				<span class="gg-svg-image">
		            <svg class="gg-svg-icon gg-location-icon">
		                <use xlink:href="#location"></use>
		            </svg>
	        	</span>
	        	<span>Direccion hotel</span>
	        </div>
	  </div>
	</section>
		
	<section class="gg-intback">
		<div id="detail-container" class="container">
			<div class="tabs">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#galleryHotel" data-toggle="tab"> <?= echo ("hotel.gallery");?></a></li>
				</ul>
			</div>
				

			<div id="top-navs" class="tab-content">
				<div class="tab-pane fade in active row" id="galleryHotel">

					<div class="col-xs-12 col-md-8 gg-main-img">
						<img src="#" alt="<?=$hotel->path;?>" title="<?=$hotel->path;?>" width="700" height="500">
					</div>

					<div class="col-xs-12 col-md-4 row">
						<div class="col-md-12" id="gg-map"></div>
							<?php if(false): ?>
								<?php foreach($galeria as $i => $imagen): ?>
								<div class="gg-img-gallery col-xs-4 col-sm-2 col-md-6">
									<?php 
									$pathGalleryImg = $_SERVER['assets'].'images/hotels/'.$dataHotel->ruta_imagenes.'/'.$imagen->nombre.'.jpg';
									if(empty($imagen->nombre) || @getimagesize($pathGalleryImg) == false)
										$pathGalleryImg = $_SERVER['assets'].'images/no-image.jpg';
									?>
									<img src="<?php echo $pathGalleryImg; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500" />
								</div>
								<?php if($i >= 5){
									break;
								} ?>
								<?php endforeach; ?>
							<?php else: ?>
								<?php for($i=0; $i<=5; $i++): ?>
									<div class="gg-img-gallery col-xs-4 col-sm-2 col-md-6">
										<img src="http://placehold.it/700x500?text=Hotel" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500">
									</div>
								<?php endfor; ?>
							<?php endif; ?>
					</div>
				</div>
			</div>



	   		<div class="tabs">
				<ul class="nav nav-tabs">
					<li><a><?php echo ("hotel.roomrates");?></a></li>
				</ul>
	   		</div>

	   		<div class="book-container">
				<!-- <div>Viajando el </div> -->
				<div><? $this->booking->show('hotels'); ?></div>
			</div>

	   		<div>
	   			<?php if(!empty($rooms)){ ?>
					<?php foreach($rooms as $room): ?>
					<?php if($hotel->tipo_contrato == 1): ?>
					<article class="gg-itemlist row">
						<div class="gg-thumb col-xs-12 col-sm-5 col-md-3 ">
							<?php 
							$pathRoom = $_SERVER['assets'].'images/hotels/'.$dataHotel->ruta_imagenes.'/'.$room['imagen'].'.jpg';
							if(empty($room['imagen']) || @getimagesize($pathRoom) == false)
								$pathRoom = $_SERVER['assets'].'images/no-image.jpg';
							?>
							<img src="<?php echo $pathRoom; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500">
	                  	</div>

	                  	<div class="gg-inforoom col-xs-12 col-sm-7 col-md-9">
	                  		<div class="gg-room-name"><h2><? echo $room['nombre']; ?></h2></div>
	                  		<div class="gg-room-plan row">
	                  		<?php if(!empty($room['planes'])){ ?>
						       	<?php foreach($room['planes'] as $plan): ?>
						       		<div class="gg-info col-xs-12 col-sm-6 col-md-8">
										<div class="plan-nombre">
											<div><?php echo $plan['nombre']; ?></div>
											<?php if(isset($plan['promos']) && !empty($plan['promos'])){ ?>
												<?php foreach($plan['promos'] as $promo): ?>
													<div class="promo">
														<span class="promo-nombre gg-descpromo"><?php //echo echo ('global.promotion'); ?><?php echo $promo->descripcion; //$promo->nombre; ?></span>
														<!-- <div class="promo-descripcion"><?php //echo $promo->descripcion; ?></div> -->
													</div>
												<?php endforeach; ?>
											<?php } ?>
								    	</div>
																
										<!-- <?php if(!empty($plan['tarifas'])){ ?>	
											<?php if(isset($plan['tarifas']['dias'])){ ?>
												<div class="dias">
													<span class="dias-detalle"><?php echo ('hotel.details');?> </span>
													<div class="dias-cotizados">
														<table>
															<tr>
															<?php $days = 1; ?>
															<?php foreach($plan['tarifas']['dias'] as $dia): ?>
																<?php if($days == 4){
																	echo '</tr><tr>';
																	$days = 1;
																} ?>
																<td>
																	<div class="hab-dia">
																		<div class="dia-nombre"><?php echo date_format(date_create($dia['fecha']), 'D j'); ?></div>
																		<div class="dia-tarifa">
																			<?php if($dia['cierre'] == FALSE){ ?>
																				<?php if($dia['total'] != $dia['total_desc']){ ?>
																					<div style="text-decoration:line-through;"><?php echo '$'.number_format($dia['total'], 2, '.', ',');?>  <?php echo $this->config->item('currency');?></div>
																					<div><?php echo '$'.number_format($dia['total_desc'], 2, '.', ',');?>  <?php echo $this->config->item('currency');?></div>
																				<?php }else{ ?>
																					<div>&nbsp;</div>
																					<div><?php echo '$'.number_format($dia['total'], 2, '.', ',');?>  <?php echo $this->config->item('currency');?></div>
																				<?php } ?>
																			<?php }else{ ?>
																				<?php echo ("hotel.na");?>
																			<?php } ?>
																		</div>
																	</div>
																</td>
																<?php $days++; ?>
																<?php endforeach; ?>
															</tr>
														</table>
													</div>
												</div>
											<?php } ?>
										<?php }?> -->
									</div>

									<div class="gg-rate col-xs-12 col-sm-6 col-md-4">
										
						                	
						                		<?php if(!empty($plan['tarifas'])){ ?>
						                			<?php if(!$plan['tarifas']['disponible']){ ?>
						                				<div>
		           											<div class="gg-item-price">
		           												<?php if($plan['tarifas']['total'] != $plan['tarifas']['total_desc']){ ?>
		           													<div>
		           														<span class="gg-currency gg-pricepromo"><sup>$</sup></span>
										                          		<span class="gg-price gg-pricepromo"><strike><?php echo number_format(ceil($plan['tarifas']['total']), 2, '.', ',') ; ?></strike></span>
										                          		<span class="gg-currency gg-pricepromo"><?php echo $this->config->item('currency');?> </span>
										                          	</div>
										                          	
										                          	<div>
	              														<span class="gg-currency"><sup>$</sup></span>
										                          		<span class="gg-price"><?php echo number_format(ceil($plan['tarifas']['total_desc']), 2, '.', ',') ; ?> </span>
										                          		<span class="gg-currency"><?php echo $this->config->item('currency');?> </span>
										                          	</div>
	              												<?php }else{ ?>
										                          	<span class="gg-currency"><sup>$</sup></span>
										                          	<span class="gg-price"><?php echo number_format(ceil($plan['tarifas']['total']), 2, '.', ',') ; ?> </span>
										                          	<span class="gg-currency"><?php echo $this->config->item('currency');?> </span>
										                        <?php } ?>
									                        </div>

									                        <div class="gg-item-btn">
									                        	<form  method="post" action="<?php echo site_url('checkout/carrito');?>">
							                  						<input type="hidden" name="tipo_producto" value="1">
							                  						<input type="hidden" name="item" value="<?php echo $hotel->id; ?>">
							                  						<input type="hidden" name="room" value="<?php echo $room['id']; ?>">
							                  						<input type="hidden" name="plan" value="<?php echo $plan['id']; ?>">
							                  						<input type="hidden" name="tc" value="<?php echo $hotel->tipo_contrato; ?>">
							                  						<button type="submit" class="gg-btn-book"><?php echo ("global.book");?></button>
							                					</form>
									                        </div>

									                        	<!-- FRAGMENTOS ENRIQUECIDOS -->
																<div class="rich-snippets">
																	<div vocab="http://schema.org/" typeof="Product">
																		<span property="brand">GoGuy Travel</span>
																	 	<span property="name"><?= $room['nombre']; ?></span>
																	  	<img property="image" src="<?= $pathRoom;?>" alt="<?= $hotel->path;?>" />
																	  	<span property="description"></span>
																	  	<span property="offers" typeof="Offer">
																	    	<meta property="priceCurrency" content="<?= $this->config->item('currency');?>" />
																	    	$<span property="price"><?= number_format(ceil($plan['tarifas']['total_desc']), 2, '.', ',') ; ?></span> 
																	  	</span>
																	</div>
																</div>
																<!-- FRAGMENTOS ENRIQUECIDOS-->
														</div>
						                			<?php }else{ ?>
						                				<?php echo ("global.notavailable");?>
						                			<?php } ?>
						                		<?php }else{ ?> 
						                			<?php echo ("global.notavailable");?>
						                		<?php } ?>
					                		
		       					</div>
		    					<?php endforeach; ?>
							<?php }else{ ?>
							    <div class="gg-info col-xs-12 col-sm-6 col-md-8"></div>
								<div class="gg-rate col-xs-12 col-sm-6 col-md-4">
									<div class="price-box right">
										<div class="info-price-box">
						        			<span class="price"><?php echo ("global.notavailable");?></span>
						            	</div>
						            </div>
								</div>
							<?php } ?>
						</div>
					</article>

					
					<?php elseif($hotel->tipo_contrato == 2): ?>

					<article class="gg-itemlist row">
						<div class="gg-thumb col-xs-12 col-sm-5 col-md-3 ">
	                    	<?
	                    	$pathRoom = (string)$room->Image;
	                    	if(empty($pathRoom))
								$pathRoom = $_SERVER['assets'].'images/no-image.jpg';
							?>
							<img src="<?php echo $pathRoom; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500">
	                  	</div>

	                  	<div class="gg-inforoom col-xs-12 col-sm-7 col-md-9">
	                  		<div class="gg-room-name"><h2><?= $room->Name; ?><h2></div>
	                  		<div class="gg-room-plan row">
	                  		<?php if(!empty($room->MealPlans)){ ?>
						       	<?php foreach($room->MealPlans->MealPlan as $plan): ?>

						       		

						       		 <div class="gg-info col-xs-12 col-sm-6 col-md-8">
										<div class="plan-nombre">
											<?= $plan->Name; ?>
								    	</div>
								    	<? if($plan->Available->Status == 'AV' && isset($plan->Promotions)):?>
								    	<div class="plan-promocion">
											<div><?= $plan->Promotions->Promotion->Description; ?></div>
								    	</div>
								    	<? endif; ?>

								    	<? if($plan->Available->Status == 'AV'):?>
								    		<div class="plan-policies">
								    			<a class="modalCancellation" data-toggle="modal" >
													Cancellation Policies
												</a>
												<div class="policies">
													<?= (string)$plan->RateDetails->RateDetail->CancellationPolicy->Description;?>
												</div>
									    	</div>
								    	<? endif; ?>

									</div>

									<div class="gg-rate col-xs-12 col-sm-6 col-md-4">
						                	<?php if($plan->Available->Status == 'AV'){?>
						                		
						                				<?php if($plan->Total != 0){ ?>
						                				<div>
		           											<div class="gg-item-price">
		           												<?php if(floatval($plan->Normal) != floatval($plan->Total)){ ?>
		           													<div>
		           														<span class="gg-currency gg-pricepromo"><sup>$</sup></span>
										                          		<span class="gg-price gg-pricepromo"><strike><?php echo number_format(round(floatval($plan->Normal)), 2, '.', ','); ?></strike></span>
										                          		<span class="gg-currency gg-pricepromo"><?php echo $this->config->item('currency');?> </span>
										                          	</div>
										                          	
										                          	<div>
	              														<span class="gg-currency"><sup>$</sup></span>
										                          		<span class="gg-price"><?php echo number_format(round(floatval($plan->Total)), 2, '.', ','); ?> </span>
										                          		<span class="gg-currency"><?php echo $this->config->item('currency');?> </span>
										                          	</div>
	              												<?php }else{ ?>
	              													<span class="gg-currency"><sup>$</sup></span>
										                          	<span class="gg-price"><?php echo number_format(round(floatval($plan->Total)), 2, '.', ','); ?> </span>
										                          	<span class="gg-currency"><?php echo $this->config->item('currency');?> </span>
										                        <?php } ?>
									                        </div>

									                        <div class="gg-item-btn">
									                        	<form  method="post" action="<?php echo site_url('checkout/carrito');?>">
							                  						<input type="hidden" name="tipo_producto" value="1">
							                  						<input type="hidden" name="item" value="<?php echo $dataHotel->id; ?>">
											                  		<input type="hidden" name="room" value="<?php echo $room->Id; ?>">
											                  		<input type="hidden" name="plan" value="<?php echo $plan->Id; ?>">
							                  						<input type="hidden" name="tc" value="<?php echo $hotel->tipo_contrato; ?>">
							                  						<button type="submit" class="gg-btn-book"><?php echo ("global.book");?></button>
							                					</form>
									                        </div>


									                        <!-- FRAGMENTOS ENRIQUECIDOS -->
																<div class="rich-snippets">
																	<div vocab="http://schema.org/" typeof="Product">
																		<span property="brand">GoGuy Travel</span>
																	 	<span property="name"><?= $room->Name; ?></span>
																	  	<img property="image" src="<?= $pathRoom;?>" alt="<?= $hotel->path;?>" />
																	  	<span property="description"></span>
																	  	<span property="offers" typeof="Offer">
																	    	<meta property="priceCurrency" content="<?= $this->config->item('currency');?>" />
																	    	$<span property="price"><?= number_format(round(floatval($plan->Total)), 2, '.', ','); ?></span> 
																	  	</span>
																	</div>
																</div>
																<!-- FRAGMENTOS ENRIQUECIDOS-->
														</div>
														<?php }else{ ?>
															<?php echo ("global.notavailable");?>
														<?php } ?>
												<?php }else{ ?>
													<?php echo ("global.notavailable");?>
												<?php } ?>
					                		
		       					</div>
		    					<?php endforeach; ?>
							<?php }else{ ?>
							    <div class="col-xs-12 col-sm-6 col-md-9"></div>
								<div class="col-xs-12 col-sm-6 col-md-3">
									<div class="price-box right">
										<div class="info-price-box">
						        			<span class="price"><?php echo ("global.notavailable");?></span>
						            	</div>
						            </div>
								</div>
							<?php } ?>
						</div>
					</article>
					<?php endif; ?>

								<?php endforeach; ?>
							<?php }else{ ?>
								<div class="well">
									No rooms were found matching your search criteria. Please select different dates or number of adults or children. 
								</div>
							<?php } ?>
				            </div>


					<div id="tabs-producto" class="tabs tabs-producto">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#overview" data-toggle="tab"> <?php echo ("hotel.overview");?></a></li>
							<!-- <li ><a href="#acommodations" data-toggle="tab"> <?php echo ("hotel.accommodations");?></a></li> -->
							<?php if(!empty($restaurants) || !empty($bars)): ?>
								<li ><a href="#restbars" data-toggle="tab"> <?php echo ("hotel.restbars");?></a></li>
							<?php endif; ?>
							<?php if(!empty($spa)): ?>
								<li ><a href="#spa" data-toggle="tab"> <?php echo ("hotel.spa");?></a></li>
							<?php endif; ?>
							<?php if(!empty($kids)): ?>
								<li ><a href="#kids" data-toggle="tab"> <?php echo ("hotel.kidsclub");?></a></li>
							<?php endif; ?>
							<?php if(($hotel->aplica_todo_incluido) &&  !empty($hotel->todo_incluido)): ?>
								<li ><a href="#allinclusive" data-toggle="tab"> <?php echo ("hotel.allinclusive");?></a></li>
							<?php endif; ?>
							<?php if(!empty($hotel->politicas)): ?>
								<li ><a href="#politicas" data-toggle="tab"> <?php echo ("global.policies");?></a></li>
							<?php endif; ?>
						</ul>
						  
						<div class="tab-content">
							
							<div class="tab-pane fade in active item-info" id="overview">
								<div class="col-sm-12 col-md-12">
									<div>
										<?php if($hotel->tipo_contrato == 1):?>	
											<?php if(!empty($hotel->total_habitaciones)): ?>
							              		<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.rooms");?>:</b> <?php echo $hotel->total_habitaciones; ?></div>
							              	<?php endif; ?>
						              	<?php elseif($hotel->tipo_contrato == 2):?>
						              		<?php if(!empty($hotel->TotalRooms)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.rooms");?>:</b> <?php echo $hotel->TotalRooms; ?></div>
						              		<?php endif; ?>
					              		<?php endif; ?>

						              	<?php if($hotel->tipo_contrato == 1):?>	
							              	<?php if(!empty($hotel->total_albercas)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.pools");?>:</b> <?php echo $hotel->total_albercas; ?></div>
						              		<?php endif; ?>
					              		<?php endif; ?>
										
										<?php if($hotel->tipo_contrato == 1):?>	
							              	<?php if(!empty($hotel->checkin)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.checkin");?>:</b> <?php echo $hotel->checkin; ?></div>
						              		<?php endif; ?>
					              		<?php elseif($hotel->tipo_contrato == 2):?>
						              		<?php if(!empty($hotel->CheckIn)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.checkout");?>:</b> <?php echo $hotel->CheckIn; ?></div>
						              		<?php endif; ?>
					              		<?php endif; ?>
										
										<?php if($hotel->tipo_contrato == 1):?>	
						              		<?php if(!empty($hotel->checkout)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.checkout");?>:</b> <?php echo $hotel->checkout; ?></div>
						              		<?php endif; ?>
					              		<?php elseif($hotel->tipo_contrato == 2):?>
						              		<?php if(!empty($hotel->CheckOut)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.checkout");?>:</b> <?php echo $hotel->CheckOut; ?></div>
						              		<?php endif; ?>
					              		<?php endif; ?>
										
										<?php if($hotel->tipo_contrato == 1):?>	
						              		<?php if(!empty($hotel->solo_adultos)): ?>
							                	<div class="col-sm-6 col-md-6"><b><?php echo ("hotel.onlyadults");?>:</b> <?php echo ($hotel->solo_adultos) ? echo ("global.yes"): echo ("global.no"); ?></div>
							                <?php endif; ?>
							            <?php elseif($hotel->tipo_contrato == 2):?>
						              		<?php if($hotel->AdultOnly == 'true'): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.onlyadults");?>:</b> <?php echo ($hotel->solo_adultos) ? echo ("global.yes"): echo ("global.no"); ?></div>
						              		<?php endif; ?>
						                <?php endif; ?>

					              		<?php if($hotel->tipo_contrato == 1):?>	
						              		<?php if(!empty($hotel->acepta_mascotas)): ?>
						              			<div class="col-sm-6 col-md-6 "><b><?php echo ("hotel.pets");?>:</b> <?php echo ($hotel->acepta_mascotascotas) ? echo ("global.yes"): echo ("global.no"); ?></div>
						              		<?php endif; ?>
					              		<?php endif; ?>

				              		</div>

				              		<div class="col-sm-12 col-md-12 ">
				              		<?php if($hotel->tipo_contrato == 1):?>	
						            	<?php if(!empty($hotel->descripcion_larga)): ?>
						              		<div class="hotel-descripcion">
						              			<?php echo $hotel->descripcion_larga; ?>
						              		</div>
						              	<?php endif; ?>
						            <?php elseif($hotel->tipo_contrato == 2):?>
										<?php if(!empty($hotel->Description)): ?>
						              		<div class="hotel-descripcion">
						              			<?php echo $hotel->Description; ?>
						              		</div>
						              	<?php endif; ?>
						            <?php endif; ?>

					              	<?php if(!empty($facilities)): ?>
					              		
								  		<div class="tab-pane fade in" id="facilities">
								  			<h3><?php echo ("hotel.facilities");?></h3>
								  			<ul class="list-items row">
								  			<?php if($hotel->tipo_contrato == 1):?>
					            			<?php foreach($facilities as $instalacion): ?>
					            				<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
					              					<span><?php echo $instalacion->nombre; ?></span>
					              				</li>
					              			<?php endforeach; ?>
					              			
					              			<?php elseif($hotel->tipo_contrato == 2):?>
					              				<?php foreach($facilities as $facility): ?>
					              					<?php //var_dump($facility); ?>
					            				<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
					              					<span><?php echo $facility->Name; ?></span>
					              					<?php echo ($facility->ExtraCharge == 'true')?' ($)':'';?></span>
					              				</li>
					              			<?php endforeach; ?>
					              			<?php endif; ?>
					              			</ul>
										</div>
									<?php endif; ?>

							        <?php if(!empty($services)): ?>
							        	<div class="tab-pane fade in" id="services">
							        		<h3><?php echo ("hotel.services");?></h3>
								  			<ul class="list-items row">
								  			<?php if($hotel->tipo_contrato == 1):?>
					            			<?php foreach($services as $servicio): ?>
					            				<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
					              					<span><?php echo $servicio->nombre; ?><?php echo ($servicio->costo_extra)?' ($)':'';?></span>
					              				</li>
					              			<?php endforeach; ?>
					              			<?php elseif($hotel->tipo_contrato == 2):?>
					              				<?php foreach($services as $service): ?>
					              					
					            				<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
					              					<span>
					              						<?php echo $service->Name; ?>
					              						<?php echo ($service->ExtraCharge == 'true')?' ($)':'';?></span>
					              					</span>
					              				</li>
					              			<?php endforeach; ?>
					              			<?php endif; ?>

					              			</ul>
										</div>
									<?php endif; ?>

									<?php if($hotel->tipo_contrato == 1):?>		
									<?php if(!empty($activities)): ?>
										 <div class="tab-pane fade in" id="activities">
										 	<h3><?php echo ("hotel.activities");?></h3>
										 	<ul class="list-items row">
							             		<?php foreach($activities as $actividad): ?>
							             			<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
							             				<span><?php echo $actividad->nombre; ?><?php echo ($actividad->costo_extra)?' ($)':'';?></span>
							             			</li>
							             		<?php endforeach; ?>
							            	</ul>
										</div>
									<?php endif; ?>
									<?php endif; ?>

									<?php if(!empty($temas)): ?>
										<div class="tab-pane fade in" id="temas">
											<h3><?php echo ("hotel.themes");?></h3>
											<ul class="list-items row">
												<?php if($hotel->tipo_contrato == 1):?>
							            		<?php foreach($temas as $tema): ?>
							            			<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
							            				<span><?php echo $tema->nombre; ?></span>
							            			</li>
							            		<?php endforeach; ?>
							            		<?php elseif($hotel->tipo_contrato == 2):?>
					              				<?php foreach($temas as $theme): ?>
						            				<li class="col-xs-6 col-sm-5 col-md-4 col-lg-4">
						              					<span><?php echo $theme->Name; ?></span>
						              				</li>
						              			<?php endforeach; ?>
						              			<?php endif; ?>
							            	</ul>
										</div>
									<?php endif; ?>

								</div>
							</div>
							</div>


							<?php if(!empty($restaurants) || !empty($bars)): ?>
							<div class="tab-pane fade in item-info" id="restbars">
								<?php if(!empty($restaurants)): ?>
									<div class="top20">
										<h3><?php echo ("hotel.restaurants");?></h3>
									</div>
									<?php foreach($restaurants as $restaurante): ?>
										<div class="articulo row">
								            <?php if($hotel->tipo_contrato == 1):?>
						            			<div class="col-sm-3 col-md-3 offset-0">
								              		<?php 
													$pathRestImg = $_SERVER['assets'].'images/hotels/'.$hotel->ruta_imagenes.'/'.$restaurante->imagen.'.jpg';
													if(empty($restaurante->imagen) || @getimagesize($pathRestImg) == false)
														$pathRestImg = $_SERVER['assets'].'images/no-image.jpg';
													?>
													<a><img src="<?php echo $pathRestImg; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500" /></a>
								            	</div>

									            <div class="col-sm-9 col-md-9">
					            					<div class="nombre-producto"><?php echo $restaurante->nombre; ?></div>
					            					<div class="descripcion-producto">
					            					<p class="block-with-text">
					            						<?php echo $restaurante->descripcion; ?>
					            						<!-- <?php if(!empty($restaurante->info_adicional)){ ?><br><?php echo $restaurante->info_adicional; ?><?php } ?>
					            						<?php if(!empty($restaurante->horario)){ ?><br><?php echo $restaurante->horario; ?><?php } ?> -->
					            					</p>
					            					</div>
					            				</div>

				            				<?php elseif($hotel->tipo_contrato == 2):?>
				            					<div class="col-sm-3 col-md-3 offset-0">
								              		<?php 
								              			$pathRest = (string)$restaurante->Image;
								                    	if(empty($pathRest))
															$pathRest = $_SERVER['assets'].'images/no-image.jpg';
													?>
													<a><img src="<?php echo $pathRest; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500" /></a>
								            	</div>
					              				<div class="col-sm-9 col-md-9">
					            					<div class="nombre-producto"><?php echo $restaurante->Name; ?></div>
					            					<div class="descripcion-producto">
					            					<p class="block-with-text">
					            						<?php echo $restaurante->Description; ?>
					            					</p>
				            						</div>
				            					</div>
						              		<?php endif; ?>

				            			</div>
				            		<?php endforeach; ?>
				            	<?php endif; ?>
				            	

				            	<?php if(false): ?>
				            		<h3><?php echo ("hotel.bars");?></h3>
				            		<?php foreach($bars as $bar): ?>
				              			<div class="articulo row">
				              				 <?php if($hotel->tipo_contrato == 1):?>
									            <div class="col-sm-3 col-md-3 offset-0">
									            	<?php 
													$pathBarImg = $_SERVER['assets'].'images/hotels/'.$hotel->ruta_imagenes.'/'.$bar->imagen.'.jpg';
													if(empty($bar->imagen) || @getimagesize($pathBarImg) == false)
														$pathBarImg = $_SERVER['assets'].'images/no-image.jpg';
													?>
									              	<a><img src="<?php echo $pathBarImg; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500" /></a>
									           	</div>
							            
									           	<div class="col-sm-9 col-md-9">
					              					<div class="nombre-producto"><?php echo $bar->nombre; ?></div>
					              					<div class="descripcion-producto"><?php echo $bar->descripcion; ?>
					              						<?php if(!empty($bar->info_adicional)){ ?><br><?php echo $bar->info_adicional; ?><?php } ?>
					            						<?php if(!empty($bar->horario)){ ?><br><?php echo $bar->horario; ?><?php } ?>
					              					</div>
					              				</div>
				              				<?php elseif($hotel->tipo_contrato == 2):?>
				              					<div class="col-sm-3 col-md-3 offset-0">
				              						<?php 
								              			$pathBar = (string)$bar->Image;
								                    	if(empty($pathBar))
															$pathBar = $_SERVER['assets'].'images/no-image.jpg';
													?>
									              	<a><img src="<?php echo $pathBar; ?>" alt="<?=$dataHotel->url;?>" title="<?=$dataHotel->url;?>" width="700" height="500" /></a>
									           	</div>
							            
									           	<div class="col-sm-9 col-md-9">
					              					<div class="nombre-producto"><?php echo $bar->Name; ?></div>
					              					<div class="descripcion-producto"><?php echo $bar->Description; ?>
					              					</div>
					              				</div>
				              				<?php endif; ?>			
				              			</div>
				              		<?php endforeach; ?>
				              	<?php endif; ?>
							</div>
							<?php endif; ?>
								
								<?php if(false): ?>
							  		<div class="tab-pane fade in item-info" id="spa">
							  			<?php foreach($spa as $s): ?>
				              				<div class="articulo row">
								            <div class="col-sm-3 col-md-3 offset-0">
								              	<a><img src="<? echo $_SERVER['assets']; ?>images/hotels/<? echo $hotel->ruta_imagenes; ?>/<? echo $s->imagen; ?>.jpg" alt="<?=$hotel->path;?>" title="<?=$hotel->path;?>" width="270" height="200"></a>
								           	</div>
						            
								           	<div class="col-sm-9 col-md-9">
				              					<div class="nombre-producto"><?php echo $s->nombre; ?></div>
				              					<div class="descripcion-producto"><?php echo $s->descripcion; ?>
				              						<?php if(!empty($s->info_adicional)){ ?><br><?php echo $s->info_adicional; ?><?php } ?>
				            						<?php if(!empty($s->tratamientos)){ ?><br><?php echo $s->tratamientos; ?><?php } ?>
				              					</div>
				              				</div>
				              			</div>
				              			<?php endforeach; ?>
							  		</div>
							  	<?php endif; ?>
								
								<?php if(false): ?>
							  		<div class="tab-pane fade in item-info" id="kids">
							  			<?php foreach($kids as $k): ?>
				              				<div class="articulo row">
									            <div class="col-sm-3 col-md-3 offset-0">
									              	<a><img src="<? echo $_SERVER['assets']; ?>images/hotels/<? echo $hotel->ruta_imagenes; ?>/<? echo $k->imagen; ?>.jpg" alt="<?=$hotel->path;?>" title="<?=$hotel->path;?>" width="270" height="200"></a>
									           	</div>
							            
									           	<div class="col-sm-9 col-md-9">
					              					<div class="nombre-producto"><?php echo $k->nombre; ?></div>
					              					<div class="descripcion-producto"><?php echo $k->descripcion; ?>
				              						<?php if(!empty($k->info_adicional)){ ?><br><?php echo $k->info_adicional; ?><?php } ?>
				              					</div>
					              				</div>
				              				</div>
				              			<?php endforeach; ?>
							  		</div>
							  	<?php endif; ?>

							  	<?php if(($hotel->aplica_todo_incluido) &&  !empty($hotel->todo_incluido)): ?>
							  		<div class="tab-pane fade in item-info" id="allinclusive">
							  			<?php echo $hotel->todo_incluido; ?>
							  		</div>
							  	<?php endif; ?>

							  	<?php if(!empty($hotel->politicas)): ?>
							  		<div class="tab-pane fade in" id="politicas">
							  			<?php echo $hotel->politicas; ?>
							  		</div>
							  	<?php endif; ?>
							</div>
					</div><!-- tabs-->
				
	   	</div>
	</section><!-- #main -->


	<!-- Modal -->
	<div class="modal fade" id="modalRoomPolicies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Cancellation Policies</h4>
	      </div>
	      <div class="modal-body">
	        <div id="cancellations"></div>
	      </div>
	      <!-- <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div> -->
	    </div>
	  </div>
	</div>

	<script>
		function initMap() {
	  		var myLatlng = {lat: 0, lng: 0};

			var map = new google.maps.Map(document.getElementById('gg-map'), {
	    		zoom: 14,
	    		scrollwheel: false,
	    		// mapTypeId: google.maps.MapTypeId.HYBRID,
	    		center: myLatlng
	  		});

	  		var marker = new google.maps.Marker({
	    		position: myLatlng,
	    		map: map,
	  		});

	  		map.addListener('center_changed', function() {
	    		// 3 seconds after the center of the map has changed, pan back to the
	    		// marker.
	    		window.setTimeout(function() {
	      			map.panTo(marker.getPosition());
	    		}, 500);
	  		});
			
			marker.addListener('click', function() {
	    	map.setZoom(14);
	    	map.setCenter(marker.getPosition());
	  	});
	}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpBI57bCpC039YWHQsKpDvYoUYEneYPIE&callback=initMap"></script>

@endsection