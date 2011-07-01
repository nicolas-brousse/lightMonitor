{% extends "layouts/default.html.php" %}

{% block title %}Serveur 2{% endblock %}

{% block content %}
<div class="clear"></div> <!-- End .clear -->

<div class="content-box column-left"><!-- Start Content Box -->
  <div class="content-box-header">
    <h3>Logiciels</h3>
    <div class="clear"></div>
  </div> <!-- End .content-box-header -->

  <div class="content-box-content">

    <table>
      <thead>
        <tr>
          <th>État</th>
          <th>Logiciel</th>
          <th>Depuis</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td><img src="{{ app.request.getBaseUrl() }}/img/icons/on.png" alt="off" /></td>
          <td>Ping</td>
          <td>10 jours</td>
        </tr>

        <tr>
          <td><img src="{{ app.request.getBaseUrl() }}/img/icons/off.png" alt="off" /></td>
          <td>FTP</td>
          <td>5 minutes</td>
        </tr>
      </tbody>

    </table>
  </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->


<div class="clear"></div>

<div class="content-box"><!-- Start Content Box -->
  <div class="content-box-header">
    <h3>Graphiques</h3>
    <div class="clear"></div>
  </div> <!-- End .content-box-header -->

  <div class="content-box-content">
    <h4>Charge</h4>
    <img src="{{ app.request.getBaseUrl() }}/graphs/serveur-2/uptime-0.png" alt="">
    <hr />
    <h4>Mémoire RAM</h4>
    <img src="{{ app.request.getBaseUrl() }}/graphs/serveur-2/mem-0.png">
  </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
{% endblock %}