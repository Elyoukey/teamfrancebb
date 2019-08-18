<script>
function $(i){return document.getElementById(i);}
//sequence object
function fullSequence()
{
	this.proba = 1;
	this.sequence = new Array();
	this.playerNumber = 1;
	
	//add an action at the end of the sequence
	this.addAction=function( action )
	{ 
		if(action.isNewPlayer){this.playerNumber++}
		this.sequence[this.sequence.length]=action; 
	}
	
	//remove an action (actually replace it with a 1
	this.removeAction=function(i)
	{
	
		this.sequence.splice(i,1);
		
		showResult();
	}
	
	//get odd for an action teamRerollLeft = true to get the result with a reroll
	this.getProbaReroll2=function( teamRerollLeft )
	{
		return this.getProbaAction(0, teamRerollLeft, true, true , true, true, true);
	}
	                        
	//get recursivelly the probability for 1 action
	this.getProbaAction = function(n, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, ProRerollLeft )
	{
		
	 	var p1,p2;
		//no element left
		if( n >= this.sequence.length){return 1;}
		
		//new player
		if(this.sequence[n].isNewPlayer )
		{
			return this.getProbaAction(n+1, teamRerollLeft, true , true, true);
		}
		
		//get odd for this action without reroll
		p1 = this.sequence[n].probaNoReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft, SurefeetRerollLeft, ProRerollLeft );		
		
		//get odd for this action with reroll
		p2=0;
		//with player reroll
		if( DodgeRerollLeft == true && this.sequence[n].canUseDodgeSkill )
		{
			p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, false , SurefeetRerollLeft, ProRerollLeft);
		}
		else if( this.sequence[n].canUsePassSkill )
		{
			p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, ProRerollLeft);
		}
		else if( this.sequence[n].canUseCatchSkill )
		{
			p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, ProRerollLeft);
		}
		else if( this.sequence[n].canUseSurehandSkill )
		{
			p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, ProRerollLeft);
		}
		else if( SurefeetRerollLeft == true && this.sequence[n].canUseSurefeetSkill )
		{
			p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , false, ProRerollLeft);
		}
		//with team reroll
		else if(teamRerollLeft == true)
		{
			if( this.sequence[n].canUseProSkill == true && this.sequence[n].doUseProSkill == true && ProRerollLeft ) //use pro skill prior to using the reroll
			{
				//pro roll succeed
				     //( roll fail )*( pro succeed )*( roll succeed )* ( rest of the sequence )
				pPro = (1-this.sequence[n].probaNoReroll)*(1/2)*this.sequence[n].probaNoReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, false);
				
				//pro roll fails but use the reroll
				     //( roll fail )*( pro fail )*( pro succeed )*( roll succeed )* ( rest of the sequence )
				pPro2 = (1-this.sequence[n].probaNoReroll)*(1/2)*(1/2)*this.sequence[n].probaNoReroll*this.getProbaAction(n+1, false, DodgeRerollLeft , SurefeetRerollLeft, false);
				p2 = pPro+pPro2;
				if(  this.sequence[n].isLoner )p2=p2/2;
				
			}
			else //do not use pro unless there is no reroll
			{
				p2 = this.sequence[n].probaYesReroll*this.getProbaAction(n+1, false, DodgeRerollLeft , SurefeetRerollLeft, ProRerollLeft);
				if(  this.sequence[n].isLoner )p2=p2/2;
			}
		}
		//with pro reroll if reroll already used
		else if ( this.sequence[n].canUseProSkill == true && ProRerollLeft )
		{
			p2 = (1/2)*this.sequence[n].probaYesReroll*this.getProbaAction(n+1, teamRerollLeft, DodgeRerollLeft , SurefeetRerollLeft, false);
		}
		
		result = p1+p2;
		if( this.sequence[n].interceptionOdd ) result = (p1+p2)*this.sequence[n].interceptionOdd;
		if( this.sequence[n].cannotUseReroll ) result = p1; //for rolls that cannot be rerolled with team reroll 
		
		return result;
	}
	
	
	//return a string of the full sequence 
	this.display = function()
	{
		var text = '';
		var playerColor=1;
		for( i=0; i<this.sequence.length ; i++ )
		{
			if(this.sequence[i].isNewPlayer)playerColor++;
			if(playerColor>5)playerColor=1;
			
			//Pro checkbox
			checked='';
			if(this.sequence[i].doUseProSkill)checked = 'checked=checked';
			if(this.sequence[i].canUseProSkill)protext = '<input type="checkbox" onclick="usePro('+i+');" '+checked+'/>Use Pro prior to teamReroll';
			else protext =" &nbsp;";
			
			text+='<div class="action player'+playerColor+'"><div class="removeAction" onclick="theSequence.removeAction('+i+')">X</div>'+this.sequence[i].toString+'<div class="useProSkillBlock">'+protext+'</div></div>';
		}
		return text;
	
	}
}




