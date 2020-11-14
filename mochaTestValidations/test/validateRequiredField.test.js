// JavaScript Document

var assert = require('chai').assert;	//Chai assertion library
const validatePhoneNumber = require('../app/validatePhoneNumber.js');
var validInput = require('../app/validatePhoneNumber');


// begin testing valid phone  number
describe("Testing Valid Phone Number", function(){
	
	it("Empty or '' should fail", function() {
		assert.isFalse(validatePhoneNumber(''));
	});	
	
	it("A single space should fail", function() {
		assert.isFalse(validatePhoneNumber(' '));
	});
	
	it("Two or more spaces should fail", function(){
		assert.isFalse(validatePhoneNumber('  '));
	});
	
	it("The word null should fail", function() {
		assert.isFalse(validatePhoneNumber('null'));
	});
	
	it("The word undefined should fail", function() {
		assert.isFalse(validatePhoneNumber('undefined'));
	});

	it("4 numbers should fail", function() {
		assert.isFalse(validatePhoneNumber(1234));
	});
	
	it("the letter a should fail", function() {
		assert.isFalse(validatePhoneNumber('a'));
	});

	it("any non numeric characters should fail", function() {
		assert.isFalse(validatePhoneNumber('123d456789'));
	});

	it("special characters should fail", function() {
		assert.isFalse(validatePhoneNumber('123%456789'));
	});

	it("decimals should fail", function() {
		assert.isFalse(validatePhoneNumber('123.456789'));
	});

	it("555-555-5555 should pass", function() {
		assert.isTrue(validatePhoneNumber('555-555-5555'));
	});

	it("1234567890 should pass", function() {
		assert.isTrue(validatePhoneNumber('1234567890'));
	});
});

