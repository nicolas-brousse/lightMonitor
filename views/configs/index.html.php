{% extends "layouts/default.html.php" %}

{% block title %}Configurations{% endblock %}

{% block content %}
<div class="clear"></div> <!-- End .clear -->

<div class="content-box"><!-- Start Content Box -->
  <div class="content-box-header">
    <h3>Liste des serveurs à monitorer</h3>
    <div class="clear"></div>
  </div> <!-- End .content-box-header -->


  <div class="content-box-content">

    {{ flash_messenger }}

    <table>

      <thead>
        <tr>
          <th>Host</th>
          <th>IP</th>
          <th>Protocole</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>Serveur 1</td>
          <td>10.0.0.1</td>
          <td>SNMP</td>
        </tr>

        <tr>
          <td>Serveur 2</td>
          <td>10.0.0.2</td>
          <td>SSH</td>
        </tr>
      </tbody>

    </table>


  </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
{% endblock %}