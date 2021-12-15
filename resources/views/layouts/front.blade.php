@php
    $currencyCode = SC::getCurrentCurrencyCode();
    $assetVersion = 52;
    $settings = SettingsComponent::getSettings();
@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="c2ZL1n5MRFtSrh52arKw3cz3kRxkopSdYSHcRykdDYM" />
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{SettingsComponent::getIcoUrl()}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/app.css?v=$assetVersion")}}">
    {!! SEO::generate(true) !!}
    @include("layouts.afcss")
</head>
<body>
    <header id="gg-header">
            <div class="container">
                <div class="text-right" style="overflow: hidden;">
                    @if(SC::$affiliateId == 1)
                        <!-- <a href="#" onclick="doGTranslate('en|en');return false;" title="English" class="gflag nturl" style="background-position:-0px -0px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="English" /></a><a href="#" onclick="doGTranslate('en|fr');return false;" title="French" class="gflag nturl" style="background-position:-200px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="French" /></a><a href="#" onclick="doGTranslate('en|de');return false;" title="German" class="gflag nturl" style="background-position:-300px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="German" /></a><a href="#" onclick="doGTranslate('en|it');return false;" title="Italian" class="gflag nturl" style="background-position:-600px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Italian" /></a><a href="#" onclick="doGTranslate('en|pt');return false;" title="Portuguese" class="gflag nturl" style="background-position:-300px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Portuguese" /></a><a href="#" onclick="doGTranslate('en|ru');return false;" title="Russian" class="gflag nturl" style="background-position:-500px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Russian" /></a><a href="#" onclick="doGTranslate('en|es');return false;" title="Spanish" class="gflag nturl" style="background-position:-600px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Spanish" /></a> -->
                    @else
                        <div id="google_translate_element"></div>
                    @endif
                    <button 
                        id='flags-btn'
                        type="button" 
                        class="btn btn-default" 
                        data-toggle="popover" 
                        data-placement="bottom" 
                        data-html="true"
                        data-content="
                            @if (SC::$affiliateId==1)
                                <a href='tel:888-222-0906' class='btn'><span class='langflag us'></span> US & Canada - 888-222-0906</a>
                                <a href='tel:+52-998-253-6178' class='btn'><span class='langflag mx'></span> Mexico - +52-998-253-6178</a>
                                <a href='whatsapp://send?phone=+5219981288784' class='btn'><i class='fa fa-whatsapp'></i> WhatsApp - +52 1 998 128 8784</a>
                            @else
                                <a href='tel:{{$settings['contactPhone']}}' class='btn'> Contact - {{$settings['contactPhone']}}</a>
                            @endif
                        "
                        >
                        @if (SC::$affiliateId==1)
                            <span class="langflag us"></span> US & Canada - 888-222-0906 <span class="caret"></span>
                        @else
                            Contact - {{$settings['contactPhone']}} <span class="caret"></span>
                        @endif
                    </button>                
                </div>
                <div id="gg-hmain">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="col-sm-3 col-md-2 navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>

                                <a id="gg-logo" class="navbar-brand" href="/">
                                    <img src="{{SettingsComponent::getLogoUrl()}}">
                                </a>
                            </div>

                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="col-sm-8 col-md-10 nav navbar-nav">
                                    <li class="dropdown">
                                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$currencyCode}}<span class="caret"></span></a>
                                      <ul class="dropdown-menu">
                                        <li><a href="/setcur/{{$currencyCode=='MXN'?'USD':'MXN'}}" class="set-cur">{{$currencyCode=='MXN'?'USD':'MXN'}}</a></li>
                                      </ul>
                                    </li>
                                    <li><a href="/contact">Contact</a></li>
                                    <!-- <li><a href="/offers">Offers</a></li> -->
                                    <li><a href="/transfers">Transfers</a></li>
                                    <li><a href="/tours">Tours</a></li>
                                    <li><a href="/">Home</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        @yield('content')
        <footer class="{{SC::$affiliateId==1?'goguy':''}}">
            <div class="container">
                <div class="row d-flex-between">
                    <div class="col-xs-12 col-sm-6 col-md-4-H">
                        <p class="title">CONNECT</p>
                        <div id="footer-sl">
                            @if ($settings['facebookLink'])
                                <a href="{{$settings['facebookLink']}}" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                            @endif
                            @if ($settings['twitterLink'])
                                <a href="{{$settings['twitterLink']}}" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                            @endif
                            @if ($settings['googleLink'])
                                <a href="{{$settings['googleLink']}}" target="_blank"><i class="fa fa-google-plus"></i></a>
                            @endif
                            @if ($settings['instagramLink'])
                                <a href="{{$settings['instagramLink']}}" target="_blank"><i class="fa fa-instagram"></i></a>
                            @endif
                        </div>
                        <br>
                        <p style="">RECEIVE THE BEST TRAVEL DEALS!</p>
{{--                         <div id="mc_embed_signup">
                        <form action="https://goguytravel.us17.list-manage.com/subscribe/post?u=f5468fe7d54c6b5eb75369e6e&amp;id=fdf958c06b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll">
                                <div class="mc-field-group">
                                    <input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" placeholder="EMAIL ADDRESS" style="display: inline-block;width: 60%;">
                                    <input type="submit" value="GO" name="subscribe" id="mc-embedded-subscribe" class="button btn pull-right" >
                                </div>
                                <div id="mce-responses" class="clear">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f5468fe7d54c6b5eb75369e6e_fdf958c06b" tabindex="-1" value=""></div>
                            </div>
                        </form>
                        </div>
                        <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='BIRTHDAY';ftypes[3]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
 --}}                    </div>
 
 <div class="col-xs-12 col-sm-6 col-md-6">
     <ul class="nav_Neo">
                        <li><a href="/about" title="ABOUT">ABOUT</a></li>
                        <li><a href="/policies" title="TERMS">TERMS</a></li>
                        <li><a href="/privacy" title="PRIVACY">PRIVACY</a></li>
                        <li><a href="/contact" title="CONTACT">CONTACT</a></li>
                        <li class="nav-item"><a href="tel:{{$settings['contactPhone']}}" title="{{$settings['contactPhone']}}">{{$settings['contactPhone']}}</a></li>
                    </ul>
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
                    
                </div>                    
            </div>
            <div id="subfooter">
                <div class="container">
                    <p class="text-center">SEO by Neo E-Marketing <a href="https://neo-emarketing.com/" title="Agencia de Marketing Digital Cancún">Agencia de Marketing Digital Cancún</a></p>
                </div>
            </div>
        </footer>

        <div id='alertModal' class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        @if (\SC::$affiliateId==1)
            <a href="https://m.me/MariturDMC" id='fb-msn' target="_blank">
                <img src="/images/fb-messenger-new.png">
            </a>
        @endif
        <div id="popupHelp" class="text-center white-popup mfp-hide">
            <p class="text-center h2">Need Help?</p>
            <p class="text-center h3">Call us!</p>
            <div class="text-center">
                @if (\SC::$affiliateId==1)
                    <p><a href='tel:888-222-0906' class='btn btn-default'><span class='langflag us'></span> US & Canada - 888-222-0906</a></p>
                    <p><a href='tel:+52-998-253-6178' class='btn btn-default'><span class='langflag mx'></span> Mexico - +52-998-253-6178</a></p>
                    <p><a href='whatsapp://send?phone=+5219981288784' class='btn btn-default'><i class='fa fa-whatsapp'></i> WhatsApp - +52 1 998 128 8784</a></p>                
                @else
                    <p><a href='tel:{{$settings['contactPhone']}}' class='btn btn-default'><span class='langflag us'></span> Contact - {{$settings['contactPhone']}}</a></p>
                @endif
            </div>       
        </div>

    <!-- js app -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5ab59253c3f1d762"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@11.0.6/dist/lazyload.min.js"></script>
    <script type="text/javascript">
        var lazyLoadInstance = new LazyLoad({
            // elements_selector: ".lazy"
            // ... more custom settings?
        });
    </script>
    <script src="{{asset('assets/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/jquery-validation/dist/additional-methods.min.js')}}"></script>
    <script src="{{asset('assets/jquery-form/dist/jquery.form.min.js')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.min.js')}}"></script>
    <script src="{{asset('assets/flexslider/jquery.flexslider.js')}}"></script>
    <script src="{{asset('assets/nivo-slider/jquery.nivo.slider.js')}}"></script>
    <script src="{{asset('assets/seiyria-bootstrap-slider/dist/bootstrap-slider.js')}}"></script>
    <script src="{{asset('assets/listjs/dist/list.min.js')}}"></script>
    <script src="{{asset('assets/sticky-kit/jquery.sticky-kit.min.js')}}"></script>
    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/magnific-popup.js')}}"></script>
    <script src="{{asset('js/daterangepicker.js')}}"></script>
    @stack('scripts')
    <script src="{{asset("js/app.js?v=$assetVersion")}}"></script>
    <script src="{{asset("js/tours.js?v=$assetVersion")}}"></script>
    <script src="{{asset("js/transfers.js?v=$assetVersion")}}"></script>

    @if(SC::$affiliateId == 1)
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
         fbq('init', '275496146207597'); 
        fbq('track', 'PageView');
        </script>
        <noscript>
         <img height="1" width="1" 
        src="https://www.facebook.com/tr?id=275496146207597&ev=PageView
        &noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
        <!-- GTranslate: https://gtranslate.io/ -->
        <style type="text/css">
        <!--
        a.gflag {vertical-align:middle;font-size:16px;padding:1px 0;background-repeat:no-repeat;background-image:url(//gtranslate.net/flags/16.png);}
        a.gflag img {border:0;}
        a.gflag:hover {background-image:url(//gtranslate.net/flags/16a.png);}
        -->
        </style>



        <script type="text/javascript">
        /* <![CDATA[ */
        function doGTranslate(lang_pair) {if(lang_pair.value)lang_pair=lang_pair.value;if(lang_pair=='')return;var lang=lang_pair.split('|')[1];var plang=location.pathname.split('/')[1];if(plang.length !=2 && plang.toLowerCase() != 'zh-cn' && plang.toLowerCase() != 'zh-tw')plang='en';if(lang == 'en')location.href=location.protocol+'//'+location.host+location.pathname.replace('/'+plang+'/', '/')+location.search;else location.href=location.protocol+'//'+location.host+'/'+lang+location.pathname.replace('/'+plang+'/', '/')+location.search;}
        /* ]]> */
        </script>


        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-75293624-15"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-75293624-15');
        </script>
        @yield('gtagScripts')
        <script type="text/javascript">
        window.__lc = window.__lc || {};
        window.__lc.license = 9923330;
        (function() {
          var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
          lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
        })();
        </script>
        <!-- End of LiveChat code -->
    @else
        <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-75293624-15'}, 'google_translate_element');
        }
        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    @endif
</body>
</html>