/* ---------------------------- ATOMIC ODDS --------------------------------------------- */
//actions object
function playerAction(x, isLoner)
{
	this.isLoner = isLoner;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	tLoner = (isLoner)?' loner':'';
	this.toString = 'Action '+x+'+'+tLoner;
}

function dodge(x, hasSkill, isLoner)
{
	this.canUseDodgeSkill = hasSkill;
	this.isLoner = isLoner;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	wDodge = (hasSkill)?'with dodge skill':'';
	tLoner = (isLoner)?' loner':'';
	this.toString = 'Dodge '+x+'+ '+wDodge+tLoner;
}
function pass(x, hasSkill, isLoner)
{
	this.canUsePassSkill = hasSkill;
	this.isLoner = isLoner;
	
	//add the interception odd
	baseInterceptionOdd = 1;
	if($('interception2').checked)baseInterceptionOdd = 1/6;
	if($('interception3').checked)baseInterceptionOdd = 2/6;
	if($('interception4').checked)baseInterceptionOdd = 3/6;
	if($('interception5').checked)baseInterceptionOdd = 4/6;
	if($('interception6').checked)baseInterceptionOdd = 5/6;

	interceptionOdd = baseInterceptionOdd;
	//interceptor has the pro skill
	if($('interceptionWithPro').checked)interceptionOdd = baseInterceptionOdd*( 1/2 + baseInterceptionOdd/2 );
	//interceptor has the catch skill
	if($('interceptionWithCatch').checked)interceptionOdd = baseInterceptionOdd*baseInterceptionOdd;
	
	this.interceptionOdd = interceptionOdd;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	wSkill = (hasSkill)?'with pass skill':'';
	tLoner = (isLoner)?' loner':'';
	
	rInterceptionOdd =  Math.round(interceptionOdd*10000)/100;;
	tInterceptionOdd = ( interceptionOdd!=1 )? ' <span class="negative">(interception:'+rInterceptionOdd+'%)</span>':'';
	this.toString = 'Pass '+x+'+ '+wSkill+tLoner+tInterceptionOdd;
}
function catching(x, hasSkill, isLoner)
{
	this.canUseCatchSkill = hasSkill;
	this.isLoner = isLoner;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	wSkill = (hasSkill)?'with catch skill':'';
	tLoner = (isLoner)?' loner':'';
	this.toString = 'Catch '+x+'+ '+wSkill+tLoner;
}
function pickup(x, hasSkill, isLoner)
{
	this.canUseSurehandSkill = hasSkill;
	this.isLoner = isLoner;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	wSkill = (hasSkill)?'with sure hand':'';
	tLoner = (isLoner)?' loner':'';
	this.toString = 'Pick up '+x+'+ '+wSkill+tLoner;
}


function gfi(x, hasSurefeet, isLoner) 
{

	this.canUseSurefeetSkill = hasSurefeet;
	this.isLoner = isLoner;
	this.probaNoReroll = (7-x)/6;
	this.probaYesReroll = ((x-1)/6)*((7-x)/6);
	wDodge = (hasSurefeet)?'with sure feet':'';
	tLoner = (isLoner)?' loner':'';
	this.toString = 'Go For It '+x+'+ '+wDodge+tLoner;
}

function block(nbDice, nbSuccess, isLoner)
{
	this.isLoner = isLoner;
	
	switch(nbDice)
	{
		case -3:
			this.probaNoReroll = nbSuccess*nbSuccess*nbSuccess/216;
			break;
		case -2:
			this.probaNoReroll = nbSuccess*nbSuccess/36;
			break;
		case 1:
			this.probaNoReroll = nbSuccess / 6;
			break;s
		case 2:
			this.probaNoReroll = ( ( 12 * nbSuccess ) - ( nbSuccess*nbSuccess ) ) /36;
			break;
		case 3:
			this.probaNoReroll = ( (108*nbSuccess)-(18*nbSuccess*nbSuccess)+(nbSuccess*nbSuccess*nbSuccess) )/216;
			break;
	}
	this.probaYesReroll = (1-this.probaNoReroll)*this.probaNoReroll;
	tLoner = (isLoner)?' loner':'';
	this.toString = 'block '+nbDice+'D ('+nbSuccess+' success)'+tLoner;
}

