function inputbg(id){
	$('#'+id).css('background', '#fec');
	setTimeout("$('#"+id+"').css('background', '#fff');", 500);
}

function copyToList(o1, o2){
	var s=0;
	var m=o2.find('option');
	if(m.length>0){
		m.each(function(){
			if($(this).is(':selected')){
				o1.append('<option value="'+$(this).attr('value')+'">'+$(this).html()+'</option>');
				$(this).remove();
				s++;
			}
		});
	}
	if(s==0)alert('请至少选择一项内容');
}

function seAll(o){
	var m=o.find('option');
	if(m.length>0){
		m.each(function(){
			$(this).attr('selected', 'selected');
		});
	}
}

function add_tw(){
	var c=parseInt($('#twc').val());
	$('#tw_info').append('<div class="tw_div" id="tw_'+c+'"><table><tr><td>标题：</td><td><input size="100" class="set_input" name="pic_title_'+c+'" />*</td></tr><tr><td>图片：</td><td><input size="50" class="set_input" name="pic_pic_'+c+'" /> 或 <input type="file" name="pic_file_'+c+'">*</td></tr><tr><td>链接：</td><td><input size="100" class="set_input" name="pic_url_'+c+'" />*</td></tr><tr class="tw_js" style="display: none;"><td valign="top">描述：</td><td><textarea name="pic_content_'+c+'" cols="82" rows="8"></textarea></td></tr></table><div class="tw_dlink"><a href="#" onclick="del_tw(\''+c+'\');return false;">删除</a></div></div>');
	$('.tw_dlink').show();
	$('.tw_js').hide();
	c++;
	$('#twc').val(c);
}

function del_tw(id){
	$('#tw_'+id).remove();
	if($('.tw_div').length<=1){
		$('.tw_dlink').hide();
		if($('.tw_js').length>0)$('.tw_js').show();
	}
}

function add_hsz(){
	var c=parseInt($('#hszc').val());
	$('#hsz_info').append('<div class="tw_div" id="tw_'+c+'"><table><tr><td>图片：</td><td><input size="50" class="set_input" name="hsz_pic_'+c+'" /> 或 <input type="file" name="hsz_file_'+c+'">*</td></tr><tr><td>说明：</td><td><input size="100" class="set_input" name="hsz_info_'+c+'" /></td></tr></table><div class="tw_dlink"><a href="#" onclick="del_tw(\''+c+'\');return false;">删除</a></div></div>');
	$('.tw_dlink').show();
	c++;
	$('#hszc').val(c);
}

function add_logo(){
	var c=parseInt($('#logo_c').val());
	$('#logo_v').append('<div class="logo_v" id="logo_v_'+c+'"><input size="50" class="set_input" name="pic_pic_'+c+'" /> 或 <input type="file" name="pic_file_'+c+'"><br/>链接：<input class="set_input" name="logo_link_'+c+'" size="80" /> <a href="#" onclick="$(\'#logo_v_'+c+'\').remove();return false;">删除</a></div>');
	c++;
	$('#logo_c').val(c);
}

function add_qjt(){
	var c=parseInt($('#qjt_c').val());
	$('#qjt_v').append('<div class="logo_v" id="qjt_v_'+c+'"><input size="50" class="set_input" name="qjt_pic_'+c+'" /> 或 <input type="file" name="qjt_file_'+c+'"><br/>说明：<input class="set_input" name="qjt_c_'+c+'" size="80" /> <a href="#" onclick="$(\'#qjt_v_'+c+'\').remove();return false;">删除</a></div>');
	c++;
	$('#qjt_c').val(c);
}

function add_xc(){
	var c=parseInt($('#xc_c').val());
	$('#xc_v').append('<div class="logo_v" id="xc_v_'+c+'"><input size="50" class="set_input" name="xc_pic_'+c+'" /> 或 <input type="file" name="xc_file_'+c+'"><br/><input type="radio" name="xc_fm" value="'+c+'"/>设置为封面 <a href="#" onclick="$(\'#xc_v_'+c+'\').remove();return false;">删除</a></div>');
	c++;
	$('#xc_c').val(c);
}

