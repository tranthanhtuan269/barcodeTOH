<?php
Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('config:clear');
	Artisan::call('view:clear');
	// return what you want
});
// BACKEND
Route::get('/toh-admin','LoginController@getLogin');
Route::get('login',function(){
	return redirect('/');
});
Route::get('logout_administrator','LoginController@getLogout');
Route::post('login','LoginController@postLogin');

//AJAX
Route::get('settingPackageBarCodeAjax',['as' => 'settingPackageBarCodeAjax', 'uses' => 'SettingController@settingPackageBarCodeAjax']);

Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix'=>'admin'],function(){
		Route::get('/home','HomeController@getIndex');



		Route::group(['prefix' => 'roles'],function () {
			Route::get('list',['as' => 'getRoleList', 'uses' => 'RoleController@getRoleList']);
			Route::get('add',['as' => 'getRoleAdd', 'uses' => 'RoleController@getRoleAdd']);
	        Route::post('add',['as' => 'postRoleAdd', 'uses' => 'RoleController@postRoleAdd']);
	        Route::get('edit/{id}',['as' => 'getRoleEdit', 'uses' => 'RoleController@getRoleEdit']);
	        Route::put('edit/{id}',['as' => 'putRoleEdit', 'uses' => 'RoleController@putRoleEdit']);
	        Route::delete('del/{id}',['as' => 'deleteRoleDel', 'uses' => 'RoleController@deleteRoleDel'])->where('id', '[0-9]+');
	        /*Route::get('delete/{id}',['as' => 'getRoleDel', 'uses' => 'RoleController@getRoleDel'])->where('id', '[0-9]+');*/    
		});
		Route::group(['prefix' => 'user'],function () {
			Route::get('listBarcode',['as' => 'getBarcodeList', 'uses' => 'UserController@getBarcodeList']);
			Route::delete('deleteBarcode',['as' => 'deleteBarcode', 'uses' => 'UserController@deleteBarcode']);
			Route::get('list',['as' => 'getUserList', 'uses' => 'UserController@getUserList']);
			Route::get('add',['as' => 'getUserAdd', 'uses' => 'UserController@getUserAdd']);
	        Route::post('add',['as' => 'postUserAdd', 'uses' => 'UserController@postUserAdd']);
	        Route::put('active',['as' => 'putUserActive', 'uses' => 'UserController@putUserActive']);
	        Route::get('edit/{id}',['as' => 'getUserEdit', 'uses' => 'UserController@getUserEdit']);
	        Route::put('edit/{id}',['as' => 'putUserEdit', 'uses' => 'UserController@putUserEdit']);
	        Route::delete('del/{id}',['as' => 'deleteUserDel', 'uses' => 'UserController@deleteUserDel'])->where('id', '[0-9]+');
		});

		Route::group(['prefix' => 'setting'],function () {

			Route::get('getListSettingPackageBarCode',['as' => 'getListSettingPackageBarCode', 'uses' => 'SettingController@getListSettingPackageBarCode']);

			Route::get('getSettingPriceBarCode',['as' => 'getSettingPriceBarCode', 'uses' => 'SettingController@getSettingPriceBarCode']);
			Route::put('setSettingPriceBarCode',['as' => 'setSettingPriceBarCode', 'uses' => 'SettingController@setSettingPriceBarCode']);
			
			Route::get('getSettingBarCodeFree',['as' => 'getSettingBarCodeFree', 'uses' => 'SettingController@getSettingBarCodeFree']);
			Route::put('getSettingBarCodeFree',['as' => 'putSettingBarCodeFree', 'uses' => 'SettingController@putSettingBarCodeFree']);

			Route::get('getSettingMessage',['as' => 'getSettingMessage', 'uses' => 'SettingController@getSettingMessage']);
			Route::put('putSettingMessage',['as' => 'putSettingMessage', 'uses' => 'SettingController@putSettingMessage']);
			
			Route::get('emailConfig',['as' => 'emailConfig', 'uses' => 'SettingController@emailConfig']);
			Route::put('emailSaveConfig',['as' => 'emailSaveConfig', 'uses' => 'SettingController@emailSaveConfig']);
			
			Route::put('settingBarcodeType',['as' => 'settingBarcodeType', 'uses' => 'SettingController@settingBarcodeType']);
		});


		Route::group(['prefix' => 'taikhoan'],function () {
			Route::get('thongtin/{id}',['as' => 'getTaikhoanInfo', 'uses' => 'TaikhoanController@getTaikhoanInfo']);
		    Route::get('editpass/{id}',['as' => 'getTaikhoanEditPass','uses' => 'TaikhoanController@getTaikhoanEditPass'])->where('id','[0-9]+');
		    Route::put('editpass/{id}',['as' => 'putTaikhoanEditPass','uses' => 'TaikhoanController@putTaikhoanEditPass'])->where('id','[0-9]+');
		    Route::get('editinfo/{id}',['as' => 'getTaikhoanEditInfo','uses' => 'TaikhoanController@getTaikhoanEditInfo'])->where('id','[0-9]+');
		    Route::put('editinfo/{id}',['as' => 'putTaikhoanEditInfo','uses' => 'TaikhoanController@putTaikhoanEditInfo'])->where('id','[0-9]+');
		});

		Route::group(['prefix' => 'page'],function () {
			Route::get('contactus', ['as' => 'getContactUsPage', 'uses' => 'PagesController@getContactUsPage']);
			Route::put('contactus', ['as' => 'putContactUsPage', 'uses' => 'PagesController@putContactUsPage']);
			Route::get('list',['as' => 'getPageList', 'uses' => 'PagesController@getPageList']);
			Route::get('add',['as' => 'getPageAdd', 'uses' => 'PagesController@getPageAdd']);
	        Route::post('add',['as' => 'postPageAdd', 'uses' => 'PagesController@postPageAdd']);
	        Route::get('edit/{id}',['as' => 'getPageEdit', 'uses' => 'PagesController@getPageEdit']);
	        Route::put('edit/{id}',['as' => 'putPageEdit', 'uses' => 'PagesController@putPageEdit']);
	        Route::delete('del/{id}',['as' => 'deletePageDel', 'uses' => 'PagesController@deletePageDel'])->where('id', '[0-9]+');
		});
	});

});


