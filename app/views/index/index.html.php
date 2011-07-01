{% extends "layouts/default.html.php" %}

{% block title %}Dashboard{% endblock %}

{% block content %}
<div class="clear"></div> <!-- End .clear -->

<div class="content-box"><!-- Start Content Box -->
  <div class="content-box-header">
    <h3>Serveurs</h3>

    <ul class="content-box-tabs">
      <li><a href="#listing" class="default-tab">Liste</a></li> <!-- href must be unique and match the id of target div -->
      <li><a href="#historic">Historique</a></li>
    </ul>
    <div class="clear"></div>
  </div> <!-- End .content-box-header -->

  <div class="content-box-content">
    <div class="tab-content default-tab" id="listing"> <!-- This is the target div. id must match the href of this div's tab -->

      {{ flash_messenger }}

      <table>
        <thead>
          <tr>
            <th>Host</th>
            <th>IP</th>
            <th>Status</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <td colspan="6">
              <div class="pagination">
                <a href="#" title="First Page">&laquo; First</a><a href="#" title="Previous Page">&laquo; Previous</a>
                <a href="#" class="number" title="1">1</a>
                <a href="#" class="number" title="2">2</a>
                <a href="#" class="number current" title="3">3</a>
                <a href="#" class="number" title="4">4</a>
                <a href="#" title="Next Page">Next &raquo;</a><a href="#" title="Last Page">Last &raquo;</a>
              </div> <!-- End .pagination -->
              <div class="clear"></div>
            </td>
          </tr>
        </tfoot>

        <tbody>
          <tr>
            <td><a href="#" title="title">Serveur 1</a></td>
            <td>10.0.0.1</td>
            <td><img src="{{ app.request.getBaseUrl() }}/img/icons/on.png" alt="on" /></td>
          </tr>

          <tr>
            <td><a href="{# app.url_generator.generate('servers') servername: 'serveur-2' #}{{ app.request.getBaseUrl() }}/servers/serveur-2" title="title">Serveur 2</a></td>
            <td>10.0.0.2</td>
            <td><img src="{{ app.request.getBaseUrl() }}/img/icons/off.png" alt="off" /></td>
          </tr>
        </tbody>

      </table>

    </div> <!-- End #tab1 -->

    <div class="tab-content" id="historic">
      <p>Aucun problème</p>
    </div> <!-- End #tab2 -->        

  </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
{% endblock %}