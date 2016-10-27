requirejs.config({
	paths:{
	},
	urlArgs:"bust" + (new Date()).getTime(),
});

requirejs(['cookie','public_parameters','citylist','store','choosedate','common'],function($){
})