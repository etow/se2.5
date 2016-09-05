function RenderProductos(source, destino, listaProductos){
	var source   = source;
	var template = Handlebars.compile(source);
	var html    = template({producto: listaProductos});

	console.log(listaProductos);

	$(destino).html(html);
	$('.Loop-update').click(function(e){
      e.preventDefault();
      var el = $(this);
      var id_articulo = $(this).attr('data-id_articulo');
      var cantidad = $(this).prev().val();
      var sumar = $(this).attr('data-sumar');
      Cart.actualizar(id_articulo,cantidad,sumar,el);
   });
}

var TemplateProducto = ["{{#each producto}}\
		<div class='Loop colSe' id='{{id_articulo}}'>\
			<figure>\
				<a href='{{linkProducto}}' class='Loop-imagen-wrapper'>\
					<span class='Loop-imagen' style='background-image:url({{foto_file}}'>\
						<img src='{{foto_file}}' alt=''>\
					</span>\
				</a>\
				<figcaption>\
					{{#if nombre}}\
						<h3 class='Loop-nombre'>\
							<a href='{{linkProducto}}'>\
								{{nombre}}\
							</a>\
						</h3>\
					{{/if}}\
					{{#if descripcion}}\
						<p class='Loop-desc'>\
								{{descripcion}}\
						</p>\
					{{/if}}\
					<div class='Loop-precio-compra-wrapper'>\
					{{#if precio}}\
						<p class='Loop-precio'>\
								<span>{{moneda}}</span>{{precio}}\
						</p>\
					{{/if}}\
						<div class='Loop-compra Producto-compra'>\
							<ul class='u-flexRow'>\
								<li>\
									<input min='0' name='cantidad' value='1' class='Producto-cantidad' type='number'>\
									<button class='Producto-add btn btn-default Loop-update' data-id_articulo='{{id_articulo}}' data-sumar='true' type='submit'>{{#if_carrito}}<i class='fa fa-plus'></i>{{else}} Cotizar {{/if_carrito}} </button>\
								</li>\
							</ul>\
						</div>\
					</div>\
				</figcaption>\
			</figure>\
		</div>\
{{/each}}"];
