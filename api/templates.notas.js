var TemplateNota = ['{{#each nota}}\
		<div class="Loop" id="{{id_nota}}">\
			<figure>\
				<a href="{{linkNota}}" class="Loop-imagen-wrapper">\
					<span class="Loop-imagen" style="background-image:url({{foto_file}}">\
						<img src="{{foto_file}}" alt="">\
					</span>\
				</a>\
				<figcaption>\
					{{#if titulo}}\
						<h3 class="Loop-titulo">\
							<a href="{{linkNota}}">\
								{{titulo}}\
							</a>\
						</h3>\
					{{/if}}\
					{{#if texto}}\
						<div class="Loop-desc">\
								{{texto}}\
						</div>\
					{{/if}}\
					<div class="Loop-button">\
						<a href="{{linkNota}}" class="btn btn-default">{{textoBotton}}</a>\
					</div>\
				</figcaption>\
			</figure>\
		</div>\
{{/each}}'];

function RenderNotas(source, destino, listaNotas){
	var template = Handlebars.compile(source);
	var html    = template({nota: listaNotas});
	$(destino).html(html);
}
