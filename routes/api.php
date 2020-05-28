<?php


Route::resource('graphs','GraphController');
Route::resource('nodes','NodeController');
Route::get('nodes/graph/{id}','NodeController@byGraphId');
Route::resource('neigbors','NodeNeighborController');