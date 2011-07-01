<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>{% block title %}Home{% endblock %} &bull; LightMonitor</title>
    <!--                       CSS                       -->
    <!-- Reset Stylesheet -->
    <link rel="stylesheet" href="{{ app.request.getBaseUrl() }}/css/reset.css" type="text/css" media="screen" />
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ app.request.getBaseUrl() }}/css/style.css" type="text/css" media="screen" />
    <!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
    <link rel="stylesheet" href="{{ app.request.getBaseUrl() }}/css/invalid.css" type="text/css" media="screen" />	

    <!-- Internet Explorer Fixes Stylesheet -->
    <!--[if lte IE 7]>
      <link rel="stylesheet" href="public/css/ie.css" type="text/css" media="screen" />
    <![endif]-->

    <!--                       Javascripts                       -->

    <script type="text/javascript">
      BASE_URL = '{{ app.request.getBaseUrl() }}';
    </script>
    <!-- jQuery -->
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/simpla.jquery.configuration.js"></script>
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/facebox.js"></script>
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/jquery.datePicker.js"></script>
    <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/jquery.date.js"></script>
    <!--[if IE]><script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/jquery.bgiframe.js"></script><![endif]-->

    <!-- Internet Explorer .png-fix -->

    <!--[if IE 6]>
      <script type="text/javascript" src="{{ app.request.getBaseUrl() }}/js/DD_belatedPNG_0.0.7a.js"></script>
      <script type="text/javascript">
        DD_belatedPNG.fix('.png_bg, img, li');
      </script>
    <![endif]-->

  </head>

  <body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

    <div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
      <h1 id="sidebar-title"><a href="{{ app.url_generator.generate('homepage') }}">Simpla Admin</a></h1>
      <a href="{{ app.url_generator.generate('homepage') }}"><img id="logo" src="{{ app.request.getBaseUrl() }}/img/logo.png" alt="Simpla Admin logo" /></a>


      <ul id="main-nav">  <!-- Accordion Menu -->
        <li>
          <a href="{{ app.url_generator.generate('homepage') }}" class="nav-top-item no-submenu{% if app.request.attributes.get('_route') == 'homepage' %} current{% endif %}"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
            Dashboard
          </a>
        </li>

        <li> 
          <a href="#" class="nav-top-item{% if app.request.attributes.get('_route') == 'servers' %} current{% endif %}">
            Serveurs
          </a>
          <ul>
            <li><a href="{# app.url_generator.generate('servers') servername: 'serveur-1' #}">Serveur 1</a></li>
            <li><a class="{% if app.request.attributes.get('servername') == 'serveur-2' %}current{% endif %}" href="{# app.url_generator.generate('servers') servername: 'serveur-2' #}{{ app.request.getBaseUrl() }}/servers/serveur-2">Serveur 2</a></li>
          </ul>
        </li>

        <li>
          <a href="{{ app.url_generator.generate('configs') }}" class="nav-top-item no-submenu{% if app.request.attributes.get('_route') == 'configs' %} current{% endif %}">
            Configurations
          </a>
        </li> 
      </ul> <!-- End #main-nav -->

    </div></div> <!-- End #sidebar -->

    <div id="main-content"> <!-- Main Content Section with everything -->

      <noscript> <!-- Show a notification if the user has disabled javascript -->
        <div class="notification error png_bg">
          <div>
            Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
            Download From <a href="http://www.exet.tk">exet.tk</a></div>
        </div>
      </noscript>

      <!-- Page Head -->
      <h2>{{ block('title') }}</h2>
      {% if page_intro %}
        <p id="page-intro">{{ page_intro }}</p>
      {% endif %}

      {{ flash_messenger }}

      {% block content %}{% endblock %}

      <div class="clear"></div>

      <div id="footer">
        <small> <!-- Remove this notice or replace it with whatever you want -->
          &#169; Copyright 2011 <a href="https://github.com/nicolas-brousse/lightMonitor">Nicolas BROUSSE</a> | Powered by <a href="http://themeforest.net/item/simpla-admin-flexible-user-friendly-admin-skin/46073">Simpla Admin</a> | <a href="#">Top</a>
        </small>
      </div><!-- End #footer -->

    </div> <!-- End #main-content -->

  </div></body>

</html>
