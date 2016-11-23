requirejs.config({
	paths:{
	},
	urlArgs:"bust" + (new Date()).getTime(),
});

<<<<<<< HEAD
requirejs(['cookie','public_parameters','citylist','store','choosedate','common'],function($){
=======
requirejs(['common','cookie','public_parameters','citylist','store','choosedate'],function($){
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
})