<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<script type="text/javascript">
	function tPost() {
		if($(':radio[name="alipay[switch]"]:checked').val() == 'true') {
			if($.trim($(':text[name="alipay[account]"]').val()) == '') {
				message('必须输入收款支付宝账号.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="alipay[partner]"]').val()) == '') {
				message('必须输入合作者身份.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="alipay[secret]"]').val()) == '') {
				message('必须输入收款支付宝账号.', '', 'error');
				return false;
			}
			if(confirm('你可能需要修改您的浏览器 User-Agent 来模拟手机浏览才能正常测试, 请确认已经修改好.')) {
				$(':hidden[name="alipay[t]"]').val('true');
				$('#payform')[0].submit();
			}
		}
	}
	function validate() {
		if($(':radio[name="alipay[switch]"]:checked').val() == 'true') {
			if($.trim($(':text[name="alipay[account]"]').val()) == '') {
				message('必须输入收款支付宝账号.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="alipay[partner]"]').val()) == '') {
				message('必须输入合作者身份.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="alipay[secret]"]').val()) == '') {
				message('必须输入收款支付宝账号.', '', 'error');
				return false;
			}
		}
		$(':hidden[name="alipay[t]"]').val('');
		if($(':radio[name="wechat[switch]"]:checked').val() == 'true') {
			if($.trim($(':text[name="wechat[appid]"]').val()) == '') {
				message('必须输入身份标识.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="wechat[secret]"]').val()) == '') {
				message('必须输入身份密钥.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="wechat[signkey]"]').val()) == '') {
				message('必须输入通信密钥.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="wechat[partner]"]').val()) == '') {
				message('必须输入商户身份.', '', 'error');
				return false;
			}
			if($.trim($(':text[name="wechat[key]"]').val()) == '') {
				message('必须输入商户密钥.', '', 'error');
				return false;
			}
		}
		return true;
	}
	$(function(){
		$(':radio[name="alipay[switch]"]').click(function(){
			if(this.checked) {
				if($(this).val() == 'true') {
					$('.alipay-params').show();
				} else {
					$('.alipay-params').hide();
				}
			}
		});
		$(':radio[name="wechat[switch]"]').click(function(){
			if(this.checked) {
				if($(this).val() == 'true') {
					$('.wechat-params').show();
				} else {
					$('.wechat-params').hide();
				}
			}
		});
		$(':radio[name="offline[switch]"]').click(function(){
			if(this.checked) {
				if($(this).val() == 'true') {
					$('.offline-params').show();
				} else {
					$('.offline-params').hide();
				}
			}
		});
	});
</script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo create_url('account/post', array('id' => $id))?>"><i class="icon-edit"></i> 编辑公众号</a></li>
	<li class="active"><a href="<?php  echo create_url('account/payment', array('id' => $id))?>"><i class="icon-money"></i> 编辑支付选项</a></li>
	<li><a href="<?php  echo create_url('account/display')?>">管理公众号</a></li>
