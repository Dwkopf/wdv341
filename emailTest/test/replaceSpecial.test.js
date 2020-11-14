// JavaScript Document

var assert = require('chai').assert;	//Chai assertion library
const replaceSpecials = require('../app/replaceSpecials.js');
var validInput = require('../app/replaceSpecials');

// begin replacing specials <,>,/,'
describe("Testing replace specials", function(){
    it("Empty or '' should pass", function() {
		assert.isTrue(replaceSpecials(''));
    });	

    it("<h2>Hello World</h2> should replace all", function() {
		assert.isTrue(replaceSpecials('<h2>Hello World</h2>'));
    });	
})