function armorbreak()
{
	armorvalue = 1*$('armorvalue').value;
	var proba;
	proba = 1;
	switch(armorvalue )
	{
		case 1:proba = 1;break;
		case 2:proba = 35/36;break;
		case 3:proba = 33/36;break;
		case 4:proba = 30/36;break;
		case 5:proba = 26/36;break;
		case 6:proba = 21/36;break;
		case 7:proba = 15/36;break;
		case 8:proba = 10/36;break;
		case 9:proba = 6/36;break;
		case 10:proba = 3/36;break;
		case 11:proba = 1/36;break;
		case 12:proba = 0;
	}
	this.cannotUseReroll=true;
	this.probaNoReroll=proba;
	this.probaYesReroll = (1-this.probaNoReroll)*this.probaNoReroll;
	var ar = 1*armorvalue+1;
	this.toString = 'armor break ('+ar+'+)';
}

function injury(stunChecked, koChecked, casChecked)
{
	if( !stunChecked && !koChecked && !casChecked){
		this.probaNoReroll=this.probaYesReroll = 0;
		this.toString = 'No injury roll specified, you have to roll injuries, this is bloodbowl we are talking about';
		return;
	}
	proba = 0;
	injuryText = '';
	if( stunChecked )
	{
		proba+= 21/36;
		injuryText += 'stun ';
	}
	if( koChecked )
	{
		proba+= 9/36;
		injuryText += 'KO ';
	}
	if( casChecked )
	{
		proba+= 6/36;
		injuryText += 'cas ';
	}
	this.cannotUseReroll=true;
	this.probaNoReroll=proba;
	this.probaYesReroll = (1-this.probaNoReroll)*this.probaNoReroll;
	this.toString = 'injury ( '+injuryText+' )';
}

/* -----------------------------ACTION ADDING----------------------------------------------------------*/
var theSequence = new fullSequence();


