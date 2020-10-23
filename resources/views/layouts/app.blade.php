<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122305608-1"></script>
  <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122305608-1');
  </script>
  <script>
window['_fs_debug'] = false;
window['_fs_host'] = 'fullstory.com';
window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
window['_fs_org'] = 'PQBJZ';
window['_fs_namespace'] = 'FS';
(function(m,n,e,t,l,o,g,y){
    if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
    g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
    o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
    y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
    g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
    g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
    g.log = function(a,b) { g("log", [a,b]) };
    g.consent=function(a){g("consent",!arguments.length||a)};
    g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
    g.clearUserCookie=function(){};
})(window,document,window['_fs_namespace'],'script','user');
</script>
  <script charset="UTF-8" src="//cdn.sendpulse.com/js/push/80d831a5274cd079abe7b91208c4402f_1.js" async></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="assets-url" content="{{ asset('') }}">
  <meta name="description" content="uHomie - Encuentra y postula de forma rápida, transparente y segura a tu próxima propiedad. Arriendo de casas, alquileres vacacionales, arriendo de departamentos, todo en un solo lugar.">
  <meta name="keywords" content="arriendo casas, alquiler vacacional, arriendo de departamentos">
  @yield('meta-fb')
  <title>uHomie | Arriendo casas, alquiler vacacional y arriendo de departamentos.</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script>
    // load Branch
      (function(b,r,a,n,c,h,_,s,d,k){if(!b[n]||!b[n]._q){for(;s<_.length;)c(h,_[s++]);d=r.createElement(a);d.async=1;d.src="https://cdn.branch.io/branch-latest.min.js";k=r.getElementsByTagName(a)[0];k.parentNode.insertBefore(d,k);b[n]=h}})(window,document,"script","branch",function(b,r){b[r]=function(){b._q.push([r,arguments])}},{_q:[],_v:1},"addListener applyCode autoAppIndex banner closeBanner closeJourney creditHistory credits data deepview deepviewCta first getCode init link logout redeem referrals removeListener sendSMS setBranchViewData setIdentity track validateCode trackCommerceEvent logEvent disableTracking".split(" "), 0);
    // init Branch
    branch.init('key_live_kkUxqDgIrmIbHB12XBY80kcjqxiKQYiD');
  </script>

  <link rel="shortcut icon" href="{{ url('favicon.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ url('css/app.css') }}">
  <!-- Google Tag Manager -->
  <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-NBTLLJZ');</script>
  <script>
    !function(p,r,i,v,a,c,y){p.Metomic={apiKey:i};p[i]||(p[i]=function(){
    (p[i].q=p[i].q||[]).push(arguments)});p[i].l=+new Date;c=r.createElement(v);
    y=r.getElementsByTagName(v)[0];p.Metomic.script=c;c.src=a;y.parentNode.insertBefore(c,y)}
    (window,document,'prj:2cacec8a-9a69-4f0f-b2ba-107981bbd1f8','script','https://consent-manager.metomic.io/embed.js');
  </script>
  <!-- End Google Tag Manager -->
  @if (!Auth::user())
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    
  @endif
  @yield('flow-styles')
  @yield('styles')

  <!-- Inicio del script del widget de Zendesk -->
     <!-- <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=web_widget/uhomieayuda.zendesk.com"></script>-->
  <!-- Fin del script del widget de Zendesk -->
  <!--<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=2793c744-3610-4feb-a8d7-5ca8d13dfe49"> </script>-->
  <!-- CAPTCHA -->
  <script type="text/javascript">
    var onloadCallback = function() {
      if ( $('#captcha_element_login').length ) {
        grecaptcha.render('captcha_element_login', {
          'sitekey' : "{{ env('GOOGLE_RECAPTCHA_KEY') }}"
        });
      }
      if ( $('#captcha_element_register').length ) {
        grecaptcha.render('captcha_element_register', {
          'sitekey' : "{{ env('GOOGLE_RECAPTCHA_KEY') }}"
        });
      }
        

    };
  </script>

  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- HTTPS required. HTTP will give a 403 forbidden response -->
  <script src="https://sdk.accountkit.com/es_LA/sdk.js"></script>
  <script>
    // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId:"{{ env('FACEBOOK_CLIENT_ID') }}", 
        state:"{{ csrf_token() }}", 
        version:"v1.1",
        fbAppEventsEnabled:true,
        //redirect:"{{url('toolkit-login')}}"
      }
    );
  };
  </script>
  <script src="//fast.appcues.com/56570.js"></script>