//FRONTEND
Route::get('/','PublicController@getIndex')->name('index');
Route::get('logout','PublicController@getLogout');
Route::post('registerAjax',['as' => 'registerAjax', 'uses' => 'PublicController@registerAjax']);
Route::post('loginAjax',['as' => 'loginAjax', 'uses' => 'PublicController@loginAjax']);
Route::get('contact',['as' => 'getContact', 'uses' => 'ContactController@getContact']);
Route::post('contact',['as' => 'postContact', 'uses' => 'ContactController@postContact']);
Route::get('pricing', ['as'  => 'getPricePage', 'uses' =>'PagesController@getPricePage']);
Route::get('add-barcode', ['as'  => 'getAddBarcodePage', 'uses' =>'PagesController@getAddBarcodePage']);


Route::group(['middleware' => 'authfrontend'], function () {
	Route::post('droponejs-file', 'BarCodeController@droponeJs')->name('droponejs-file');
	
	Route::group(['prefix' => 'account'],function () {
		Route::get('info/{id}',['as' => 'getInfoAccount', 'uses' => 'TaikhoanController@getInfoAccount']);
	    Route::get('edit/{id}',['as' => 'getAccountEdit','uses' => 'TaikhoanController@getAccountEdit'])->where('id','[0-9]+');
	    Route::put('edit/{id}',['as' => 'putAccountEdit','uses' => 'TaikhoanController@putAccountEdit'])->where('id','[0-9]+');
	    Route::put('changepassAjax',['as' => 'changepassAjax', 'uses' => 'PublicController@changepassAjax']);
	});
	Route::group(['prefix' => 'barcode'],function () {
		Route::get('search',['as' => 'getSearchBarCode', 'uses' => 'BarCodeController@getSearchBarCode']);
		Route::get('list/{id}',['as' => 'listBarCodebyUser', 'uses' => 'BarCodeController@listBarCodebyUser'])->where('id', '[0-9]+');
		Route::get('add',['as' => 'getBarCodeAddbyUser', 'uses' => 'BarCodeController@getBarCodeAddbyUser']);
		Route::post('add',['as' => 'postBarCodeAddbyUser', 'uses' => 'BarCodeController@postBarCodeAddbyUser']);
		Route::post('addBarCodeNormalByUserAjax',['as' => 'addBarCodeNormalByUserAjax', 'uses' => 'BarCodeController@addBarCodeNormalByUserAjax']);
	    Route::get('edit/{id}',['as' => 'getBarCodeEditbyUser','uses' => 'BarCodeController@getBarCodeEditbyUser'])->where('id','[0-9]+');
	    Route::put('putBarCodeEditbyUser',['as' => 'putBarCodeEditbyUser','uses' => 'BarCodeController@putBarCodeEditbyUser'])->where('id','[0-9]+');
	    Route::get('view/{id}',['as' => 'getBarCode','uses' => 'BarCodeController@getBarCode'])->where('id','[0-9]+');
	    Route::put('putStateBarcodeTable',['as' => 'putStateBarcodeTable','uses' => 'BarCodeController@putStateBarcodeTable'])->where('id','[0-9]+');
        Route::delete('del',['as' => 'deleteBarCodebyUser', 'uses' => 'BarCodeController@deleteBarCodebyUser']);
        Route::delete('delMulti',['as' => 'deleteMultiBarCodebyUser', 'uses' => 'BarCodeController@deleteMultiBarCodebyUser']);

		Route::get('listBarCodeUserAjax',['as' => 'listBarCodeUserAjax', 'uses' => 'BarCodeController@listBarCodeUserAjax']);
	});

	Route::get('payment',['as' => 'getPayment', 'uses' => 'PaymentController@getPayment']);

	// Route::get('paymentHistory',['as' => 'paymentHistory', 'uses' => 'PaymentController@getPaymentHistory']);
	Route::get('paymentHistoryAjax',['as' => 'paymentHistoryAjax', 'uses' => 'PaymentController@paymentHistoryAjax']);
	// Route::post('payment',['as' => 'postPayment', 'uses' => 'PaymentController@postPayment']);
	Route::get('paymentConfirm',['as' => 'paymentConfirm', 'uses' => 'PaymentController@paymentConfirm']);
	Route::post('paymentConfirm',['as' => 'postPaymentConfirm', 'uses' => 'PaymentController@postPaymentConfirm']);
	Route::get('paypal', array('as' => 'payment.status','uses' => 'PaymentController@getPaymentStatus',));

	Route::get('resultPaymentLocal',['as' => 'resultPaymentLocal', 'uses' => 'PaymentController@resultPaymentLocal']);
	Route::get('resultPaymentInternational',['as' => 'resultPaymentInternational', 'uses' => 'PaymentController@resultPaymentInternational']);
	Route::put('putStatePaymentTable',['as' => 'putStatePaymentTable','uses' => 'PaymentController@putStatePaymentTable']);

});

