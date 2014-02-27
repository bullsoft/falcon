<div class="navigation" id="navigation">
	<ul class="navigation-ul">
          <?php
          if(empty($_GET['category'])) {
              $_GET['category'] = "index";
          }
          ?>
          {% for cat in categories %}
              {% if cat.ck == request.getQuery('category') %}
	      <li class="current">
              {% else %}
              <li>
              {% endif %}
		<span class="nav-icon icon-{{cat.ck}}"> </span>
		<a href="{{url('?category=')}}{{cat.ck}}">{{cat.name}}</a>
	      </li>
          {% endfor %}
	</ul>
</div>
