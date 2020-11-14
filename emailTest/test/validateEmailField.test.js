// JavaScript Document

var assert = require('chai').assert;	//Chai assertion library
const validateEmail = require('../app/validateEmail.js');
var validInput = require('../app/validateEmail');


// begin testing valid email
describe("Testing Valid Email", function(){

    it("Empty or '' should fail", function() {
		assert.isFalse(validateEmail(''));
    });	
    
    it("www.wilfail.com should fail", function() {
		assert.isFalse(validateEmail('www.wilfail.com'));
    });	

    it("@ symbol in string is valid", function() {
		assert.isTrue(validateEmail('www.willsucceed@fail.com'));
    });	
    it("@ symbol at start is valid", function() {
		assert.isTrue(validateEmail(' @fail.com'));
    });	

    it("'much more.unusual'@example.com is valid", function() {
		assert.isTrue(validateEmail(' @fail.com'));
    });

    it("user@tt is valid", function() {
		assert.isTrue(validateEmail('user@tt'));
    });
    
    


});