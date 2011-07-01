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
          <th><input class="check-all" type="checkbox" /></th>
          <th>Host</th>
          <th>IP</th>
          <th>Protocole</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <td colspan="6">
            <div class="bulk-actions align-left">
              <select name="dropdown">
<option value="option1">Choose an action...</option>
                <option value="option2">Edit</option>
                <option value="option3">Delete</option>
              </select>
              <a class="button" href="#">Apply to selected</a>
            </div>

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
        {% for server in servers %}
          <tr>
            <td><input type="checkbox" /></td>
            <td>{{ server.servername|e }}</td>
            <td>{{ server.ip|e }}</td>
            <td>{{ server.protocol|e }}</td>
            <td>
               <!-- Icons -->
               <a href="#" title="Edit"><img src="{{ app.request.getBaseUrl() }}/img/icons/pencil.png" alt="Edit" /></a>
               <a href="#" title="Delete"><img src="{{ app.request.getBaseUrl() }}/img/icons/cross.png" alt="Delete" /></a>
             </td>
          </tr>
        {% endfor %}
      </tbody>

    </table>


  </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
{% endblock %}