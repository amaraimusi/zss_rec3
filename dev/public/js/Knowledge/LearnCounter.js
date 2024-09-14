/**
 * 覚えカウンタークラス
 * 
 * @date 2018-5-17
 * @version 1.0
 */
class LearnCounter{
	
	
	/**
	 * コンストラクタ
	 * 
	 * @param param
	 * - object data 一覧データ
	 * - string tbl_xid 一覧テーブルのid属性
	 * - string id_slt 行内のid要素セレクタ
	 * - string csrf_token CSRFトークン
	 */
	constructor(params){

		// 休眠日数配列(単位は日）
		this.dormants = [0,0.01,0.5,2,7,14,30,45,60,90,120,150,180,210,270,360];

		var data = $.extend(true, {}, params.data); // データのクローンを作成
		this.data = this._prosKlData(data); // 心得データを加工
		
		// 一覧テーブルへ覚え済みフラグ関連をセットする
		this._setLearnedToTbl(this.data, params.tbl_xid, params.id_slt);
		
		this.params = params;

	}
	
	
	/**
	 * 心得データを加工
	 * @param object 心得データ
	 * @return 加工後の心得データ
	 */
	_prosKlData(data0){
		
		var data = {};
		for(var i in data0){
			var ent = data0[i];

			var res = this._checkDormant(ent);// 休眠チェック

			ent['learned'] = res.flg; // 覚え済み
			ent['about_rem'] = res.about_rem; // 約残
			
			data[ent.id] = ent;
		}
		return data;
		
	}
	
	
	/**
	 * 一覧テーブルへ覚え済みフラグ関連をセットする
	 * @param object data 心得データ
	 * @param string tbl_xid 一覧テーブルのid属性
	 * @param string id_slt 行内のid要素セレクタ
	 */
	_setLearnedToTbl(data, tbl_xid, id_slt){
		
		var tbl_slt = '#' + tbl_xid;
		var tbl = jQuery(tbl_slt);
		
		tbl.find('tbody tr').each((i,tr)=>{
			tr = jQuery(tr);
			
			// 心得IDを取得する
			var td0 = tr.find('td').eq(0);
			var id = td0.find(id_slt).val();

			var ent = data[id];

			// 覚え済みである場合、覚えボタンを隠し、約残日を表示する
			if(ent.learned == false){

				tr.find('.learn_btn').hide(); // 覚えボタンを隠す
				
				// 約残日を表示する
				var learned_span = tr.find('.learned');
				learned_span.html(ent.about_rem);
				learned_span.show();
			}

		});
	}

	
	
	/**
	 * 覚ボタンクリック
	 * @param object btnElm ボタン要素
	 * @param int id 心得ID
	 */
	learnClick(btnElm,id){

		// 心得データから心得IDにひもづく心得エンティティを取得する
		var ent = this.data[id];
		
		// 休眠チェック
		var res = this._checkDormant(ent);
		
		// 覚えの更新
		if(res.flg == true){

			// 現在日時を取得（文字列）
			var now_dt = new Date().toLocaleString();

			// レベルの加算と、日付を更新
			ent.level ++;
			ent.dtm = now_dt;
			ent.modified = now_dt;
			ent['next_dtm'] = this._calcNextDtm(ent.level); // 次回日時

			btnElm = jQuery(btnElm);
			btnElm.hide();// 覚えボタンをいったん隠す
			
			// AJAXによる心得エンティティの保存
			this._saveByAjax(ent,()=>{

				// ボタン名に覚数を表示
				var btn_v = "覚:" + ent.level;
				btnElm.val(btn_v);
				btnElm.hide();
				
				// 先祖をさかのぼってTD要素を取得
				var td = btnElm.parents('td');
				
				// 約残日を表示する
				var learned_span = td.find('.learned');
				var about_rem = this._calcAboutRem2(ent);
				learned_span.html(about_rem);
				learned_span.show();
			});
			
		}
		
		// 覚え済みである場合（休眠期間を経過していない場合）
		else{
			alert(res.err_msg);
		}


	}
	
	
	/**
	 * AJAXによる心得エンティティの保存
	 * @param array ent 心得えんてぃちh
	 * @param function callback AJAX通信後に実行するコールバック関数
	 */
	_saveByAjax(ent,callback){

		var json_str = JSON.stringify(ent);//データをJSON文字列にする。
		let fd = new FormData(); // 送信フォームデータ
		let json = JSON.stringify(ent);
		fd.append( "key1", json );
		
		// CSRFトークンを送信フォームデータにセットする。
		fd.append( "_token", this.params.csrf_token );
	
		// AJAX
		$.ajax({
			type: "POST",
			url: "knowledge/learn_save",
			data: fd,
			cache: false,
			dataType: "text",
			processData: false,
			contentType: false,

		})
		.done((str_json, type) => {

			var res;
			try{
				res =jQuery.parseJSON(str_json);//パース
				callback(); // コールバックを実行
				
			}catch(e){
				alert('エラー');
				jQuery("#err").html(str_json);
				return;
			}
			
		})
		.fail((jqXHR, statusText, errorThrown) => {
			jQuery('#err').html(jqXHR.responseText);
			alert(statusText);
		});		
	}
	