function addAction( action )
{
	switch(action)
	{
		case 'dodge2':
			var theAction = new dodge(2, $("dodgeSkill").checked, $('lonerSkill').checked );
			
		break;
		case 'dodge3':
			var theAction = new dodge(3,  $("dodgeSkill").checked, $('lonerSkill').checked );
		break;
		case 'dodge4':
			var theAction = new dodge(4,  $("dodgeSkill").checked, $('lonerSkill').checked );
		break;
		case 'dodge5':
			var theAction = new dodge(5,  $("dodgeSkill").checked, $('lonerSkill').checked );
		break;
		case 'dodge6':
			var theAction = new dodge(6,  $("dodgeSkill").checked, $('lonerSkill').checked );
		break;
		
		case 'pass2':
			var theAction = new pass(2, $("passSkill").checked, $('lonerSkill').checked );
		break;
		case 'pass3':
			var theAction = new pass(3,  $("passSkill").checked, $('lonerSkill').checked );
		break;
		case 'pass4':
			var theAction = new pass(4,  $("passSkill").checked, $('lonerSkill').checked );
		break;
		case 'pass5':
			var theAction = new pass(5,  $("passSkill").checked, $('lonerSkill').checked );
		break;
		case 'pass6':
			var theAction = new pass(6,  $("passSkill").checked, $('lonerSkill').checked );
		break;
		
		case 'catch2':
			var theAction = new catching(2, $("catchSkill").checked, $('lonerSkill').checked );
		break;
		case 'catch3':
			var theAction = new catching(3,  $("catchSkill").checked, $('lonerSkill').checked );
		break;
		case 'catch4':
			var theAction = new catching(4,  $("catchSkill").checked, $('lonerSkill').checked );
		break;
		case 'catch5':
			var theAction = new catching(5,  $("catchSkill").checked, $('lonerSkill').checked );
		break;
		case 'catch6':
			var theAction = new catching(6,  $("catchSkill").checked, $('lonerSkill').checked );
		break;
		
		case 'pickup2':
			var theAction = new pickup(2, $("surehandSkill").checked, $('lonerSkill').checked );
		break;
		case 'pickup3':
			var theAction = new pickup(3, $("surehandSkill").checked, $('lonerSkill').checked );
		break;
		case 'pickup4':
			var theAction = new pickup(4, $("surehandSkill").checked, $('lonerSkill').checked );
		break;
		case 'pickup5':
			var theAction = new pickup(5, $("surehandSkill").checked, $('lonerSkill').checked );
		break;
		case 'pickup6':
			var theAction = new pickup(6, $("surehandSkill").checked, $('lonerSkill').checked );
		break;
		
		case 'action2':
			var theAction = new playerAction(2, $('lonerSkill').checked );
		break;
		case 'action3':
			var theAction = new playerAction(3, $('lonerSkill').checked);
		break;
		case 'action4':
			var theAction = new playerAction(4, $('lonerSkill').checked);
		break;
		case 'action5':
			var theAction = new playerAction(5, $('lonerSkill').checked);
		break;
		case 'action6':
			var theAction = new playerAction(6, $('lonerSkill').checked);
		break;
		
		case 'gfi2':
			var theAction = new gfi(2, $("surefeetSkill").checked, $('lonerSkill').checked);
		break;
		case 'gfi3':
			var theAction = new gfi(3, $("surefeetSkill").checked, $('lonerSkill').checked);
		break;
		
		
		
		
		case 'block':
			var nbDice=1;
			//number of dice
			if( $('nbDiceM3').checked==true )nbDice = -3;
			if( $('nbDiceM2').checked==true )nbDice = -2;
			if( $('nbDice1').checked==true )nbDice = 1;
			if( $('nbDice2').checked==true )nbDice = 2;
			if( $('nbDice3').checked==true )nbDice = 3;
			
			//number of success on the block die
			var nbSuccess = 0;
			if( $('blockSuccessSkull').checked==true)nbSuccess+=1;
			if( $('blockSuccessPowSkull').checked==true)nbSuccess+=1;
			if( $('blockSuccessPush').checked==true)nbSuccess+=2;
			if( $('blockSuccessStumble').checked==true)nbSuccess+=1;
			if( $('blockSuccessDown').checked==true)nbSuccess+=1;
			
			var theAction = new block(nbDice,nbSuccess, $('lonerSkill').checked);
			
		break;
		
		case 'armorbreak':
			var theAction = new armorbreak();
		break;
		case 'injury':
			var theAction = new injury($("injuryStun").checked, $('injuryKO').checked, $('injuryCas').checked);
		break;
		
	}

	theAction.isNewPlayer = false;
	
	theAction.canUseProSkill = $('proSkill').checked;
	theAction.doUseProSkill = false;
	
	theSequence.addAction(theAction);
	
	showResult();
	
}

function showResult()
{
	$('sequence').innerHTML = theSequence.display() ;
	r = theSequence.getProbaReroll2(false);
	result =  Math.round(r*100000)/1000;
	if(result <=0)result = r;
	$('probaNoReroll').innerHTML =result+' %' ;
	
	r = theSequence.getProbaReroll2(true);
	result =  Math.round(r*100000)/1000;
	if(result <=0)result = r;
	$('probaYesReroll').innerHTML = result+' %' ;
}

/* -------------------------------------------PLAYER OBJECT------------------------------------------------*/
function player()
{
	this.name = $('playerName').value;
	this.number = theSequence.playerNumber;
	this.isNewPlayer = true;
	name = ( this.name != '' )?': '+this.name:'';
	
	this.toString = 'other player'+name;
	this.color = 'blue';//todo set a random color for player changes
}

/* -----------------------------PLAYER CHANGING----------------------------------------------------------*/

function changePlayer()
{
	newPlayer = new player();
	theSequence.addAction(newPlayer);
	//$('sac').reset();
	$('sequence').innerHTML = theSequence.display() ;
}

/* -----------------------------USING PRO----------------------------------------------------------*/
function usePro(i)
{
	theSequence.sequence[i].canUseProSkill = true; //if not set, set to true, if we are in this function theSequence.sequence[i] must be a pro Action
	if(theSequence.sequence[i].doUseProSkill)theSequence.sequence[i].doUseProSkill = false;
	else theSequence.sequence[i].doUseProSkill = true;
	showResult();
}

