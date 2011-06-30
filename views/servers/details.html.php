{% extends "layouts/default.html.php" %}

{% block title %}Serveur 2{% endblock %}

{% block content %}
<div class="clear"></div> <!-- End .clear -->

<div class="content-box"><!-- Start Content Box -->
  <div class="content-box-header">
    <h3>Serveur 2</h3>
    <div class="clear"></div>
  </div> <!-- End .content-box-header -->

  <div class="content-box-content">

    {{ flash_messenger }}

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
          <td>Ok</td>
          <td>Ping</td>
          <td>10 jours</td>
        </tr>

        <tr>
          <td>erreur</td>
          <td>FTP</td>
          <td>5 minutes</td>
        </tr>
      </tbody>

    </table>


  </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
{% endblock %}