Route::get('barcode/{slug}','PublicController@getSearchBarcode')->name('seo-barcode');
// Route::get('barcode/{slug}-{id}','PublicController@getSearchBarcode')->name('seo-barcode');
Route::get('/sitemap.xml', 'SitemapController@index');
Route::get('/barcode-sitemap.xml', 'SitemapController@barcodes');
Route::get('/page-sitemap.xml', 'SitemapController@pages');
//AJAX
Route::get('resetCodeAjax',['as' => 'resetCodeAjax', 'uses' => 'PublicController@resetCodeAjax']);
Route::get('resetpass',['as' => 'resetpass', 'uses' => 'PublicController@resetpass']);
Route::put('resetpassAjax',['as' => 'resetpassAjax', 'uses' => 'PublicController@resetpassAjax']);
Route::post('images/uploadImage',['as' => 'uploadImage', 'uses' => 'PublicController@uploadImage']);
Route::post('images/uploadImageBarcode',['as' => 'uploadImageBarcode', 'uses' => 'PublicController@uploadImageBarcode']);
Route::post('get-slug-barcode', 'PublicController@getSlugAjax')->name('get-slug-barcode');
// Category ( LUÔN ĐẮT Ở CUỐI )
Route::get('page/{cat}', ['as'  => 'getCategories', 'uses' =>'PagesController@getCategories']);
Route::get('page/{cat}/{id}-{slug}', ['as'  => 'getDetailPage', 'uses' =>'PagesController@getDetailPage']);




