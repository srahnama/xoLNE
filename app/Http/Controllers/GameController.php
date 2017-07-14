<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
class GameController extends Controller
{
    //
    /*
    //save game results
    */

    public function save(Request $request){
    	$game = new Game();
    	$game->oplayer = "o";
    	$game->xplayer = "x";
    	$game->steps = json_encode($request->gsteps);
    	$game->winner = "idont know";
    	
    		$game->save();
    }
}