/*-----------------------------Store the sequence --------------------------------------------------*/
var storeId = 0;
function storeSequence()
{
	if(localStorage == null) alert("Storage not supported, you have to upgrade your browser to allow this feature to work.");
	
	localStorage.setItem("sequence_"+storeId , JSON.stringify(theSequence.sequence) );
	
	text ='';
	text += '<div class="container" id="container_'+storeId+'">';
	
	for(i=0;i<theSequence.sequence.length;i++)
	{
		text += '<span class="sequence">'+theSequence.sequence[i].toString+'</span>';
		
		if( i != theSequence.sequence.length-1)text += ' - ';
	}
	
	text += '<div class="proba noreroll">'+$('probaNoReroll').innerHTML+'</div>';
	text += '<div class="proba yesreroll">'+$('probaYesReroll').innerHTML+'</div>';
	text += '<input type="button" onclick="loadSequence('+storeId+');" class="reset" value="Load"/>';
	text += '<input type="button" onclick="deleteSequence('+storeId+');" class="reset" value="Delete"/>';
	text += '</div>';
	$('storeContainer').innerHTML += text;
	storeId++;
}

function loadSequence(id)
{
	theSequence.sequence = JSON.parse(localStorage.getItem("sequence_"+id));
	showResult();
}
function deleteSequence(id)
{
	localStorage.removeItem("sequence_"+id);
	c=$('container_'+id);
	c.style.display='none';
	c.parentNode.removeChild(c);
}
</script>
<form id="sac" name="sac">
<div id="maincontent">
	
