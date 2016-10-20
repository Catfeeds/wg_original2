requirejs.config({
	paths:{
	},
	urlArgs:"bust" + (new Date()).getTime(),
});

requirejs(['common','cookie','public_parameters','citylist','store'],function($){
	console.log('aa');
})