function add_vote(){
	var c=parseInt($('#xx_c').val());
	$('#vote_table').append('<tr id="vote_td_'+c+'"><td><input name="xx_k_'+c+'" size="50" class="set_input"/></td><td><a href="#" onclick="$(\'#vote_td_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_vote_text(){
	var c=parseInt($('#xx_c').val());
	$('#vote_table').append('<tr id="vote_td_'+c+'"><td><input name="xx_k_'+c+'" size="10" class="set_input"/></td><td><input name="xx_v_'+c+'" size="50" class="set_input"/></td><td><input name="xx_c_'+c+'" size="5" class="set_input"/></td><td><a href="#" onclick="$(\'#vote_td_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_vote_img(){
	var c=parseInt($('#xx_c').val());
	var id='twxx_'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#vote_table').append('<tr id="vote_td_'+c+'"><td><input type="hidden" name="xx_id_'+c+'" value="'+id+'"/><input name="xx_v_'+c+'" size="50" class="set_input"/></td><td><input name="xx_i_'+c+'" size="50" class="set_input"/><br/>或<input type="file" name="xx_f_'+c+'"></td><td><input name="xx_c_'+c+'" size="5" class="set_input"/></td><td><a href="#" onclick="$(\'#vote_td_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_da(){
	var c=parseInt($('#xx_c').val());
	var id='twxx_'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#vote_table').append('<tr id="vote_td_'+c+'"><td><input type="hidden" name="xx_id_'+c+'" value="'+id+'"/><input name="xx_v_'+c+'" size="50" class="set_input"/></td><td><input name="xx_i_'+c+'" size="50" class="set_input"/><br/>或<input type="file" name="xx_f_'+c+'"></td><td><input type="radio" name="zq" value="'+c+'"/></td><td><a href="#" onclick="$(\'#vote_td_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function addtext(){
	var c=parseInt($('#text_c').val());
	var id='text'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#zc_text').append('<tr id="h_text'+c+'"><td><input type="hidden" name="xx_textid_'+c+'" value="'+id+'">单行文字</td><td><input size="20" class="set_input" name="xx_text_'+c+'" /></td><td>-</td><td><a href="#" onclick="$(\'#h_text'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#text_c').val(c);
}

function addse(){
	var c=parseInt($('#se_c').val());
	var id='se'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#zc_se').append('<tr id="h_se'+c+'"><td><input type="hidden" name="xx_seid_'+c+'" value="'+id+'">下拉选项</td><td><input size="20" class="set_input" name="xx_se_'+c+'" /></td><td><input size="50" class="set_input" name="xx_seo_'+c+'" /></td><td><a href="#" onclick="$(\'#h_se'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#se_c').val(c);
}

function add_jx(){
	var c=parseInt($('#xx_c').val());
	var id='jx_'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#vote_table').append('<tr id="h_'+c+'"><td><input type="hidden" name="xx_id_'+c+'" value="'+id+'"><input name="xx_n_'+c+'" size="10" class="set_input"/></td><td><input name="xx_v_'+c+'" size="50" class="set_input"/></td><td><input name="xx_c_'+c+'" size="5" class="set_input"/></td><td><a href="#" onclick="$(\'#h_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_yhj(){
	var c=parseInt($('#xx_c').val());
	var id='yhj_'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#vote_table').append('<tr id="h_'+c+'"><td><input type="hidden" name="xx_id_'+c+'" value="'+id+'"><input name="xx_n_'+c+'" size="50" class="set_input"/></td><td><input name="xx_c_'+c+'" size="5" class="set_input"/></td><td><input name="xx_k_'+c+'" size="5" class="set_input"/></td><td><a href="#" onclick="$(\'#h_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_yzjx(){
	var c=parseInt($('#xx_c').val());
	var id='jx_'+c+'_'+Math.random();
	id=id.replace('.', '');
	$('#vote_table').append('<tr id="h_'+c+'"><td><input type="hidden" name="xx_id_'+c+'" value="'+id+'"><input name="xx_n_'+c+'" size="10" class="set_input"/></td><td><input name="xx_c_'+c+'" size="5" class="set_input"/></td><td><input name="xx_v_'+c+'" size="50" class="set_input"/></td><td><a href="#" onclick="$(\'#h_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#xx_c').val(c);
}

function add_sub(id){
	var c=parseInt($('#csb_'+id).val());
	var kid='sub_'+id+'_'+Math.random();
	kid=kid.replace('.', '');
	$('#m_'+id).append('<tr id="t_'+id+'_'+c+'"><td>　└<input class="set_input" size="2" value="0" name="o_'+id+'_'+c+'"/><input type="hidden" class="set_input" value="'+kid+'" name="k_'+id+'_'+c+'"/></td><td><input class="set_input" size="50" name="n_'+id+'_'+c+'"/></td><td><input class="set_input" size="50" name="m_'+id+'_'+c+'"/></td><td><a href="#" onclick="$(\'#t_'+id+'_'+c+'\').remove();return false;">删除</a></td></tr>');
	c++;
	$('#csb_'+id).val(c);
}

function moveP(id){
	if($('#tr_'+id).prev().length>0 && $('#tr_'+id).prev().data('m')=='td'){
		var cx=$('#xid_'+id).val();
		var pid=$('#tr_'+id).prev().data('id');
		var px=$('#xid_'+pid).val();
		$('#tr_'+id).after($('#tr_'+pid));
		$('#xid_'+id).val(px);
		$('#xid_'+pid).val(cx);
		moveC();
	}
}

function moveD(id){
	if($('#tr_'+id).next().length>0 && $('#tr_'+id).next().data('m')=='td'){
		var cx=$('#xid_'+id).val();
		var pid=$('#tr_'+id).next().data('id');
		var px=$('#xid_'+pid).val();
		$('#tr_'+pid).after($('#tr_'+id));
		$('#xid_'+id).val(px);
		$('#xid_'+pid).val(cx);
		moveC();
	}
}

function moveC(){
	var i=0;
	$('tr').each(function(){
		if($(this).data('m')=='td'){
			$(this).removeClass();
			if((i%2)>0)$(this).addClass('tdline_1');
			i++;
		}
	});
}
