<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package dazzling
 */
?>
                </div><!-- close .row -->
            </div><!-- close .container -->
        </div><!-- close .site-content -->

	<div id="footer-area">
		<div class="container footer-inner">
			<?php get_sidebar( 'footer' ); ?>
		</div>
		<footer style="background-image: url('https://www.goguytravel.com/images/goguy-footer.png');background-size: 169px">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <p class="title">CONNECT</p>
                        <div id="footer-sl">
                        	<a href="https://www.facebook.com/goguytravel/" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
							<a href="https://twitter.com/goguytravel" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
							<a href="https://www.instagram.com/goguy_travel/" target="_blank"><i class="fa fa-instagram"></i></a>
                       </div>
                        <br>
                        <p style="">RECEIVE THE BEST TRAVEL DEALS!</p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 payment-methods">
                        <p class="title">PAYMENT METHODS</p>
                        <div class="row">
                            <div class="col-xs-6">
                                <p><i class="fa fa-cc-visa fa-3x"></i></p>
                            </div>
                            <div class="col-xs-6">
                                <p><i class="fa fa-cc-mastercard fa-3x"></i></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <p><i class="fa fa-cc-amex fa-3x"></i></p>
                            </div>
                            <div class="col-xs-6">
                                <p><i class="fa fa-cc-discover fa-3x"></i></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <p class="title">HOTELS IN MEXICO</p>
                        <ul>
                            <li><a href="https://www.goguytravel.com/hotels/cancun" title="Cancun">Cancun</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/playa-del-carmen" title="Playa del Carmen">Playa del Carmen</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/riviera-maya" title="Riviera Maya">Riviera Maya</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/los-cabos" title="Los Cabos">Los Cabos</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/acapulco" title="Acapulco">Acapulco</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <p class="title">HOTELS IN BRAZIL</p>
                        <ul>
                            <li><a href="https://www.goguytravel.com/hotels/sao-paulo" title="Sao Paulo">Sao Paulo</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/rio-de-janeiro" title="Rio de Janeiro">Rio de Janeiro</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/gramado" title="Gramado">Gramado</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/porto-seguro" title="Porto Seguro">Porto Seguro</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/brasilia" title="Brasilia">Brasilia</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <p class="title"><br></p>
                        <ul>
                            <li><a href="https://www.goguytravel.com/hotels/armacao-de-buzios" title="Armacao de Buzios">Armacao de Buzios</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/curitiba" title="Curitiba">Curitiba</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/natal" title="Natal">Natal</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/fortaleza" title="Fortaleza">Fortaleza</a></li>
                            <li><a href="https://www.goguytravel.com/hotels/porto-alegre" title="Porto Alegre">Porto Alegre</a></li>
                        </ul>
                    </div>
                </div>                    
            </div>
            <div id="subfooter">
                <div class="container">
                    <ul>
                        <li><a href="https://www.goguytravel.com/about" title="ABOUT">ABOUT</a></li>
                        <li><a href="https://www.goguytravel.com/policies" title="TERMS">TERMS</a></li>
                        <li><a href="https://www.goguytravel.com/privacy" title="PRIVACY">PRIVACY</a></li>
                        <li><a href="https://www.goguytravel.com/contact" title="CONTACT">CONTACT</a></li>
                        <li><a href="tel:MEX: +521998 109 5610               USA & CANADA: 888-222-0906" title="MEX: +521998 109 5610               USA & CANADA: 888-222-0906">MEX: +521998 109 5610               USA & CANADA: 888-222-0906</a></li>
                    </ul>
                    <p>SEO by Neo E-Marketing <a href="https://neo-emarketing.com/" title="Agencia de Marketing Digital Cancún">Agencia de Marketing Digital Cancún</a></p>
                </div>
            </div>
        </footer>
		
		<!-- <footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<?php if( of_get_option('footer_social') ) dazzling_social_icons(); ?>
				<nav role="navigation" class="col-md-6">
					<?php dazzling_footer_links(); ?>
				</nav>
				<div class="copyright col-md-6">
					<?php echo of_get_option( 'custom_footer_text', 'dazzling' ); ?>
					<?php dazzling_footer_info(); ?>
				</div>
			</div>
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div>
		</footer> -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>