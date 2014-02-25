<div class="navigation" id="navigation">
	<ul class="navigation-ul">
          <?php
          if(empty($_GET['catid'])) {
              $_GET['catid'] = 1;
          }
          ?>
          {% for cat in categories %}
              {% if cat.id == request.getQuery('catid') %}
	      <li class="current">
              {% else %}
              <li>
              {% endif %}
		<span class="nav-icon icon-{{cat.ck}}"> </span>
		<a href="#">{{cat.name}}</a>
	      </li>
          {% endfor %}
	</ul>
</div>
