<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 19/08/18
 * Time: 04:56
 */

Route::group(['namespace' => 'ScooterSam\CrudModels\Http\Controllers'], function () {
    Route::get("cm/{alias}/list", 'ModelController@list');
    Route::post('cm/{alias}/create', 'ModelController@create');
    Route::get("cm/{alias}/{id}", 'ModelController@resource');
    Route::get("cm/{alias}/{id}/update", 'ModelController@update');
    Route::delete('cm/{alias}/{id}', 'ModelController@delete');
});
