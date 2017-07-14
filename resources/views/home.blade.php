@extends('layouts.app')

@section('content')
<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
<script language="javascript" type="text/javascript">

 
  var socket = io('http://localhost:3000');
  
 socket.on('change', function (data) {
 	var news = document.getElementById('news');
 	var z = document.createElement('div');
	z.innerHTML = '<p> you can move : '+data['xo']+' </p>';
	var a = document.createElement('div');
	a.innerHTML = '<p> you can not move  </p>';
 	if(data['move'])
    news.appendChild(z);
  else
  	news.appendChild(a);
     // set _drag for dragable and xo : x or ocan be moves
  _drag = data['move'];
 
  _XO = data['xo'];
 });
  var _ON, _TO, GSteps = {},Opos={},Xpos={}, username;
  var _drag = true ,_XO;
  function allowDrop(ev) {
  	
  	ev.preventDefault();
}

function drag(ev) {
	if(_drag && ev.target.innerHTML==_XO)//can user move ? 
		ev.dataTransfer.setData("text", ev.target.id);   
}

mysocket();
function drop(ev) {
	ev.preventDefault();
	var data = ev.dataTransfer.getData("text");

	if (!ev.target.hasChildNodes()&&okLocation(ev.target['id'],document.getElementById(data))) {
    
    ev.target.appendChild(document.getElementById(data));
        //console.log(document.getElementById(data).innerHTML);
        _ON= ev.target['id'];
        //console.log(document.getElementById(data).innerHTML=="O");
        _TO =document.getElementById(data)['id'];
        mysocket();



      }


    }
    function okLocation (st,ts) {//rules of game

    	var l1 = ["2","4","5"],l2 = ["1","5","3"],l3 = ["2","6","5"],l4 = ["1","5","7"],l5 = ["1","2","3","4","6","7","8","9"],l6 = ["3","5","9"],l7 = ["4","8","5"],l8 = ["7","5","9"],l9 = ["6","8","5"];
    	console.log(ts);
    	var t;
    	if(ts.innerHTML=="O")
    		t = Opos[ts['id']];
    	else {
    		t = Xpos[ts['id']];
    	}
    	switch(t) {
    		case "1":
    		if(l1.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "2":
    		if(l2.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "3":
    		if(l3.indexOf(st)>=0){
    			return true;
    		}
    		break;    
    		case "4":
    		if(l4.indexOf(st)>=0){
    			return true;
    		}
    		break;    
    		case "5":
    		if(l5.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "6":
    		if(l6.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "7":
    		if(l7.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "8":
    		if(l8.indexOf(st)>=0){
    			return true;
    		}
    		break;
    		case "9":
    		if(l9.indexOf(st)>=0){
    			return true;
    		}
    		break; 
    		case undefined:

    		return true;


    		break;        
    		default:
    		return false;
    	}
    }
   
    
  
    var i= 0;
    function mysocket(){
    	
    	 
    	 socket.on('xo', function (data) {

          
            if(data['_TO'] == "drag4" || data['_TO'] =="drag5" || data['_TO'] =="drag6")//check if user is X and save x's locations
            {
            	Xpos[data['_TO']]=data['_ON'];

              //console.log(Xpos )
              winner(Xpos, "X");
              

            }
              if(data['_TO'] == "drag1" || data['_TO'] =="drag2" || data['_TO'] =="drag3")//check if user is O and save o's locations
              {
              	Opos[data['_TO']]=data['_ON'];

              //console.log(Xpos )
              winner(Opos, "O");
              
            }
            if(!_.isEqual(GSteps[i-1], [data['_TO'],data['_ON']] )){
            	GSteps[i]= [data['_TO'],data['_ON']] ;
            	console.log(GSteps);
            	i++;
            }
            console.log(GSteps);
            $("#"+data['_TO']).appendTo("#"+data['_ON']);
           
          });
            if(_TO == "drag4" || _TO =="drag5" || _TO =="drag6")//check if user is X and save x's locations
            {
            	Xpos[_TO]=_ON;

              console.log(Xpos )
              winner(Xpos, "X");
              

            }
              if(_TO == "drag1" ||  _TO =="drag2" || _TO =="drag3")//check if user is O and save o's locations
              {
              	Opos[_TO]=_ON;

              console.log(Xpos )
              winner(Opos, "O");
              
            }
          GSteps[i]= [_TO,_ON] ;
          i++;

          socket.emit('my other event', { _ON : _ON , _TO : _TO });//SEND TO server moves

    	
    }
      function winner(pos, mess){//check if user win
      	var win =[["1","2","3"],["4","5","6"],["7","8","9"],["1","4","7"],["2","5","8"],["3","6","9"],["1","5","9"],["3","5","7"]
      	]
      	var p = [];
      	for( var i in pos){
      		p.push(pos[i]);

      	}
      
        for( var i in win){
         
          if (_.isEqual(win[i], p.sort())){
          	alert(mess + " win!");
            $.ajaxSetup({// send data to save them in database
            	headers: {
            		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            	}
            });
            $.ajax({
            	type : "post",
            	url :"/save",
            	data :{
            		'gsteps' : GSteps
            		//'winner' : winneremail,

            	}, success: function(result){
            		alert(result);
            	}
            });
          }


        }
        

        


      }

      socket.on('connect', function (data) {
          //console.log(socket.io.engine.id);
          //console.log(document.getElementsByTagName('a')[1].innerHTML.replace(/(?=^|>)\s+|\s+(?=<|$)/g, ""));
          	var news = document.getElementById('news');
					 	var z = document.createElement('div');
						z.innerHTML = '<p> you connected : '+document.getElementsByTagName('a')[1].innerHTML.replace(/(?=^|>)\s+|\s+(?=<|$)/g, "")+' </p>';
					 	
					    news.appendChild(z);
          socket.emit('user', document.getElementsByTagName('a')[1].innerHTML.replace(/(?=^|>)\s+|\s+(?=<|$)/g, ""));
        });
      function replay(i){

         setTimeout(function () {    //  call a 1s setTimeout when the loop is called
         	 //console.log(i);
           //console.log(GSteps[i][0]);
           $("#"+GSteps[i][0]).appendTo("#"+GSteps[i][1]);

           if (i < Object.keys(GSteps).length-1 ) {
            i++;            //  increment the counter
               replay(i);             //  ..  again which will trigger another 
            }                        //  ..  setTimeout()
          }, 1000);





       }



     </script>




     <style>
     	.location{
     		float: left;
     		width: 50px;
     		height: 50px;
     		margin: 10px;
     		padding: 10px;
     		border: 1px solid black;
     	}
     	#divo1, #divo2, #divo3 {
     		background: skyblue;
     	}
     	#divx1, #divx2, #divx3 {
     		background: red;
     	}
     	span{
     		font-size: 30px;
     	}
     </style>
     <script>

     </script>














     <div class="container">
     	<div class="row">
     		<div class="col-md-3 col-md-offset-2">
     			<div class="panel panel-default">
     				<div class="panel-heading">Dashboard</div>
     				<p id="output"></p>
     				<div class="location" id="divo1"  ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag1">O</span>
     				</div>
     				<div class="location" id="divo2" ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag2">O</span>
     				</div>
     				<div class="location" id="divo3" ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag3">O</span>
     				</div>


     				<div class="location" id="1"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="2"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="3"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="4"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="5"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="6"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="7"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="8"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>
     				<div class="location" id="9"  ondrop="drop(event)" ondragover="allowDrop(event)"></div>

     				<div class="location" id="divx1" ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag4">X</span>
     				</div>
     				<div class="location" id="divx2" ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag5">X</span>
     				</div>
     				<div class="location" id="divx3" ondrop="drop(event)" ondragover="allowDrop(event)">
     					<span  draggable="true" ondragstart="drag(event)" id="drag6">X</span>
     				</div>
     				
     				<button id="replay" onclick="replay(0);">بازپخش</button>
     				<div id="news"></div>

     			</div>
     		</div>
     	</div>
     </div>












     @endsection
