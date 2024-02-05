
var aud; // AudienceModelクラスのインスタンス

$(() => {
	
	aud = new AudienceModel();

});

function regAudiense(){
	aud.regAudiense();
}

function audience_list(){
	aud.audience_list();
}

function _getDefaultEntity(){
	
	let ent = [
		'description', '', // オーディエンス名
		'isIfaAudience', 'fail', // IFAフラグ
		'uploadDescription', '', // ジョブ説明
		'audiences', '', // ユーザー名リスト
	];
	
	return ent;
}


function _setEntityToFormTbl(ent){
	let jqFormTbl = $('#form_tbl');
	jqFormTbl.find("[name='access_token']").val(ent.access_token);
	jqFormTbl.find("[name='description']").val(ent.description);
}

