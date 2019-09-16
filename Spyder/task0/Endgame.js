var j=0,i=0;
console.log("hi");
var picz =new Array ("main-qimg-fe5902e1836a12fdcc7281f19443db39.jpeg",
           "main-qimg-3b97add6a3131d5a44633b188f4c75fe.jpeg",
           "main-qimg-9160e21c474eda345e3ab3b9d677ab9c.jpeg",
           "main-qimg-4ce343c2a3cf8adff2958d6a6c59a767.jpeg",
           "main-qimg-d4989071d7d2d182ac35b7df05b18fcc.jpeg");
function changpic(){
   document.getElementById('image').src=picz[i];
    i=i+1;
	if(i==5)
		 i=0;
}

var imgs = new Array ("index2.jpeg",
            "index11.jpeg",
            "index0.jpeg",
            "endgame_captain_america_mjolnir_primary.jpg",
            "_419e7b50-44c5-11e9-9b94-c272560e6ac5.jpg");

function changimg(){
	document.getElementById('capam').src=imgs[j];
	j++;
	if(j == 5)
		j=0;
}
setInterval( changimg() , 5000);
setInterval( changpic() , 5000);