/**
*	search function
**/
var request = require('superagent')

module.exports = function search(query,fn){
	request.get('https://twitter.com/search?q=abc')
			.data({q:query})
			.end(function(res){
				if(res.body && Array.isArray(res.body.results)){
					return fn(null,res.body.results);
				}
				return fn(new Error('bad twitter response'));
			});
}