</head>
<body>
<style>
    .modal-loading {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 ) 
                    url('/images/gifs/gif-uhomie.gif') 
                    50% 50% 
                    no-repeat;
    }
    /* When the body has the loading class, we turn
    the scrollbar off with overflow:hidden */
    body.loading .modal-loading {
        overflow: hidden;   
    }

    /* Anytime the body has the loading class, our
      modal element will be visible */
    body.loading .modal-loading {
        display: block;
    }
    
  </style>
  <div class="modal-loading" style="text-align: center;" id="mlmlml">Cargando</div>
  
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NBTLLJZ"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '482242902383290');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=482242902383290&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Facebook Pixel Code -->

  <script>
      window['_fs_debug'] = false;
      window['_fs_host'] = 'fullstory.com';
      window['_fs_script'] = 'fullstory.com/s/fs.js';
      window['_fs_org'] = 'PQBJZ';
      window['_fs_namespace'] = 'FS';
      (function(m,n,e,t,l,o,g,y){
          if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
          g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
          o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
          y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
          g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
          g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
          g.log = function(a,b) { g("log", [a,b]) };
          g.consent=function(a){g("consent",!arguments.length||a)};
          g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
          g.clearUserCookie=function(){};
      })(window,document,window['_fs_namespace'],'script','user');
  </script>
  
  @section('header')
    @include('layouts.header', ['isSolid' => false])
  @show

  @yield('content')

  @section('footer')
    @include('layouts.footer')
  @show
  
  @if (!Auth::user())
    @include('components.users.auth.login-modal')
    @include('components.users.auth.register-modal')
    <script src="{{ asset('js/auth.js') }}"></script>
    {{-- <script src='https://www.google.com/recaptcha/api.js'></script> --}}
    {{-- @include('components.contact.form-modal') --}}
  @elseif (Auth::user() && !Auth::user()->mail_verified)
    @include('components.users.verification.email.modal')
  @elseif(Auth::user() && !Auth::user()->phone_verified)
    {{-- @include('components.users.verification.phone.modal') --}}
  @elseif(Auth::user() && Auth::user()->phone_verified && Auth::user()->mail_verified)
    @php
      cookie('test', "dsfsdfsf",-1)
    @endphp
    @include('components.users.profile.profile-modal')
  @endif

  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
  {{-- <div id="google_translate_element2" style="display:none;"></div>
  <script type="text/javascript">
    function googleTranslateElementInit2() {
      new google.translate.TranslateElement({pageLanguage: 'es', autoDisplay: false}, 'google_translate_element2');
    }
  </script>
 
  <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script> --}}
  <script>
      function preloadImage(url)
      {
          var img=new Image();
          img.src=url;
      }
      preloadImage("/images/gifs/gif-uhomie.gif");
  </script>
  @if (!Auth::user())
    {{-- @include('components.users.register.js') --}}
    {{-- @include('components.contact.js') --}}
  @elseif (Auth::user() && !Auth::user()->mail_verified)
    {{-- @include('components.users.verification.email.js') --}}
  @elseif (Auth::user() && !Auth::user()->phone_verified)
    {{-- @include('components.users.verification.phone.js') --}}
  @endif
  @if (Auth::user())
  <div id='invtrflfloatbtn'></div>
  <script>    
  var invite_referrals = window.invite_referrals || {}; (function() { 
      invite_referrals.auth = { bid_e :'3D2338A56AD87CBF588CAE9F52848C63',
                                  bid : '28321', 
                                  t : '420', 
                                  email : '{{Auth::user()->email}}', userParams : {'fname': '{{Auth::user()->firstname}}', 'lname': '{{Auth::user()->lastname}}'}};    
                                  invite_referrals.async = false;
  var script = document.createElement('script');
  script.src = "//cdn.invitereferrals.com/js/invite-referrals-1.0.js";
  var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
  </script>
  @else
  <div id='invtrflfloatbtn'></div>
  <script>    
  var invite_referrals = window.invite_referrals || {}; (function() { 
      invite_referrals.auth = { bid_e :'3D2338A56AD87CBF588CAE9F52848C63',
                                  bid : '28321', 
                                  t : '420', 
                                  email : '', userParams : {'fname': ''}};    
                                  invite_referrals.async = false;
  var script = document.createElement('script');
  script.src = "//cdn.invitereferrals.com/js/invite-referrals-1.0.js";
  var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
  </script>
  @endif
  @if (session('referido_flag'))
  <img style="position:absolute; visibility:hidden" src="https://www.ref-r.com/campaign/t1/settings?bid_e=3D2338A56AD87CBF588CAE9F52848C63&bid=28321&t=420&event=register&email={{Auth::user()->email}}&orderID={{Auth::user()->email}}&fname={{Auth::user()->firstname}}&lname={{Auth::user()->lastname}}" />
  @endif
  @if( request()->query('utm_content') )
  <script>
  $(document).ready(function(){
      $('#registrarButton')[0].click();
    });
  </script>
  @endif
  @yield('flow-scripts')
  @yield('scripts')

  <script>
  function termyCond(){
    $.ajax({
      url: `pdf/Terminos-y-Condiciones-UHOMIE.pdf`,
      method: 'GET',
      xhrFields: {
        responseType: 'blob'
      },
      success: function (data) {
        var a = document.createElement('a');
        var url = window.URL.createObjectURL(data);
        a.href = url;
        a.download = `Terminos y Condiciones UHOMIE.pdf`;
        a.click();
        window.URL.revokeObjectURL(url);
      }
    });
  }
  </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
  <script>
    //Typeahead del buscar utiliza la libreria de arriba.
    
    var path = "{{ route('explore.autocomplete') }}";
    $('input.typeahead').typeahead({
      hint: true,
      minLength: 1,
      displayKey: 'name',
      items: 'all',
      autoSelect: true,
      dupChecker: true,
      item: '<li class="js-city-sel"><a class="dropdown-item" href="#" role="option"></a></li>',
      source:  function (query, process) {
        $.get(path, { query: query }, function (data) {
          return process(data);
        })
      },
      updater: function(item) {
        if(item.citie_id) {
          $('.search-city-id').val(item.citie_id)
        }
        if(item.commune_id) {
          $('.search-commune-id').val(item.commune_id)
        }
      },
      afterSelect: function() {
        $('#searchBar').submit();
      }
    });
    
  </script>
  @if ($errors->any())
  <script>
    $(document).ready(function(){
      $('#ingresarButton')[0].click();
    });
    
    @foreach ($errors->all() as $error)
      toastr['error']('{{$error}}');
    @endforeach
    
    

  </script>    
  @endif
  @if( Session::has('error_toolkit') )
  <script>
    toastr['error']("{{ Session::get('error_toolkit') }}");
  </script>
  @endif
  @if( session('register') )
  <script>
    $(document).ready(function(){
      $('#registrarButton')[0].click();
    });
    toastr['info']('Continue el proceso de registro');
  </script>
  @endif
</body>
</html>