	/**
	 * 次回日時を算出する
	 * @param int next_level 次レベル
	 */
	_calcNextDtm(next_level){
		
		var today = new Date();
		
		// レベルから休眠日数を算出する
		var dorman = this._getDormanByLevel(next_level);
		
		var today_u = Math.floor(today);
		var dorman_u = dorman * 86400000;
		
		// 残り = 更新日 + 休眠 - 現在
		var next_u = dorman_u + today_u;
		var d = new Date(next_u).toLocaleString(); // UNIXタイムスタンプからDateに変換

		return d;

	}
	
	
	/**
	 *  休眠チェック
	 *  @param ent 覚エンティティ
	 */
	_checkDormant(ent){
		
		// 現在日時
		var today = new Date();

		// 休眠チェック
		var flg = this._checkDormant0(today,ent);

		
		// 経過日数が休眠日数に達していない場合は、チェックＮＯにしエラーメッセージを作成する。
		var err_msg = '';
		var about_rem = '';
		if(flg==false){
			
			// レベルから休眠日数を算出する
			var dorman = this._getDormanByLevel(ent.level);
			
			// 残り約時を算出
			var about_rem = this._calcAboutRem(today,dorman,ent.dtm);
			
			err_msg = '残り:' + about_rem;
		}
		
		var res = {
			'flg':flg,
			'err_msg':err_msg,
			'about_rem':about_rem,
		};
		return res;
	}
	
	/**
	 * 休眠チェック(シンプル版）
	 * @return true:覚え対象 , false:休眠中
	 */
	_checkDormant0(today,ent){
		// 2つの日付の日数差を経過日数として取得する
		var elapsed = this._diffDate(today,ent.dtm);
		
		// レベルから休眠日数を算出する
		var dorman = this._getDormanByLevel(ent.level);
		
		// 経過日数が休眠日数を超えている場合、チェックＯＫにする。
		var flg = false;
		if (elapsed > dorman){
			flg = true;
		}
		
		return flg;
		
	}
	
	/**
	 * エンティティから約残を取得
	 * @return string 約残
	 */
	_calcAboutRem2(ent,today){
		
		if (today==null) today =  new Date();
		
		// レベルから休眠日数を算出する
		var dorman = this._getDormanByLevel(ent.level);
		
		// 残り約時を算出
		var about_rem = this._calcAboutRem(today,dorman,ent.dtm);
		
		return about_rem;
	}
	
	/**
	 * 残り約時を算出
	 * @param date today 現在日時
	 * @param int dorman 休眠日数
	 * @param string dtm 更新日
	 * @return 残り約時
	 */
	_calcAboutRem(today,dorman,dtm){

		var today_u = Math.floor(today);
		var dorman_u = dorman * 86400000;
		var dtm_u = Math.floor(new Date(dtm));

		// 残り = 更新日 + 休眠 - 現在
		var rem_u = dtm_u + dorman_u - today_u;
		
		var str_rem = this._aboutDate(rem_u);
		
		return str_rem;

		
	}
	
	
	
	/**
	 * レベルから休眠日数を算出する
	 * @param int level レベル
	 * @return int 休眠日数
	 * 
	 */
	_getDormanByLevel(level){
		
		var dorman = 0;//休眠日数
		
		if(this.dormants.length > level){
			dorman = this.dormants[level]
		}else{
			var y = level - this.dormants.length + 1;
			dorman = y * 365.2425;
		}
		
		return dorman;
	}
	
	
	/**
	 * UNIXタイムスタンプから適切な単位（年月日時分秒のいずれか）で返す
	 * 
	 * 文字列型日付、日付オブジェクトの両方に対応
	 * 
	 * @param date1 比較日付1
	 * @param date2 比較日付2
	 * @returns number 日数
	 */
	_aboutDate(u){

		var v = 0;
		var date_str = '';
		
		if(u >= 31556952000){
			v = Math.round(u / 31556952000);
			date_str = '約' + v + '年間';
		}else if(u >= 2629746000){
			v = Math.round(u / 2629746000);
			date_str = '約' + v + 'ヶ月間';
		}else if(u >= 86400000){
			v = Math.round(u / 86400000);
			date_str = '約' + v + '日間';
		}else if(u >= 3600000){
			v = Math.round(u / 3600000);
			date_str = '約' + v + '時間';
		}else if(u >= 60000){
			v = Math.round(u / 60000);
			date_str = '約' + v + '分間';
		}else if(u >= 1000){
			v = Math.round(u / 1000);
			date_str = '約' + v + '秒';
		}else{
			date_str = '約' + v + 'ミリ秒';
		}
		return date_str;
	}
	
	
	
	
	/**
	 * 2つの日付の日数差を算出
	 * 
	 * 文字列型日付、日付オブジェクトの両方に対応
	 * 
	 * @param d1 比較日付1
	 * @param d2 比較日付2
	 * @returns number 日数
	 */
	_diffDate(d1,d2){
		
		// 引数が文字列型の日付なら日付オブジェクトに変換
		if(typeof d1 == "string"){
			
			if(d1.indexOf('-') > -1){
				d1 = d1.replace('-','/');// IEは「-」の区分に対応していないので「/」に置換
			}
			var d1 = new Date(d1);
		}
		if(typeof d2 == "string"){
			if(d2.indexOf('-') > -1){
				d2 = d2.replace('-','/');
			}
			var d2 = new Date(d2);
		}
		
		var u1 = Math.floor(d1);// UNIXタイムスタンプに変換
		var u2 = Math.floor(d2);
		
		// 2つの日付の日数差を算出
		var diff_u = u1 - u2;
		var date_count = diff_u / 86400000 ;
		
		return date_count;

	}
	
	
}