<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style " style="float:right;">
	<a class="addthis_button_preferred_1"></a>
	<a class="addthis_button_preferred_2"></a>
	<a class="addthis_button_preferred_3"></a>
	<a class="addthis_button_preferred_4"></a>
	<a class="addthis_button_compact"></a>
	<a class="addthis_counter addthis_bubble_style"></a>
	</div>
	<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-509a73c5585f3693"></script>
	<!-- AddThis Button END -->
	
	
	<!-- PLAYER -->
	<div class="player" >
		<h2>Player</h2>
		Name: <input type="text" id="playerName" class="playerName" name="playerName" value=""/> 
		<input type="button" value="Change player" class="changePlayer" onclick="changePlayer()" /><br/>
		Skills: 
		<input type="checkbox" value="" id="dodgeSkill" /><label for="dodgeSkill">Dodge</label>
		<input type="checkbox" value="" id="surehandSkill" /><label for="surehandSkill">Sure hands</label>
		<input type="checkbox" value="" id="surefeetSkill" /><label for="surefeetSkill">Sure feet</label>
		<input type="checkbox" value="" id="passSkill" /><label for="passSkill">Pass</label>
		<input type="checkbox" value="" id="catchSkill" /><label for="catchSkill">Catch</label>
		<input type="checkbox" value="" id="proSkill" /><label for="proSkill">Pro *</label>
		
		<input type="checkbox" value="" id="lonerSkill" /><label for="lonerSkill">Loner</label>
		<br/>
		
	</div>
	<!-- ACTIONS -->
	<div class="actionCluster">
		<h2>Dodges</h2>
		<input type="button" value="dodge 2+ " onclick="addAction('dodge2')"/>
		<input type="button" value="dodge 3+ " onclick="addAction('dodge3')"/>
		<input type="button" value="dodge 4+ " onclick="addAction('dodge4')"/>
		<input type="button" value="dodge 5+ " onclick="addAction('dodge5')"/>
		<input type="button" value="dodge 6+ " onclick="addAction('dodge6')"/>
		<br/>
		
	</div>
	<div class="actionCluster">
		<h2>Pick-up</h2>
		<input type="button" value="Pick-up 2+ " onclick="addAction('pickup2')"/>
		<input type="button" value="Pick-up 3+ " onclick="addAction('pickup3')"/>
		<input type="button" value="Pick-up 4+ " onclick="addAction('pickup4')"/>
		<input type="button" value="Pick-up 5+ " onclick="addAction('pickup5')"/>
		<input type="button" value="Pick-up 6+ " onclick="addAction('pickup6')"/>
		<br/>
		
	</div>
	
	<div class="actionCluster">
		<h2>Go For It</h2>
		<input type="button" value="gfi 2+ " onclick="addAction('gfi2')"/>
		<input style="width:120px" type="button" value="gfi 3+ (blizzard)" onclick="addAction('gfi3')"/>
		<br/>
		
	</div>
	

	<div class="actionCluster">
		<h2>Passes</h2>
		<input type="button" value="pass 2+ " onclick="addAction('pass2')"/>
		<input type="button" value="pass 3+ " onclick="addAction('pass3')"/>
		<input type="button" value="pass 4+ " onclick="addAction('pass4')"/>
		<input type="button" value="pass 5+ " onclick="addAction('pass5')"/>
		<input type="button" value="pass 6+ " onclick="addAction('pass6')"/>
		<br/>
		<b>Interception **:</b> 
		<input class="interception" type="radio" value="interception No" checked="checked" name="interception" id="interceptionNo" />		
		<label class="interceptionLabel" for="interceptionNo" checked="checked">No</label>
		<br/>
		Roll:
		<input  class="interception" type="radio" value="interception 2+" name="interception" id="interception2" />		
		<label class="interceptionLabel" for="interception2">2+</label>
		<input class="interception" type="radio" value="interception 3+" name="interception" id="interception3" />		
		<label class="interceptionLabel" for="interception3">3+</label>
		<input class="interception" type="radio" value="interception 4+" name="interception" id="interception4" />		
		<label class="interceptionLabel" for="interception4">4+</label>
		<input class="interception" type="radio" value="interception 5+" name="interception" id="interception5" />		
		<label class="interceptionLabel" for="interception5">5+</label>
		<input class="interception" type="radio" value="interception 6+" name="interception" id="interception6" />		
		<label class="interceptionLabel" for="interception6">6+</label>
		<br/>
		Skills :
		<input class="interception" type="checkbox" value="interception with Pro skill" name="interceptionPro" id="interceptionWithPro" />
		<label class="interceptionLabel" for="interceptionWithPro" checked="checked">Pro</label>
		<input class="interception" type="checkbox" value="interception with Catch skill" name="interceptionCatch" id="interceptionWithCatch" />
		<label class="interceptionLabel" for="interceptionWithCatch" checked="checked">Catch</label>

	</div>
	
	
	<div class="actionCluster">
		<h2>Other Action</h2>
		<input type="button" value="action 2+ " onclick="addAction('action2')"/>
		<input type="button" value="action 3+ " onclick="addAction('action3')"/>
		<input type="button" value="action 4+ " onclick="addAction('action4')"/>
		<input type="button" value="action 5+ " onclick="addAction('action5')"/>
		<input type="button" value="action 6+ " onclick="addAction('action6')"/>
	</div>
	
	<div class="actionCluster">
		<h2>Catch</h2>
		<input type="button" value="catch 2+ " onclick="addAction('catch2')"/>
		<input type="button" value="catch 3+ " onclick="addAction('catch3')"/>
		<input type="button" value="catch 4+ " onclick="addAction('catch4')"/>
		<input type="button" value="catch 5+ " onclick="addAction('catch5')"/>
		<input type="button" value="catch 6+ " onclick="addAction('catch6')"/>
		<br/>
		
	</div>
	<div class="actionCluster blockblock" style="text-align:left;">
		<h2>Blocks</h2> 
		<b>Dice</b>
		<br/>
		<br/>
		<input type="radio" name="nbDice" class="nbDice" id="nbDiceM3" value="-3"/><label for="nbDiceM3">-3D</label>
		<input type="radio" name="nbDice" class="nbDice" id="nbDiceM2" value="-2"/><label for="nbDiceM2">-2D</label>
		<input type="radio" name="nbDice" class="nbDice" id="nbDice1" value="1" checked="checked"/><label for="nbDice1">1D</label>
		<input type="radio" name="nbDice" class="nbDice" id="nbDice2" value="2"/><label for="nbDice2">2D</label>
		<input type="radio" name="nbDice" class="nbDice" id="nbDice3" value="3"/><label for="nbDice3">3D</label>
		<br/><br/>
		<b>Success</b>
		<br/><br/>
		<input type="checkbox" class="successDice" name="blockSuccess" id="blockSuccessSkull" value="1"/><label for="blockSuccessSkull"><img src="images/skull.jpg" alt="skull"/></label>
		<input type="checkbox" class="successDice" name="blockSuccess" id="blockSuccessPowSkull" value="1"/><label for="blockSuccessPowSkull"><img src="images/powskull.jpg" alt="powskull"/></label>
		<input type="checkbox" class="successDice" name="blockSuccess" id="blockSuccessPush" value="2"/><label for="blockSuccessPush"><img src="images/push.jpg" alt="push"/></label>
		<input type="checkbox" class="successDice" name="blockSuccess" id="blockSuccessStumble" checked="checked" value="1"/><label for="blockSuccessStumble"><img src="images/stumbles.jpg" alt="stumbles"/></label>
		<input type="checkbox" class="successDice" name="blockSuccess" id="blockSuccessDown" checked="checked" value="1"/><label for="blockSuccessDown"><img src="images/pow.jpg" alt="pow"/></label>
		
		<br/>
		<br/>
		<input type="button" value="add" class="add" onclick="addAction('block')" />
	</div>

	<div class="actionCluster armorbreak" style="text-align:left;">
		<h2>Armor Break</h2>
		
		<input type="button" value="-" class="armordown" onclick="if($('armorvalue').value>1)$('armorvalue').value--" />
		<input type="text" id="armorvalue" value="8" disabled="disabled"/>
		<input type="button" value="+" class="armorup" onclick="if($('armorvalue').value<12)$('armorvalue').value++" />
		<br/><br/>
		<input type="button" value="add" class="add" onclick="addAction('armorbreak')" />
	</div>
	<div class="actionCluster injury" style="text-align:left;">
		<h2>Injury Roll</h2>
		<input class="" type="checkbox" value="" name="injuryStun" id="injuryStun" />
		<label class="injuryLabel" for="injuryStun" checked="checked">stun</label>
		<input class="" type="checkbox" value="" name="injuryKO" id="injuryKO" />
		<label class="injuryLabel" for="injuryKO" checked="checked">KO</label>
		<input class="" type="checkbox" value="" name="injuryCas" id="injuryCas" />
		<label class="injuryLabel" for="injuryCas" checked="checked">CAS</label>
		<br/><br/>
		<input type="button" value="add" class="add" onclick="addAction('injury')" />
	</div>
	
	<div style="clear:both; height: 60px;"></div>
	<h2>Sequence</h2>
	<div id="sequence">Choose actions to display the sequence</div>
	
	<h2>Success percent Without team reroll</h2>
	<div id="probaNoReroll" >100%</div>
	
	<h2>Success percent With a team reroll</h2>
	<div id="probaYesReroll" >100%</div>
	<input type="button" onclick="theSequence = new fullSequence();showResult();" class="reset" value="reset"/>
	<input type="button" onclick="storeSequence();" class="reset" value="Store"/>
	
	<div id="storeContainer"><h3>Stored Sequences</h3>...</div>
	
	<div class="warning">
	Warning: this tool will(should) give you the odds for a precise sequence. It will not demonstrate that Nuffle hates you. 
	<br/>
	Warning: this tool is still in development, it may display errors in the calculations. If you do spot any error, please report it to Elyoukey (if you do spot errors you are smart and interested enough to not need more information to track me). Thanks in advance. <br/>
	<br/>
	English not being my mother tongue, this tool may also display English errors, feel free to report those too. 
	<br/><br/>
	

	 </div>
	<div class="notices">
	* Pro reroll: the system by default will assume that you are using the pro skill only if there is no team reroll left. 
	To change this behavior for an action, use the checkbox in the actionbox. The system will then calculate the odds assuming that you will use the pro skill prior to a reroll but will use the reroll if the pro roll fails.
	It may lead to little odd increase for several sequences (for exemple : action5+ action5+ action3+ action6+ the pro skill should be used on first roll)
	<br/><br/>
	** Interception: the sequence displays <span class="negative">in red the probability for the pass to NOT be intercepted</span> ( = 1-odd to succeed the interception) 
	</div>
</div>
</form>
<!--
If you read this, you are probably able to help me improve this tool. Feel free to contact me with any input you have: elyoukey_at_gmail.com

Release Notes:
 v1.1: 
  -Add the "Interception" in the passing calculation. Odd for the interception is displayed in the action blocks.
  -Add the "pro" calculation and modify display to fit.
 v1.2:
  -add the storage with localStorage features
 v1.3:
 -add the addthis bar
 -add link to the itunes version
 v1.4:
 -add the armor break odds
 next TODO: add injury roll and implement mightyblow and piling on
v1.5:
-add the sac.manifest for offline consulting
 -->