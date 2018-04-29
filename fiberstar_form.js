var $ = jQuery;

function resetSelect(select){
	select.children('option').each(function(index){
		if(index != 0) $(this).remove();
	});
}

function ajax_get_fiberstar_data(select, id_kota=null, id_kecamatan=null, id_kelurahan=null){
	var params = {
		'action' : 'fiberstar_get_data',
		'id_kota' : id_kota,
		'id_kecamatan' : id_kecamatan,
		'id_kelurahan' : id_kelurahan,
	}
	$.post(fiberstar_option.ajax_url, params, function(response) {
		var data = JSON.parse(response);
		resetSelect(select);
		$.each(data, function(key, val){
			select.append($("<option></option>")
                    .attr("value",val.id)
                    .text(val.name));
		});
	});
}

$(document).ready(function(){
	var select_kota = $('.fiberstar-form .fiberstar-form-kota');
	var select_kecamatan = $('.fiberstar-form .fiberstar-form-kecamatan');
	var select_kelurahan = $('.fiberstar-form .fiberstar-form-kelurahan');
	var select_jalan = $('.fiberstar-form .fiberstar-form-jalan');

	ajax_get_fiberstar_data( select_kota );

	select_kota.change(function(){
		ajax_get_fiberstar_data( select_kecamatan, $(this).val() );
	});

	select_kecamatan.change(function(){
		ajax_get_fiberstar_data( select_kelurahan, select_kota.val(), $(this).val() );
	});

	select_kelurahan.change(function(){
		ajax_get_fiberstar_data( select_jalan, select_kota.val(), select_kecamatan.val(), $(this).val() );
	});


});