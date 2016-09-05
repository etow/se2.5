var SeColores = {
	'_001':	{
		paleta: '_001',
		keys: 'naranja verde azul',
		hex:{
			primario:'#1b374c',
			claro:'#ffffff',
			oscuro:'#152C3D',
			acento1: '#ef6043',
			acento2: '#108896',
			callTo:'#152C3D'
		}
	},
	'_002': {
		paleta: '_002',
		keys: 'rosa azul verde',
		hex:{
			primario:'#262d2e',
			claro:'#ffffff',
			oscuro:'#1D2324',
			acento1: '#e83f6f',
			acento2: '#2274a5',
			callTo:'#32936f'
		}
	},
	'_003':	{
		paleta: '_003',
		keys: 'azul violeta rosa',
		hex:{
			primario:'#31384d',
			claro:'#ffffff',
			oscuro:'#1a1e2d',
			acento1: '#3bc2df',
			acento2: '#564dad',
			callTo:'#f35790'
		}
	},
	'_004':	{
		paleta: '_004',
		keys: 'azul marron amarillo',
		hex:{
			primario:'#072942',
			claro:'#ffffff',
			oscuro:'#051E31',
			acento1: '#00a8e8',
			acento2: '#987862',
			callTo:'#fbb351'
		}
	},
	'_005':	{
		paleta: '_005',
		keys: 'rosa violeta',
		hex:{
			primario:'#262d2e',
			claro:'#ffffff',
			oscuro:'#1D2324',
			acento1: '#d32c45',
			acento2: '#742e46',
			callTo:'#2d2c41'
		}
	},
	'amarillo_001':	{
		paleta: 'amarillo_001',
		keys: 'amarillo naranja azul',
		hex:{
			primario:'#363a3a',
			claro:'#ffffff',
			oscuro:'#272a2a',
			acento1: '#e9b934',
			acento2: '#e78028',
			callTo:'#41b1d1'
		}
	},
	'amarillo_002':	{
		paleta: 'amarillo_002',
		keys: 'amarillo naranja',
		hex:{
			primario : '#363735',
			claro : '#ffffff',
			oscuro : '#272826',
			acento1 : '#f09806',
			acento2 : '#f06f06',
			callTo : '#dd4213'
		}
	},
	'azul_001':	{
		paleta: 'azul_001',
		keys: 'azul',
		hex:{
			primario:'#061024',
			claro:'#ffffff',
			oscuro:'#343838',
			acento1: '#1d53bf',
			acento2: '#2989ff',
			callTo:'#133780'
		}
	},
	'azul_002':	{
		paleta: 'azul_002',
		keys: 'azul violeta',
		hex:{
			primario:'#343838',
			claro:'#ffffff',
			oscuro:'#061024',
			acento1: '#32b0ab',
			acento2: '#34A1DE',
			callTo:'#66667f'
		}
	},
	'gris_001':	{
		paleta: 'gris_001',
		keys: 'negro gris verde naranja',
		hex:{
			primario:'#333533',
			claro:'#ffffff',
			oscuro:'#282A28',
			acento1: '#92988A',
			acento2: '#58A4B0',
			callTo:'#D8805A'
		}
	},
	'marron_001':	{
		paleta: 'marron_001',
		keys: 'marron verde',
		hex:{
			primario:'#333233',
			claro:'#ffffff',
			oscuro:'#282728',
			acento1: '#72595b',
			acento2: '#ab6559',
			callTo:'#507172'
		}
	},
	'naranja_001':	{
		paleta: 'naranja_001',
		keys: 'naranja verde',
		hex:{
			primario:'#303b33',
			claro:'#ffffff',
			oscuro:'#263029',
			acento1: '#f9984c',
			acento2: '#f1785c',
			callTo:'#899f5f'
		}
	},
	'naranja_002':	{
		paleta: 'naranja_002',
		keys: 'naranja amarillo rojo marron',
		hex:{
			primario:'#353535',
			claro:'#ffffff',
			oscuro:'#2A2A2A',
			acento1: '#bf3100',
			acento2: '#ec9f05',
			callTo:'#5F5448'
		}
	},
	'rojo_001':	{
		paleta: 'rojo_001',
		keys: 'rojo azul marron',
		hex:{
			primario:'#272a2a',
			claro:'#ffffff',
			oscuro:'#1e2020',
			acento1: '#e22c23',
			acento2: '#529fdc',
			callTo:'#8C6752'
		}
	},
	'rojo_002':	{
		paleta: 'rojo_002',
		keys: 'rojo azul',
		hex:{
			primario:'#2c3e50',
			claro:'#ffffff',
			oscuro:'#19242e',
			acento1: '#e74c3c',
			acento2: '#3498db',
			callTo:'#0c64b5'
		}
	},
	'verde_001':	{
		paleta: 'verde_001',
		keys: 'verde marron',
		hex:{
			primario:'#373734',
			claro:'#ffffff',
			oscuro:'#5f5448',
			acento1: '#a8c545',
			acento2: '#6b5c49',
			callTo:'#dfd3b4'
		}
	},
	'verde_002':	{
		paleta: 'verde_002',
		keys: 'verde amarillo',
		hex:{
			primario : '#3c3f3c',
			claro : '#ffffff',
			oscuro : '#3C3F3C',
			acento1 : '#81c148',
			acento2 : '#ffc243',
			callTo : '#ef707d'
		}
	},
	'violeta_001':	{
		paleta: 'violeta_001',
		keys: 'violeta amarillo',
		hex:{
			primario : '#353641',
			claro : '#ffffff',
			oscuro : '#414258',
			acento1 : '#81C148',
			acento2 : '#9f98a5',
			callTo : '#ddae50'
		}
	}


};

SePaletaActual = "";
SeCurrentFilter = "all";

$(document).ready(function(){
	
	filterByColor();

	$('[data-action="filtrar"]').click(function(e){
		filterByColor($(this).attr("data-var"));
	});


	function filterByColor (filtro){

		var result ={};

		if(filtro){

			$.each(SeColores, function(paleta,obj){
				
				if(obj.keys.indexOf(filtro) != -1){
					result[paleta] = obj;
				}

			});
			generarPaletas(result);
		}else{
			generarPaletas();
		}
		
		
	}

	function generarPaletas (paletas){

		var Colores = paletas || SeColores;

		var TemplateColores = "";

		$.each(Colores, function(i, e){
			var Matiz = '<span class="Matiz" style="background:'+e.hex.acento1+';"></span><span class="Matiz" style="background:'+e.hex.acento2+';"></span><span class="Matiz" style="background:'+e.hex.callTo+';"></span>';
			var color = '<a href="" class="ConfigCheckbox p_'+e.paleta+'" data-action="paleta" data-var="'+e.paleta+'">'+Matiz+'</a>';
			TemplateColores += color;
		});

		$('#colores').html(TemplateColores);
		$('#colores a').click(function(e){
			  e.preventDefault();
			  var $el = $(this);

			  var paleta = $el.attr("data-var");
		 
			  $el.siblings().removeClass('is-active');
				$el.addClass('is-active');

			  var src = $('#PaletaDeColor').attr('href');
			  var href = src.split('colores/');
			  var option = SeColores[paleta].paleta;
			  
			  $('#PaletaDeColor').attr('href',href[0]+'colores/'+ option +'.css').attr('data-color',option);
			  
			  ActualizarPaletaActual();
		});



	}

});




