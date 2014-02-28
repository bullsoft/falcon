<div class="hot-goods">
  <div class="hd">
    推荐商品
  </div>
  <ul class="hot-goods-list clearfix">
    {% for other in other_products%}
    <li class="">
      <a href="{{url('goods/detail-')}}{{other.id}}.html"><img class="goods-img" src="{{other.image_url}}"></a>
    </li>
    {% endfor %}
  </ul>
</div>
