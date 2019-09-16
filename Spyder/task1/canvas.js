function gamearea(){
document.getElementById('gamearea').style.display = "block";
document.getElementById('cover').style.display = "none";
}
function playsolo(){
var i=0,vel=2,savevel=2,j=0,count=25000,name1,x,b=[],index,current;
var ind1,ind2;
var coin=new Image();
coin.src="1.png";
function createtable() {
  var table = document.getElementById('SB');
  var row,cell1,cell2;
  if(b.length>0)
  {
     b.sort(function(a, b){return b.score -a.score});
     for(index=1;index<=b.length;++index)
     {
       row=table.insertRow(index);
       cell1 = row.insertCell(0);
       cell2 = row.insertCell(1);
       cell1.innerHTML = b[index-1].name;
       cell2.innerHTML = b[index-1].score;
     } 
   }
}
function newrowtable(){
   var table = document.getElementById('SB');
   var row,cell1,cell2;
   row=table.insertRow(b.length+1);
   cell1 = row.insertCell(0);
   cell2 = row.insertCell(1);
   cell1.innerHTML = duo1.name;
   cell2.innerHTML = duo1.score;
   current=b.length+1;
}
function updatetable(){
    var ind1,ind2,p,q;
    document.getElementById('SB').rows[current].cells[1].innerHTML=duo1.score;
    if(b.length>0)
    {
    ind1=document.getElementById('SB').rows[current].cells[1].innerHTML;
    ind2=document.getElementById('SB').rows[current-1].cells[1].innerHTML;
    if((ind1>ind2)&&(current>1)){
            p=document.getElementById('SB').rows[current].cells[0].innerHTML;
            q=document.getElementById('SB').rows[current-1].cells[0].innerHTML;
            document.getElementById('SB').rows[current-1].cells[1].innerHTML=ind1;
            document.getElementById('SB').rows[current-1].cells[0].innerHTML=p;
            document.getElementById('SB').rows[current].cells[1].innerHTML=ind2;
            document.getElementById('SB').rows[current].cells[0].innerHTML=q;
            current=current-1;
    }
    } 
}
x=document.getElementById('username').value;
var canvas=document.querySelector('canvas');
var highscore=0;
if (localStorage.hasOwnProperty("highsco")) {
    highscore=parseInt(localStorage.getItem("highsco"));
}
else
    localStorage.setItem("highsco",highscore);
canvas.width=800;
canvas.height=1500;
var c = canvas.getContext('2d');
class duo{
constructor(){
    this.x=canvas.width/2;
    this.r=180;
    this.y=(canvas.height-this.r )-20;
    this.vy=10;
    this.angle=0;
    this.dangle=Math.PI*0.04;
    this.p=0;
    this.q=0;
    this.s=0;
    this.t=0;
    this.score=0;
    this.name=x;
    this.lvl=1;
    }
    initialise(){
    this.x=canvas.width/2;
    this.r=180;
    this.y=(canvas.height-this.r )-20;
    this.vy=10;
    this.angle=0;
    this.dangle=Math.PI*0.04;
    this.p=0;
    this.q=0;
    this.s=0;
    this.t=0;
    this.score=0;
    this.lvl=1;   
    }
    build(){
    if((this.score%50==0)&&(this.score>0)&&(this.r>3))
    {
        this.r=this.r-3;
    }
    if(this.score%50==30)
    {
        this.r=180;
    }
    this.p=this.x + (this.r * Math.cos(this.angle));
    this.q=this.y + (this.r * Math.sin(this.angle));
    this.s=this.x - (this.r * Math.cos(this.angle));
    this.t=this.y - (this.r * Math.sin(this.angle));
    c.beginPath();
    c.lineWidth=5;
    c.strokeStyle='rgb(0,255,0)';
    c.arc(this.x,this.y,this.r,0,2*Math.PI,true);
    c.stroke();
    c.beginPath();
    c.strokeStyle='rgb(255,0,0)';
    c.lineWidth=20;
    c.arc(this.p,this.q,10,0,2*Math.PI,true);
    c.stroke();
    c.beginPath();
    c.strokeStyle='rgb(0,0,255)';
    c.lineWidth=20;
    c.arc(this.s,this.t,10,0,2*Math.PI,true);   
    c.stroke();
    }
    cwrotate(){
    this.angle=this.angle+this.dangle;
    }
    awrotate(){
    this.angle=this.angle-this.dangle;
    }

}
function restart(){
  for(i=0;i<3;i++)
  {
    obss[i].initialise();
   }
   b.push(duo1);
   localStorage.setItem("players",JSON.stringify(b));
   b=JSON.parse(localStorage.getItem("players"));
   duo1.initialise();
   powerup.y=1510;
   newrowtable();
}
function rotate(event){
    if(event.keyCode==37)
    {
        duo1.awrotate();
        if(duo1.r<10)
            duo1.x-=10;
        else
            duo1.x=400;
    }
    else if(event.keyCode==39)       
    {
        duo1.cwrotate();
        if(duo1.r<10)
        {
            duo1.x+=10;
        }
        else
            duo1.x=400;
    }
    else if(event.keyCode==32)
        { 

          if(duo1.dangle>0)
          {
            duo1.dangle=0;
          }
          else if(duo1.dangle==0)
          {
             duo1.dangle=Math.PI*0.04;
          }
          if(vel>0)
          {
            savevel=vel;
            vel=0;
          }
          else if(vel==0)
          {
             vel=savevel;
          }
        }
    else if(event.keyCode==18)
    {
        restart();
    }
}
class sprite{
    constructor(){
    this.x=(duo1.x-duo1.r)+ (duo1.r*2)*Math.random();
    this.y=1510;
    this.wid=40;
    this.vy=savevel;
    this.disx1;
    this.disx2;
    this.disy1;
    this.disy2;
    this.rad1;
    this.rad2;
    }
    initialise(){
    this.x=(duo1.x-duo1.r)+ (duo1.r*2)*Math.random();
    this.y=0;
    this.wid=40;
    this.vy=savevel;
    }
    build(){
    c.drawImage(coin,0,0,46,46,this.x,this.y,40,40);
    }
    falldown(){
        this.vy=vel;
        this.y=this.y+this.vy;  
        this.build();                                                                                                                                                                                                                             
    }
    coldet(){
        if(duo1.p<this.x)
            {
            this.disx1=this.x-duo1.p;
            }
        else if(duo1.p>this.x+this.wid)
            {
            this.disx1=duo1.p-this.wid-this.x;
            }
        else
            {
            this.disx1=0;
            }
        if (duo1.q<this.y)
            {
            this.disy1=this.y-duo1.q;
            }
        else if(duo1.q>this.y+40)
            {
            this.disy1=duo1.q-this.y-40;   
            }
        else 
            {
             this.disy1=0;
            }
        if(duo1.s<this.x)
            {
            this.disx2=this.x-duo1.s;
            }
        else if(duo1.s>this.x+this.wid)
            {
            this.disx2=duo1.s-this.wid-this.x;
            }
        else
            {
            this.disx2=0;   
            }
        if (duo1.t<this.y)
            {
            this.disy2=this.y-duo1.t;
            }
        else if(duo1.t>this.y+40)
            {
            this.disy2=duo1.t-this.y-40;   
            }
        else
            {
            this.disy2=0;
            }
        this.rad1=Math.sqrt((this.disx1*this.disx1)+(this.disy1*this.disy1));
        this.rad2=Math.sqrt((this.disx2*this.disx2)+(this.disy2*this.disy2));
        if(this.rad1<=17)
        {
            alert("powerup");
            this.y=1510;
            duo1.dangle=Math.PI*0.06;
            count=1;
        }
        if (this.rad2<=17)
        {
            alert("powerup");
            this.y=1510;
            duo1.dangle=Math.PI*0.06;
            count=1;
        }

    }
    meter(){
        c.fillStyle='rgb(0,255,0)';
        c.fillRect(0,0,800*(duo1.score%210)*0.005,10);
        c.fillStyle='rgb(255,0,0)';
    }

}
class obstacle{
    constructor(){
        this.vy=0;
        this.wid= (360)-25;
        this.x=(Math.random() * canvas.width)-this.wid;
        if(this.x<0)
            this.x=-this.x;
        if(this.x>(duo1.x-duo1.r-40) && this.x<duo1.x)
            this.wid=this.wid*0.6;
        this.y=0;
        this.disx1;
        this.disx2;
        this.disy1;
        this.disy2;
        this.rad1;
        this.rad2;
    }
    initialise(){
        this.x=(Math.random() * canvas.width)-this.wid;
        if(this.x<0)
            this.x=-this.x;
        if((this.x>360-duo1.r) && this.x<400)
            this.wid=this.wid*0.6;
        this.y=0;
        this.vy=0;
        this.wid=(duo1.r*2)-25;
    }
    build(){
        if(this.y>0)
        {
         c.fillRect(this.x,this.y,this.wid,10);
         c.fillStyle='rgb(255,0,0)';
        }
    }
    falldown(){
        this.y=this.y+this.vy;  
        this.build();                                                                                                                                                                                                                             
    }
    coldet(){
        if(duo1.p<this.x)
            {
            this.disx1=this.x-duo1.p;
            }
        else if(duo1.p>this.x+this.wid)
            {
            this.disx1=duo1.p-this.wid-this.x;
            }
        else
            {
            this.disx1=0;
            }
        if (duo1.q<this.y)
            {
            this.disy1=this.y-duo1.q;
            }
        else if(duo1.q>this.y+10)
            {
            this.disy1=duo1.q-this.y-10;   
            }
        else 
            {
             this.disy1=0;
            }
        if(duo1.s<this.x)
            {
            this.disx2=this.x-duo1.s;
            }
        else if(duo1.s>this.x+this.wid)
            {
            this.disx2=duo1.s-this.wid-this.x;
            }
        else
            {
            this.disx2=0;   
            }
        if (duo1.t<this.y)
            {
            this.disy2=this.y-duo1.t;
            }
        else if(duo1.t>this.y+10)
            {
            this.disy2=duo1.t-this.y-10;   
            }
        else
            {
            this.disy2=0;
            }
        this.rad1=Math.sqrt((this.disx1*this.disx1)+(this.disy1*this.disy1));
        this.rad2=Math.sqrt((this.disx2*this.disx2)+(this.disy2*this.disy2));
        if(this.rad1<=17)
        {
            alert("collision");
            restart();
        }
        if (this.rad2<=17)
        {
            alert("collision");
            restart();
        }

    }
}
var obss = [];
var duo1 = new duo();
var powerup=new sprite();
if (localStorage.hasOwnProperty("players")) {
    b=JSON.parse(localStorage.getItem("players"));
}
console.log(b.length);
  createtable();
  newrowtable();
for(i=0;i<3;++i)
{
    obss.push(new obstacle());
}
obss[0].vy=2;
function animate()
{
	c.clearRect(0,0,window.innerWidth,window.innerHeight);         
 	   for(i=0;i<3;i++)
	   {
		obss[i].falldown();
		obss[i].coldet();
	    if(obss[i].y>=(canvas.height-4))
	      {   
		      obss[i].initialise();
		      duo1.score+=10;
              for(j=0;j<=b.length;++j)
                 updatetable();
              duo1.lvl=duo1.lvl+Math.floor(duo1.score/300);  //level increase for every 300points
              vel=2+duo1.lvl;                                //speed increases as level increases
              savevel=vel;
              if(duo1.score>highscore)
              {
                highscore=duo1.score;
                localStorage.setItem('highsco',highscore);
              }
		  }
		if((obss[0].y >= i*0.33*canvas.height)&&(duo1.score==0))
		  {
		 		obss[i].vy=vel;
		  }
		if(duo1.score>=10)
          {
		 	obss[i].vy=vel;
          }
	    } 
    for(i=0;i<3;i++)
       if((duo1.score%50==0)&&(obss[i].y>0.16*canvas.height)&&(obss[i].y<0.17*canvas.height)&&(duo1.score>0))
          powerup.initialise();
    if(powerup.y<1510)
    {
        powerup.falldown();
        powerup.coldet();
    }
	window.addEventListener("keydown",rotate,true);
	duo1.build();
    powerup.meter();
    if(count<1000*50*5)
        count++;
    if(count>=1000*50*5)
        duo1.dangle=Math.PI*0.04;
}
setInterval(animate,1000/50);
}
/*dual mode*/
function playdual(){
var i=0,vel=2,savevel=2,count=25000;
var canvas=document.querySelector('canvas');
var highscore=0;
if (localStorage.hasOwnProperty("highsco")) {
    highscore=parseInt(localStorage.getItem("highsco"));
}
else
    localStorage.setItem("highsco",highscore);
canvas.width=800;
canvas.height=1500;
var coin=new Image();
coin.src="1.png";
var c = canvas.getContext('2d');
function createtable() {
  var table = document.getElementById('SB');
  var row,cell1,cell2;
   row=table.insertRow(1);
   cell1 = row.insertCell(0);
   cell2 = row.insertCell(1);
   cell1.innerHTML = document.getElementById('username1').value;
   cell2.innerHTML = 0;
   row=table.insertRow(2);
   cell1 = row.insertCell(0);
   cell2 = row.insertCell(1);
   cell1.innerHTML = document.getElementById('username2').value;
   cell2.innerHTML = 0;
}
function updatetable(){
    document.getElementById('SB').rows[1].cells[1].innerHTML=duo1.score1;
    document.getElementById('SB').rows[2].cells[1].innerHTML=duo1.score2;
}
class duo{
constructor(){
    this.x=canvas.width/2;
    this.r=180;
    this.y=(canvas.height-this.r )-20;
    this.vy=10;
    this.angle=0;
    this.dangle=Math.PI*0.04;
    this.p=0;
    this.q=0;
    this.s=0;
    this.t=0;
    this.score1=0;
    this.score2=0;
    this.score=0;
    this.turn=0;
    this.lvl=1;
    }
    initialise(){
    this.x=canvas.width/2;
    this.r=180;
    this.y=(canvas.height-this.r )-20;
    this.vy=10;
    this.angle=0;
    this.dangle=Math.PI*0.04;
    this.p=0;
    this.q=0;
    this.s=0;
    this.t=0;
    this.score=0;
    this.lvl=1;   
    }
    build(){
    this.p=this.x + (this.r * Math.cos(this.angle));
    this.q=this.y + (this.r * Math.sin(this.angle));
    this.s=this.x - (this.r * Math.cos(this.angle));
    this.t=this.y - (this.r * Math.sin(this.angle));
    c.beginPath();
    c.lineWidth=5;
    c.strokeStyle='rgb(0,255,0)';
    c.arc(this.x,this.y,this.r,0,2*Math.PI,true);
    c.stroke();
    c.beginPath();
    c.strokeStyle='rgb(255,0,0)';
    c.lineWidth=20;
    c.arc(this.p,this.q,10,0,2*Math.PI,true);
    c.stroke();
    c.beginPath();
    c.strokeStyle='rgb(0,0,255)';
    c.lineWidth=20;
    c.arc(this.s,this.t,10,0,2*Math.PI,true);   
    c.stroke();
    }
    cwrotate(){
    this.angle=this.angle+this.dangle;
    this.build();
    }
    awrotate(){
    this.angle=this.angle-this.dangle;
    this.build();
    }

}
class sprite{
    constructor(){
    this.x=(duo1.x-duo1.r)+ (duo1.r*2)*Math.random();
    this.y=1510;
    this.wid=40;
    this.vy=savevel;
    this.disx1;
    this.disx2;
    this.disy1;
    this.disy2;
    this.rad1;
    this.rad2;
    }
    initialise(){
    this.x=(duo1.x-duo1.r)+ (duo1.r*2)*Math.random();
    this.y=0;
    this.wid=40;
    this.vy=savevel;
    }
    build(){
    c.drawImage(coin,0,0,46,46,this.x,this.y,40,40);
    }
    falldown(){
        this.vy=vel;
        this.y=this.y+this.vy;  
        this.build();                                                                                                                                                                                                                             
    }
    coldet(){
        if(duo1.p<this.x)
            {
            this.disx1=this.x-duo1.p;
            }
        else if(duo1.p>this.x+this.wid)
            {
            this.disx1=duo1.p-this.wid-this.x;
            }
        else
            {
            this.disx1=0;
            }
        if (duo1.q<this.y)
            {
            this.disy1=this.y-duo1.q;
            }
        else if(duo1.q>this.y+40)
            {
            this.disy1=duo1.q-this.y-40;   
            }
        else 
            {
             this.disy1=0;
            }
        if(duo1.s<this.x)
            {
            this.disx2=this.x-duo1.s;
            }
        else if(duo1.s>this.x+this.wid)
            {
            this.disx2=duo1.s-this.wid-this.x;
            }
        else
            {
            this.disx2=0;   
            }
        if (duo1.t<this.y)
            {
            this.disy2=this.y-duo1.t;
            }
        else if(duo1.t>this.y+40)
            {
            this.disy2=duo1.t-this.y-40;   
            }
        else
            {
            this.disy2=0;
            }
        this.rad1=Math.sqrt((this.disx1*this.disx1)+(this.disy1*this.disy1));
        this.rad2=Math.sqrt((this.disx2*this.disx2)+(this.disy2*this.disy2));
        if(this.rad1<=17)
        {
            alert("powerup");
            this.y=1510;
            duo1.dangle=Math.PI*0.06;
            count=1;
        }
        if (this.rad2<=17)
        {
            alert("powerup");
            this.y=1510;
            duo1.dangle=Math.PI*0.06;
            count=1;
        }

    }

}
function restart(){
  for(i=0;i<3;i++)
  {
    obss[i].initialise();
   }
   if(duo1.turn==0)
    {
      duo1.initialise();
      powerup.y=1510;
      duo1.turn=1;
    }
    else if(duo1.turn==1)
    {  
        powerup.y=1510;
        if(duo1.score1>duo1.score2)
            alert("PLAYER1 WON");
        if(duo1.score1<duo1.score2)
            alert("PLAYER 2 WON");
    }   
}
function rotate(event){
    if(event.keyCode==37)
        duo1.awrotate();
    else if(event.keyCode==39)       
        duo1.cwrotate();
    else if(event.keyCode==32)
        { 

          if(duo1.dangle>0)
          {
            duo1.dangle=0;
          }
          else if(duo1.dangle==0)
          {
             duo1.dangle=Math.PI*0.04;
          }
          if(vel>0)
          {
            savevel=vel;
            vel=0;
          }
          else if(vel==0)
          {
             vel=savevel;
          }
        }
    else if(event.keyCode==18)
    {
        restart();
    }
}
class obstacle{
    constructor(){
        this.vy=0;
        this.wid= (duo1.r*2)-25;
        this.x=(Math.random() * canvas.width)-this.wid;
        if(this.x<0)
            this.x=-this.x;
        if(this.x>duo1.x-duo1.r && this.x<duo1.x)
            this.wid=this.wid*0.6;
        this.y=-10;
        this.disx1;
        this.disx2;
        this.disy1;
        this.disy2;
        this.rad1;
        this.rad2;
    }
    initialise(){
        this.x=(Math.random() * canvas.width)-this.wid;
        if(this.x<0)
            this.x=-this.x;
        if(this.x>(duo1.x-duo1.r-40) && this.x<duo1.x)
            this.wid=this.wid*0.6;
        this.y=0;
        this.vy=0;
        this.wid=(duo1.r*2)-25;
    }
    build(){
        if(this.y>0)
        {
         c.fillRect(this.x,this.y,this.wid,10);
         c.fillStyle='rgb(255,0,0)';
        }
    }
    falldown(){
        this.y=this.y+this.vy;  
        this.build();                                                                                                                                                                                                                             
    }
    coldet(){
        if(duo1.p<this.x)
            {
            this.disx1=this.x-duo1.p;
            }
        else if(duo1.p>this.x+this.wid)
            {
            this.disx1=duo1.p-this.wid-this.x;
            }
        else
            {
            this.disx1=0;
            }
        if (duo1.q<this.y)
            {
            this.disy1=this.y-duo1.q;
            }
        else if(duo1.q>this.y+10)
            {
            this.disy1=duo1.q-this.y-10;   
            }
        else 
            {
             this.disy1=0;
            }
        if(duo1.s<this.x)
            {
            this.disx2=this.x-duo1.s;
            }
        else if(duo1.s>this.x+this.wid)
            {
            this.disx2=duo1.s-this.wid-this.x;
            }
        else
            {
            this.disx2=0;   
            }
        if (duo1.t<this.y)
            {
            this.disy2=this.y-duo1.t;
            }
        else if(duo1.t>this.y+10)
            {
            this.disy2=duo1.t-this.y-10;   
            }
        else
            {
            this.disy2=0;
            }
        this.rad1=Math.sqrt((this.disx1*this.disx1)+(this.disy1*this.disy1));
        this.rad2=Math.sqrt((this.disx2*this.disx2)+(this.disy2*this.disy2));
        if(this.rad1<=17)
        {
            alert("collision");
            if(duo1.turn==0)
                duo1.score1=duo1.score;
            else if(duo1.turn==1)
                duo1.score2=duo1.score;
            restart();
        }
        if (this.rad2<=17)
        {
            alert("collision");
            if(duo1.turn==0)
                duo1.score1=duo1.score;
            else if(duo1.turn==1)
                duo1.score2=duo1.score;
            restart();
        }

    }
}
var obss = [];
var duo1 = new duo();
var powerup=new sprite();
for(i=0;i<3;++i)
{
    obss.push(new obstacle());
}
obss[0].vy=2;
createtable();
function animate()
{
    requestAnimationFrame(animate);
    c.clearRect(0,0,window.innerWidth,window.innerHeight);         
       for(i=0;i<3;i++)
       {
        obss[i].falldown();
        obss[i].coldet();
        if(obss[i].y>=canvas.height)
          {
              obss[i].initialise();
              duo1.score+=10;
              duo1.lvl=duo1.lvl+Math.floor(duo1.score/300);
              vel=2+duo1.lvl;
              updatetable();
              if(duo1.score>highscore)
              {
                highscore=duo1.score;
                localStorage.setItem('highsco',highscore);
              }
          }
        if((obss[0].y >= i*0.33*canvas.height)&&(duo1.score==0))
          {
                obss[i].vy=vel;
          }
        if(duo1.score>=10)
          {
            obss[i].vy=vel;
          }
        } 
    for(i=0;i<3;i++)
       if((duo1.score%50==0)&&(obss[i].y>0.16*canvas.height)&&(obss[i].y<0.17*canvas.height)&&(duo1.score>0))
          powerup.initialise();
    if(powerup.y<1510)
    {
        powerup.falldown();
        powerup.coldet();
    }
    window.addEventListener("keydown",rotate,true);
    duo1.build();
    if(count<1000*50*5)
        count++;
    if(count>=1000*50*5)
        duo1.dangle=Math.PI*0.04;
}
animate();
}