</ul>
<div class="main">
	<form id="payform" action="" method="post" class="form-horizontal form" onsubmit="return validate();">
		<h4>设置线下汇款支付开关</h4>
		<table class="tb">
			<tr>
				<th>汇款支付</th>
				<td>
					<label class="radio">
						<input type="radio" name="offline[switch]" value="true" <?php  if($offline['switch']) { ?> checked="checked"<?php  } ?>/>
						开启
					</label>
					<label class="radio">
						<input type="radio" name="offline[switch]" value="false" <?php  if(empty($offline['switch'])) { ?> checked="checked"<?php  } ?>/>
						关闭
					</label>
					<span class="help-block">是否支持线下汇款方式付款</span>
				</td>
			</tr>
			<tbody class="offline-params<?php  if(empty($offline['switch'])) { ?> hide<?php  } ?>">
				<tr>
					<th><label for="">帐户信息</label></th>
					<td>
						<textarea id="richtext" style="height:200px;" class="span7" name="offline[account]" cols="70"><?php  echo $offline['account'];?></textarea>
						<span class="help-block">支持HTML</span>
					</td>
				</tr>
			</tbody>
		</table>
		<script type="text/javascript">
		kindeditor($('#richtext'));
		</script>
		<h4>设置余额支付开关</h4>
		<table class="tb">
			<tr>
				<th>余额支付</th>
				<td>
					<label class="radio">
						<input type="radio" name="credit[switch]" value="true" <?php  if($credit['switch']) { ?> checked="checked"<?php  } ?>/>
						开启
					</label>
					<label class="radio">
						<input type="radio" name="credit[switch]" value="false" <?php  if(empty($credit['switch'])) { ?> checked="checked"<?php  } ?>/>
						关闭
					</label>
					<span class="help-block">是否使用粉丝中心的余额直接支付</span>
				</td>
			</tr>
		</table>
		<h4>设置支付宝支付参数</h4>
		<table class="tb">
			<tr>
				<th>支付宝无线支付</th>
				<td>
					<div class="alert alert-block">
						您的支付宝账号必须支持手机网页即时到账接口, 才能使用手机支付功能, <a href="https://b.alipay.com/order/productDetail.htm?productId=2013080604609688" target="_blank">申请及详情请查看这里</a>.
					</div>
					<label class="radio">
						<input type="radio" name="alipay[switch]" value="true" <?php  if($alipay['switch']) { ?> checked="checked"<?php  } ?>/>
						开启
					</label>
					<label class="radio">
						<input type="radio" name="alipay[switch]" value="false" <?php  if(empty($alipay['switch'])) { ?> checked="checked"<?php  } ?>/>
						关闭
					</label>
					<span class="help-block">是否使用支付宝无线支付</span>
					<input type="hidden" name="alipay[t]" />
				</td>
			</tr>
			<tbody class="alipay-params<?php  if(empty($alipay['switch'])) { ?> hide<?php  } ?>">
				<tr>
					<th><label for="">收款支付宝账号</label></th>
					<td>
						<input type="text" name="alipay[account]" class="span6" value="<?php  echo $alipay['account'];?>" autocomplete="off">
						<span class="help-block">如果开启兑换或交易功能，请填写真实有效的支付宝账号，用于收取用户以现金兑换交易积分的相关款项。如账号无效或安全码有误，将导致用户支付后无法正确对其积分账户自动充值，或进行正常的交易对其积分账户自动充值，或进行正常的交易。 如您没有支付宝帐号，<a href="https://memberprod.alipay.com/account/reg/enterpriseIndex.htm" target="_blank">请点击这里注册</a></span>
					</td>
				</tr>
				<tr>
					<th><label for="">合作者身份</label></th>
					<td>
						<input type="text" name="alipay[partner]" class="span6" value="<?php  echo $alipay['partner'];?>" autocomplete="off"/>
						<span class="help-block">支付宝签约用户请在此处填写支付宝分配给您的合作者身份，签约用户的手续费按照您与支付宝官方的签约协议为准。<br>如果您还未签约，<a href="https://memberprod.alipay.com/account/reg/enterpriseIndex.htm" target="_blank">请点击这里签约</a>；如果已签约,<a href="https://b.alipay.com/order/pidKey.htm?pid=2088501719138773&amp;product=fastpay" target="_blank">请点击这里获取PID、Key</a>;如果在签约时出现合同模板冲突，请咨询0571-88158090</span>
					</td>
				</tr>
				<tr>
					<th><label for="">校验密钥</label></th>
					<td>
						<input type="text" name="alipay[secret]" class="span6" value="<?php  echo $alipay['secret'];?>" autocomplete="off"/>
						<span class="help-block">支付宝签约用户可以在此处填写支付宝分配给您的交易安全校验码，此校验码您可以到支付宝官方的商家服务功能处查看</span>
					</td>
				</tr>
				<tr>
					<th><label for="">模拟测试</label></th>
					<td>
						<a href="javascript:;" onclick="tPost();">交易模拟测试</a>
						<span class="help-block">本测试将模拟提交 0.01 元人民币的订单进行测试，如果提交后成功出现付款界面，说明您站点的支付宝功能可以正常使用</span>
					</td>
				</tr>
			</tbody>
		</table>
		<h4>设置微信支付参数</h4>
		<table class="tb">
			<tr>
				<th>微信支付</th>
				<td>
					<div class="alert alert-block">
						你必须向微信公众平台提交企业信息以及银行账户资料，审核通过并签约后才能使用微信支付功能
					</div>
					<label class="radio">
						<input type="radio" name="wechat[switch]" value="true" <?php  if($wechat['switch']) { ?> checked="checked"<?php  } ?>/>
						开启
					</label>
					<label class="radio">
						<input type="radio" name="wechat[switch]" value="false" <?php  if(empty($wechat['switch'])) { ?> checked="checked"<?php  } ?>/>
						关闭
					</label>
					<span class="help-block">是否使用微信支付</span>
				</td>
			</tr>
			<tbody class="wechat-params<?php  if(empty($wechat['switch'])) { ?> hide<?php  } ?>">
				<tr>
					<th><label for="">身份标识<br />(appId)</label></th>
					<td>
						<input type="text" name="wechat[appid]" class="span6" value="<?php  echo $_W['account']['key'];?>" readonly="readonly" autocomplete="off">
						<span class="help-block">公众号身份标识 <a href="<?php  echo create_url('account/post', array('id' => 'current'))?>">请通过修改公众号信息来保存</a></span>
					</td>
				</tr>
				<tr>
					<th><label for="">身份密钥<br />(appSecret)</label></th>
					<td>
						<input type="text" name="wechat[secret]" class="span6" value="<?php  echo $_W['account']['secret'];?>" readonly="readonly" autocomplete="off"/>
						<span class="help-block">公众平台API(参考文档API 接口部分)的权限获取所需密钥Key <a href="<?php  echo create_url('account/post', array('id' => 'current'))?>">请通过修改公众号信息来保存</a></span>
					</td>
				</tr>
				<tr>
					<th><label for="">通信密钥<br />(paySignKey)</label></th>
					<td>
						<input type="text" name="wechat[signkey]" class="span6" value="<?php  echo $wechat['signkey'];?>" autocomplete="off"/>
						<span class="help-block">公众号支付请求中用于加密的密钥Key</span>
					</td>
				</tr>
				<tr>
					<th><label for="">商户身份<br />(partnerId)</label></th>
					<td>
						<input type="text" name="wechat[partner]" class="span6" value="<?php  echo $wechat['partner'];?>" autocomplete="off"/>
						<span class="help-block">财付通商户身份标识</span>
					</td>
				</tr>
				<tr>
					<th><label for="">商户密钥<br />(partnerKey)</label></th>
					<td>
						<input type="text" name="wechat[key]" class="span6" value="<?php  echo $wechat['key'];?>" autocomplete="off"/>
						<span class="help-block">财付通商户权限密钥Key</span>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="tb">
			<tr>
				<th></th>
				<td>
					<input name="do-submit" type="submit" value="提交" class="btn btn-primary span3" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
