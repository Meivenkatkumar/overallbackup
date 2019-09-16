var m=0,x,y,z;
var  a={},b={},c={},d={};
class heal
{
   constructor()
   {
     this.nam = "user";
     this.age = 0;
     this.heigt =0;
     this.weigt = 0;
     this.h2oin = 0;
     this.food = [];
     this.sum = 0;
     this.bmi=0;
   }
    
   initialise()                                        //function to be used by global object
   {
     this.nam = document.getElementById('name1').value;
     this.age = document.getElementById('Age').value;
     this.heigt = document.getElementById('Height').value;
     this.weigt = document.getElementById('Weight').value;
     this.h2oin = document.getElementById('H2OIN').value;      //is this way of array assignment correct?
     this.bmi = ((this.sum==0)||(this.weigt/this.heigt)/this.heigt)*10000;
     if((this.nam == "user")||(this.age==0)||(this.weigt==0)||(this.heigt==0)||(this.bmi==0))
     {
      alert("Invalid Entry");
     }
     else
     {
        if(this.h2oin < 2)
        {
           alert("Drink more water");
        }
        a.name=document.getElementById('foodname1').value;
        a.cal = document.getElementById('Calin1').value;
        this.food.push(a);
        b.name=document.getElementById('foodname2').value;
        b.cal=document.getElementById('Calin2').value;
        this.food.push(b);
        c.name=document.getElementById('foodname3').value;
        c.cal=document.getElementById('Calin3').value;
        this.food.push(c);
        for(m=0;m<this.food.length;++m)
         {
            this.sum+=parseInt(this.food[m].cal);
         }
      if(this.sum>0)
        document.getElementById("caltot").innerHTML= "<strong>Total Calorie Intake:</strong>"+this.sum;       //sets the p tag to output the sum value in webpage
     }
   } 
}
  
var heal1 = new heal;                                          //global object

function saveheal()                                            //saves progress(havent yet started working on that)
{
   localStorage.setItem("Healobj",JSON.stringify(heal1));
}   
function seeold()
{
  document.getElementById('report').style.display = "block";
  var sugh2o,rounded,fixed;
  heal1=JSON.parse(localStorage.getItem("Healobj"));
  y=document.getElementById("uname1").value;
  if(heal1.nam ==y)
  {
  document.getElementById("ouname").innerHTML= "<strong>Happy to see you again "+heal1.nam+"<br>";
  document.getElementById("oage").innerHTML= "<strong>You are "+heal1.age+" years old, "+heal1.heigt+ "cms tall and "+heal1.weigt+"KG<br>";
  fixed=heal1.h2oin;
  rounded=Math.round(fixed*100)/100;
  document.getElementById("oh2oin").innerHTML= "<strong>"+rounded+"lts/day";
  fixed=heal1.bmi;
  rounded=Math.round(fixed*100)/100;
  document.getElementById("bmi1").innerHTML="<strong>"+rounded+"";
  if(heal1.age<30)
  {
  sugh2o=(heal1.weigt/28.3)*0.029*40;
  }
  if((heal1.age<55)&&(heal1.age>=30))
  {
  sugh2o=(heal1.weigt/28.3)*0.029*35;
  }
  if(heal1.age>=50)
  {
  sugh2o=(heal1.weigt/28.3)*0.029*30;
  }
  fixed=sugh2o;
  rounded=Math.round(fixed*100)/100;
  if(rounded<2)
    rounded=2;
  document.getElementById("sug1").innerHTML="<strong>"+rounded+"";
  document.getElementById("sug2").innerHTML="<strong>18.5-24.9";
  if(heal1.age<15)
  document.getElementById("sug3").innerHTML="<strong>1600-1800 Kcal";
  if((heal1.age>=15)&&(heal1.age<30))
  document.getElementById("sug3").innerHTML="<strong>2200-2800 Kcal";
  if((heal1.age>=30))
  document.getElementById("sug3").innerHTML="<strong>2000-2400 Kcal";
  heal1.sum=0;
  for(m=0;m<heal1.food.length;++m)
      {
       document.getElementById("foodie").innerHTML+=heal1.food[m].name+" with "+heal1.food[m].cal+"calories<br>";
       heal1.sum+=parseInt(heal1.food[m].cal);
      }
  document.getElementById("ocaltot").innerHTML= "<strong>"+heal1.sum+"";
  if(heal1.sum<1600)
  {
  document.getElementById("suggestion").innerHTML="Eat some more healthy food";
  }if(heal1.sum>3000)
  {document.getElementById("suggestion").innerHTML="Burn some calories and follow stricct diet";}
  }
  else
  {
    alert("Wrong Username");
  }
  localStorage.setItem("Healobj",JSON.stringify(heal1));
}    
   
function createuser()                                           //onclick invoked function
{  
   heal1.initialise();                                         //class method
}

function addfoodheal()                                          //onclick invoked function
{
   x=document.getElementById('fooname1').value;
   z=document.getElementById('cal1').value;
   y=document.getElementById('uname').value;
   heal1=JSON.parse(localStorage.getItem("Healobj"));
  if(heal1.nam ==y)
  {
   d.name=x;
   d.cal=z;
   if(d.cal>0)
   {
   heal1.food.push(d);
   }
   else
     {
      alert("Invalid Calorie entry");
     }  
  }                               //pushes the food array into corresponding uname
  else
  {
    alert("Wrong Username");
  }
   localStorage.setItem("Healobj",JSON.stringify(heal1));                                            //class method
 }


function removefoodheal()                                       //onclick invoked function
{
     y=document.getElementById('fooname1').value;
     heal1=JSON.parse(localStorage.getItem("Healobj"));
     for(m=0;m<heal1.food.length;++m)                           
     {
         if(heal1.food[m].name === y)
         {
            console.log("found element");
            heal1.food.splice(m,1);                           //removes the last extra copy element 
         }
     }
     localStorage.setItem("Healobj",JSON.stringify(heal1));
}                                         
function clearstorage()
{
  localStorage.clear();
  alert("localStorage cleared");
}
